{{-- resources/views/components/public/navbar.blade.php --}}
{{-- Uses $menu, $otherLocale, $otherLabel from the layout; `open` comes from <body x-data> --}}
@php
    // Flag of the language the user will switch TO: en_flag.webp / km_flag.webp
    $otherFlag = asset("assets/images/flags/{$otherLocale}_flag.webp");
@endphp

<div x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 40" class="sticky top-0 z-40">

    {{-- Top utility bar (desktop only, hides after scrolling) --}}
    <div x-show="!scrolled" x-transition.opacity.duration.200ms class="hidden bg-slate-900 text-slate-300 md:block">
        <div class="mx-auto flex h-10 max-w-7xl items-center justify-between px-4 text-xs sm:px-6 lg:px-8">
            <div class="flex items-center gap-6">
                <a href="mailto:hello@example.com" class="inline-flex items-center gap-2 transition hover:text-white">
                    <svg class="h-3.5 w-3.5 text-rose-400" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                    </svg>
                    hello@example.com
                </a>
                <a href="tel:+85500000000" class="inline-flex items-center gap-2 transition hover:text-white">
                    <svg class="h-3.5 w-3.5 text-rose-400" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 5a2 2 0 012-2h2.3a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.5 1.2l-1.6.8a11 11 0 005.3 5.3l.8-1.6a1 1 0 011.2-.5l3.3 1.1a1 1 0 01.7.95V19a2 2 0 01-2 2A16 16 0 013 5z" />
                    </svg>
                    +855 00 000 000
                </a>
            </div>

            <div class="flex items-center gap-5">
                <p class="hidden lg:block">
                    &hearts; {{ trans_json('New templates added') }} &mdash;
                    <a href="{{ url('/templates') }}" class="font-semibold text-rose-400 hover:text-rose-300">{{trans_json('Browse now')}}</a>
                </p>
                <a href="{{ route('lang.switch', $otherLocale) }}" hreflang="{{ $otherLocale }}"
                    class="inline-flex items-center gap-2 rounded-full border border-slate-700 py-1 pl-1.5 pr-3 font-medium transition hover:border-rose-400 hover:text-white">
                    <img src="{{ $otherFlag }}" alt="" width="20" height="20" loading="lazy"
                        class="h-5 w-5 rounded-full object-cover ring-1 ring-white/20">
                    {{ $otherLabel }}
                </a>
            </div>
        </div>
    </div>

    {{-- Main header --}}
    <header :class="scrolled ? 'shadow-lg shadow-slate-900/5 bg-white/95 h-20' : 'bg-white/85 h-20 lg:h-24'"
        class="flex items-center border-b border-slate-200/70 backdrop-blur-md transition-all duration-300">

        <div class="mx-auto flex w-full max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="group flex items-center gap-3 text-slate-900">
                <span
                    class="flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-rose-500 to-pink-500 text-white shadow-lg shadow-rose-500/30 transition duration-300 group-hover:rotate-6 group-hover:scale-110 lg:h-12 lg:w-12">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path
                            d="M12 21s-7.5-4.6-9.6-9.2C1 8.6 2.8 5 6.3 5c2 0 3.5 1.1 4.3 2.6h2.8C14.2 6.1 15.7 5 17.7 5 21.2 5 23 8.6 21.6 11.8 19.5 16.4 12 21 12 21z" />
                    </svg>
                </span>
                <span class="leading-tight">
                    <span class="block text-xl font-bold tracking-tight lg:text-2xl">Wedding<span
                            class="text-rose-500">Platform</span></span>
                    <span class="hidden text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400 sm:block">
                        Online invitations
                    </span>
                </span>
            </a>

            {{-- Desktop links (centered) --}}
            <nav class="hidden items-center gap-2 md:flex" aria-label="Main navigation">
                @foreach ($menu as $item)
                    <a href="{{ $item['href'] }}" @if ($item['active']) aria-current="page" @endif
                        class="group relative rounded-lg px-4 py-2 text-base font-medium transition
                            {{ $item['active'] ? 'text-rose-600' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ $item['label'] }}
                        <span
                            class="absolute inset-x-4 bottom-0 h-0.5 origin-left rounded-full bg-rose-500 transition-transform duration-300
                                {{ $item['active'] ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></span>
                    </a>
                @endforeach
            </nav>

            {{-- Desktop actions --}}
            <div class="hidden items-center gap-3 md:flex">
                @auth
                    <a href="{{ route('admin') }}"
                        class="rounded-full px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 hover:text-slate-900">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="rounded-full px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 hover:text-slate-900">
                        Login
                    </a>
                @endauth
                <a href="{{ url('/templates') }}"
                    class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-rose-500 to-pink-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-rose-500/30 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-rose-500/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-500 focus-visible:ring-offset-2">
                    Create Invitation
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </a>
            </div>

            {{-- Mobile toggle --}}
            <button type="button" @click="open = !open" :aria-expanded="open" aria-controls="mobile-menu"
                aria-label="Toggle menu"
                class="inline-flex h-11 w-11 items-center justify-center rounded-full text-slate-700 transition hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-500 md:hidden">
                <svg x-show="!open" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
                <svg x-show="open" x-cloak class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </div>
    </header>

    {{-- Mobile menu --}}
    <div id="mobile-menu" x-show="open" x-cloak @click.outside="open = false"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
        class="border-b border-slate-200 bg-white shadow-lg md:hidden">
        <div class="mx-auto max-w-7xl space-y-1 px-4 py-5 sm:px-6">
            @foreach ($menu as $item)
                <a href="{{ $item['href'] }}"
                    class="block rounded-xl px-4 py-3.5 text-lg font-medium
                        {{ $item['active'] ? 'bg-rose-50 text-rose-600' : 'text-slate-700 hover:bg-slate-100' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach

            <div class="space-y-3 border-t border-slate-100 pt-5">
                <a href="{{ url('/templates') }}"
                    class="block rounded-full bg-gradient-to-r from-rose-500 to-pink-500 px-4 py-3.5 text-center text-sm font-semibold text-white shadow-lg shadow-rose-500/30">
                    Create Invitation
                </a>
                <div class="flex items-center gap-3">
                    <a href="{{ route('lang.switch', $otherLocale) }}"
                        class="flex flex-1 items-center justify-center gap-2 rounded-full border border-slate-200 px-3 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100">
                        <img src="{{ $otherFlag }}" alt="" width="20" height="20" loading="lazy"
                            class="h-5 w-5 rounded-full object-cover ring-1 ring-slate-200">
                        {{ $otherLabel }}
                    </a>
                    @auth
                        <a href="{{ route('admin') }}"
                            class="flex-1 rounded-full bg-slate-900 px-3 py-3 text-center text-sm font-semibold text-white hover:bg-slate-700">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="flex-1 rounded-full bg-slate-900 px-3 py-3 text-center text-sm font-semibold text-white hover:bg-slate-700">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
