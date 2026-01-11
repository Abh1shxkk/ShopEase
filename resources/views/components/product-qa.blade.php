@props(['product'])

<div class="mt-12 border-t border-slate-100 pt-12" x-data="{ showQuestionForm: false }">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-xl font-serif text-slate-900">Questions & Answers</h2>
        @auth
        <button @click="showQuestionForm = !showQuestionForm" class="text-[11px] font-bold tracking-widest uppercase text-slate-900 hover:text-slate-600 transition-colors">
            Ask a Question
        </button>
        @endauth
    </div>

    {{-- Question Form --}}
    @auth
    <div x-show="showQuestionForm" x-transition class="bg-slate-50 p-6 mb-8">
        <form action="{{ route('product.question.store', $product) }}" method="POST">
            @csrf
            <label class="text-[11px] font-bold tracking-widest uppercase text-slate-500 block mb-2">Your Question</label>
            <textarea name="question" rows="3" required minlength="10" maxlength="500" placeholder="What would you like to know about this product?" class="w-full px-4 py-3 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors resize-none mb-4"></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" @click="showQuestionForm = false" class="px-6 py-2 text-[11px] font-bold tracking-widest uppercase text-slate-500 hover:text-slate-900 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors">
                    Submit Question
                </button>
            </div>
        </form>
    </div>
    @endauth

    {{-- Questions List --}}
    @php
        $questions = $product->approvedQuestions()->with(['user', 'approvedAnswers.user'])->take(5)->get();
    @endphp

    @if($questions->count() > 0)
    <div class="space-y-6">
        @foreach($questions as $question)
        <div class="border-b border-slate-100 pb-6 last:border-0" x-data="{ showAnswerForm: false }">
            <div class="flex gap-3 mb-3">
                <span class="text-[11px] font-bold tracking-widest uppercase text-slate-400 mt-0.5">Q:</span>
                <div class="flex-1">
                    <p class="text-sm text-slate-900">{{ $question->question }}</p>
                    <p class="text-xs text-slate-400 mt-1">Asked by {{ $question->user->name }} · {{ $question->created_at->diffForHumans() }}</p>
                </div>
            </div>

            {{-- Answers --}}
            @foreach($question->approvedAnswers as $answer)
            <div class="flex gap-3 ml-6 mt-4 bg-slate-50 p-4">
                <span class="text-[11px] font-bold tracking-widest uppercase {{ $answer->is_seller_answer ? 'text-green-600' : 'text-slate-400' }} mt-0.5">A:</span>
                <div class="flex-1">
                    <p class="text-sm text-slate-700">{{ $answer->answer }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <p class="text-xs text-slate-400">
                            @if($answer->is_seller_answer)
                            <span class="text-green-600 font-medium">Seller</span>
                            @else
                            {{ $answer->user->name }}
                            @endif
                            · {{ $answer->created_at->diffForHumans() }}
                        </p>
                        @if($answer->helpful_count > 0)
                        <span class="text-xs text-slate-400">{{ $answer->helpful_count }} found this helpful</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Answer Form --}}
            @auth
            <button @click="showAnswerForm = !showAnswerForm" class="ml-6 mt-3 text-[10px] font-bold tracking-widest uppercase text-slate-500 hover:text-slate-900 transition-colors">
                Answer this question
            </button>
            <div x-show="showAnswerForm" x-transition class="ml-6 mt-3">
                <form action="{{ route('product.answer.store', $question) }}" method="POST">
                    @csrf
                    <textarea name="answer" rows="2" required minlength="10" maxlength="1000" placeholder="Share your answer..." class="w-full px-4 py-3 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors resize-none mb-2"></textarea>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showAnswerForm = false" class="px-4 py-1.5 text-[10px] font-bold tracking-widest uppercase text-slate-500 hover:text-slate-900 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-1.5 bg-slate-900 text-white text-[10px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
            @endauth
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-10 bg-slate-50">
        <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-slate-500 text-sm mb-4">No questions yet. Be the first to ask!</p>
        @auth
        <button @click="showQuestionForm = true" class="text-[11px] font-bold tracking-widest uppercase text-slate-900 hover:text-slate-600 transition-colors">
            Ask a Question
        </button>
        @else
        <a href="{{ route('login') }}" class="text-[11px] font-bold tracking-widest uppercase text-slate-900 hover:text-slate-600 transition-colors">
            Login to Ask a Question
        </a>
        @endauth
    </div>
    @endif
</div>
