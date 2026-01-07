@extends('layouts.admin')

@section('title', 'Campaign Details')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('admin.newsletter.campaigns') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Campaigns
            </a>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ $campaign->subject }}</h1>
            <div class="flex flex-wrap items-center gap-3 mt-3">
                @php
                    $statusConfig = [
                        'draft' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'sending' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'sent' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'failed' => 'bg-red-50 text-red-700 border-red-200',
                    ];
                @endphp
                <span class="inline-flex px-2.5 py-1 text-[10px] font-bold tracking-[0.1em] uppercase border {{ $statusConfig[$campaign->status] ?? 'bg-slate-50 text-slate-700 border-slate-200' }}">
                    {{ ucfirst($campaign->status) }}
                </span>
                <span class="text-[12px] text-slate-500">{{ ucfirst(str_replace('_', ' ', $campaign->type)) }}</span>
                <span class="text-[12px] text-slate-300">•</span>
                <span class="text-[12px] text-slate-500">Created {{ $campaign->created_at->format('M d, Y') }}</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($campaign->status === 'draft')
            <a href="{{ route('admin.newsletter.campaigns.edit', $campaign) }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">
                Edit
            </a>
            @endif
            <a href="{{ route('admin.newsletter.campaigns.preview', $campaign) }}" target="_blank" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">
                Preview
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Email Preview --}}
            <div class="bg-white border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Email Content</h3>
                </div>
                <div class="p-6">
                    <div class="prose prose-sm max-w-none prose-headings:font-medium prose-p:text-slate-600 prose-li:text-slate-600">
                        {!! $campaign->content !!}
                    </div>
                </div>
            </div>

            {{-- Send Logs --}}
            @if($campaign->status === 'sent' && $campaign->logs->count() > 0)
            <div class="bg-white border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Send Log</h3>
                    <div class="flex items-center gap-4 text-[11px]">
                        <span class="text-emerald-600">{{ $logStats['sent'] }} sent</span>
                        <span class="text-red-600">{{ $logStats['failed'] }} failed</span>
                    </div>
                </div>
                <div class="max-h-80 overflow-y-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Email</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Status</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($campaign->logs as $log)
                            <tr>
                                <td class="px-6 py-3 text-[12px] text-slate-900">{{ $log->email }}</td>
                                <td class="px-6 py-3">
                                    @if($log->status === 'sent')
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-emerald-700">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        Sent
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-red-700">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                        Failed
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-[12px] text-slate-500">{{ $log->sent_at ? $log->sent_at->format('H:i:s') : '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Stats --}}
            @if($campaign->status === 'sent')
            <div class="bg-white border border-slate-200 p-6">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-6">Campaign Stats</h3>
                <div class="space-y-5">
                    <div class="flex justify-between items-center">
                        <span class="text-[13px] text-slate-500">Total Recipients</span>
                        <span class="text-[14px] font-semibold text-slate-900">{{ $campaign->total_recipients }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[13px] text-slate-500">Successfully Sent</span>
                        <span class="text-[14px] font-semibold text-emerald-600">{{ $campaign->sent_count }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[13px] text-slate-500">Failed</span>
                        <span class="text-[14px] font-semibold text-red-600">{{ $campaign->failed_count }}</span>
                    </div>
                    <div class="pt-5 border-t border-slate-100">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[13px] text-slate-500">Success Rate</span>
                            <span class="text-[14px] font-semibold text-slate-900">{{ $campaign->success_rate }}%</span>
                        </div>
                        <div class="h-2 bg-slate-100 overflow-hidden">
                            <div class="h-full bg-emerald-500 transition-all" style="width: {{ $campaign->success_rate }}%"></div>
                        </div>
                    </div>
                    <div class="pt-5 border-t border-slate-100">
                        <div class="flex justify-between items-center">
                            <span class="text-[13px] text-slate-500">Sent At</span>
                            <span class="text-[13px] font-medium text-slate-900">{{ $campaign->sent_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Actions --}}
            @if($campaign->status === 'draft')
            <div class="bg-white border border-slate-200 p-6">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-6">Send Campaign</h3>
                
                {{-- Test Email --}}
                <form action="{{ route('admin.newsletter.campaigns.send-test', $campaign) }}" method="POST" class="mb-6">
                    @csrf
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Send Test Email</label>
                    <div class="flex gap-2">
                        <input type="email" name="email" placeholder="your@email.com" required
                            class="flex-1 h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors">
                        <button type="submit" class="h-11 px-6 bg-slate-100 text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-200 transition-colors">
                            Test
                        </button>
                    </div>
                </form>

                {{-- Send to All --}}
                <form action="{{ route('admin.newsletter.campaigns.send', $campaign) }}" method="POST" onsubmit="return confirm('Send this campaign to all {{ \App\Models\NewsletterSubscriber::active()->count() }} subscribers?')">
                    @csrf
                    <button type="submit" class="w-full h-11 bg-emerald-600 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-emerald-700 transition-colors">
                        Send to {{ \App\Models\NewsletterSubscriber::active()->count() }} Subscribers
                    </button>
                </form>
            </div>
            @endif

            {{-- Delete --}}
            <div class="bg-white border border-slate-200 p-6">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-4">Danger Zone</h3>
                <form action="{{ route('admin.newsletter.campaigns.destroy', $campaign) }}" method="POST" onsubmit="return confirm('Delete this campaign permanently?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full h-11 text-red-600 text-[11px] font-bold tracking-[0.15em] uppercase border border-red-200 hover:bg-red-50 transition-colors">
                        Delete Campaign
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
