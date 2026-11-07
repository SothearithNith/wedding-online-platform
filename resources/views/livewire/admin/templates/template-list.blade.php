<div class="space-y-6">

    {{-- Hero header --}}
    <div
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 p-6 text-white shadow-xl shadow-indigo-500/20 sm:p-8">
        <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-16 left-1/3 h-48 w-48 rounded-full bg-fuchsia-300/20 blur-3xl">
        </div>

        <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold sm:text-3xl">{{ __('Invitation Templates') }}</h1>
                <p class="mt-1.5 max-w-xl text-sm text-indigo-100">
                    {{ __('Design and manage the wedding invitation templates your couples can choose from.') }}
                </p>
            </div>

            <a href="{{ route('templates.create') }}" wire:navigate
                class="group inline-flex shrink-0 items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-indigo-700 shadow-lg transition hover:scale-[1.03] hover:shadow-xl active:scale-95">
                <x-heroicon-o-plus class="h-5 w-5 transition-transform duration-300 group-hover:rotate-90" />
                {{ __('Create template') }}
            </a>
        </div>

        <div class="relative mt-6 grid grid-cols-3 gap-3">
            @foreach ([[__('Total'), $total, 'heroicon-o-swatch'], [__('Active'), $activeCount, 'heroicon-o-check-circle'], [__('Public'), $publicCount, 'heroicon-o-globe-alt']] as [$label, $value, $icon])
                <div class="flex items-center gap-3 rounded-2xl bg-white/15 p-3 backdrop-blur sm:p-4">
                    <div class="hidden h-10 w-10 items-center justify-center rounded-xl bg-white/20 sm:flex">
                        <x-dynamic-component :component="$icon" class="h-5 w-5" />
                    </div>
                    <div>
                        <p class="text-xl font-bold leading-none sm:text-2xl">{{ $value }}</p>
                        <p class="mt-1 text-xs text-indigo-100">{{ $label }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Flash message --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            <x-heroicon-s-check-circle class="h-5 w-5" />
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <div
        class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm lg:flex-row lg:items-center">
        <div class="relative flex-1">
            <x-heroicon-o-magnifying-glass
                class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
            <input type="search" wire:model.live.debounce.300ms="search"
                placeholder="{{ __('Search by name or key...') }}"
                class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
        </div>

        <select wire:model.live="visibility"
            class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
            <option value="all">{{ __('All visibility') }}</option>
            <option value="public">{{ __('Public') }}</option>
            <option value="private">{{ __('Private') }}</option>
        </select>

        <div class="inline-flex rounded-xl bg-slate-100 p-1 text-sm font-medium">
            @foreach (['all' => __('All'), 'active' => __('Active'), 'inactive' => __('Inactive')] as $key => $label)
                <button type="button" wire:click="$set('status', '{{ $key }}')"
                    class="flex-1 rounded-lg px-3.5 py-1.5 transition {{ $status === $key ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Skeleton while loading --}}
    <div wire:loading.grid wire:target="search,status,visibility,gotoPage,nextPage,previousPage"
        class="hidden gap-5 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
        @foreach (range(1, 4) as $i)
            <div class="animate-pulse overflow-hidden rounded-2xl border border-slate-200 bg-white">
                <div class="aspect-[3/4] bg-slate-100"></div>
                <div class="space-y-2 p-4">
                    <div class="h-4 w-2/3 rounded bg-slate-100"></div>
                    <div class="h-3 w-1/3 rounded bg-slate-100"></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Cards --}}
    <div wire:loading.remove wire:target="search,status,visibility,gotoPage,nextPage,previousPage"
        class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">

        @forelse ($templates as $template)
            <div wire:key="template-{{ $template->id }}"
                class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1.5 hover:border-indigo-200 hover:shadow-2xl hover:shadow-indigo-500/10">

                {{-- Preview --}}
                <div
                    class="relative aspect-[3/4] overflow-hidden bg-gradient-to-br from-indigo-100 via-violet-100 to-fuchsia-100">
                    @if ($template->preview_src)
                        <img src="{{ $template->preview_src }}" alt="{{ $template->name }}" loading="lazy"
                            class="h-full w-full object-cover transition duration-700 group-hover:scale-110">
                    @else
                        <div class="flex h-full w-full flex-col items-center justify-center gap-2 text-indigo-300">
                            <x-heroicon-o-photo class="h-14 w-14" />
                            <span class="text-xs font-medium">{{ __('No preview') }}</span>
                        </div>
                    @endif

                    {{-- Status badge --}}
                    <span
                        class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold text-white shadow backdrop-blur {{ $template->is_active ? 'bg-emerald-500/90' : 'bg-slate-700/80' }}">
                        <span
                            class="h-1.5 w-1.5 rounded-full bg-white {{ $template->is_active ? 'animate-pulse' : '' }}"></span>
                        {{ $template->is_active ? __('Active') : __('Inactive') }}
                    </span>

                    {{-- Visibility chip --}}
                    <span
                        class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full bg-white/90 px-2.5 py-1 text-xs font-medium text-slate-700 shadow backdrop-blur">
                        @if ($template->is_public)
                            <x-heroicon-o-globe-alt class="h-3.5 w-3.5" /> {{ __('Public') }}
                        @else
                            <x-heroicon-o-lock-closed class="h-3.5 w-3.5" /> {{ __('Private') }}
                        @endif
                    </span>

                    {{-- Hover overlay --}}
                    <div
                        class="absolute inset-0 flex items-end justify-center gap-2 bg-gradient-to-t from-slate-900/70 via-slate-900/10 to-transparent p-4 opacity-0 transition duration-300 group-hover:opacity-100">
                        <a href="{{ route('templates.preview', $template) }}" target="_blank"
                            class="inline-flex translate-y-3 items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-lg transition duration-300 group-hover:translate-y-0 hover:bg-slate-50">
                            <x-heroicon-o-eye class="h-4 w-4" />
                            {{ __('Preview') }}
                        </a>
                        <a href="{{ route('templates.edit', $template) }}" wire:navigate
                            class="inline-flex translate-y-3 items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-indigo-700 shadow-lg transition duration-300 group-hover:translate-y-0 hover:bg-indigo-50">
                            <x-heroicon-o-pencil-square class="h-4 w-4" />
                            {{ __('Edit template') }}
                        </a>
                    </div>
                </div>

                {{-- Info --}}
                <div class="flex flex-1 flex-col p-4">
                    <h3 class="truncate text-base font-semibold text-slate-900" title="{{ $template->name }}">
                        {{ $template->name }}
                    </h3>
                    <p class="mt-0.5 truncate font-mono text-xs text-indigo-500">{{ $template->key }}</p>

                    @if ($template->description)
                        <p class="mt-2 line-clamp-2 text-sm text-slate-500">{{ $template->description }}</p>
                    @endif

                    <p class="mt-2 flex items-center gap-1.5 text-xs text-slate-400">
                        <x-heroicon-o-envelope class="h-4 w-4" />
                        {{ trans_choice('{0} No invitations|{1} :count invitation|[2,*] :count invitations', $template->invitations_count, ['count' => $template->invitations_count]) }}
                    </p>

                    <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-3 mt-4">
                        {{-- Toggle switch --}}
                        <button type="button" wire:click="toggleActive({{ $template->id }})" role="switch"
                            aria-checked="{{ $template->is_active ? 'true' : 'false' }}"
                            title="{{ $template->is_active ? __('Deactivate') : __('Activate') }}"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition {{ $template->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}">
                            <span
                                class="inline-block h-5 w-5 rounded-full bg-white shadow transition-transform {{ $template->is_active ? 'translate-x-5' : 'translate-x-0.5' }}"></span>
                        </button>

                        <div class="flex items-center gap-1">
                            <a href="{{ route('templates.preview', $template) }}" target="_blank"
                                title="{{ __('Preview') }}"
                                class="rounded-lg p-2 text-slate-500 transition hover:bg-indigo-50 hover:text-indigo-600">
                                <x-heroicon-o-eye class="h-5 w-5" />
                            </a>
                            <a href="{{ route('templates.edit', $template) }}" wire:navigate
                                title="{{ __('Edit') }}"
                                class="rounded-lg p-2 text-slate-500 transition hover:bg-indigo-50 hover:text-indigo-600">
                                <x-heroicon-o-pencil-square class="h-5 w-5" />
                            </a>
                            <button type="button" wire:click="delete({{ $template->id }})"
                                wire:confirm="{{ __('Delete this template?') }}" title="{{ __('Delete') }}"
                                class="rounded-lg p-2 text-slate-500 transition hover:bg-red-50 hover:text-red-600">
                                <x-heroicon-o-trash class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div
                class="col-span-full flex flex-col items-center gap-4 rounded-3xl border-2 border-dashed border-slate-200 bg-white px-6 py-20 text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-indigo-50 text-indigo-400">
                    <x-heroicon-o-swatch class="h-8 w-8" />
                </div>
                <div>
                    <p class="text-base font-semibold text-slate-800">{{ __('No templates found') }}</p>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ __('Try another search, or create your first template.') }}</p>
                </div>
                <a href="{{ route('templates.create') }}" wire:navigate
                    class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                    <x-heroicon-o-plus class="h-5 w-5" />
                    {{ __('Create template') }}
                </a>
            </div>
        @endforelse
    </div>

    <div>{{ $templates->links() }}</div>
</div>
