{{-- Live Chat Integration (Tawk.to / Crisp) --}}
@php
    $liveChatEnabled = \App\Models\SiteSetting::get('live_chat_enabled', false);
    $liveChatProvider = \App\Models\SiteSetting::get('live_chat_provider', 'none');
    $tawkPropertyId = \App\Models\SiteSetting::get('tawk_property_id', '');
    $tawkWidgetId = \App\Models\SiteSetting::get('tawk_widget_id', '1i');
    $crispWebsiteId = \App\Models\SiteSetting::get('crisp_website_id', '');
    
    // Convert to boolean properly
    $isEnabled = filter_var($liveChatEnabled, FILTER_VALIDATE_BOOLEAN);
@endphp

@if($isEnabled && $liveChatProvider !== 'none')
    @if($liveChatProvider === 'tawk' && $tawkPropertyId)
    {{-- Tawk.to Integration --}}
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/{{ $tawkPropertyId }}/{{ $tawkWidgetId }}';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
    
    // Pass user info to Tawk.to if logged in
    @auth
    Tawk_API.onLoad = function(){
        Tawk_API.setAttributes({
            'name': '{{ auth()->user()->name }}',
            'email': '{{ auth()->user()->email }}',
            'id': '{{ auth()->user()->id }}'
        }, function(error){});
    };
    @endauth
    </script>
    @elseif($liveChatProvider === 'crisp' && $crispWebsiteId)
    {{-- Crisp Integration --}}
    <script type="text/javascript">
    window.$crisp=[];
    window.CRISP_WEBSITE_ID="{{ $crispWebsiteId }}";
    (function(){
        d=document;
        s=d.createElement("script");
        s.src="https://client.crisp.chat/l.js";
        s.async=1;
        d.getElementsByTagName("head")[0].appendChild(s);
    })();
    
    // Pass user info to Crisp if logged in
    @auth
    window.$crisp.push(["set", "user:email", ["{{ auth()->user()->email }}"]]);
    window.$crisp.push(["set", "user:nickname", ["{{ auth()->user()->name }}"]]);
    @endauth
    </script>
    @endif
@else
{{-- Fallback: Static Chat Widget UI (when no live chat is configured) --}}
<button 
    id="chat-button"
    class="chat-button visible fixed bottom-8 right-8 z-[60] w-14 h-14 bg-slate-900 text-white flex items-center justify-center hover:bg-slate-800 transition-all rounded-none"
    onclick="document.getElementById('chat-widget').classList.toggle('closed'); this.classList.toggle('hidden');"
>
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
    </svg>
</button>

<div 
    id="chat-widget"
    class="chat-widget closed fixed bottom-8 right-8 z-[70] w-[380px] h-[550px] bg-white border border-slate-900 flex flex-col rounded-none shadow-2xl"
>
    <div class="bg-slate-900 p-6 flex items-center justify-between text-white rounded-none">
        <h4 class="font-serif tracking-widest uppercase text-sm">ShopEase Help</h4>
        <button onclick="document.getElementById('chat-widget').classList.add('closed'); document.getElementById('chat-button').classList.remove('hidden');" class="hover:text-slate-300">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-white hide-scrollbar">
        <div class="flex justify-start">
            <div class="p-4 text-[13px] leading-relaxed max-w-[90%] border bg-white border-slate-200 italic">
                Welcome to ShopEase! For immediate assistance, please <a href="{{ route('support.ticket.create') }}" class="text-blue-600 underline">submit a support ticket</a> or browse our <a href="{{ route('support.faq') }}" class="text-blue-600 underline">FAQ section</a>.
            </div>
        </div>
    </div>

    <div class="p-4 border-t border-slate-100">
        <a href="{{ route('support.index') }}" class="block w-full bg-slate-900 text-white py-3 text-center text-sm tracking-wider uppercase hover:bg-slate-800 transition-all">
            Visit Support Center
        </a>
    </div>
</div>

<style>
.chat-widget.closed { display: none; }
</style>
@endif
