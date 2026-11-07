<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Wedding Platform' }}</title>

    {{-- Khmer-friendly font stack --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+Khmer:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @if (app()->getLocale() === 'km')
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@100..900&display=swap"
            rel="stylesheet">

        <style>
            body,
            .title,
            .title-two,
            .footer__widget-title {
                font-family: 'Noto Sans Khmer', sans-serif;
            }
        </style>
    @else
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">

        <style>
            body,
            .title,
            .title-two,
            .footer__widget-title {
                font-family: 'Space Grotesk', sans-serif;
            }
        </style>
    @endif
</head>

{{-- Shared state: sidebarOpen = mobile drawer, collapsed = desktop icon-only (remembered) --}}

<body class="bg-slate-50 text-slate-800 antialiased" x-data="{
    sidebarOpen: false,
    collapsed: (() => { try { return JSON.parse(localStorage.getItem('sidebarCollapsed') ?? 'false') } catch { return false } })()
}" x-init="$watch('collapsed', v => { try { localStorage.setItem('sidebarCollapsed', JSON.stringify(v)) } catch {} });
$watch('sidebarOpen', v => document.body.classList.toggle('overflow-hidden', v));"
    @keydown.escape.window="sidebarOpen = false" @resize.window="if (window.innerWidth >= 1024) sidebarOpen = false">

    {{-- Mobile overlay --}}
    <div x-cloak x-show="sidebarOpen" x-transition.opacity.duration.200ms @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden" aria-hidden="true"></div>

    @include('components.admin.sidebar')

    <div class="flex min-h-screen min-w-0 flex-col transition-[padding] duration-300 ease-out"
        :class="collapsed ? 'lg:pl-20' : 'lg:pl-72'">

        @include('components.admin.navbar')

        <main class="min-w-0 flex-1 p-4 sm:p-6 lg:p-8">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        @include('components.admin.footer')
    </div>
</body>

</html>
