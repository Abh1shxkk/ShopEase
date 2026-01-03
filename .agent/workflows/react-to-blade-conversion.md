---
description: Convert React landing page to Laravel Blade format - Component by component migration
---

# React to Laravel Blade Landing Page Conversion Plan

## Overview
This plan outlines the step-by-step process to convert the React landing page from `luxcommerce` to Laravel Blade format in the `ShopEase` project. All content will be **static** initially - functionality will be added later.

---

## Source Project Analysis

### React Components to Convert (in order of appearance):
| Component | React File | Blade Destination | Priority |
|-----------|------------|-------------------|----------|
| Navbar | `components/Navbar.tsx` | `partials/landing/navbar.blade.php` | 1 |
| HeroSlider | `components/HeroSlider.tsx` | `partials/landing/hero-slider.blade.php` | 2 |
| CategoryShowcase | `components/CategoryShowcase.tsx` | `partials/landing/category-showcase.blade.php` | 3 |
| HeritageNarrative | `App.tsx` (inline) | `partials/landing/heritage-narrative.blade.php` | 4 |
| ArtisanSpotlight | `App.tsx` (inline) | `partials/landing/artisan-spotlight.blade.php` | 5 |
| ProductGrid | `components/ProductGrid.tsx` | `partials/landing/product-grid.blade.php` | 6 |
| JournalSection | `App.tsx` (inline) | `partials/landing/journal-section.blade.php` | 7 |
| InstagramFeed | `App.tsx` (inline) | `partials/landing/instagram-feed.blade.php` | 8 |
| Footer | `components/Footer.tsx` | `partials/landing/footer.blade.php` | 9 |
| AIShoppingAssistant | `components/AIShoppingAssistant.tsx` | `partials/landing/chat-widget.blade.php` | 10 (Optional) |

### Data Files:
- `constants.tsx` - Contains HERO_SLIDES, PRODUCTS, CATEGORIES, BRANDS data
- `types.ts` - TypeScript interfaces (for reference only)

---

## Directory Structure to Create

```
resources/
├── views/
│   ├── landing-new.blade.php          # New main landing page
│   └── partials/
│       └── landing/
│           ├── navbar.blade.php
│           ├── hero-slider.blade.php
│           ├── category-showcase.blade.php
│           ├── heritage-narrative.blade.php
│           ├── artisan-spotlight.blade.php
│           ├── product-grid.blade.php
│           ├── journal-section.blade.php
│           ├── instagram-feed.blade.php
│           ├── footer.blade.php
│           └── chat-widget.blade.php
├── css/
│   └── landing.css                    # Custom landing page styles
```

---

## Implementation Steps

### Phase 1: Setup & Preparation

#### Step 1.1: Create directory structure
Create the `partials/landing` directory in resources/views.

#### Step 1.2: Add required fonts and styles
- Add Google Fonts (Inter + Playfair Display) to the landing page
- Create `landing.css` with custom styles (hide-scrollbar, film-grain, animations)

#### Step 1.3: Ensure TailwindCSS is configured
The existing Laravel app uses Vite + TailwindCSS. Verify the configuration supports all the utility classes used in React components.

---

### Phase 2: Component-by-Component Conversion

#### Step 2.1: Convert Navbar
**Source:** `components/Navbar.tsx`
**Target:** `partials/landing/navbar.blade.php`

Key elements to include (static HTML):
- Announcement bar (Free Shipping text)
- Navigation links (Women, Men, Accessories, Stores)
- Logo (RES*I*PSA)
- Icon buttons (Search, User, Heart, Shopping Bag)
- Mobile menu overlay (hidden by default)

**JavaScript needed:** 
- Scroll detection for navbar background change
- Mobile menu toggle

#### Step 2.2: Convert Hero Slider
**Source:** `components/HeroSlider.tsx` + `constants.tsx`
**Target:** `partials/landing/hero-slider.blade.php`

Static data to embed:
```php
@php
$heroSlides = [
    [
        'id' => 1,
        'title' => 'SHOP KILIM CLOGS',
        'subtitle' => 'Hand-crafted comfort using vintage textiles.',
        'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&q=80&w=1600',
    ],
    [
        'id' => 2,
        'title' => 'THE ART OF WANDERING',
        'subtitle' => 'Premium weekender bags for your next journey.',
        'image' => 'https://images.unsplash.com/photo-1547949003-9792a18a2601?auto=format&fit=crop&q=80&w=1600',
    ],
    [
        'id' => 3,
        'title' => 'VINTAGE TEXTILE SOULS',
        'subtitle' => 'Each piece tells a story of heritage and craft.',
        'image' => 'https://images.unsplash.com/photo-1523381294911-8d3cead13475?auto=format&fit=crop&q=80&w=1600',
    ],
];
@endphp
```

**JavaScript needed:** Auto-slide every 8 seconds, dot navigation

#### Step 2.3: Convert Category Showcase
**Source:** `components/CategoryShowcase.tsx` + `constants.tsx`
**Target:** `partials/landing/category-showcase.blade.php`

Static categories:
```php
@php
$categories = [
    ['id' => 'c1', 'name' => 'Footwear', 'image' => 'https://...', 'itemCount' => 42],
    ['id' => 'c2', 'name' => 'Travel Bags', 'image' => 'https://...', 'itemCount' => 18],
    ['id' => 'c3', 'name' => 'Home Goods', 'image' => 'https://...', 'itemCount' => 56],
    ['id' => 'c4', 'name' => 'New Arrivals', 'image' => 'https://...', 'itemCount' => 12],
];
@endphp
```

