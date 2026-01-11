@props(['product'])

@php
    $sizeGuide = $product->sizeGuide();
@endphp

@if($sizeGuide)
<div x-data="{ open: false }">
    <button @click="open = true" type="button" class="text-[11px] font-bold tracking-widest uppercase text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        Size Guide
    </button>

    {{-- Modal --}}
    <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-black/50" @click="open = false"></div>
        <div class="relative bg-white max-w-2xl w-full max-h-[90vh] overflow-y-auto p-8" @click.stop>
            <button @click="open = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h2 class="text-2xl font-serif text-slate-900 mb-2">{{ $sizeGuide->name }}</h2>
            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400 mb-6">{{ ucfirst($sizeGuide->type) }} Size Guide</p>

            {{-- Size Chart Table --}}
            @if($sizeGuide->measurements)
            <div class="overflow-x-auto mb-6">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50">
                            @foreach(array_keys($sizeGuide->measurements[0] ?? []) as $header)
                            <th class="px-4 py-3 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500 border-b border-slate-100">{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sizeGuide->measurements as $row)
                        <tr class="border-b border-slate-100">
                            @foreach($row as $value)
                            <td class="px-4 py-3 text-slate-600">{{ $value }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Fit Tips --}}
            @if($sizeGuide->fit_tips)
            <div class="bg-slate-50 p-4">
                <h3 class="text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">Fit Tips</h3>
                <p class="text-sm text-slate-600">{{ $sizeGuide->fit_tips }}</p>
            </div>
            @endif

            <div class="mt-6 text-center">
                <p class="text-xs text-slate-400">All measurements are in {{ $sizeGuide->type === 'shoes' ? 'US sizes' : 'inches' }}</p>
            </div>
        </div>
    </div>
</div>
@endif
