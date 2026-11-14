@php
    $filters = [
        'all' => [trans_json('All'), 'text-gray-900', 'heroicon-o-squares-2x2', 'bg-gray-100 text-gray-600'],
        'published' => [
            trans_json('Published'),
            'text-green-600',
            'heroicon-o-globe-alt',
            'bg-green-100 text-green-600',
        ],
        'draft' => [trans_json('Drafts'), 'text-gray-500', 'heroicon-o-pencil-square', 'bg-amber-100 text-amber-600'],
    ];
@endphp

<div class="mx-auto max-w-6xl space-y-6 p-4">

    @include('livewire.admin.wedding.partials.swal')
    <div>
        <div
            class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 p-6 text-white shadow-xl shadow-indigo-500/20 sm:p-8">
            <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10 blur-2xl">
            </div>
            <div
                class="pointer-events-none absolute -bottom-16 left-1/3 h-48 w-48 rounded-full bg-fuchsia-300/20 blur-3xl">
            </div>

            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold sm:text-3xl">{{ trans_json('Weddings') }}</h1>
                    <p class="mt-1.5 max-w-xl text-sm text-indigo-100">
                        {{ trans_json("Each wedding has its own link built from the groom's name.") }}
                    </p>
                </div>

                <a href="{{ route('weddings.create') }}" wire:navigate
                    class="group inline-flex shrink-0 items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-indigo-700 shadow-lg transition hover:scale-[1.03] hover:shadow-xl active:scale-95">
                    <x-heroicon-o-plus class="h-5 w-5 transition-transform duration-300 group-hover:rotate-90" />
                    {{ trans_json('New wedding') }}
                </a>
            </div>

            <div class="relative mt-6 grid grid-cols-3 gap-3">
                @foreach ([[trans_json('Total'), $counts['all'], 'heroicon-o-heart'], [trans_json('Published'), $counts['published'], 'heroicon-o-check-circle'], [trans_json('Draft'), $counts['draft'], 'heroicon-o-globe-alt']] as [$label, $value, $icon])
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
    </div>


    <div class="relative">
        <x-heroicon-o-magnifying-glass
            class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />

        <input type="search" wire:model.live.debounce.300ms="search"
            placeholder="{{ trans_json('Search groom, bride or link…') }}"
            class="w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-11 pr-10 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 [&::-webkit-search-cancel-button]:hidden">
        @if ($search !== '')
            <button type="button" wire:click="$set('search', '')" aria-label="{{ trans_json('Clear') }}"
                class="absolute right-2.5 top-1/2 flex h-6 w-6 -translate-y-1/2 items-center justify-center rounded-full text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                <x-heroicon-o-x-mark class="h-4 w-4" />
            </button>
        @endif
    </div>

    @if ($weddings->count())
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($weddings as $w)
                @php($preview = $w->template?->preview_src ? asset('storage/templates/' . basename($w->template->preview_src)) : null)

                <div wire:key="wedding-{{ $w->id }}" x-data="{ copied: false, menu: false }"
                    class="group relative h-[34rem] rounded-3xl shadow-md transition duration-300 hover:-translate-y-1 hover:shadow-2xl sm:h-[36rem]">
                    <div
                        class="absolute inset-0 overflow-hidden rounded-3xl bg-gradient-to-br from-amber-300 via-rose-300 to-amber-400">

                        <img src="{{ $preview }}" alt="" loading="lazy"
                            class="h-full w-full object-cover object-top transition duration-700 group-hover:scale-105"
                            onerror="this.style.display='none'">

                        <div
                            class="pointer-events-none absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/55 via-black/15 to-transparent">
                        </div>
                        <div
                            class="pointer-events-none absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-black/25 to-transparent">
                        </div>
                    </div>
                    <span
                        class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full border border-white/30 bg-white/20 px-3 py-1 text-[11px] font-semibold uppercase tracking-wider text-white shadow-sm backdrop-blur-md">
                        <span @class([
                            'h-2 w-2 rounded-full',
                            'bg-green-400 animate-pulse' => $w->isPublished(),
                            'bg-white/60' => !$w->isPublished(),
                        ])></span>
                        {{ $w->isPublished() ? trans_json('Live') : trans_json('Draft') }}
                    </span>
                    <span
                        class="absolute right-4 top-4 rounded-full border border-white/30 bg-white/20 px-3 py-1 text-[11px] font-medium text-white shadow-sm backdrop-blur-md">
                        {{ $w->template_key }}
                    </span>
                    <div
                        class="absolute inset-x-3 bottom-3 flex flex-col gap-3 rounded-2xl border border-white/25 bg-white/10 p-3.5 text-white shadow-2xl shadow-black/30 ring-1 ring-inset ring-white/10 backdrop-blur-lg">
                        <div class="px-1 text-center">
                            <h2 class="truncate   text-2xl font-semibold leading-tight drop-shadow">
                                {{ $w->groom_name ?: '—' }}
                                <span class="mx-0.5 text-amber-300">&</span>
                                {{ $w->bride_name ?: '—' }}
                            </h2>

                            <p class="mt-1 flex items-center justify-center gap-2 text-[11px] text-white/75">
                                @if ($w->label)
                                    <span class="max-w-[60%] truncate">{{ $w->label }}</span>
                                    <span class="h-1 w-1 shrink-0 rounded-full bg-white/50"></span>
                                @endif
                                <span class="inline-flex shrink-0 items-center gap-1">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $w->updated_at->diffForHumans() }}
                                </span>
                            </p>
                        </div>

                        <div
                            class="flex items-center gap-2 rounded-xl border border-white/15 bg-black/25 py-1.5 pl-3 pr-1.5 text-xs">
                            <svg class="h-4 w-4 shrink-0 text-white/60" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            <a href="{{ $w->url }}" target="_blank"
                                class="min-w-0 flex-1 truncate text-white/85 hover:text-white">
                                {{ urldecode($w->url) }}
                            </a>
                            <button type="button"
                                x-on:click="navigator.clipboard.writeText(@js($w->url)); copied = true; setTimeout(() => copied = false, 1500)"
                                class="inline-flex shrink-0 items-center gap-1 rounded-lg px-2.5 py-1 font-medium transition"
                                x-bind:class="copied ? 'bg-green-400/90 text-green-950' : 'bg-white/20 text-white hover:bg-white/30'">
                                <span x-show="!copied" class="inline-flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    {{ trans_json('Copy') }}
                                </span>
                                <span x-show="copied" x-cloak class="inline-flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ trans_json('Copied') }}
                                </span>
                            </button>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('weddings.edit', $w) }}" wire:navigate
                                class="flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-white py-2 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-amber-50">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.232 5.232l3.536 3.536M9 13l6.768-6.768a2.5 2.5 0 113.536 3.536L12.536 16.536 8 18l1-5z" />
                                </svg>
                                {{ trans_json('Edit') }}
                            </a>

                            <a href="{{ $w->url }}" target="_blank"
                                class="flex flex-1 items-center justify-center gap-1.5 rounded-xl border border-white/25 bg-white/15 py-2 text-sm font-medium text-white transition hover:bg-white/25">
                                {{ trans_json('View') }}
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>

                            <button type="button" wire:click="togglePublish({{ $w->id }})"
                                class="flex flex-1 items-center justify-center rounded-xl border border-white/25 bg-white/15 py-2 text-sm font-medium text-white transition hover:bg-white/25">
                                {{ $w->isPublished() ? trans_json('Unpublish') : trans_json('Publish') }}
                            </button>

                            {{-- More --}}
                            <div class="relative" x-on:click.outside="menu = false"
                                x-on:keydown.escape.window="menu = false">
                                <button type="button" x-on:click="menu = !menu"
                                    aria-label="{{ trans_json('More') }}"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/25 bg-white/15 text-white transition hover:bg-white/25">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </button>

                                <div x-show="menu" x-cloak x-transition.origin.bottom.right
                                    class="absolute bottom-full right-0 z-30 mb-2 w-44 overflow-hidden rounded-xl border border-gray-200 bg-white py-1 text-sm text-gray-700 shadow-2xl">

                                    <button type="button" x-show="typeof navigator.share === 'function'" x-cloak
                                        x-on:click="menu = false; navigator.share({ title: @js($w->title), url: @js($w->url) }).catch(() => {})"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-left hover:bg-gray-50">
                                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                        </svg>
                                        {{ trans_json('Share') }}
                                    </button>

                                    <div class="my-1 border-t border-gray-100"></div>

                                    <button type="button"
                                        x-on:click="menu = false; Swal.fire({
                                        icon: 'warning',
                                        title: @js(trans_json('Delete this wedding?')),
                                        text: @js(trans_json('All its uploaded files will be deleted too. This cannot be undone.')),
                                        showCancelButton: true,
                                        confirmButtonColor: '#dc2626',
                                        confirmButtonText: @js(trans_json('Delete')),
                                        cancelButtonText: @js(trans_json('Cancel')),
                                    }).then(r => { if (r.isConfirmed) $wire.delete({{ $w->id }}) })"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-left text-red-600 hover:bg-red-50">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        {{ trans_json('Delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div>{{ $weddings->links() }}</div>
    @else
        <div class="rounded-2xl border-2 border-dashed border-gray-300 p-12 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <p class="mt-3 text-lg font-medium">
                {{ $search !== '' || $status !== 'all' ? trans_json('No weddings match your filter.') : trans_json('No weddings yet.') }}
            </p>
            @if ($search === '' && $status === 'all')
                <p class="mt-1 text-sm text-gray-500">{{ trans_json('Create your first wedding from a template.') }}
                </p>
                <a href="{{ route('weddings.create') }}" wire:navigate
                    class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-amber-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ trans_json('New wedding') }}
                </a>
            @endif
        </div>
    @endif
</div>
