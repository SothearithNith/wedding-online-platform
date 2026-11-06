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
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        collapsed ? 'lg:w-20' : 'lg:w-72'
    ]"
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-200 bg-white
           transition-all duration-300 lg:translate-x-0">

    {{-- Brand --}}
    <div class="flex h-20 shrink-0 items-center gap-3 border-b border-slate-100 px-5"
        :class="collapsed ? 'lg:justify-center lg:px-0' : ''">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white">
            <i class="bi bi-heart-fill text-xl"></i>
        </div>

        <div class="min-w-0" :class="collapsed ? 'lg:hidden' : ''">
            <h1 class="truncate text-lg font-bold tracking-tight text-slate-900">{{ __('Wedding Platform') }}</h1>
            <p class="text-xs text-slate-500">{{ __('Management System') }}</p>
        </div>

        <button type="button" @click="sidebarOpen = false" class="ml-auto text-slate-400 hover:text-slate-700 lg:hidden"
            aria-label="{{ __('Close menu') }}">
            <i class="bi bi-x-lg text-xl"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5" aria-label="{{ __('Main menu') }}">
        <p class="mb-2 px-3 text-xs font-semibold text-slate-400" :class="collapsed ? 'lg:hidden' : ''">
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
            @endphp

            @if ($hasChildren)
                @continue($visibleChildren->isEmpty())

                {{-- Group with sub-menu --}}
                <div x-data="{ expanded: {{ $childActive ? 'true' : 'false' }} }">
                    <button type="button"
                        @click="collapsed ? (collapsed = false, expanded = true) : (expanded = !expanded)"
                        title="{{ $menu->name }}"
                        class="relative flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                               {{ $childActive ? 'text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}"
                        :class="collapsed ? 'lg:justify-center' : ''">
                        <i class="{{ $menu->icon ?: 'bi bi-grid' }} w-5 shrink-0 text-center text-lg"></i>
                        <span class="flex-1 truncate text-left"
                            :class="collapsed ? 'lg:hidden' : ''">{{ $menu->name }}</span>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="[expanded ? 'rotate-180' : '', collapsed ? 'lg:hidden' : '']"></i>
                    </button>

                    <div x-show="expanded && (!collapsed || window.innerWidth < 1024)" x-collapse
                        class="ml-6 mt-1 space-y-1 border-l border-slate-200 pl-3">
                        @foreach ($visibleChildren as $child)
                            @php $childIsActive = $child->route_name && request()->routeIs($child->route_name); @endphp
                            <a href="{{ $href($child) }}"
                                class="block rounded-lg px-3 py-2 text-sm transition
                                    {{ $childIsActive
                                        ? 'bg-indigo-50 font-semibold text-indigo-700'
                                        : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- Single link --}}
                <a href="{{ $href($menu) }}" title="{{ $menu->name }}"
                    @if ($isActive) aria-current="page" @endif
                    class="relative flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                        {{ $isActive ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}"
                    :class="collapsed ? 'lg:justify-center' : ''">

                    @if ($isActive)
                        <span class="absolute inset-y-2 left-0 w-1 rounded-r-full bg-indigo-600"></span>
                    @endif

                    <i class="{{ $menu->icon ?: 'bi bi-circle' }} w-5 shrink-0 text-center text-lg"></i>
                    <span class="truncate" :class="collapsed ? 'lg:hidden' : ''">{{ $menu->name }}</span>
                </a>
            @endif
        @empty
            <p class="px-3 py-4 text-sm text-slate-400">{{ __('No menu items available.') }}</p>
        @endforelse
    </nav>

    {{-- Bottom: user + collapse toggle --}}
    <div class="shrink-0 space-y-2 border-t border-slate-100 p-3">
        <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-2.5"
            :class="collapsed ? 'lg:justify-center lg:bg-transparent lg:p-0' : ''">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 font-semibold text-indigo-700"
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
            :title="collapsed ? '{{ __('Expand sidebar') }}' : '{{ __('Collapse sidebar') }}'">
            <i class="bi text-sm" :class="collapsed ? 'bi-chevron-double-right' : 'bi-chevron-double-left'"></i>
            <span :class="collapsed ? 'hidden' : ''">{{ __('Collapse') }}</span>
        </button>
    </div>
</aside>
