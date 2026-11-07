<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Wedding Platform' }} | Wedding Platform</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (app()->getLocale() === 'km')
        {{-- Khmer Font --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@100..900&display=swap">
        <style>
            body,
            .title,
            .title-two,
            .footer__widget-title {
                font-family: "Noto Sans Khmer", sans-serif;
                font-stretch: condensed;
            }
        </style>
    @else
        {{-- English Font --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap">
        <style>
            body,
            .title,
            .title-two,
            .footer__widget-title {
                font-family: "Space Grotesk", sans-serif;
            }
        </style>
    @endif

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @livewireStyles
</head>

@php
    $locale = app()->getLocale();
    $otherLocale = $locale === 'km' ? 'en' : 'km';
    $otherLabel = $locale === 'km' ? 'English' : 'ខ្មែរ';

    // Adjust URLs to match your app.
    $menu = [
        ['label' => 'Home', 'href' => url('/'), 'active' => request()->is('/')],
        ['label' => 'Templates', 'href' => url('/templates'), 'active' => request()->is('templates*')],
    ];

    $globeIcon =
        'M12 21a9 9 0 100-18 9 9 0 000 18zM3.6 9h16.8M3.6 15h16.8M12 3c2.5 2.5 3.8 5.5 3.8 9s-1.3 6.5-3.8 9c-2.5-2.5-3.8-5.5-3.8-9S9.5 5.5 12 3z';
@endphp

<body class="relative flex min-h-screen flex-col overflow-x-hidden bg-gradient-to-b from-rose-50/60 via-slate-50 to-slate-50 text-slate-700 antialiased"
    x-data="{ open: false, showTop: false }" @keydown.escape.window="open = false"
    @scroll.window="showTop = window.scrollY > 400">

    {{-- Decorative background blobs --}}
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[32rem] overflow-hidden" aria-hidden="true">
        <div class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-rose-200/40 blur-3xl"></div>
        <div class="absolute -right-16 top-40 h-80 w-80 rounded-full bg-pink-200/40 blur-3xl"></div>
    </div>

    <a href="#content"
        class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-md focus:bg-white focus:px-4 focus:py-2 focus:shadow">
        Skip to content
    </a>

    @include('components.public.navbar')

    {{-- Optional full-width hero: use @push('hero') ... @endpush in a page view --}}
    @stack('hero')

    <main id="content" class="mx-auto w-full max-w-7xl flex-1 px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
        {{ $slot }}
    </main>

    @include('components.public.footer')

    {{-- Floating back-to-top button --}}
    <button type="button" x-show="showTop" x-cloak x-transition.opacity.scale.90
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })" aria-label="Back to top"
        class="fixed bottom-6 right-6 z-40 flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-rose-500 to-pink-500 text-white shadow-lg shadow-rose-500/40 transition hover:-translate-y-1">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5M6 11l6-6 6 6" />
        </svg>
    </button>

    @livewireScripts
</body>

</html>
