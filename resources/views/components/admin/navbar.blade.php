<header x-data="{ scrolled: false }" x-init="scrolled = window.scrollY > 4" @scroll.window.passive="scrolled = window.scrollY > 4"
    :class="scrolled ? 'shadow-sm border-slate-200' : 'border-transparent'"
    class="sticky top-0 z-30 flex h-16 items-center justify-between gap-3 border-b bg-white/80 px-4 backdrop-blur-md transition-shadow sm:h-20 sm:px-6 lg:px-8">

    {{-- Left --}}
    <div class="flex min-w-0 items-center gap-2 sm:gap-3">
        <button type="button" @click="sidebarOpen = true"
            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-slate-600 transition hover:bg-slate-100 lg:hidden"
            aria-label="{{ trans_json('Open menu') }}">
            <x-heroicon-o-bars-3 class="h-6 w-6" />
        </button>

        <button type="button" @click="collapsed = !collapsed"
            class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 lg:flex"
            :aria-label="collapsed ? @js(trans_json('Expand sidebar')) : @js(trans_json('Collapse sidebar'))">
            <x-heroicon-o-bars-3-center-left class="h-6 w-6" />
        </button>

        <div class="min-w-0">
            <h2 class="truncate text-base font-bold text-slate-900 sm:text-lg">
                {{ trans_json($title) ?? trans_json('Dashboard') }}</h2>
            <p class="hidden truncate text-xs text-slate-500 md:block">
                {{ trans_json('Welcome to your wedding management system.') }}
            </p>
        </div>
    </div>

    <div class="flex shrink-0 items-center gap-1.5 sm:gap-3">
        @php
            $languages = [
                'km' => ['name' => 'ខ្មែរ', 'flag' => 'assets/images/flags/km_flag.webp'],
                'en' => ['name' => 'English', 'flag' => 'assets/images/flags/en_flag.webp'],
            ];
            $currentLocale = app()->getLocale();
        @endphp
        <div class="relative inline-flex h-10 items-center rounded-full border border-slate-200 bg-slate-100 p-1"
            role="group" aria-label="{{ trans_json('Switch language') }}">
            <span aria-hidden="true"
                class="absolute left-1 top-1 h-8 w-8 rounded-full bg-white shadow-md ring-1 ring-indigo-200 transition-transform duration-300 ease-out
               {{ $currentLocale === 'km' ? 'translate-x-0' : 'translate-x-full' }}"></span>

            @foreach ($languages as $code => $lang)
                @php $isActive = $code === $currentLocale; @endphp

                @if ($isActive)
                    <span title="{{ $lang['name'] }}" aria-current="true"
                        class="relative z-10 flex h-8 w-8 items-center justify-center">
                        <img src="{{ asset($lang['flag']) }}" alt="{{ $lang['name'] }}"
                            class="h-6 w-6 rounded-full object-cover ring-2 ring-indigo-500">
                    </span>
                @else
                    <a href="{{ route('lang.switch', $code) }}" wire:navigate title="{{ $lang['name'] }}"
                        aria-label="{{ $lang['name'] }}"
                        class="group relative z-10 flex h-8 w-8 items-center justify-center rounded-full">
                        <img src="{{ asset($lang['flag']) }}" alt="{{ $lang['name'] }}"
                            class="h-6 w-6 rounded-full object-cover opacity-50 grayscale transition duration-200 group-hover:opacity-100 group-hover:grayscale-0 group-hover:scale-110">
                    </a>
                @endif
            @endforeach
        </div>
        <div x-data="{ open: false }" class="relative">
            <button type="button" @click="open = !open" @click.outside="open = false"
                @keydown.escape.window="open = false" :aria-expanded="open"
                class="relative flex h-10 w-10 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-indigo-600"
                title="{{ trans_json('Notifications') }}" aria-label="{{ trans_json('Notifications') }}">
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
                    <p class="text-sm font-semibold text-slate-800">{{ trans_json('Notifications') }}</p>
                    <span
                        class="rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-600">{{ trans_json('New') }}</span>
                </div>
                <div class="flex flex-col items-center gap-2 px-4 py-10 text-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <x-heroicon-o-bell-slash class="h-6 w-6" />
                    </div>
                    <p class="text-sm text-slate-500">{{ trans_json('You are all caught up.') }}</p>
                </div>
            </div>
        </div>
        <div x-data="{ open: false }" class="relative">
            <button type="button" @click="open = !open" @click.outside="open = false"
                @keydown.escape.window="open = false" :aria-expanded="open"
                class="flex items-center gap-2 rounded-xl p-1 transition hover:bg-slate-100 sm:pr-2">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 font-semibold text-white">
                    {{ mb_strtoupper(mb_substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                </div>
                <span class="hidden max-w-32 truncate text-sm font-medium text-slate-700 md:block">
                    {{ auth()->user()?->name }}
                </span>
                <span class="hidden transition-transform md:block" :class="open ? 'rotate-180' : ''">
                    <x-heroicon-o-chevron-down class="h-4 w-4 text-slate-400" />
                </span>
            </button>

            <div x-cloak x-show="open" x-transition.origin.top.right
                class="absolute right-0 mt-3 w-60 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl">
                <div class="mb-1 rounded-xl bg-slate-50 px-3 py-2.5">
                    <p class="truncate text-sm font-semibold text-slate-800">{{ auth()->user()?->name }}</p>
                    <p class="truncate text-xs text-slate-500">{{ auth()->user()?->email }}</p>
                </div>

                <a href="#"
                    class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                    <x-heroicon-o-user-circle class="h-5 w-5" /> {{ trans_json('My profile') }}
                </a>

                <div class="my-1 border-t border-slate-100"></div>

                {{-- Logout: normal POST, no wire:navigate --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm text-red-600 transition hover:bg-red-50">
                        <x-heroicon-o-arrow-right-on-rectangle class="h-5 w-5" /> {{ trans_json('Sign out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
