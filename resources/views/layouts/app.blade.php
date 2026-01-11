<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ShopEase') }} - @yield('title', 'Shop Smart, Live Better')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        :root {
            --background: 0 0% 100%;
            --foreground: 222.2 84% 4.9%;
            --card: 0 0% 100%;
            --card-foreground: 222.2 84% 4.9%;
            --popover: 0 0% 100%;
            --popover-foreground: 222.2 84% 4.9%;
            --primary: 222.2 47.4% 11.2%;
            --primary-foreground: 210 40% 98%;
            --secondary: 210 40% 96.1%;
            --secondary-foreground: 222.2 47.4% 11.2%;
            --muted: 210 40% 96.1%;
            --muted-foreground: 215.4 16.3% 46.9%;
            --accent: 210 40% 96.1%;
            --accent-foreground: 222.2 47.4% 11.2%;
            --destructive: 0 84.2% 60.2%;
            --destructive-foreground: 210 40% 98%;
            --border: 214.3 31.8% 91.4%;
            --input: 214.3 31.8% 91.4%;
            --ring: 222.2 84% 4.9%;
            --radius: 0.5rem;
        }
        body { font-family: 'Inter', system-ui, sans-serif; }
        .btn { display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; transition: all 150ms; outline: none; }
        .btn:focus-visible { outline: 2px solid hsl(var(--ring)); outline-offset: 2px; }
        .btn:disabled { pointer-events: none; opacity: 0.5; }
        .btn-primary { background-color: hsl(var(--primary)); color: hsl(var(--primary-foreground)); height: 2.75rem; padding: 0 1.25rem; }
        .btn-primary:hover { background-color: hsl(var(--primary) / 0.9); }
        .btn-outline { border: 1px solid hsl(var(--input)); background-color: hsl(var(--background)); height: 2.75rem; padding: 0 1.25rem; }
        .btn-outline:hover { background-color: hsl(var(--accent)); color: hsl(var(--accent-foreground)); }
        .btn-ghost { height: 2.5rem; padding: 0 1rem; }
        .btn-ghost:hover { background-color: hsl(var(--accent)); color: hsl(var(--accent-foreground)); }
        .input { display: flex; height: 2.75rem; width: 100%; border-radius: 0.5rem; border: 1px solid hsl(var(--input)); background-color: hsl(var(--background)); padding: 0.5rem 0.75rem; font-size: 0.875rem; transition: all 150ms; }
        .input::placeholder { color: hsl(var(--muted-foreground)); }
        .input:focus { outline: none; border-color: hsl(var(--ring)); box-shadow: 0 0 0 2px hsl(var(--ring) / 0.1); }
        .input:disabled { cursor: not-allowed; opacity: 0.5; }
        .input-error { border-color: hsl(var(--destructive)); }
        .input-error:focus { box-shadow: 0 0 0 2px hsl(var(--destructive) / 0.1); }
        .label { font-size: 0.875rem; font-weight: 500; color: hsl(var(--foreground)); }
        .card { border-radius: 0.75rem; border: 1px solid hsl(var(--border)); background-color: hsl(var(--card)); box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); }
        .checkbox { height: 1rem; width: 1rem; border-radius: 0.25rem; border: 1px solid hsl(var(--primary)); accent-color: hsl(var(--primary)); }
        .text-muted { color: hsl(var(--muted-foreground)); }
        .divider { display: flex; align-items: center; gap: 1rem; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background-color: hsl(var(--border)); }
    </style>
</head>
<body class="antialiased bg-white">
    @yield('content')
    @stack('scripts')
</body>
</html>
