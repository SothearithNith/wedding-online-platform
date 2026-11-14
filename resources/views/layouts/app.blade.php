<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Wedding Platform' }}</title> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@100..900&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Moul&display=swap" rel="stylesheet">

    @if (app()->getLocale() === 'km')
        {{-- Khmer Font --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@100..900&display=swap">

        <style>
            body,
            .title,
            .title-two,
            .footer__widget-title {
                font-family: "Noto Sans Khmer", sans-serif;
                font-stretch: condensed;
            }
        </style>
        <style>
            [x-cloak] {
                display: none !important
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('swal', (event) => {
                    const d = Array.isArray(event) ? event[0] : event;
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: d.icon ?? 'success',
                        title: d.title ?? '',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                });
            });
        </script>
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

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @stack('styles')
</head>

{{-- Shared state: sidebarOpen = mobile drawer, collapsed = desktop icon-only (remembered) --}}

<body data-locale="{{ app()->getLocale() }}" class="bg-slate-50 text-slate-800 antialiased" x-data="{
    sidebarOpen: false,
    collapsed: (() => {
        try { return JSON.parse(localStorage.getItem('sidebarCollapsed') ?? 'false') } catch (e) { return false }
    })()
}"
    x-init="$watch('collapsed', v => {
        try { localStorage.setItem('sidebarCollapsed', JSON.stringify(v)) } catch (e) {}
    });
    $watch('sidebarOpen', v => document.body.classList.toggle('overflow-hidden', v));" x-on:keydown.escape.window="sidebarOpen = false"
    x-on:resize.window="if (window.innerWidth >= 1024) sidebarOpen = false"
    x-on:livewire:navigated.window="sidebarOpen = false">

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

    @livewireScripts
    @stack('scripts')
</body>

</html>
