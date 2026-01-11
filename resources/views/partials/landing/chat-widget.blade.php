{{-- Live Chat Integration (Tawk.to / Crisp / AI Chat) --}}
@php
    $liveChatEnabled = \App\Models\SiteSetting::get('live_chat_enabled', false);
    $liveChatProvider = \App\Models\SiteSetting::get('live_chat_provider', 'none');
    $tawkPropertyId = \App\Models\SiteSetting::get('tawk_property_id', '');
    $tawkWidgetId = \App\Models\SiteSetting::get('tawk_widget_id', '1i');
    $crispWebsiteId = \App\Models\SiteSetting::get('crisp_website_id', '');
    
    // Convert to boolean properly
    $isEnabled = filter_var($liveChatEnabled, FILTER_VALIDATE_BOOLEAN);
@endphp

@if($isEnabled && $liveChatProvider !== 'none' && $liveChatProvider !== 'ai')
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
    
    @auth
    window.$crisp.push(["set", "user:email", ["{{ auth()->user()->email }}"]]);
    window.$crisp.push(["set", "user:nickname", ["{{ auth()->user()->name }}"]]);
    @endauth
    </script>
    @endif
@else
{{-- AI Chat Widget --}}
<div x-data="aiChatWidget()" x-cloak>
    {{-- Chat Button --}}
    <button 
        x-show="!isOpen"
        @click="openChat()"
        class="fixed bottom-6 right-6 z-[60] w-14 h-14 bg-slate-900 text-white flex items-center justify-center hover:bg-slate-800 transition-all shadow-lg rounded-full hover:scale-105"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        {{-- Notification dot for new messages --}}
        <span x-show="hasUnread" class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
    </button>

    {{-- Chat Widget --}}
    <div 
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="fixed bottom-6 right-6 z-[70] w-[380px] h-[520px] bg-white border border-slate-200 flex flex-col rounded-2xl shadow-2xl overflow-hidden"
    >
        {{-- Header --}}
        <div class="bg-slate-900 px-5 py-4 flex items-center justify-between text-white rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <h4 class="font-medium text-sm">ShopEase Support</h4>
            </div>
            <div class="flex items-center gap-1">
                <button @click="clearChat()" class="hover:bg-slate-800 p-2 rounded-lg transition-colors" title="Clear chat">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
                <button @click="closeChat()" class="hover:bg-slate-800 p-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Messages Container --}}
        <div 
            x-ref="messagesContainer"
            class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50 hide-scrollbar"
        >
            {{-- Welcome Message --}}
            <template x-if="messages.length === 0">
                <div class="flex justify-start">
                    <div class="p-4 text-[13px] leading-relaxed max-w-[90%] bg-white rounded-2xl rounded-tl-sm shadow-sm">
                        <p class="mb-2">👋 Hi{{ auth()->check() ? ', ' . auth()->user()->name : '' }}! Welcome to ShopEase.</p>
                        <p class="text-slate-600">I'm your AI assistant. I can help you with:</p>
                        <ul class="text-slate-600 mt-2 space-y-1 text-[12px]">
                            <li>• Order tracking & status</li>
                            <li>• Shipping & delivery info</li>
                            <li>• Returns & refunds</li>
                            <li>• Product inquiries</li>
                        </ul>
                        <p class="mt-3 text-slate-400 text-[11px]">Type your question below to get started!</p>
                    </div>
                </div>
            </template>

            {{-- Chat Messages --}}
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div 
                        :class="msg.role === 'user' 
                            ? 'bg-slate-900 text-white rounded-2xl rounded-tr-sm' 
                            : 'bg-white text-slate-800 rounded-2xl rounded-tl-sm shadow-sm'"
                        class="p-3 text-[13px] leading-relaxed max-w-[85%]"
                    >
                        <p x-html="formatMessage(msg.content)"></p>
                        <p class="text-[10px] mt-1.5 opacity-50" x-text="msg.created_at"></p>
                    </div>
                </div>
            </template>

            {{-- Typing Indicator --}}
            <div x-show="isTyping" class="flex justify-start">
                <div class="p-3 bg-white rounded-2xl rounded-tl-sm shadow-sm">
                    <div class="flex gap-1">
                        <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="px-4 py-2 bg-white border-t border-slate-100 flex gap-2 overflow-x-auto hide-scrollbar">
            <button @click="sendQuickMessage('Track my order')" class="flex-shrink-0 px-3 py-1.5 text-[11px] font-medium bg-slate-100 hover:bg-slate-200 rounded-full transition-colors">
                📦 Track Order
            </button>
            <button @click="sendQuickMessage('What is your return policy?')" class="flex-shrink-0 px-3 py-1.5 text-[11px] font-medium bg-slate-100 hover:bg-slate-200 rounded-full transition-colors">
                ↩️ Returns
            </button>
            <button @click="sendQuickMessage('Shipping information')" class="flex-shrink-0 px-3 py-1.5 text-[11px] font-medium bg-slate-100 hover:bg-slate-200 rounded-full transition-colors">
                🚚 Shipping
            </button>
        </div>

        {{-- Input Area --}}
        <div class="p-3 bg-white border-t border-slate-100">
            <form @submit.prevent="sendMessage()" class="flex gap-2">
                <input 
                    x-model="newMessage"
                    type="text" 
                    placeholder="Type your message..."
                    class="flex-1 h-10 px-4 bg-slate-100 border-0 text-[13px] focus:outline-none focus:ring-2 focus:ring-slate-300 rounded-full transition-all"
                    :disabled="isTyping"
                    maxlength="500"
                >
                <button 
                    type="submit"
                    :disabled="!newMessage.trim() || isTyping"
                    class="w-10 h-10 bg-slate-900 text-white flex items-center justify-center hover:bg-slate-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed rounded-full"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
            <p class="text-[10px] text-slate-400 mt-2 text-center">
                AI-powered • <a href="{{ route('support.ticket.create') }}" class="underline hover:text-slate-600">Create ticket</a> for complex issues
            </p>
        </div>
    </div>
