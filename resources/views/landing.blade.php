<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- SEO Meta Tags --}}
    <title>ShopEase | Shop Smart, Live Better</title>
    <meta name="description" content="Discover quality products at unbeatable prices. ShopEase is your one-stop destination for fashion, accessories, home goods, and more with fast delivery.">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Critical CSS - Loads BEFORE everything else to prevent FOUC --}}
    <style>
        /* Hide Alpine.js elements until initialized */
        [x-cloak] { display: none !important; }
        
        /* Mobile menu MUST be hidden on page load */
        .mobile-menu { 
            opacity: 0 !important; 
            visibility: hidden !important; 
            pointer-events: none !important;
        }
        .mobile-menu.open { 
            opacity: 1 !important; 
            visibility: visible !important; 
            pointer-events: auto !important;
        }
        
        /* Header/Navbar critical styles - start transparent for hero overlay */
        #main-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
        }
        #navbar {
            background: transparent;
            border-bottom: 1px solid transparent;
        }
        
        /* Prevent any transition flicker on load */
        html.loading * { 
            transition: none !important; 
        }
    </style>
    
    {{-- Styles --}}
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js', 'resources/js/landing.js'])
</head>
<body class="bg-white text-slate-900 overflow-x-hidden antialiased" style="font-family: 'Inter', sans-serif;">
    
    {{-- Film Grain Overlay for Luxury Aesthetic --}}
    <div class="film-grain"></div>
    
    {{-- Main Content Wrapper --}}
    <div class="relative min-h-screen">
        
        {{-- Navbar --}}
        @include('partials.landing.navbar')
        
        {{-- Main Content --}}
        <main class="pt-16">
            {{-- Hero Slider --}}
            @include('partials.landing.hero-slider')
            
            {{-- Category Showcase --}}
            @include('partials.landing.category-showcase')
            
            {{-- Heritage Narrative --}}
            @include('partials.landing.heritage-narrative')
            
            {{-- Artisan Spotlight --}}
            @include('partials.landing.artisan-spotlight')
            
            {{-- Product Grid --}}
            @include('partials.landing.product-grid')
            
            {{-- Journal Section --}}
            @include('partials.landing.journal-section')
            
            {{-- Instagram Feed --}}
            @include('partials.landing.instagram-feed')
        </main>
        
        {{-- Footer --}}
        @include('partials.landing.footer')
        
        {{-- Chat Widget --}}
        @include('partials.landing.chat-widget')
    </div>
    
    {{-- Additional Scripts Stack --}}
    @stack('scripts')
</body>
</html>
