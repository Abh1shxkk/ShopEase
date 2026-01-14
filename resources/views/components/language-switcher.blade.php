{{-- Language Switcher Component --}}
@php
    $currentLocale = app()->getLocale();
    $locales = [
        'en' => ['name' => 'English', 'native' => 'EN', 'flag' => '🇬🇧'],
        'hi' => ['name' => 'Hindi', 'native' => 'हि', 'flag' => '🇮🇳'],
    ];
@endphp

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" @click.away="open = false" 
        class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-slate-100 transition-colors rounded">
        <span class="text-base">{{ $locales[$currentLocale]['flag'] }}</span>
        <span class="hidden sm:inline">{{ $locales[$currentLocale]['native'] }}</span>
        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-40 bg-white border border-slate-200 shadow-lg z-50">
        @foreach($locales as $code => $locale)
        <a href="{{ route('language.switch', $code) }}" 
           class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-slate-50 transition-colors {{ $currentLocale === $code ? 'bg-slate-50 font-medium' : '' }}">
            <span class="text-base">{{ $locale['flag'] }}</span>
            <span>{{ $locale['name'] }}</span>
            @if($currentLocale === $code)
            <svg class="w-4 h-4 ml-auto text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            @endif
        </a>
        @endforeach
    </div>
</div>