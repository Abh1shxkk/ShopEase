<?php

namespace App\Services;

use App\Mail\NewsletterMail;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterLog;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Mail;

class NewsletterService
{
    public function sendCampaign(NewsletterCampaign $campaign): array
    {
        $subscribers = NewsletterSubscriber::active()->get();
        
        if ($subscribers->isEmpty()) {
            return ['success' => false, 'message' => 'No active subscribers found.'];
        }

        $campaign->update([
            'status' => 'sending',
            'total_recipients' => $subscribers->count(),
        ]);

        $sentCount = 0;
        $failedCount = 0;

        foreach ($subscribers as $subscriber) {
            try {
                Mail::to($subscriber->email)->send(new NewsletterMail($campaign, $subscriber));
                
                NewsletterLog::create([
                    'campaign_id' => $campaign->id,
                    'subscriber_id' => $subscriber->id,
                    'email' => $subscriber->email,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
                
                $sentCount++;
            } catch (\Exception $e) {
                NewsletterLog::create([
                    'campaign_id' => $campaign->id,
                    'subscriber_id' => $subscriber->id,
                    'email' => $subscriber->email,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
                
                $failedCount++;
            }
        }

        $campaign->update([
            'status' => 'sent',
            'sent_at' => now(),
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
        ]);

        return [
            'success' => true,
            'message' => "Campaign sent! {$sentCount} delivered, {$failedCount} failed.",
            'sent' => $sentCount,
            'failed' => $failedCount,
        ];
    }

    public function sendTestEmail(NewsletterCampaign $campaign, string $email): bool
    {
        try {
            $testSubscriber = new NewsletterSubscriber([
                'email' => $email,
                'unsubscribe_token' => 'test-token',
            ]);
            
            Mail::to($email)->send(new NewsletterMail($campaign, $testSubscriber));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getCampaignStats(): array
    {
        return [
            'total_campaigns' => NewsletterCampaign::count(),
            'sent_campaigns' => NewsletterCampaign::sent()->count(),
            'draft_campaigns' => NewsletterCampaign::draft()->count(),
            'total_subscribers' => NewsletterSubscriber::active()->count(),
            'total_emails_sent' => NewsletterLog::where('status', 'sent')->count(),
        ];
    }
}
