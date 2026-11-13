@php
    $isBackground = ($s['section'] ?? null) !== null;

    $hasLibrary = $isBackground && !empty($this->library);

    $ownImages = $isBackground ? $this->ownImages : [];

    $canPick = $isBackground && ($hasLibrary || !empty($ownImages));

    $isNew = $s['slot'] === 'gallery_new';

    $btn =
        'inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg border px-2 py-1.5 text-xs font-medium transition';
@endphp

<div wire:key="slot-{{ $s['slot'] }}" x-data="weddingUploader(@js($s['slot']), @js((bool) $s['is_music']), @js($this->uploadLimit), @js($isNew))"
    class="flex flex-col gap-2 rounded-2xl border border-gray-200 bg-white p-2.5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-2 px-0.5">
        <p class="truncate text-xs font-semibold text-gray-800">
            {{ $s['label'] }}
        </p>

        @if ($s['saved'])
            <span
                class="inline-flex shrink-0 items-center gap-1 rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-semibold text-green-700">
                <x-heroicon-s-check class="h-3 w-3" />
                {{ trans_json('Yours') }}
            </span>
        @elseif ($s['url'])
            <span class="shrink-0 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-500">
                {{ trans_json('Default') }}
            </span>
        @endif
    </div>

    {{-- Preview --}}
    <div
        class="group relative flex {{ $s['is_music'] ? 'min-h-24' : 'aspect-[3/4]' }} items-center justify-center overflow-hidden rounded-xl bg-gradient-to-b from-gray-100 to-gray-200 ring-1 ring-inset ring-gray-200">

        @if ($s['is_music'])
            @if ($s['url'])
                <audio controls preload="none" src="{{ $s['url'] }}" class="w-full px-2"></audio>
            @else
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-white text-gray-300 shadow-sm">
                    <x-heroicon-o-musical-note class="h-6 w-6" />
                </span>
            @endif
        @elseif ($s['url'])
            <img wire:key="img-{{ $s['slot'] }}-{{ md5((string) $s['url']) }}" src="{{ $s['url'] }}"
                alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                x-on:load="imgErr = false" x-on:error="imgErr = true">

            <p x-show="imgErr" x-cloak
                class="absolute inset-x-1 bottom-1 flex items-start gap-1 rounded bg-red-50 px-1.5 py-1 text-[10px] leading-tight text-red-600">
                <x-heroicon-s-exclamation-circle class="mt-px h-3 w-3 shrink-0" />
                {{ trans_json('Preview failed. Check the link, `php artisan storage:link` and APP_URL.') }}
            </p>
        @else
            <button type="button" x-on:click="$refs.file.click()"
                class="flex flex-col items-center gap-1.5 text-gray-300 transition hover:text-amber-500">
                <span
                    class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-dashed border-current">
                    <x-heroicon-o-plus class="h-6 w-6" />
                </span>
                @if ($isNew)
                    <span class="text-[11px] font-medium text-gray-400">
                        {{ trans_json('Add photos') }}
                    </span>
                @endif
            </button>
        @endif

        {{-- Upload progress --}}
        <div x-show="busy" x-cloak
            class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-white/85 px-4 text-xs font-medium text-amber-700 backdrop-blur-sm">

            <x-heroicon-o-arrow-path class="h-6 w-6 animate-spin" />

            <span x-text="status || '{{ trans_json('Uploading…') }}'"></span>

            <div class="h-1.5 w-full overflow-hidden rounded-full bg-amber-100">
                <div class="h-full rounded-full bg-amber-500 transition-all" x-bind:style="`width: ${pct}%`"></div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-wrap gap-1.5">

        <input type="file" x-ref="file" x-on:change="pick($event)" class="hidden"
            @if ($isNew) multiple @endif
            accept="{{ $s['is_music'] ? 'audio/mpeg,audio/mp4,audio/x-m4a,.mp3,.m4a' : 'image/*' }}">

        {{-- Upload --}}
        <button type="button" x-on:click="$refs.file.click()" x-bind:disabled="busy"
            class="{{ $btn }} border-amber-200 bg-amber-50 text-amber-800 hover:bg-amber-100 disabled:opacity-60">
            <x-heroicon-o-arrow-up-tray class="h-3.5 w-3.5" />
            {{ $isNew ? trans_json('Upload') : ($s['saved'] ? trans_json('Replace') : trans_json('Upload')) }}
        </button>

        {{-- Link --}}
        <button type="button" x-on:click="linkOpen = ! linkOpen"
            x-bind:class="linkOpen ? 'border-amber-400 bg-amber-50 text-amber-800' :
                'border-gray-300 text-gray-700 hover:bg-gray-50'"
            class="{{ $btn }}">
            <x-heroicon-o-link class="h-3.5 w-3.5" />
            {{ trans_json('Link') }}
        </button>

        {{-- Choose image --}}
        @if ($canPick)
            <button type="button" x-on:click="picker = '{{ $s['slot'] }}'"
                class="{{ $btn }} w-full basis-full border-gray-300 text-gray-700 hover:bg-gray-50">
                <x-heroicon-o-photo class="h-3.5 w-3.5" />
                {{ trans_json('Choose image') }}
            </button>
        @endif

        {{-- Remove --}}
        @if ($s['gallery_index'] !== null)
            <button type="button" wire:click="removeGallery({{ $s['gallery_index'] }})"
                title="{{ trans_json('Remove') }}" aria-label="{{ trans_json('Remove') }}"
                class="inline-flex items-center justify-center rounded-lg border border-red-200 px-2.5 py-1.5 text-red-600 transition hover:bg-red-50">
                <x-heroicon-o-trash class="h-3.5 w-3.5" />
            </button>
        @elseif ($s['saved'])
            <button type="button" wire:click="clearSlot('{{ $s['slot'] }}')" title="{{ trans_json('Remove') }}"
                aria-label="{{ trans_json('Remove') }}"
                class="inline-flex items-center justify-center rounded-lg border border-red-200 px-2.5 py-1.5 text-red-600 transition hover:bg-red-50">
                <x-heroicon-o-trash class="h-3.5 w-3.5" />
            </button>
        @endif
    </div>

    {{-- Link input --}}
    <div x-show="linkOpen" x-cloak class="flex gap-1.5">
        <input type="text" x-ref="link"
            wire:key="url-{{ $s['slot'] }}-{{ md5((string) ($s['saved'] ? $s['url'] : '')) }}"
            value="{{ $isNew ? '' : ($s['saved'] ? $s['url'] : '') }}"
            placeholder="{{ $s['is_music'] ? '/assets/music/wedding.mp3' : 'https://…' }}"
            x-on:keydown.enter.prevent="applyLink($refs.link.value)"
            class="min-w-0 flex-1 rounded-lg border border-gray-300 bg-white px-2 py-1.5 text-xs placeholder:text-gray-400 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30">

        <button type="button" x-on:click="applyLink($refs.link.value)"
            class="inline-flex items-center gap-1 rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-gray-700">
            <x-heroicon-o-check class="h-3.5 w-3.5" />
            {{ $isNew ? trans_json('Add') : trans_json('Use') }}
        </button>
    </div>

    {{-- Error --}}
    <p x-show="err" x-text="err" x-cloak
        class="rounded-lg bg-red-50 px-2 py-1.5 text-[11px] leading-snug text-red-700"></p>

    @error('uploads.' . $s['slot'])
        <p class="flex items-center gap-1 text-xs text-red-600">
            <x-heroicon-s-exclamation-circle class="h-3.5 w-3.5 shrink-0" />{{ $message }}
        </p>
    @enderror

    {{-- Image picker --}}
    @if ($canPick)

        @php
            $groups = collect($this->library)->sortBy(fn($items, $section) => $section === $s['section'] ? 0 : 1);

            $start = !empty($ownImages) ? 'mine' : 'library';
        @endphp

        <div x-show="picker === '{{ $s['slot'] }}'" style="display:none" x-on:keydown.escape.window="picker = null"
            x-on:click.self="picker = null"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">

            <div x-data="{ pane: '{{ $start }}' }"
                class="max-h-[85vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white p-5 text-gray-900 shadow-2xl">

                {{-- Modal header --}}
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="flex items-center gap-2 font-semibold">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                            <x-heroicon-o-photo class="h-4 w-4" />
                        </span>
                        {{ trans_json('Choose a background') }}
                        <span class="text-gray-300">/</span>
                        <span class="font-medium text-gray-500">{{ $s['label'] }}</span>
                    </h3>

                    <button type="button" x-on:click="picker = null" aria-label="{{ trans_json('Close') }}"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100">
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                    </button>
                </div>

                {{-- Tabs --}}
                <div class="mb-4 flex gap-1 rounded-lg bg-gray-100 p-1 text-sm">
                    @if (!empty($ownImages))
                        <button type="button" x-on:click="pane = 'mine'"
                            x-bind:class="pane === 'mine' ? 'bg-white shadow' : 'text-gray-500'"
                            class="flex flex-1 items-center justify-center gap-1.5 rounded-md px-3 py-1.5 font-medium transition">
                            <x-heroicon-o-user-circle class="h-4 w-4" />
                            {{ trans_json('My images') }} ({{ count($ownImages) }})
                        </button>
                    @endif

                    @if ($hasLibrary)
                        <button type="button" x-on:click="pane = 'library'"
                            x-bind:class="pane === 'library' ? 'bg-white shadow' : 'text-gray-500'"
                            class="flex flex-1 items-center justify-center gap-1.5 rounded-md px-3 py-1.5 font-medium transition">
                            <x-heroicon-o-rectangle-stack class="h-4 w-4" />
                            {{ trans_json('Library') }}
                        </button>
                    @endif
                </div>

                {{-- My images --}}
                @if (!empty($ownImages))
                    <div x-show="pane === 'mine'" class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                        @foreach ($ownImages as $img)
                            <button type="button" wire:key="own-{{ $s['slot'] }}-{{ $loop->index }}"
                                wire:click="pickImage('{{ $s['slot'] }}', @js($img['url']))"
                                x-on:click="picker = null"
                                class="group overflow-hidden rounded-xl border-2 border-gray-200 text-left transition hover:-translate-y-0.5 hover:border-amber-500 hover:shadow-md">
                                <div class="aspect-[3/4] overflow-hidden bg-gray-100">
                                    <img src="{{ $img['url'] }}" alt="" loading="lazy"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                </div>
                                <p class="truncate px-2 py-1.5 text-xs">{{ $img['label'] }}</p>
                            </button>
                        @endforeach
                    </div>
                @endif

                {{-- Image library --}}
                @if ($hasLibrary)
                    <div x-show="pane === 'library'">
                        @foreach ($groups as $section => $items)
                            <p
                                class="mb-2 mt-4 flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-gray-500 first:mt-0">
                                {{ \Illuminate\Support\Str::headline((string) $section) }}

                                @if ($section === $s['section'])
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-[10px] normal-case tracking-normal text-amber-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                        {{ trans_json('this section') }}
                                    </span>
                                @endif
                            </p>

                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                                @foreach ($items as $b)
                                    <button type="button" wire:key="lib-{{ $s['slot'] }}-{{ $b['id'] }}"
                                        wire:click="pickBackground('{{ $s['slot'] }}', {{ $b['id'] }})"
                                        x-on:click="picker = null"
                                        class="group overflow-hidden rounded-xl border-2 border-gray-200 text-left transition hover:-translate-y-0.5 hover:border-amber-500 hover:shadow-md">
                                        <div class="aspect-[3/4] overflow-hidden bg-gray-100">
                                            <img src="{{ $b['url'] }}" alt="" loading="lazy"
                                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                        </div>

                                        <p class="flex items-center gap-1 truncate px-2 py-1.5 text-xs">
                                            <span class="truncate">{{ $b['name'] ?: trans_json('Background') }}</span>
                                            @if ($b['is_default'])
                                                <x-heroicon-s-star class="h-3.5 w-3.5 shrink-0 text-amber-500" />
                                            @endif
                                        </p>
                                    </button>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
