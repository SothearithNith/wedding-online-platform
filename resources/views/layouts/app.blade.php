<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Wedding Platform' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">

    <div x-data="{ sidebarOpen: false }" class="min-h-screen">

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden" style="display: none;"></div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-slate-200 bg-white transition-transform duration-300 lg:translate-x-0">

            {{-- Brand --}}
            <div class="flex h-16 items-center gap-3 border-b border-slate-100 px-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-600 text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M12 21s-8-4.5-8-11a4.5 4.5 0 0 1 8-2.9A4.5 4.5 0 0 1 20 10c0 6.5-8 11-8 11Z" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-sm font-bold text-slate-900">
                        Wedding Platform
                    </h1>
                    <p class="text-xs text-slate-400">
                        Administration
                    </p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 space-y-7 overflow-y-auto px-4 py-6">

                {{-- Overview --}}
                <div>
                    <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Overview
                    </p>

                    <a href="{{ url('/dashboard') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                       {{ request()->is('dashboard') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <span>▦</span>
                        Dashboard
                    </a>
                </div>

                {{-- Management --}}
                <div>
                    <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Management
                    </p>

                    <div class="space-y-1">
                        <a href="{{ url('/weddings') }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                           {{ request()->is('weddings*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <span>♡</span>
                            Weddings
                        </a>

                        <a href="{{ url('/temples') }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                           {{ request()->is('temples*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <span>⌂</span>
                            Temples
                        </a>

                        <a href="{{ url('/templates') }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                           {{ request()->is('templates*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <span>▧</span>
                            Templates
                        </a>
                    </div>
                </div>

                {{-- System --}}
                <div>
                    <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        System
                    </p>

                    <div class="space-y-1">
                        <a href="{{ url('/users') }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                           {{ request()->is('users*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <span>♙</span>
                            Users
                        </a>

                        <a href="{{ url('/settings') }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                           {{ request()->is('settings*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <span>⚙</span>
                            Settings
                        </a>
                    </div>
                </div>

            </nav>

            {{-- Sidebar Footer --}}
            <div class="border-t border-slate-100 p-4">
                <p class="text-xs font-medium text-slate-500">
                    Wedding Platform
                </p>
                <p class="mt-1 text-xs text-slate-400">
                    Version 1.0.0
                </p>
            </div>

        </aside>

        {{-- Main Content --}}
        <div class="min-h-screen lg:pl-64">

            {{-- Topbar --}}
            <header
                class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white/90 px-4 backdrop-blur sm:px-6 lg:px-8">

                <div class="flex items-center gap-4">

                    {{-- Mobile Menu --}}
                    <button type="button" @click="sidebarOpen = !sidebarOpen"
                        class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <h2 class="text-sm font-semibold text-slate-800">
                        {{ $title ?? 'Dashboard' }}
                    </h2>

                </div>

                {{-- User Profile --}}
                <div class="flex items-center gap-4">

                    <button type="button" class="relative rounded-lg p-2 text-slate-500 hover:bg-slate-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" />
                        </svg>
                    </button>

                    <div class="flex items-center gap-3 border-l border-slate-200 pl-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-violet-100 text-sm font-bold text-violet-700">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>

                        <div class="hidden sm:block">
                            <p class="text-sm font-semibold text-slate-800">
                                {{ auth()->user()->name ?? 'Administrator' }}
                            </p>
                            <p class="text-xs text-slate-400">
                                Administrator
                            </p>
                        </div>
                    </div>

                </div>

            </header>

            {{-- Page Content --}}
            <main class="p-4 sm:p-6 lg:p-8">

                {{ $slot }}

            </main>

        </div>

    </div>

    @livewireScripts

</body>

</html>
