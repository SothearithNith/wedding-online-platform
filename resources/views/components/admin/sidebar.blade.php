<aside
    x-cloak
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-200 bg-white transition-transform duration-300 lg:translate-x-0"
>
    {{-- Brand --}}
    <div class="flex h-20 items-center gap-3 border-b border-slate-100 px-6">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-600 text-white">
            <i class="bi bi-heart-fill text-xl"></i>
        </div>

        <div class="min-w-0">
            <h1 class="truncate text-lg font-bold tracking-tight text-slate-900">
                Wedding Platform
            </h1>
            <p class="text-xs text-slate-500">Management System</p>
        </div>

        <button
            type="button"
            @click="sidebarOpen = false"
            class="ml-auto text-slate-400 hover:text-slate-700 lg:hidden"
        >
            <i class="bi bi-x-lg text-xl"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">

        <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
            Main Menu
        </p>

        @php
            $menus = \App\Models\Menu::query()
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->with(['children' => function ($query) {
                    $query->where('is_active', true)->orderBy('sort_order');
                }])
                ->orderBy('sort_order')
                ->get();
        @endphp

        @forelse ($menus as $menu)
            @php
                $canViewMenu = !$menu->permission_name
                    || auth()->user()?->can($menu->permission_name);

                $visibleChildren = $menu->children->filter(
                    fn ($child) => !$child->permission_name
                        || auth()->user()?->can($child->permission_name)
                );

                $isActive = $menu->route_name
                    && request()->routeIs($menu->route_name);
            @endphp

            @if ($canViewMenu)
                @if ($menu->children->isNotEmpty())
                    @if ($visibleChildren->isNotEmpty())
                        <div x-data="{ expanded: {{ $isActive ? 'true' : 'false' }} }">
                            <button
                                type="button"
                                @click="expanded = !expanded"
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-indigo-600"
                            >
                                <i class="{{ $menu->icon ?: 'bi bi-grid' }} text-lg"></i>

                                <span class="flex-1 text-left">{{ $menu->name }}</span>

                                <i
                                    class="bi bi-chevron-down text-xs transition-transform"
                                    :class="expanded ? 'rotate-180' : ''"
                                ></i>
                            </button>

                            <div x-show="expanded" x-collapse class="ml-5 mt-1 space-y-1 border-l border-slate-200 pl-4">
                                @foreach ($visibleChildren as $child)
                                    <a
                                        href="{{ $child->route_name && \Illuminate\Support\Facades\Route::has($child->route_name) ? route($child->route_name) : '#' }}"
                                        class="block rounded-lg px-3 py-2 text-sm transition
                                            {{ $child->route_name && request()->routeIs($child->route_name)
                                                ? 'bg-indigo-50 font-semibold text-indigo-700'
                                                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
                                    >
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <a
                        href="{{ $menu->route_name && \Illuminate\Support\Facades\Route::has($menu->route_name) ? route($menu->route_name) : '#' }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                            {{ $isActive
                                ? 'bg-indigo-50 text-indigo-700'
                                : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}"
                    >
                        <i class="{{ $menu->icon ?: 'bi bi-circle' }} text-lg"></i>
                        <span>{{ $menu->name }}</span>

                        @if ($isActive)
                            <span class="ml-auto h-2 w-2 rounded-full bg-indigo-600"></span>
                        @endif
                    </a>
                @endif
            @endif
        @empty
            <p class="px-3 py-4 text-sm text-slate-400">
                No menu items available.
            </p>
        @endforelse
    </nav>

    {{-- Sidebar bottom --}}
    <div class="border-t border-slate-100 p-4">
        <div class="rounded-xl bg-slate-50 p-3">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 font-semibold text-indigo-700">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                </div>

                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-slate-800">
                        {{ auth()->user()?->name }}
                    </p>
                    <p class="truncate text-xs text-slate-500">
                        {{ auth()->user()?->getRoleNames()->first() ?? 'User' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</aside>
