<header
    class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur sm:px-6 lg:px-8">

    <div class="flex items-center gap-4">
        {{-- Mobile menu button --}}
        <button type="button" @click="sidebarOpen = true"
            class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-600 hover:bg-slate-100 lg:hidden">
            <i class="bi bi-list text-2xl"></i>
        </button>

        <div>
            <h2 class="text-lg font-bold text-slate-900">
                {{ $title ?? 'Dashboard' }}
            </h2>
            <p class="hidden text-xs text-slate-500 sm:block">
                Welcome to your wedding management system.
            </p>
        </div>
    </div>

    <div class="flex items-center gap-3">

        {{-- Notification --}}
        <button type="button"
            class="relative flex h-10 w-10 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-indigo-600"
            title="Notifications">
            <i class="bi bi-bell text-xl"></i>
            <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
        </button>

        {{-- User menu --}}
        <div x-data="{ open: false }" class="relative">
            <button type="button" @click="open = !open" @click.outside="open = false"
                class="flex items-center gap-2 rounded-xl p-1.5 transition hover:bg-slate-100">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 font-semibold text-indigo-700">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                </div>

                <span class="hidden max-w-32 truncate text-sm font-medium text-slate-700 sm:block">
                    {{ auth()->user()?->name }}
                </span>

                <i class="bi bi-chevron-down hidden text-xs text-slate-400 sm:block"></i>
            </button>

            <div x-cloak x-show="open" x-transition
                class="absolute right-0 mt-3 w-56 rounded-xl border border-slate-200 bg-white p-2 shadow-lg">
                <div class="border-b border-slate-100 px-3 py-2">
                    <p class="truncate text-sm font-semibold text-slate-800">
                        {{ auth()->user()?->name }}
                    </p>
                    <p class="truncate text-xs text-slate-500">
                        {{ auth()->user()?->email }}
                    </p>
                </div>

                <a href="#"
                    class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-slate-50">
                    <i class="bi bi-person"></i>
                    My Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                        <i class="bi bi-box-arrow-right"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
