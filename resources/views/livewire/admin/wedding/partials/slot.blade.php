{{-- One upload card. Expects $s (slot array). Needs `picker` in the parent x-data. --}}
@php
    $isBackground = ($s['section'] ?? null) !== null;
    $hasLibrary = $isBackground && !empty($this->library);
    $ownImages = $isBackground ? $this->ownImages : [];
    $canPick = $isBackground && ($hasLibrary || !empty($ownImages));
@endphp

<div wire:key="slot-{{ $s['slot'] }}"
    class="group flex flex-col gap-2 rounded-xl border border-gray-200 bg-white p-2.5 shadow-sm transition hover:shadow-md">

    <div class="flex items-center justify-between gap-2">
        <p class="truncate text-xs font-medium text-gray-800">{{ $s['label'] }}</p>

        @if ($s['saved'])
            <span
                class="shrink-0 rounded-full bg-green-100 px-1.5 py-0.5 text-[10px] font-medium text-green-700">{{ __('Yours') }}</span>
        @elseif ($s['url'])
            <span
                class="shrink-0 rounded-full bg-gray-100 px-1.5 py-0.5 text-[10px] text-gray-500">{{ __('Default') }}</span>
        @endif
    </div>

    <div class="relative flex aspect-[3/4] items-center justify-center overflow-hidden rounded-lg bg-gray-100">
        @if ($s['is_music'])
            @if ($s['url'])
                <audio controls src="{{ $s['url'] }}" class="w-full px-1"></audio>
            @else
                <span class="text-3xl text-gray-300">♪</span>
            @endif
        @elseif ($s['url'])
            <img src="{{ $s['url'] }}" alt="" class="h-full w-full object-cover" loading="lazy">
        @else
            <span class="text-3xl text-gray-300">+</span>
        @endif

        <div wire:loading.flex wire:target="uploads.{{ $s['slot'] }}"
            class="absolute inset-0 items-center justify-center bg-white/80 text-xs font-medium text-amber-600">
            {{ __('Uploading…') }}
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        {{-- choose a file here to replace the current / default image --}}
        <input type="file" wire:model="uploads.{{ $s['slot'] }}"
            accept="{{ $s['is_music'] ? 'audio/mpeg,audio/mp4,.mp3,.m4a' : 'image/*' }}"
            class="block w-full min-w-0 flex-1 cursor-pointer text-xs text-gray-600 file:mr-2 file:cursor-pointer file:rounded-lg file:border-0 file:bg-amber-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-amber-800 hover:file:bg-amber-200">

        @if ($s['gallery_index'] !== null)
            <button type="button" wire:click="removeGallery({{ $s['gallery_index'] }})"
                class="rounded-lg border border-red-200 px-2 py-1 text-xs text-red-600 hover:bg-red-50">✕</button>
        @elseif ($s['saved'])
            <button type="button" wire:click="clearSlot('{{ $s['slot'] }}')"
                class="rounded-lg border border-red-200 px-2 py-1 text-xs text-red-600 hover:bg-red-50">✕</button>
        @endif

        @if ($canPick)
            <button type="button" x-on:click="picker = '{{ $s['slot'] }}'"
                class="w-full rounded-lg border border-gray-300 px-2 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                🖼 {{ __('Choose image') }}
            </button>
        @endif
    </div>

    @if ($s['slot'] !== 'gallery_new')
        <input type="text" value="{{ $s['saved'] ? $s['url'] : '' }}"
            placeholder="{{ $s['is_music'] ? '/music/wedding.mp3' : __('…or paste image link') }}"
            x-on:change="$wire.setSlotUrl('{{ $s['slot'] }}', $event.target.value)"
            class="w-full rounded-lg border border-gray-300 bg-white px-2 py-1.5 text-xs placeholder:text-gray-400 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30">
    @endif

    @error('uploads.' . $s['slot'])
        <p class="text-xs text-red-600">{{ $message }}</p>
    @enderror

    {{-- Picker (section backgrounds only): my images + library --}}
    @if ($canPick)
        @php
            $groups = collect($this->library)->sortBy(fn($items, $section) => $section === $s['section'] ? 0 : 1);
            $start = !empty($ownImages) ? 'mine' : 'library';
        @endphp

        <div x-show="picker === '{{ $s['slot'] }}'" style="display:none" x-on:keydown.escape.window="picker = null"
            x-on:click.self="picker = null" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div x-data="{ pane: '{{ $start }}' }"
                class="max-h-[85vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white p-5 text-gray-900 shadow-2xl">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="font-semibold">{{ __('Choose a background') }} — {{ $s['label'] }}</h3>
                    <button type="button" x-on:click="picker = null"
                        class="rounded-lg px-2 py-1 text-gray-500 hover:bg-gray-100">✕</button>
                </div>

                <div class="mb-4 flex gap-1 rounded-lg bg-gray-100 p-1 text-sm">
                    @if (!empty($ownImages))
                        <button type="button" x-on:click="pane = 'mine'"
                            x-bind:class="pane === 'mine' ? 'bg-white shadow' : 'text-gray-500'"
                            class="flex-1 rounded-md px-3 py-1.5 font-medium">{{ __('My images') }}
                            ({{ count($ownImages) }})</button>
                    @endif
                    @if ($hasLibrary)
                        <button type="button" x-on:click="pane = 'library'"
                            x-bind:class="pane === 'library' ? 'bg-white shadow' : 'text-gray-500'"
                            class="flex-1 rounded-md px-3 py-1.5 font-medium">{{ __('Library') }}</button>
                    @endif
                </div>

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
                                <p class="truncate px-2 py-1.5 text-xs">{{ $img['label'] }}</p>
                            </button>
                        @endforeach
                    </div>
                @endif

                @if ($hasLibrary)
                    <div x-show="pane === 'library'">
                        @foreach ($groups as $section => $items)
                            <p class="mb-2 mt-4 text-xs font-semibold uppercase tracking-wide text-gray-500 first:mt-0">
                                {{ \Illuminate\Support\Str::headline((string) $section) }}
                                @if ($section === $s['section'])
                                    <span class="text-amber-600">· {{ __('this section') }}</span>
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
                                            {{ $b['name'] ?: __('Background') }}
                                            @if ($b['is_default'])
                                                <span class="text-amber-600">★</span>
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