#### Step 2.4: Convert Heritage Narrative
**Source:** `App.tsx` (HeritageNarrative component)
**Target:** `partials/landing/heritage-narrative.blade.php`

Pure static HTML with images and text. No JavaScript needed.

#### Step 2.5: Convert Artisan Spotlight
**Source:** `App.tsx` (ArtisanSpotlight component)
**Target:** `partials/landing/artisan-spotlight.blade.php`

Pure static HTML with parallax background. No JavaScript needed (CSS handles parallax).

#### Step 2.6: Convert Product Grid
**Source:** `components/ProductGrid.tsx` + `constants.tsx`
**Target:** `partials/landing/product-grid.blade.php`

Static products:
```php
@php
$products = [
    ['id' => 'p1', 'name' => 'Kilim Weekender Bag', 'price' => 745.00, 'category' => 'Travel', 'image' => '...', 'tag' => 'Artisanal'],
    // ... more products
];
@endphp
```

**JavaScript needed:** Horizontal scroll with arrow buttons

#### Step 2.7: Convert Journal Section
**Source:** `App.tsx` (JournalSection component)
**Target:** `partials/landing/journal-section.blade.php`

Static blog posts data. No JavaScript needed.

#### Step 2.8: Convert Instagram Feed
**Source:** `App.tsx` (InstagramFeed component)
**Target:** `partials/landing/instagram-feed.blade.php`

Static image grid. No JavaScript needed.

#### Step 2.9: Convert Footer
**Source:** `components/Footer.tsx`
**Target:** `partials/landing/footer.blade.php`

Static HTML with links, newsletter form (non-functional), social icons.

#### Step 2.10: Convert Chat Widget (Optional)
**Source:** `components/AIShoppingAssistant.tsx`
**Target:** `partials/landing/chat-widget.blade.php`

Static UI only - chat bubble button and modal. AI functionality will be added later.

---

### Phase 3: Assemble Main Landing Page

#### Step 3.1: Create new landing page
**Target:** `resources/views/landing-new.blade.php`

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LUX | Premium Artisanal Goods</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/landing.js'])
</head>
<body class="bg-white text-slate-900 overflow-x-hidden" style="font-family: 'Inter', sans-serif;">
    <!-- Film Grain Overlay -->
    <div class="film-grain"></div>
    
    <div class="relative min-h-screen">
        @include('partials.landing.navbar')
        
        <main class="pt-[100px]">
            @include('partials.landing.hero-slider')
            @include('partials.landing.category-showcase')
            @include('partials.landing.heritage-narrative')
            @include('partials.landing.artisan-spotlight')
            @include('partials.landing.product-grid')
            @include('partials.landing.journal-section')
            @include('partials.landing.instagram-feed')
        </main>
        
        @include('partials.landing.footer')
        @include('partials.landing.chat-widget')
    </div>
    
    @stack('scripts')
</body>
</html>
```

---

### Phase 4: JavaScript Implementation

#### Step 4.1: Create landing.js
**Target:** `resources/js/landing.js`

Functions needed:
- `initNavbar()` - Scroll detection, mobile menu toggle
- `initHeroSlider()` - Auto-slide, dot navigation
- `initProductSlider()` - Horizontal scroll with arrows
- `initChatWidget()` - Open/close chat modal

---

### Phase 5: CSS Implementation

#### Step 5.1: Create landing.css
**Target:** `resources/css/landing.css`

```css
/* Typography */
h1, h2, h3, h4, .font-serif {
    font-family: 'Playfair Display', serif;
}

/* Hide scrollbar utility */
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Film grain overlay */
.film-grain {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 100;
    opacity: 0.05;
    background-image: url("https://www.transparenttextures.com/patterns/stardust.png");
}
```

---

### Phase 6: Route Configuration

#### Step 6.1: Add or update route
In `routes/web.php`, update the landing page route:

```php
Route::get('/', function () {
    return view('landing-new');
})->name('home');
```

---

## Conversion Checklist

- [ ] Phase 1: Setup
  - [ ] Create `partials/landing` directory
  - [ ] Create `landing.css`
  - [ ] Verify TailwindCSS config

- [ ] Phase 2: Components
  - [ ] Navbar
  - [ ] Hero Slider
  - [ ] Category Showcase
  - [ ] Heritage Narrative
  - [ ] Artisan Spotlight
  - [ ] Product Grid
  - [ ] Journal Section
  - [ ] Instagram Feed
  - [ ] Footer
  - [ ] Chat Widget

- [ ] Phase 3: Main Page
  - [ ] Create `landing-new.blade.php`
  - [ ] Include all partials

- [ ] Phase 4: JavaScript
  - [ ] Create `landing.js`
  - [ ] Implement all interactions

- [ ] Phase 5: CSS
  - [ ] Create `landing.css`
  - [ ] Add all custom styles

- [ ] Phase 6: Routes
  - [ ] Update web.php

---

## Notes

1. **Static First**: All data is hardcoded in blade files using `@php` blocks. This makes conversion straightforward and functionality can be added later.

2. **Lucide Icons**: The React version uses `lucide-react` for icons. In Blade, we'll use inline SVG icons from the same Lucide library (copy SVG paths).

3. **Tailwind Classes**: All Tailwind classes should work as-is since both projects use TailwindCSS. Just convert JSX `className` to HTML `class`.

4. **Image URLs**: External Unsplash URLs are used. These can be replaced with local assets later.

5. **Interactive Elements**: 
   - Mobile menu toggle
   - Hero slider autoplay
   - Product grid scroll
   - Chat widget open/close
   
   All will use vanilla JavaScript in `landing.js`.
