<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    // Ticket Management
    public function tickets(Request $request)
    {
        $query = SupportTicket::with(['user', 'assignedTo']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $tickets = $query->latest()->paginate(15);
        $stats = [
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'waiting' => SupportTicket::where('status', 'waiting')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
        ];

        return view('admin.support.tickets.index', compact('tickets', 'stats'));
    }

    public function showTicket(SupportTicket $ticket)
    {
        $ticket->load(['replies.user', 'replies.attachments', 'attachments', 'user', 'order', 'assignedTo']);
        $admins = \App\Models\User::where('role', 'admin')->get();
        
        return view('admin.support.tickets.show', compact('ticket', 'admins'));
    }

    public function updateTicket(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => 'sometimes|in:' . implode(',', array_keys(SupportTicket::STATUSES)),
            'priority' => 'sometimes|in:' . implode(',', array_keys(SupportTicket::PRIORITIES)),
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $updates = $request->only(['status', 'priority', 'assigned_to']);
        
        if (isset($updates['status'])) {
            if ($updates['status'] === 'resolved' && $ticket->status !== 'resolved') {
                $updates['resolved_at'] = now();
            }
            if ($updates['status'] === 'closed' && $ticket->status !== 'closed') {
                $updates['closed_at'] = now();
            }
        }

        $ticket->update($updates);

        return back()->with('success', 'Ticket updated successfully.');
    }

    public function replyTicket(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string|min:5',
            'is_internal_note' => 'boolean',
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,gif,pdf,doc,docx'
        ]);

        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_staff_reply' => true,
            'is_internal_note' => $request->boolean('is_internal_note'),
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

        if (!$request->boolean('is_internal_note') && $ticket->status === 'open') {
            $ticket->update(['status' => 'waiting']);
        }

        return back()->with('success', 'Reply added successfully.');
    }

    // FAQ Management
    public function faqCategories()
    {
        $categories = FaqCategory::withCount('faqs')->ordered()->get();
        return view('admin.support.faq.categories', compact('categories'));
    }

    public function createFaqCategory()
    {
        return view('admin.support.faq.category-form');
    }

    public function storeFaqCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        FaqCategory::create($request->all());

        return redirect()->route('admin.support.faq.categories')
            ->with('success', 'FAQ category created successfully.');
    }

    public function editFaqCategory(FaqCategory $category)
    {
        return view('admin.support.faq.category-form', compact('category'));
    }

    public function updateFaqCategory(Request $request, FaqCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $category->update($request->all());

        return redirect()->route('admin.support.faq.categories')
            ->with('success', 'FAQ category updated successfully.');
    }

    public function destroyFaqCategory(FaqCategory $category)
    {
        $category->delete();
        return back()->with('success', 'FAQ category deleted successfully.');
    }

    public function faqs(Request $request)
    {
        $query = Faq::with('category');
        
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $faqs = $query->ordered()->paginate(15);
        $categories = FaqCategory::ordered()->get();

        return view('admin.support.faq.index', compact('faqs', 'categories'));
    }

    public function createFaq()
    {
        $categories = FaqCategory::active()->ordered()->get();
        return view('admin.support.faq.form', compact('categories'));
    }

    public function storeFaq(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        Faq::create($request->all());

        return redirect()->route('admin.support.faqs')
            ->with('success', 'FAQ created successfully.');
    }

    public function editFaq(Faq $faq)
    {
        $categories = FaqCategory::active()->ordered()->get();
        return view('admin.support.faq.form', compact('faq', 'categories'));
    }

    public function updateFaq(Request $request, Faq $faq)
    {
        $request->validate([
            'category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $faq->update($request->all());

        return redirect()->route('admin.support.faqs')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroyFaq(Faq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted successfully.');
    }

    // Live Chat Settings
    public function liveChatSettings()
    {
        $settings = [
            'live_chat_enabled' => filter_var(SiteSetting::get('live_chat_enabled', false), FILTER_VALIDATE_BOOLEAN),
            'live_chat_provider' => SiteSetting::get('live_chat_provider', 'none'),
            'tawk_property_id' => SiteSetting::get('tawk_property_id', ''),
            'tawk_widget_id' => SiteSetting::get('tawk_widget_id', '1i'),
            'crisp_website_id' => SiteSetting::get('crisp_website_id', ''),
        ];

        return view('admin.support.live-chat', compact('settings'));
    }

    public function updateLiveChatSettings(Request $request)
    {
        $request->validate([
            'live_chat_provider' => 'required|in:tawk,crisp,none',
            'tawk_property_id' => 'nullable|string|max:255',
            'tawk_widget_id' => 'nullable|string|max:255',
            'crisp_website_id' => 'nullable|string|max:255',
        ]);

        SiteSetting::set('live_chat_enabled', $request->boolean('live_chat_enabled') ? '1' : '0');
        SiteSetting::set('live_chat_provider', $request->live_chat_provider);
        SiteSetting::set('tawk_property_id', $request->tawk_property_id ?? '');
        SiteSetting::set('tawk_widget_id', $request->tawk_widget_id ?? '1i');
        SiteSetting::set('crisp_website_id', $request->crisp_website_id ?? '');

        // Clear cache to apply changes immediately
        \Illuminate\Support\Facades\Cache::forget('setting_live_chat_enabled');
        \Illuminate\Support\Facades\Cache::forget('setting_live_chat_provider');
        \Illuminate\Support\Facades\Cache::forget('setting_tawk_property_id');
        \Illuminate\Support\Facades\Cache::forget('setting_tawk_widget_id');
        \Illuminate\Support\Facades\Cache::forget('setting_crisp_website_id');

        return back()->with('success', 'Live chat settings updated successfully.');
    }
}
