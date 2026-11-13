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
    class="flex flex-col gap-2 rounded-2xl border border-gray-200 bg-white p-2.5 shadow-sm transition hover:shadow-md">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-2 px-0.5">

        <p class="truncate text-xs font-semibold text-gray-800">
            {{ $s['label'] }}
        </p>

        @if ($s['saved'])
            <span class="shrink-0 rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-semibold text-green-700">
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
        class="relative flex {{ $s['is_music'] ? 'min-h-24' : 'aspect-[3/4]' }} items-center justify-center overflow-hidden rounded-xl bg-gray-100 ring-1 ring-inset ring-gray-200">

        @if ($s['is_music'])

            @if ($s['url'])
                <audio controls preload="none" src="{{ $s['url'] }}" class="w-full px-2">
                </audio>
            @else
                <span class="text-4xl text-gray-300">
                    <i class="fa-solid fa-music"></i>
                </span>
            @endif
        @elseif ($s['url'])
            <img wire:key="img-{{ $s['slot'] }}-{{ md5((string) $s['url']) }}" src="{{ $s['url'] }}"
                alt="" class="h-full w-full object-cover" x-on:load="imgErr = false" x-on:error="imgErr = true">

            <p x-show="imgErr" x-cloak
                class="absolute inset-x-1 bottom-1 rounded bg-red-50 px-1.5 py-1 text-center text-[10px] leading-tight text-red-600">
                {{ trans_json('Preview failed. Check the link, `php artisan storage:link` and APP_URL.') }}
            </p>
        @else
            <div class="flex flex-col items-center gap-1 text-gray-300">

                <i class="fa-solid fa-plus text-4xl"></i>

                @if ($isNew)
                    <span class="text-[11px] text-gray-400">
                        {{ trans_json('Add photos') }}
                    </span>
                @endif

            </div>

        @endif

        {{-- Upload progress --}}
        <div x-show="busy" x-cloak
            class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-white/85 px-4 text-xs font-medium text-amber-700">

            <span x-text="status || '{{ trans_json('Uploading…') }}'"></span>

            <div class="h-1.5 w-full overflow-hidden rounded-full bg-amber-100">
                <div class="h-full rounded-full bg-amber-500 transition-all" x-bind:style="`width: ${pct}%`">
                </div>
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

            <i class="fa-solid fa-upload"></i>

            {{ $isNew ? trans_json('Upload') : ($s['saved'] ? trans_json('Replace') : trans_json('Upload')) }}

        </button>

        {{-- Link --}}
        <button type="button" x-on:click="linkOpen = ! linkOpen"
            x-bind:class="linkOpen ? 'border-amber-400 bg-amber-50 text-amber-800' : 'border-gray-300 text-gray-700 hover:bg-gray-50'"
            class="{{ $btn }}">

            <i class="fa-solid fa-link"></i>

            {{ trans_json('Link') }}

        </button>

        {{-- Choose image --}}
        @if ($canPick)
            <button type="button" x-on:click="picker = '{{ $s['slot'] }}'"
                class="{{ $btn }} w-full basis-full border-gray-300 text-gray-700 hover:bg-gray-50">

                <i class="fa-regular fa-image"></i>

                {{ trans_json('Choose image') }}

            </button>
        @endif

        {{-- Remove --}}
        @if ($s['gallery_index'] !== null)
            <button type="button" wire:click="removeGallery({{ $s['gallery_index'] }})" title="{{ trans_json('Remove') }}"
                class="rounded-lg border border-red-200 px-2.5 py-1.5 text-xs text-red-600 hover:bg-red-50">

                <i class="fa-solid fa-xmark"></i>

            </button>
        @elseif ($s['saved'])
            <button type="button" wire:click="clearSlot('{{ $s['slot'] }}')" title="{{ trans_json('Remove') }}"
                class="rounded-lg border border-red-200 px-2.5 py-1.5 text-xs text-red-600 hover:bg-red-50">

                <i class="fa-solid fa-xmark"></i>

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
            class="rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-medium text-white hover:bg-gray-700">

            {{ $isNew ? trans_json('Add') : trans_json('Use') }}

        </button>

    </div>

    {{-- Error --}}
    <p x-show="err" x-text="err" x-cloak
        class="rounded-lg bg-red-50 px-2 py-1.5 text-[11px] leading-snug text-red-700">
    </p>

    @error('uploads.' . $s['slot'])
        <p class="text-xs text-red-600">{{ $message }}</p>
    @enderror

    {{-- Image picker --}}
    @if ($canPick)

        @php
            $groups = collect($this->library)->sortBy(fn($items, $section) => $section === $s['section'] ? 0 : 1);

            $start = !empty($ownImages) ? 'mine' : 'library';
        @endphp

        <div x-show="picker === '{{ $s['slot'] }}'" style="display:none" x-on:keydown.escape.window="picker = null"
            x-on:click.self="picker = null" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">

            <div x-data="{ pane: '{{ $start }}' }"
                class="max-h-[85vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white p-5 text-gray-900 shadow-2xl">

                {{-- Modal header --}}
                <div class="mb-3 flex items-center justify-between">

                    <h3 class="font-semibold">
                        {{ trans_json('Choose a background') }} — {{ $s['label'] }}
                    </h3>

                    <button type="button" x-on:click="picker = null"
                        class="rounded-lg px-2 py-1 text-gray-500 hover:bg-gray-100">

                        <i class="fa-solid fa-xmark"></i>

                    </button>

                </div>

                {{-- Tabs --}}
                <div class="mb-4 flex gap-1 rounded-lg bg-gray-100 p-1 text-sm">

                    @if (!empty($ownImages))
                        <button type="button" x-on:click="pane = 'mine'"
                            x-bind:class="pane === 'mine' ? 'bg-white shadow' : 'text-gray-500'"
                            class="flex-1 rounded-md px-3 py-1.5 font-medium">

                            {{ trans_json('My images') }} ({{ count($ownImages) }})

                        </button>
                    @endif

                    @if ($hasLibrary)
                        <button type="button" x-on:click="pane = 'library'"
                            x-bind:class="pane === 'library' ? 'bg-white shadow' : 'text-gray-500'"
                            class="flex-1 rounded-md px-3 py-1.5 font-medium">

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
                                class="overflow-hidden rounded-xl border-2 border-gray-200 text-left transition hover:border-amber-500">

                                <div class="aspect-[3/4] bg-gray-100">

                                    <img src="{{ $img['url'] }}" alt="" loading="lazy"
                                        class="h-full w-full object-cover">

                                </div>

                                <p class="truncate px-2 py-1.5 text-xs">
                                    {{ $img['label'] }}
                                </p>

                            </button>
                        @endforeach

                    </div>

                @endif

                {{-- Image library --}}
                @if ($hasLibrary)

                    <div x-show="pane === 'library'">

                        @foreach ($groups as $section => $items)
                            <p
                                class="mb-2 mt-4 text-xs font-semibold uppercase tracking-wide text-gray-500 first:mt-0">

                                {{ \Illuminate\Support\Str::headline((string) $section) }}

                                @if ($section === $s['section'])
                                    <span class="text-amber-600">
                                        · {{ trans_json('this section') }}
                                    </span>
                                @endif

                            </p>

                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">

                                @foreach ($items as $b)
                                    <button type="button" wire:key="lib-{{ $s['slot'] }}-{{ $b['id'] }}"
                                        wire:click="pickBackground('{{ $s['slot'] }}', {{ $b['id'] }})"
                                        x-on:click="picker = null"
                                        class="overflow-hidden rounded-xl border-2 border-gray-200 text-left transition hover:border-amber-500">

                                        <div class="aspect-[3/4] bg-gray-100">

                                            <img src="{{ $b['url'] }}" alt="" loading="lazy"
                                                class="h-full w-full object-cover">

                                        </div>

                                        <p class="truncate px-2 py-1.5 text-xs">

                                            {{ $b['name'] ?: trans_json('Background') }}

                                            @if ($b['is_default'])
                                                <i class="fa-solid fa-star text-amber-600"></i>
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

