<header x-data="{ scrolled: false }" x-init="scrolled = window.scrollY > 4" @scroll.window.passive="scrolled = window.scrollY > 4"
    :class="scrolled ? 'shadow-sm border-slate-200' : 'border-transparent'"
    class="sticky top-0 z-30 flex h-16 items-center justify-between gap-3 border-b bg-white/80 px-4 backdrop-blur-md transition-shadow sm:h-20 sm:px-6 lg:px-8">

    <div class="flex min-w-0 items-center gap-2 sm:gap-3">
        <button type="button" @click="sidebarOpen = true"
            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-slate-600 transition hover:bg-slate-100 lg:hidden"
            aria-label="{{ __('Open menu') }}">
            <x-heroicon-o-bars-3 class="h-6 w-6" />
        </button>
        <button type="button" @click="collapsed = !collapsed"
            class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 lg:flex"
            :aria-label="collapsed ? '{{ __('Expand sidebar') }}' : '{{ __('Collapse sidebar') }}'">
            <x-heroicon-o-bars-3-center-left class="h-6 w-6" />
        </button>

        <div class="min-w-0">
            <h2 class="truncate text-base font-bold text-slate-900 sm:text-lg">{{ $title ?? __('Dashboard') }}</h2>
            <p class="hidden truncate text-xs text-slate-500 md:block">
                {{ trans_json('Welcome to your wedding management system.') }}
            </p>
        </div>
    </div>

    <div class="flex shrink-0 items-center gap-1.5 sm:gap-3">
        @php
            $isKhmer = app()->getLocale() === 'km';
            $nextLocale = $isKhmer ? 'en' : 'km';
            $currentFlag = $isKhmer ? 'assets/images/flags/kh_flag.webp' : 'assets/images/flags/en_flag.webp';
            $currentLanguage = $isKhmer ? 'ខ្មែរ' : 'English';
        @endphp

        <a href="{{ route('language.switch', $nextLocale) }}" title="{{ __('Switch language') }}"
            class="flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-2.5 text-sm font-medium text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50/50 sm:px-3">
            <img src="{{ asset($currentFlag) }}" alt="{{ $currentLanguage }}"
                class="h-4 w-6 rounded-sm object-cover shadow-sm">
            <span class="hidden sm:block">{{ $currentLanguage }}</span>
            <x-heroicon-o-chevron-up-down class="hidden h-4 w-4 text-slate-400 sm:block" />
        </a>

        <div x-data="{ open: false }" class="relative">
            <button type="button" @click="open = !open" @click.outside="open = false" @keydown.escape="open = false"
                class="relative flex h-10 w-10 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-indigo-600"
                    title="{{ trans_json('Notifications') }}" aria-label="{{ __('Notifications') }}">
                    <x-heroicon-o-bell class="h-6 w-6" />
                <span class="absolute right-2.5 top-2.5 flex h-2.5 w-2.5">
                    <span
                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white"></span>
                </span>
            </button>

            <div x-cloak x-show="open" x-transition.origin.top.right
                class="absolute right-0 mt-3 w-72 max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl sm:w-80 max-sm:fixed max-sm:inset-x-4 max-sm:right-auto max-sm:top-[4.5rem] max-sm:w-auto">
                <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                    <p class="text-sm font-semibold text-slate-800">{{ __('Notifications') }}</p>
                    <span
                        class="rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-600">{{ __('New') }}</span>
                </div>
                <div class="flex flex-col items-center gap-2 px-4 py-10 text-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <x-heroicon-o-bell-slash class="h-6 w-6" />
                    </div>
                    <p class="text-sm text-slate-500">{{ __('You are all caught up.') }}</p>
                </div>
            </div>
        </div>

        <div x-data="{ open: false }" class="relative">
            <button type="button" @click="open = !open" @click.outside="open = false" @keydown.escape="open = false"
                :aria-expanded="open"
                class="flex items-center gap-2 rounded-xl p-1 transition hover:bg-slate-100 sm:pr-2">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 font-semibold text-white">
                    {{ mb_strtoupper(mb_substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                </div>
                <span class="hidden max-w-32 truncate text-sm font-medium text-slate-700 md:block">
                    {{ auth()->user()?->name }}
                </span>
                <x-heroicon-o-chevron-down class="hidden h-4 w-4 text-slate-400 transition-transform md:block"
                    ::class="open ? 'rotate-180' : ''" />
            </button>

            <div x-cloak x-show="open" x-transition.origin.top.right
                class="absolute right-0 mt-3 w-60 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl">
                <div class="mb-1 rounded-xl bg-slate-50 px-3 py-2.5">
                    <p class="truncate text-sm font-semibold text-slate-800">{{ auth()->user()?->name }}</p>
                    <p class="truncate text-xs text-slate-500">{{ auth()->user()?->email }}</p>
                </div>

                <a href="#"
                    class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                    <x-heroicon-o-user-circle class="h-5 w-5" /> {{ __('My profile') }}
                </a>

                <div class="my-1 border-t border-slate-100"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm text-red-600 transition hover:bg-red-50">
                        <x-heroicon-o-arrow-right-on-rectangle class="h-5 w-5" /> {{ __('Sign out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
