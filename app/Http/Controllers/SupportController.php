<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\TicketAttachment;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SupportController extends Controller
{
    public function index()
    {
        $faqCategories = FaqCategory::active()->ordered()->with('activeFaqs')->get();
        $popularFaqs = Faq::active()->orderByDesc('helpful_count')->take(5)->get();
        
        return view('support.index', compact('faqCategories', 'popularFaqs'));
    }

    public function faq(Request $request)
    {
        $categories = FaqCategory::active()->ordered()->with('activeFaqs')->get();
        $selectedCategory = null;
        $faqs = collect();

        if ($request->has('category')) {
            $selectedCategory = FaqCategory::where('slug', $request->category)->first();
            if ($selectedCategory) {
                $faqs = $selectedCategory->activeFaqs;
            }
        } else {
            $faqs = Faq::active()->ordered()->with('category')->get();
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $faqs = Faq::active()
                ->where(function($q) use ($search) {
                    $q->where('question', 'like', "%{$search}%")
                      ->orWhere('answer', 'like', "%{$search}%");
                })
                ->with('category')
                ->get();
        }

        return view('support.faq', compact('categories', 'selectedCategory', 'faqs'));
    }

    public function faqFeedback(Request $request, Faq $faq)
    {
        $request->validate(['helpful' => 'required|boolean']);
        
        if ($request->helpful) {
            $faq->markHelpful();
        } else {
            $faq->markNotHelpful();
        }

        return response()->json(['success' => true]);
    }

    public function contact()
    {
        $orders = Auth::check() ? Auth::user()->orders()->latest()->take(10)->get() : collect();
        return view('support.contact', compact('orders'));
    }

    public function createTicket()
    {
        $orders = Auth::check() ? Auth::user()->orders()->latest()->take(10)->get() : collect();
        return view('support.create-ticket', compact('orders'));
    }

    public function storeTicket(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'category' => 'required|in:' . implode(',', array_keys(SupportTicket::CATEGORIES)),
            'priority' => 'sometimes|in:' . implode(',', array_keys(SupportTicket::PRIORITIES)),
            'order_id' => 'nullable|exists:orders,id',
            'description' => 'required|string|min:20',
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,gif,pdf,doc,docx'
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'order_id' => $request->order_id,
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority ?? 'medium',
            'description' => $request->description,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-attachments', 'public');
                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('support.ticket.show', $ticket)
            ->with('success', 'Your support ticket has been created. Ticket #' . $ticket->ticket_number);
    }

    public function tickets()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->orWhere('email', Auth::user()->email)
            ->latest()
            ->paginate(10);

        return view('support.tickets', compact('tickets'));
    }

    public function showTicket(SupportTicket $ticket)
    {
        if (Auth::check()) {
            if ($ticket->user_id !== Auth::id() && $ticket->email !== Auth::user()->email && !Auth::user()->isAdmin()) {
                abort(403);
            }
        }

        $ticket->load(['replies' => function($q) {
            $q->where('is_internal_note', false)->with('user', 'attachments');
        }, 'attachments', 'order']);

        return view('support.ticket-show', compact('ticket'));
    }

    public function replyTicket(Request $request, SupportTicket $ticket)
    {
        if ($ticket->user_id !== Auth::id() && $ticket->email !== Auth::user()->email) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|min:10',
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,gif,pdf,doc,docx'
        ]);

        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_staff_reply' => false,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-attachments', 'public');
                TicketAttachment::create([
                    'reply_id' => $reply->id,
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        if ($ticket->status === 'waiting') {
            $ticket->update(['status' => 'open']);
        }

        return back()->with('success', 'Your reply has been added.');
    }

    public function trackTicket(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'ticket_number' => 'required|string',
                'email' => 'required|email'
            ]);

            $ticket = SupportTicket::where('ticket_number', $request->ticket_number)
                ->where('email', $request->email)
                ->first();

            if (!$ticket) {
                return back()->with('error', 'Ticket not found. Please check your ticket number and email.');
            }

            return redirect()->route('support.ticket.show', $ticket);
        }

        return view('support.track-ticket');
    }

    public function getLiveChatConfig()
    {
        $provider = SiteSetting::get('live_chat_provider', 'tawk');
        $enabled = SiteSetting::get('live_chat_enabled', false);
        
        return response()->json([
            'enabled' => $enabled,
            'provider' => $provider,
            'tawk_property_id' => SiteSetting::get('tawk_property_id'),
            'tawk_widget_id' => SiteSetting::get('tawk_widget_id'),
            'crisp_website_id' => SiteSetting::get('crisp_website_id'),
        ]);
    }
}
