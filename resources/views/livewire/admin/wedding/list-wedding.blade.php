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
    <div
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 p-6 text-white shadow-xl shadow-indigo-500/20 sm:p-8">
        <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-16 left-1/3 h-48 w-48 rounded-full bg-fuchsia-300/20 blur-3xl">
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

    {{-- Search --}}
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

                <div wire:key="wedding-{{ $w->id }}"
                    x-data="{
                        copied: false,
                        menu: false,
                        base: @js($w->url),
                        title: @js($w->title),
                        copy(text) {
                            navigator.clipboard.writeText(text);
                            this.copied = true;
                            setTimeout(() => this.copied = false, 1500);
                        }
                    }"
                    class="group relative h-[34rem] rounded-3xl shadow-lg transition duration-300 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-indigo-500/20 sm:h-[36rem]">

                    {{-- Background --}}
                    <div
                        class="absolute inset-0 overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-400 via-fuchsia-400 to-amber-300">
                        <img src="{{ $preview }}" alt="" loading="lazy"
                            class="h-full w-full object-cover object-top transition duration-700 group-hover:scale-110"
                            onerror="this.style.display='none'">
                        <div
                            class="pointer-events-none absolute inset-x-0 bottom-0 h-3/5 bg-gradient-to-t from-black/75 via-black/30 to-transparent">
                        </div>
                        <div
                            class="pointer-events-none absolute inset-x-0 top-0 h-28 bg-gradient-to-b from-black/35 to-transparent">
                        </div>
                    </div>

                    {{-- Status --}}
                    <span
                        class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full border border-white/30 bg-black/25 px-3 py-1 text-[11px] font-semibold uppercase tracking-wider text-white backdrop-blur-md">
                        <span class="relative flex h-2 w-2">
                            @if ($w->isPublished())
                                <span
                                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full bg-green-400"></span>
                            @else
                                <span class="relative inline-flex h-2 w-2 rounded-full bg-amber-300"></span>
                            @endif
                        </span>
                        {{ $w->isPublished() ? trans_json('Live') : trans_json('Draft') }}
                    </span>

                    {{-- Template key --}}
                    <span
                        class="absolute right-4 top-4 inline-flex items-center gap-1 rounded-full border border-white/30 bg-black/25 px-3 py-1 text-[11px] font-medium text-white backdrop-blur-md">
                        <x-heroicon-o-sparkles class="h-3 w-3 text-amber-300" />
                        {{ $w->template_key }}
                    </span>

                    {{-- Glass panel --}}
                    <div
                        class="absolute inset-x-3 bottom-3 flex flex-col gap-3 rounded-2xl border border-white/25 bg-white/10 p-3.5 text-white shadow-2xl shadow-black/40 ring-1 ring-inset ring-white/10 backdrop-blur-xl">

                        {{-- Names --}}
                        <div class="px-1 text-center">
                            <h2 class="truncate text-2xl font-semibold leading-tight drop-shadow-lg">
                                {{ $w->groom_name ?: '—' }}
                                <x-heroicon-s-heart class="mx-0.5 inline h-4 w-4 -translate-y-0.5 text-rose-400" />
                                {{ $w->bride_name ?: '—' }}
                            </h2>

                            <p class="mt-1.5 flex items-center justify-center gap-2 text-[11px] text-white/70">
                                @if ($w->label)
                                    <span class="max-w-[60%] truncate">{{ $w->label }}</span>
                                    <span class="h-1 w-1 shrink-0 rounded-full bg-white/50"></span>
                                @endif
                                <span class="inline-flex shrink-0 items-center gap-1">
                                    <x-heroicon-o-clock class="h-3 w-3" />
                                    {{ $w->updated_at->diffForHumans() }}
                                </span>
                            </p>
                        </div>

                        {{-- Link + copy --}}
                        <div
                            class="flex items-center gap-2 rounded-xl border border-white/15 bg-black/30 py-1.5 pl-3 pr-1.5 text-xs">
                            <x-heroicon-o-link class="h-4 w-4 shrink-0 text-white/60" />
                            <a href="{{ $w->url }}" target="_blank"
                                class="min-w-0 flex-1 truncate text-white/85 hover:text-white">
                                {{ urldecode($w->url) }}
                            </a>
                            <button type="button" x-on:click="copy(base)"
                                class="inline-flex shrink-0 items-center gap-1 rounded-lg px-2.5 py-1 font-medium transition"
                                x-bind:class="copied ? 'bg-green-400 text-green-950' : 'bg-white/20 text-white hover:bg-white/30'">
                                <span x-show="!copied" class="inline-flex items-center gap-1">
                                    <x-heroicon-o-clipboard-document class="h-3.5 w-3.5" />
                                    {{ trans_json('Copy') }}
                                </span>
                                <span x-show="copied" x-cloak class="inline-flex items-center gap-1">
                                    <x-heroicon-o-check class="h-3.5 w-3.5" />
                                    {{ trans_json('Copied') }}
                                </span>
                            </button>
                        </div>

                        {{-- Main action: send to guest --}}
                        <button type="button"
                            x-on:click="guestInvite({
                                url: base,
                                groom: @js($w->groom_name),
                                bride: @js($w->bride_name),
                                labels: @js([
                                    'title' => trans_json('Send invitation'),
                                    'guest' => trans_json('Guest name'),
                                    'placeholder' => trans_json('e.g. Mr. Sok Dara'),
                                    'recent' => trans_json('Recent guests'),
                                    'preview' => trans_json('Link preview'),
                                    'copy' => trans_json('Copy link'),
                                    'copied' => trans_json('Link copied'),
                                ]),
                            })"
                            class="group/send relative flex w-full items-center justify-center gap-2 overflow-hidden rounded-xl bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500 py-2.5 text-sm font-semibold text-white shadow-lg shadow-fuchsia-500/30 transition hover:brightness-110 active:scale-[0.98]">
                            <span
                                class="pointer-events-none absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/30 to-transparent transition duration-700 group-hover/send:translate-x-full"></span>
                            <x-heroicon-o-paper-airplane class="relative h-4 w-4 -rotate-45" />
                            <span class="relative">{{ trans_json('Send to guest') }}</span>
                        </button>

                        {{-- Secondary actions --}}
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('weddings.edit', $w) }}" wire:navigate
                                class="flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-white py-2 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-amber-50">
                                <x-heroicon-o-pencil-square class="h-4 w-4" />
                                {{ trans_json('Edit') }}
                            </a>

                            <a href="{{ $w->url }}" target="_blank"
                                class="flex flex-1 items-center justify-center gap-1.5 rounded-xl border border-white/25 bg-white/15 py-2 text-sm font-medium text-white transition hover:bg-white/25">
                                {{ trans_json('View') }}
                                <x-heroicon-o-arrow-top-right-on-square class="h-3.5 w-3.5" />
                            </a>

                            {{-- More menu --}}
                            <div class="relative" x-on:click.outside="menu = false"
                                x-on:keydown.escape.window="menu = false">
                                <button type="button" x-on:click="menu = !menu"
                                    aria-label="{{ trans_json('More') }}"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/25 bg-white/15 text-white transition hover:bg-white/25">
                                    <x-heroicon-s-ellipsis-horizontal class="h-5 w-5" />
                                </button>

                                <div x-show="menu" x-cloak x-transition.origin.bottom.right
                                    class="absolute bottom-full right-0 z-30 mb-2 w-48 overflow-hidden rounded-xl border border-gray-200 bg-white py-1 text-sm text-gray-700 shadow-2xl">

                                    {{-- Publish / Unpublish --}}
                                    <button type="button" wire:click="togglePublish({{ $w->id }})"
                                        x-on:click="menu = false"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-left hover:bg-gray-50">
                                        @if ($w->isPublished())
                                            <x-heroicon-o-eye-slash class="h-4 w-4 text-gray-500" />
                                            {{ trans_json('Unpublish') }}
                                        @else
                                            <x-heroicon-o-globe-alt class="h-4 w-4 text-green-600" />
                                            {{ trans_json('Publish') }}
                                        @endif
                                    </button>

                                    {{-- Share --}}
                                    <button type="button" x-show="typeof navigator.share === 'function'" x-cloak
                                        x-on:click="menu = false; navigator.share({ title: title, url: base }).catch(() => {})"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-left hover:bg-gray-50">
                                        <x-heroicon-o-share class="h-4 w-4 text-gray-500" />
                                        {{ trans_json('Share') }}
                                    </button>

                                    <div class="my-1 border-t border-gray-100"></div>

                                    {{-- Delete --}}
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
                                        <x-heroicon-o-trash class="h-4 w-4" />
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
                <x-heroicon-o-heart class="h-7 w-7" />
            </div>
            <p class="mt-3 text-lg font-medium">
                {{ $search !== '' || $status !== 'all' ? trans_json('No weddings match your filter.') : trans_json('No weddings yet.') }}
            </p>
            @if ($search === '' && $status === 'all')
                <p class="mt-1 text-sm text-gray-500">{{ trans_json('Create your first wedding from a template.') }}
                </p>
                <a href="{{ route('weddings.create') }}" wire:navigate
                    class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-amber-700">
                    <x-heroicon-o-plus class="h-4 w-4" />
                    {{ trans_json('New wedding') }}
                </a>
            @endif
        </div>
    @endif
</div>