</div>

<script>
function aiChatWidget() {
    return {
        isOpen: false,
        messages: [],
        newMessage: '',
        isTyping: false,
        hasUnread: false,
        conversationId: null,

        async openChat() {
            this.isOpen = true;
            this.hasUnread = false;
            
            // Load existing conversation
            if (this.messages.length === 0) {
                await this.loadConversation();
            }
            
            this.$nextTick(() => this.scrollToBottom());
        },

        closeChat() {
            this.isOpen = false;
        },

        async loadConversation() {
            try {
                const response = await fetch('{{ route("chat.conversation") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await response.json();
                this.conversationId = data.conversation_id;
                this.messages = data.messages || [];
                this.$nextTick(() => this.scrollToBottom());
            } catch (error) {
                console.error('Failed to load conversation:', error);
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim() || this.isTyping) return;

            const message = this.newMessage.trim();
            this.newMessage = '';
            
            // Add user message immediately
            this.messages.push({
                role: 'user',
                content: message,
                created_at: 'Just now'
            });
            
            this.$nextTick(() => this.scrollToBottom());
            
            // Show typing indicator
            this.isTyping = true;

            try {
                const response = await fetch('{{ route("chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();
                
                // Update user message timestamp
                this.messages[this.messages.length - 1].created_at = data.user_message.created_at;
                
                // Add AI response
                this.messages.push(data.assistant_message);
                
            } catch (error) {
                console.error('Failed to send message:', error);
                this.messages.push({
                    role: 'assistant',
                    content: 'Sorry, I encountered an error. Please try again or create a support ticket for assistance.',
                    created_at: 'Just now'
                });
            } finally {
                this.isTyping = false;
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        sendQuickMessage(message) {
            this.newMessage = message;
            this.sendMessage();
        },

        async clearChat() {
            if (!confirm('Clear chat history?')) return;
            
            try {
                await fetch('{{ route("chat.clear") }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                this.messages = [];
            } catch (error) {
                console.error('Failed to clear chat:', error);
            }
        },

        scrollToBottom() {
            if (this.$refs.messagesContainer) {
                this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
            }
        },

        formatMessage(content) {
            // Convert URLs to links
            const urlRegex = /(https?:\/\/[^\s]+)/g;
            content = content.replace(urlRegex, '<a href="$1" target="_blank" class="underline text-blue-600">$1</a>');
            
            // Convert newlines to <br>
            content = content.replace(/\n/g, '<br>');
            
            return content;
        }
    }
}
</script>

<style>
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endif
