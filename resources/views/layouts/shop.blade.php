<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Shop') | ShopEase</title>
    <meta name="description" content="@yield('description', 'Discover quality products at unbeatable prices.')">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js', 'resources/js/landing.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-white text-slate-900 overflow-x-hidden">
    
    {{-- Film Grain Overlay --}}
    <div class="film-grain"></div>
    
    {{-- Navbar --}}
    @include('partials.landing.navbar')
    
    {{-- Main Content --}}
    <main class="pt-12 min-h-screen">
        @yield('content')
    </main>
    
    {{-- Footer --}}
    @include('partials.landing.footer')
    
    {{-- Toast Container --}}
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 space-y-3"></div>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `flex items-center gap-3 px-5 py-4 shadow-2xl transform transition-all duration-500 translate-x-full ${type === 'success' ? 'bg-slate-900 text-white' : 'bg-red-600 text-white'}`;
            toast.innerHTML = `
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'}
                </svg>
                <span class="font-medium text-sm">${message}</span>
            `;
            container.appendChild(toast);
            requestAnimationFrame(() => toast.classList.remove('translate-x-full'));
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('scripts')
</body>
</html>
