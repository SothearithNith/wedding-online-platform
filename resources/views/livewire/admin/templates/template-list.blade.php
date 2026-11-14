<div class="space-y-6">
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

    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            <x-heroicon-s-check-circle class="h-5 w-5" />
            {{ session('success') }}
        </div>
    @endif

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


    <div wire:loading.remove wire:target="search,status,visibility,gotoPage,nextPage,previousPage"
        class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">

        @forelse ($templates as $template)
            <div wire:key="template-{{ $template->id }}"
                class="group relative aspect-[3/5] overflow-hidden rounded-3xl bg-gradient-to-b from-slate-200 via-slate-300 to-slate-500 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-indigo-500/20">
                @if ($template->preview_src)
                    <img src="{{ asset('storage/templates/' . basename($template->preview_src)) }}" loading="lazy"
                        alt="{{ $template->name }}"
                        class="absolute inset-0 h-full w-full object-cover object-top transition-transform duration-700 group-hover:scale-105"
                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden')">

                    <div
                        class="hidden absolute inset-0 flex flex-col items-center justify-center gap-3 pb-40 text-white/70">
                        <x-heroicon-o-photo class="h-12 w-12" />
                        <span class="text-sm font-medium">{{ __('Preview unavailable') }}</span>
                    </div>
                @else
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 pb-40 text-white/70">
                        <x-heroicon-o-photo class="h-12 w-12" />
                        <span class="text-sm font-medium">{{ __('No preview available') }}</span>
                    </div>
                @endif

                {{-- Light shade at the bottom only, so the image stays visible --}}
                <div
                    class="pointer-events-none absolute inset-x-0 bottom-0 h-2/5 bg-gradient-to-t from-slate-950/40 to-transparent">
                </div>

                {{-- Top badges --}}
                <div class="absolute inset-x-3 top-3 flex items-center justify-between">
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-white/30 bg-white/20 px-3 py-1.5 text-xs font-semibold text-white shadow-sm backdrop-blur-md">
                        <span
                            class="h-2 w-2 rounded-full {{ $template->is_active ? 'bg-emerald-400' : 'bg-slate-300' }}"></span>
                        {{ $template->is_active ? __('Active') : __('Inactive') }}
                    </span>

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-white/30 bg-slate-950/30 px-3 py-1.5 text-xs font-semibold text-white shadow-sm backdrop-blur-md">
                        @if ($template->is_public)
                            <x-heroicon-o-globe-alt class="h-3.5 w-3.5" />
                            {{ __('Public') }}
                        @else
                            <x-heroicon-o-lock-closed class="h-3.5 w-3.5" />
                            {{ __('Private') }}
                        @endif
                    </span>
                </div>

                {{-- Glass container: title + desc + info + actions --}}
                <div
                    class="absolute inset-x-3 bottom-3 rounded-2xl border border-white/30 bg-white/10 p-3.5 text-white shadow-xl shadow-black/20 backdrop-blur-lg">

                    {{-- Title row --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-base font-bold tracking-tight drop-shadow-sm"
                                title="{{ $template->name }}">
                                {{ $template->name }}
                            </h3>
                            <p class="mt-0.5 truncate font-mono text-[11px] text-white/60">
                                {{ $template->key }}
                            </p>
                        </div>

                        <span
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-white/25 bg-white/20 text-white">
                            <x-heroicon-o-swatch class="h-5 w-5" />
                        </span>
                    </div>

                    {{-- Description --}}
                    @if ($template->description)
                        <p class="mt-2 line-clamp-2 text-xs leading-relaxed text-white/85">
                            {{ $template->description }}
                        </p>
                    @endif

                    {{-- Invitation info --}}
                    <div class="mt-2 flex items-center gap-1.5 text-[11px] text-white/75">
                        <x-heroicon-o-envelope class="h-4 w-4" />
                        <span>
                            {{ trans_choice('{0} No invitations|{1} :count invitation|[2,*] :count invitations', $template->invitations_count, ['count' => $template->invitations_count]) }}
                        </span>
                    </div>

                    <div class="my-3 border-t border-white/20"></div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-between">

                        {{-- Active toggle --}}
                        <button type="button" wire:click="toggleActive({{ $template->id }})" role="switch"
                            aria-checked="{{ $template->is_active ? 'true' : 'false' }}"
                            title="{{ $template->is_active ? __('Deactivate') : __('Activate') }}"
                            class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors duration-200 {{ $template->is_active ? 'bg-emerald-500' : 'bg-white/30' }}">
                            <span
                                class="inline-block h-5 w-5 rounded-full bg-white shadow-sm transition-transform duration-200 {{ $template->is_active ? 'translate-x-5' : 'translate-x-0.5' }}"></span>
                        </button>

                        <div class="flex items-center gap-1"><a
                                href="{{ route('admin.templates.preview', $template) }}" target="_blank"
                                title="{{ __('Preview') }}"
                                class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/20 bg-white/10 text-white/90 transition hover:bg-white/25 hover:text-white">
                                <x-heroicon-o-eye class="h-5 w-5" />
                            </a>
                            <a href="{{ route('templates.edit', $template) }}" wire:navigate
                                title="{{ __('Edit template') }}"
                                class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/20 bg-white/10 text-white/90 transition hover:bg-white/25 hover:text-white">
                                <x-heroicon-o-pencil-square class="h-5 w-5" />
                            </a>
                            <button type="button" wire:click="delete({{ $template->id }})"
                                wire:confirm="{{ __('Delete this template?') }}" title="{{ __('Delete') }}"
                                class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/20 bg-white/10 text-white/90 transition hover:bg-red-500/50 hover:text-white">
                                <x-heroicon-o-trash class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        @empty
            {{-- Empty state (unchanged) --}}
            <div
                class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-20 text-center">

                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-500">
                    <x-heroicon-o-swatch class="h-8 w-8" />
                </div>

                <h3 class="mt-5 text-base font-bold text-slate-800">
                    {{ __('No templates found') }}
                </h3>

                <p class="mt-1 max-w-sm text-sm text-slate-500">
                    {{ __('Try another search, or create your first template.') }}
                </p>

                <a href="{{ route('templates.create') }}" wire:navigate
                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                    <x-heroicon-o-plus class="h-5 w-5" />
                    {{ __('Create template') }}
                </a>
            </div>
        @endforelse
    </div>


    <div>{{ $templates->links() }}</div>
</div>
