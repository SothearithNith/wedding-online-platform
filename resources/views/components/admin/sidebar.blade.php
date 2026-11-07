{{-- Needs parent Alpine state: sidebarOpen (mobile drawer) and collapsed (desktop icon-only mode) --}}
@php
    $user = auth()->user();
    $menus = \App\Models\Menu::rootTree()->get();

    $href = fn($m) => $m->route_name && \Illuminate\Support\Facades\Route::has($m->route_name)
        ? route($m->route_name)
        : '#';

    $allowed = fn($m) => !$m->permission_name || $user?->can($m->permission_name);
@endphp

<aside x-cloak
    :class="[
        sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full',
        collapsed ? 'lg:w-20' : 'lg:w-72'
    ]"
    class="fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] flex-col border-r border-slate-200 bg-white
           transition-all duration-300 ease-out lg:translate-x-0 lg:shadow-none">

    {{-- Brand --}}
    <div class="flex h-20 shrink-0 items-center gap-3 border-b border-slate-100 px-5"
        :class="collapsed ? 'lg:justify-center lg:px-0' : ''">
        <a href="{{ route('admin') }}" wire:navigate
            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 via-indigo-600 to-fuchsia-600 text-white shadow-lg shadow-indigo-500/30">
            <x-heroicon-s-heart class="h-6 w-6" />
        </a>

        <div class="min-w-0" :class="collapsed ? 'lg:hidden' : ''">
            <h1 class="truncate text-base font-bold tracking-tight text-slate-900">{{ __('Wedding Platform') }}</h1>
            <p class="truncate text-xs text-slate-500">{{ __('Management System') }}</p>
        </div>

        <button type="button" @click="sidebarOpen = false"
            class="ml-auto rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 lg:hidden"
            aria-label="{{ __('Close menu') }}">
            <x-heroicon-o-x-mark class="h-6 w-6" />
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5" aria-label="{{ __('Main menu') }}">
        <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400"
            :class="collapsed ? 'lg:hidden' : ''">
            {{ __('Main menu') }}
        </p>

        @forelse ($menus as $menu)
            @continue(!$allowed($menu))

            @php
                $visibleChildren = $menu->children->filter($allowed);
                $hasChildren = $menu->children->isNotEmpty();
                $childActive = $visibleChildren->contains(
                    fn($c) => $c->route_name && request()->routeIs($c->route_name),
                );
                $isActive = $menu->route_name && request()->routeIs($menu->route_name);
                $icon = $menu->icon ?: 'heroicon-o-squares-2x2';
                $menuHref = $href($menu);
            @endphp

            @if ($hasChildren)
                @continue($visibleChildren->isEmpty())

                {{-- Group with sub-menu --}}
                <div x-data="{ expanded: {{ $childActive ? 'true' : 'false' }} }">
                    <button type="button"
                        @click="(collapsed && window.innerWidth >= 1024) ? (collapsed = false, expanded = true) : (expanded = !expanded)"
                        title="{{ $menu->name }}" :aria-expanded="expanded"
                        class="group relative flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors
                               {{ $childActive ? 'bg-indigo-50/60 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}"
                        :class="collapsed ? 'lg:justify-center' : ''">
                        <x-dynamic-component :component="$icon"
                            class="h-5 w-5 shrink-0 transition-transform duration-200 group-hover:scale-110" />
                        <span class="flex-1 truncate text-left"
                            :class="collapsed ? 'lg:hidden' : ''">{{ $menu->name }}</span>

                        {{-- Chevron wrapped in a span, since :class on a Blade component is unreliable --}}
                        <span class="shrink-0 transition-transform duration-200"
                            :class="[expanded ? 'rotate-180' : '', collapsed ? 'lg:hidden' : '']">
                            <x-heroicon-o-chevron-down class="h-4 w-4 text-slate-400" />
                        </span>
                    </button>

                    <div x-show="expanded" x-collapse :class="collapsed ? 'lg:hidden' : ''"
                        class="ml-5 mt-1 space-y-0.5 border-l-2 border-slate-100 pl-3">
                        @foreach ($visibleChildren as $child)
                            @php
                                $childIsActive = $child->route_name && request()->routeIs($child->route_name);
                                $childHref = $href($child);
                            @endphp
                            <a href="{{ $childHref }}" @if ($childHref !== '#') wire:navigate @endif
                                @if ($childIsActive) aria-current="page" @endif
                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors
                                    {{ $childIsActive
                                        ? 'bg-indigo-50 font-semibold text-indigo-700'
                                        : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                <span
                                    class="h-1.5 w-1.5 rounded-full {{ $childIsActive ? 'bg-indigo-600' : 'bg-slate-300' }}"></span>
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- Single link --}}
                <a href="{{ $menuHref }}" title="{{ $menu->name }}"
                    @if ($menuHref !== '#') wire:navigate @endif
                    @if ($isActive) aria-current="page" @endif
                    class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors
                        {{ $isActive
                            ? 'bg-gradient-to-r from-indigo-50 to-transparent text-indigo-700'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}"
                    :class="collapsed ? 'lg:justify-center' : ''">

                    @if ($isActive)
                        <span class="absolute inset-y-2 left-0 w-1 rounded-r-full bg-indigo-600"></span>
                    @endif

                    <x-dynamic-component :component="$icon"
                        class="h-5 w-5 shrink-0 transition-transform duration-200 group-hover:scale-110" />
                    <span class="truncate" :class="collapsed ? 'lg:hidden' : ''">{{ $menu->name }}</span>
                </a>
            @endif
        @empty
            <p class="px-3 py-4 text-sm text-slate-400">{{ __('No menu items available.') }}</p>
        @endforelse
    </nav>

    {{-- Bottom: user + collapse toggle --}}
    <div class="shrink-0 space-y-2 border-t border-slate-100 p-3">
        <div class="flex items-center gap-3 rounded-2xl bg-gradient-to-br from-slate-50 to-indigo-50/50 p-2.5"
            :class="collapsed ? 'lg:justify-center lg:bg-none lg:p-0' : ''">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 font-semibold text-white"
                title="{{ $user?->name }}">
                {{ mb_strtoupper(mb_substr($user?->name ?? 'U', 0, 1)) }}
            </div>

            <div class="min-w-0 flex-1" :class="collapsed ? 'lg:hidden' : ''">
                <p class="truncate text-sm font-semibold text-slate-800">{{ $user?->name }}</p>
                <p class="truncate text-xs text-slate-500">{{ $user?->getRoleNames()->first() ?? __('User') }}</p>
            </div>
        </div>

        <button type="button" @click="collapsed = !collapsed"
            class="hidden w-full items-center justify-center gap-2 rounded-xl py-2 text-xs font-medium text-slate-500 transition hover:bg-slate-50 hover:text-slate-800 lg:flex"
            :title="collapsed ? @js(__('Expand sidebar')) : @js(__('Collapse sidebar'))">
            <span class="transition-transform duration-300" :class="collapsed ? 'rotate-180' : ''">
                <x-heroicon-o-chevron-double-left class="h-4 w-4" />
            </span>
            <span :class="collapsed ? 'hidden' : ''">{{ __('Collapse') }}</span>
        </button>
    </div>
</aside>
