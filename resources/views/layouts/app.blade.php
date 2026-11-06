<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Wedding Platform' }}</title>

    {{-- Khmer-friendly font stack --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+Khmer:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', 'Noto Sans Khmer', ui-sans-serif, system-ui, sans-serif;
        }

        html[lang="km"] body {
            font-family: 'Noto Sans Khmer', 'Inter', ui-sans-serif, system-ui, sans-serif;
            line-height: 1.7;
        }
    </style>
</head>

{{-- Shared state: sidebarOpen = mobile drawer, collapsed = desktop icon-only (remembered) --}}

<body class="bg-slate-50 text-slate-800 antialiased" x-data="{
    sidebarOpen: false,
    collapsed: (() => { try { return JSON.parse(localStorage.getItem('sidebarCollapsed') ?? 'false') } catch { return false } })()
}" x-init="$watch('collapsed', v => { try { localStorage.setItem('sidebarCollapsed', JSON.stringify(v)) } catch {} })"
    @keydown.escape.window="sidebarOpen = false">

    {{-- Mobile overlay --}}
    <div x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden"></div>

    @include('components.admin.sidebar')

    <div class="flex min-h-screen flex-col transition-[padding] duration-300"
        :class="collapsed ? 'lg:pl-20' : 'lg:pl-72'">

        @include('components.admin.navbar')

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        @include('components.admin.footer')
    </div>
</body>

</html>
