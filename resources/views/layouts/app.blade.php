<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Wedding Platform') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">

    <div
        x-data="{ sidebarOpen: false }"
        class="min-h-screen"
    >
        {{-- Mobile sidebar backdrop --}}
        <div
            x-cloak
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-900/50 lg:hidden"
        ></div>

        {{-- Sidebar --}}
        @include('components.admin.sidebar')

        {{-- Main content --}}
        <div class="min-h-screen lg:pl-72">

            {{-- Navbar --}}
            @include('components.admin.navbar')

            {{-- Page content --}}
            <main class="min-h-[calc(100vh-8rem)] px-4 py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            @include('components.admin.footer')

        </div>
    </div>

    @livewireScripts
</body>
</html>
