<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterLog extends Model
{
    protected $fillable = [
        'campaign_id',
        'subscriber_id',
        'email',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(NewsletterCampaign::class, 'campaign_id');
    }

    public function subscriber()
    {
        return $this->belongsTo(NewsletterSubscriber::class, 'subscriber_id');
    }
}
