<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use App\Services\NewsletterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsletterController extends Controller
{
    protected NewsletterService $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }

    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query();

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscribers = $query->latest()->paginate(20);
        
        $stats = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::active()->count(),
            'unsubscribed' => NewsletterSubscriber::unsubscribed()->count(),
        ];

        return view('admin.newsletter.index', compact('subscribers', 'stats'));
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
        return redirect()->route('admin.newsletter.index')->with('success', 'Subscriber deleted successfully.');
    }

    public function export()
    {
        $subscribers = NewsletterSubscriber::active()->get(['email', 'name', 'subscribed_at']);
        
        $filename = 'newsletter_subscribers_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($subscribers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Email', 'Name', 'Subscribed At']);
            
            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->email,
                    $subscriber->name ?? '',
                    $subscriber->subscribed_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Campaign Methods
    public function campaigns(Request $request)
    {
        $query = NewsletterCampaign::with('creator');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $campaigns = $query->latest()->paginate(15);
        $stats = $this->newsletterService->getCampaignStats();

        return view('admin.newsletter.campaigns.index', compact('campaigns', 'stats'));
    }

    public function createCampaign()
    {
        $subscriberCount = NewsletterSubscriber::active()->count();
        return view('admin.newsletter.campaigns.form', compact('subscriberCount'));
    }

    public function storeCampaign(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:promotional,new_arrivals,sale,announcement,custom',
        ]);

        $campaign = NewsletterCampaign::create([
            'subject' => $request->subject,
            'content' => $request->content,
            'type' => $request->type,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.newsletter.campaigns.show', $campaign)
            ->with('success', 'Campaign created successfully.');
    }

    public function showCampaign(NewsletterCampaign $campaign)
    {
        $campaign->load(['creator', 'logs' => function($q) {
            $q->latest()->limit(50);
        }]);
        
        $logStats = [
            'sent' => $campaign->logs()->where('status', 'sent')->count(),
            'failed' => $campaign->logs()->where('status', 'failed')->count(),
        ];

        return view('admin.newsletter.campaigns.show', compact('campaign', 'logStats'));
    }

    public function editCampaign(NewsletterCampaign $campaign)
    {
        if ($campaign->status !== 'draft') {
            return redirect()->route('admin.newsletter.campaigns.show', $campaign)
                ->with('error', 'Only draft campaigns can be edited.');
        }

        $subscriberCount = NewsletterSubscriber::active()->count();
        return view('admin.newsletter.campaigns.form', compact('campaign', 'subscriberCount'));
    }

    public function updateCampaign(Request $request, NewsletterCampaign $campaign)
    {
        if ($campaign->status !== 'draft') {
            return redirect()->route('admin.newsletter.campaigns.show', $campaign)
                ->with('error', 'Only draft campaigns can be edited.');
        }

        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:promotional,new_arrivals,sale,announcement,custom',
        ]);

        $campaign->update([
            'subject' => $request->subject,
            'content' => $request->content,
            'type' => $request->type,
        ]);

        return redirect()->route('admin.newsletter.campaigns.show', $campaign)
            ->with('success', 'Campaign updated successfully.');
    }

    public function destroyCampaign(NewsletterCampaign $campaign)
    {
        $campaign->delete();
        return redirect()->route('admin.newsletter.campaigns')
            ->with('success', 'Campaign deleted successfully.');
    }

    public function sendTestEmail(Request $request, NewsletterCampaign $campaign)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $success = $this->newsletterService->sendTestEmail($campaign, $request->email);

        if ($success) {
            return back()->with('success', 'Test email sent to ' . $request->email);
        }

        return back()->with('error', 'Failed to send test email. Check your mail configuration.');
    }

    public function sendCampaign(NewsletterCampaign $campaign)
    {
        if ($campaign->status === 'sent') {
            return back()->with('error', 'This campaign has already been sent.');
        }

        $result = $this->newsletterService->sendCampaign($campaign);

        if ($result['success']) {
            return redirect()->route('admin.newsletter.campaigns.show', $campaign)
                ->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    public function previewCampaign(NewsletterCampaign $campaign)
    {
        $subscriber = new NewsletterSubscriber([
            'email' => 'preview@example.com',
            'unsubscribe_token' => 'preview-token',
        ]);

        return view('emails.newsletter', [
            'campaign' => $campaign,
            'subscriber' => $subscriber,
            'unsubscribeUrl' => '#',
        ]);
    }
}
