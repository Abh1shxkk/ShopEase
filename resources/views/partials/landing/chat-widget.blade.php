{{-- Chat Widget / AI Shopping Assistant Component --}}
{{-- Note: This is a static UI only - AI functionality will be added later --}}

{{-- Chat Button --}}
<button 
    id="chat-button"
    class="chat-button visible fixed bottom-8 right-8 z-[60] w-14 h-14 bg-slate-900 text-white flex items-center justify-center hover:bg-slate-800 transition-all rounded-none"
>
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
    </svg>
</button>

{{-- Chat Widget Panel --}}
<div 
    id="chat-widget"
    class="chat-widget closed fixed bottom-8 right-8 z-[70] w-[380px] h-[550px] bg-white border border-slate-900 flex flex-col rounded-none shadow-2xl"
>
    {{-- Chat Header --}}
    <div class="bg-slate-900 p-6 flex items-center justify-between text-white rounded-none">
        <h4 class="font-serif tracking-widest uppercase text-sm">ShopEase Help</h4>
        <button id="chat-close" class="hover:text-slate-300">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Chat Messages --}}
    <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-white hide-scrollbar">
        {{-- Welcome Message (from AI) --}}
        <div class="flex justify-start">
            <div class="p-4 text-[13px] leading-relaxed max-w-[90%] border bg-white border-slate-200 italic">
                Welcome to ShopEase! I'm your shopping assistant. How can I help you today?
            </div>
        </div>
        
        {{-- Example User Message (for demo) --}}
        {{-- 
        <div class="flex justify-end">
            <div class="p-4 text-[13px] leading-relaxed max-w-[90%] border bg-slate-50 border-slate-100">
                I'm looking for a weekender bag for travel.
            </div>
        </div>
        --}}
    </div>

    {{-- Chat Input --}}
    <div class="p-4 border-t border-slate-100">
        <div class="flex gap-2">
            <input 
                type="text"
                placeholder="Ask about products..."
                class="flex-1 text-[13px] border border-slate-200 py-3 px-4 focus:outline-none focus:border-slate-900 rounded-none"
            />
            <button class="bg-slate-900 text-white px-4 hover:bg-slate-800 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
    </div>
</div>
