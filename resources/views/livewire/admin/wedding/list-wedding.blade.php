@php
    $filters = [
        'all'       => [__('All'), 'text-gray-900'],
        'published' => [__('Published'), 'text-green-600'],
        'draft'     => [__('Drafts'), 'text-gray-500'],
    ];
@endphp

<div class="mx-auto max-w-6xl space-y-6 p-4">

    @include('livewire.admin.wedding.partials.swal')

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('Weddings') }}</h1>
            <p class="text-sm text-gray-500">{{ __('Each wedding has its own link built from the groom\'s name.') }}</p>
        </div>

        <a href="{{ route('weddings.create') }}" wire:navigate
            class="inline-flex items-center gap-1 rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-700">
            + {{ __('New wedding') }}
        </a>
    </div>

    {{-- Stat tiles = filters --}}
    <div class="grid grid-cols-3 gap-3">
        @foreach ($filters as $key => [$name, $numCls])
            <button type="button" wire:click="setStatus('{{ $key }}')"
                @class([
                    'rounded-2xl border p-4 text-left shadow-sm transition',
                    'border-amber-500 bg-amber-50 ring-2 ring-amber-500/20' => $status === $key,
                    'border-gray-200 bg-white hover:border-amber-300' => $status !== $key,
                ])>
                <span class="block text-3xl font-semibold {{ $numCls }}">{{ $counts[$key] }}</span>
                <span class="text-sm text-gray-500">{{ $name }}</span>
            </button>
        @endforeach
    </div>

    {{-- Search --}}
    <div class="relative">
        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
        <input type="search" wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search groom, bride or link…') }}"
            class="w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-3 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30">
    </div>

    {{-- List --}}
    @if ($weddings->count())
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($weddings as $w)
                @php($cover = $w->cover)

                <div wire:key="wedding-{{ $w->id }}" x-data="{ copied: false }"
                    class="group flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">

                    {{-- Cover with names on top --}}
                    <div class="relative h-48 overflow-hidden bg-gradient-to-br from-amber-300 via-amber-400 to-rose-400">
                        @if ($cover)
                            <img src="{{ $cover }}" alt="" loading="lazy"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>

                        <span @class([
                            'absolute left-3 top-3 flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium text-white backdrop-blur',
                            'bg-green-600/90' => $w->isPublished(),
                            'bg-gray-900/70'  => ! $w->isPublished(),
                        ])>
                            <span class="h-1.5 w-1.5 rounded-full bg-white {{ $w->isPublished() ? 'animate-pulse' : 'opacity-60' }}"></span>
                            {{ $w->isPublished() ? __('Live') : __('Draft') }}
                        </span>

                        <span class="absolute right-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-xs font-medium text-gray-700">
                            {{ $w->template_key }}
                        </span>

                        <div class="absolute inset-x-0 bottom-0 p-4 text-white">
                            <h2 class="truncate text-xl font-semibold drop-shadow">
                                {{ $w->groom_name ?: '—' }} <span class="text-amber-300">&</span> {{ $w->bride_name ?: '—' }}
                            </h2>
                            @if ($w->label)
                                <p class="truncate text-xs text-white/80">{{ $w->label }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-1 flex-col gap-3 p-4">
                        {{-- Link --}}
                        <div class="flex items-center gap-2 rounded-lg bg-gray-50 px-3 py-2 text-xs">
                            <span>🔗</span>
                            <a href="{{ $w->url }}" target="_blank" class="min-w-0 flex-1 truncate text-amber-700 hover:underline">
                                {{ urldecode($w->url) }}
                            </a>
                            <button type="button"
                                x-on:click="navigator.clipboard.writeText(@js($w->url)); copied = true; setTimeout(() => copied = false, 1500)"
                                class="shrink-0 rounded-md border border-gray-300 bg-white px-2 py-0.5 hover:bg-gray-50">
                                <span x-show="!copied">{{ __('Copy') }}</span>
                                <span x-show="copied" x-cloak>✓ {{ __('Copied') }}</span>
                            </button>
                        </div>

                        <p class="text-xs text-gray-400">{{ __('Updated') }} {{ $w->updated_at->diffForHumans() }}</p>

                        {{-- Actions --}}
                        <div class="mt-auto flex flex-wrap items-center gap-2">
                            <a href="{{ route('weddings.edit', $w) }}" wire:navigate
                                class="rounded-lg bg-gray-900 px-3 py-1.5 text-sm font-medium text-white hover:bg-gray-700">
                                {{ __('Edit') }}
                            </a>

                            <a href="{{ $w->url }}" target="_blank"
                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm hover:bg-gray-50">
                                {{ __('View') }} ↗
                            </a>

                            <button type="button"
                                x-show="typeof navigator.share === 'function'" x-cloak
                                x-on:click="navigator.share({ title: @js($w->title), url: @js($w->url) }).catch(() => {})"
                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm hover:bg-gray-50">
                                {{ __('Share') }}
                            </button>

                            <button type="button" wire:click="togglePublish({{ $w->id }})"
                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm hover:bg-gray-50">
                                {{ $w->isPublished() ? __('Unpublish') : __('Publish') }}
                            </button>

                            <button type="button"
                                x-on:click="Swal.fire({
                                    icon: 'warning',
                                    title: @js(__('Delete this wedding?')),
                                    text: @js(__('All its uploaded files will be deleted too. This cannot be undone.')),
                                    showCancelButton: true,
                                    confirmButtonColor: '#dc2626',
                                    confirmButtonText: @js(__('Delete')),
                                    cancelButtonText: @js(__('Cancel')),
                                }).then(r => { if (r.isConfirmed) $wire.delete({{ $w->id }}) })"
                                class="ml-auto rounded-lg border border-red-200 px-3 py-1.5 text-sm text-red-600 hover:bg-red-50">
                                {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div>{{ $weddings->links() }}</div>
    @else
        <div class="rounded-2xl border-2 border-dashed border-gray-300 p-12 text-center">
            <div class="text-4xl">💍</div>
            <p class="mt-2 text-lg font-medium">
                {{ $search !== '' || $status !== 'all' ? __('No weddings match your filter.') : __('No weddings yet.') }}
            </p>
            @if ($search === '' && $status === 'all')
                <p class="mt-1 text-sm text-gray-500">{{ __('Create your first wedding from a template.') }}</p>
                <a href="{{ route('weddings.create') }}" wire:navigate
                    class="mt-4 inline-block rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-amber-700">
                    + {{ __('New wedding') }}
                </a>
            @endif
        </div>
    @endif
</div>
