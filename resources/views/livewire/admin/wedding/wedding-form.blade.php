@php
    $basicsError = $errors->hasAny(['template_key', 'groom_name', 'bride_name', 'slug', 'label']);

    $tabs = [
        'basics' => ['1', __('Basics')],
        'photos' => ['2', __('Photos & music')],
        'details' => ['3', __('Details')],
    ];

    $inputCls =
        'w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm placeholder:text-gray-400 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30';
    $card = 'space-y-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm';
@endphp

<div class="mx-auto max-w-5xl space-y-6 p-4 pb-32" x-data="{ tab: 'basics', picker: null }"
    x-on:scroll-top.window="window.scrollTo({ top: 0, behavior: 'smooth' })">

    @include('livewire.admin.wedding.partials.swal')

    {{-- ============ SAVED SCREEN ============ --}}
    @if ($savedUrl)
        <div class="space-y-6" x-data="{ copied: false }">
            <div role="status"
                class="flex items-start gap-3 rounded-xl border border-green-500/40 bg-green-50 p-4 text-green-700">
                <span
                    class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-green-500 text-sm font-bold text-white">✓</span>
                <div>
                    <p class="font-semibold">
                        {{ $savedIsNew ? __('Invitation created successfully') : __('Changes saved successfully') }}
                    </p>
                    <p class="text-sm opacity-80">
                        {{ $is_published
                            ? __('Your invitation is live. Copy the link below and send it to your guests.')
                            : __('Saved as a draft. Only you can preview it until you publish.') }}
                    </p>
                </div>
            </div>

            <h1 class="text-2xl font-semibold">{{ $savedIsNew ? __('Invitation created') : __('Saved') }}</h1>
            <p class="text-sm text-gray-600">{{ __('Invitation link:') }}</p>

            <div class="flex items-center gap-2 rounded-xl border border-gray-200 bg-white p-3 text-sm shadow-sm">
                <a href="{{ $savedUrl }}" target="_blank"
                    class="min-w-0 flex-1 truncate text-amber-700 hover:underline">
                    {{ urldecode($savedUrl) }}
                </a>
                <button type="button"
                    x-on:click="navigator.clipboard.writeText(@js($savedUrl)); copied = true; setTimeout(() => copied = false, 1500)"
                    class="rounded-lg border border-gray-300 px-3 py-1.5 hover:bg-gray-50">
                    <span x-show="!copied">{{ __('Copy') }}</span>
                    <span x-show="copied" x-cloak>✓ {{ __('Copied') }}</span>
                </button>
            </div>

            <div class="flex flex-wrap gap-3 text-sm">
                <a href="{{ route('weddings.index') }}" wire:navigate
                    class="rounded-lg bg-amber-600 px-4 py-2.5 font-semibold text-white hover:bg-amber-700">
                    {{ __('All weddings') }}
                </a>
                <a href="{{ route('weddings.edit', $wedding) }}" wire:navigate
                    class="rounded-lg border border-gray-300 px-4 py-2.5 hover:bg-gray-50">
                    {{ __('Keep editing') }}
                </a>
                @if ($savedIsNew)
                    <a href="{{ route('weddings.create', ['template' => $template_key]) }}" wire:navigate
                        class="rounded-lg border border-gray-300 px-4 py-2.5 hover:bg-gray-50">
                        {{ __('Create another') }}
                    </a>
                @endif
            </div>
        </div>
    @else
        {{-- Header --}}
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <a href="{{ route('weddings.index') }}" wire:navigate
                    class="text-sm text-gray-500 hover:text-amber-600">
                    ← {{ __('Back to weddings') }}
                </a>
                <h1 class="mt-1 text-2xl font-semibold">
                    {{ $wedding ? __('Edit wedding') : __('New wedding') }}
                </h1>
                <p class="text-sm text-gray-500">
                    @if ($groom_name !== '' || $bride_name !== '')
                        <span class="font-medium text-gray-700">{{ $groom_name ?: '…' }}</span>
                        <span class="text-amber-600">&</span>
                        <span class="font-medium text-gray-700">{{ $bride_name ?: '…' }}</span>
                    @else
                        {{ __('Pick a template, type the names, add photos. The link is made for you.') }}
                    @endif
                </p>
            </div>

            @if ($wedding)
                <a href="{{ $wedding->url }}" target="_blank"
                    class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm shadow-sm hover:bg-gray-50">
                    {{ __('View live page') }} ↗
                </a>
            @endif
        </div>

        {{-- Tabs --}}
        <nav class="flex gap-1 rounded-xl bg-gray-100 p-1">
            @foreach ($tabs as $key => [$num, $name])
                <button type="button" x-on:click="tab = '{{ $key }}'"
                    x-bind:class="tab === '{{ $key }}'
                        ?
                        'bg-white text-gray-900 shadow' :
                        'text-gray-500 hover:text-gray-800'"
                    class="relative flex flex-1 items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition">
                    <span
                        class="flex h-5 w-5 items-center justify-center rounded-full bg-amber-500 text-[11px] font-semibold text-white">{{ $num }}</span>
                    <span class="truncate">{{ $name }}</span>
                    @if ($key === 'basics' && $basicsError)
                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500"></span>
                    @endif
                </button>
            @endforeach
        </nav>

        <form wire:submit="save" class="space-y-6">

            {{-- ============ 1. BASICS ============ --}}
            <div x-show="tab === 'basics'" class="space-y-6">

                <section class="{{ $card }}">
                    <div>
                        <h2 class="font-semibold">{{ __('Custom name') }} <span class="text-red-500">*</span></h2>
                        <p class="text-xs text-gray-500">
                            {{ __('The custom name is only for you. Guests never see it.') }}</p>
                    </div>

                    <div>
                        <input type="text" wire:model="label" maxlength="80" required
                            placeholder="{{ __('e.g. សុខា & ស្រីនាង – ភ្នំពេញ') }}" class="{{ $inputCls }}">
                        @error('label')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <section class="{{ $card }}">
                    <div>
                        <h2 class="font-semibold">{{ __('Template') }}</h2>
                        <p class="text-xs text-gray-500">
                            {{ $wedding
                                ? __('You can switch template. Your content stays; only the design changes.')
                                : __('Choosing a template fills the form with its sample content. Switching later resets what you typed.') }}
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($this->templates as $t)
                            <label wire:key="tpl-{{ $t->key }}" class="block cursor-pointer">
                                <input type="radio" class="peer sr-only" wire:model.live="template_key"
                                    value="{{ $t->key }}">
                                <div
                                    class="overflow-hidden rounded-xl border-2 border-gray-200 transition peer-checked:border-amber-500 peer-checked:ring-4 peer-checked:ring-amber-500/20 hover:border-amber-300">
                                    <div class="aspect-[4/3] bg-gradient-to-br from-amber-200 to-amber-500">
                                        @if ($t->preview_src)
                                            <img src="{{ $t->preview_src }}" alt=""
                                                class="h-full w-full object-cover" loading="lazy">
                                        @endif
                                    </div>
                                    <div class="p-3">
                                        <p class="text-sm font-semibold">{{ $t->name }}</p>
                                        @if ($t->description)
                                            <p class="line-clamp-2 text-xs text-gray-500">{{ $t->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error('template_key')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </section>

                <section class="{{ $card }}">
                    <h2 class="font-semibold">{{ __('The couple') }}</h2>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600">{{ __('Groom name') }}</label>
                            <input type="text" wire:model.live.debounce.400ms="groom_name"
                                class="{{ $inputCls }}">
                            @error('groom_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600">{{ __('Bride name') }}</label>
                            <input type="text" wire:model.live.debounce.400ms="bride_name"
                                class="{{ $inputCls }}">
                            @error('bride_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>

                <section class="{{ $card }}">
                    <div>
                        <h2 class="font-semibold">{{ __('Public link') }}</h2>
                        <p class="text-xs text-gray-500">
                            {{ __('Made from the groom\'s name (Khmer letters work). If it is taken, a number is added. After you save, the link stays the same even if you change the name.') }}
                        </p>
                    </div>

                    <div
                        class="flex items-center overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm focus-within:border-amber-500 focus-within:ring-2 focus-within:ring-amber-500/30">
                        <span
                            class="hidden bg-gray-50 px-3 py-2.5 text-sm text-gray-500 sm:block">{{ rtrim(url('/'), '/') }}/</span>
                        <input type="text" wire:model.blur="slug"
                            placeholder="{{ __('made-from-the-groom-name') }}"
                            class="min-w-0 flex-1 bg-transparent px-3 py-2.5 text-sm outline-none">
                    </div>
                    @error('slug')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if ($slug !== '')
                        <p class="break-all rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-800">
                            🔗 {{ rtrim(url('/'), '/') }}/{{ $slug }}
                        </p>
                    @endif
                </section>

                <div class="flex justify-end">
                    <button type="button"
                        x-on:click="tab = 'photos'; window.scrollTo({ top: 0, behavior: 'smooth' })"
                        class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                        {{ __('Next: Photos') }} →
                    </button>
                </div>
            </div>

            {{-- ============ 2. PHOTOS & MUSIC ============ --}}
            <div x-show="tab === 'photos'" style="display:none" class="space-y-6">

                @forelse ($this->photoGroups as $group)
                    <section wire:key="group-{{ $group['key'] }}" class="{{ $card }}">
                        <div class="flex items-center justify-between">
                            <h2 class="font-semibold">{{ $group['title'] }}</h2>
                            @if ($loop->first)
                                <span wire:loading wire:target="uploads"
                                    class="text-sm text-amber-600">{{ __('Uploading…') }}</span>
                            @endif
                        </div>

                        @if ($group['key'] === 'backgrounds')
                            <p class="-mt-2 text-xs text-gray-500">
                                {{ __('Upload your own photo to replace the background of any section. "Shared" is used for every section without its own photo. Empty slots use the template\'s default.') }}
                            </p>
                        @endif

                        @if ($group['key'] === 'gallery')
                            <p class="-mt-2 text-xs text-gray-500">{{ __('Up to 12 photos.') }}</p>
                        @endif

                        <div class="grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-4">
                            @foreach ($group['slots'] as $s)
                                @include('livewire.admin.wedding.partials.slot', ['s' => $s])
                            @endforeach
                        </div>
                    </section>
                @empty
                    <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500">
                        {{ __('No photo slots yet. Choose a template on the Basics tab first.') }}
                    </div>
                @endforelse

                <p class="text-xs text-gray-500">
                    {{ __('Files are uploaded straight away and linked to this wedding when you press Save. Images up to 8 MB, music up to 15 MB.') }}
                </p>

                <div class="flex justify-between">
                    <button type="button"
                        x-on:click="tab = 'basics'; window.scrollTo({ top: 0, behavior: 'smooth' })"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm hover:bg-gray-50">
                        ← {{ __('Back') }}
                    </button>
                    <button type="button"
                        x-on:click="tab = 'details'; window.scrollTo({ top: 0, behavior: 'smooth' })"
                        class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                        {{ __('Next: Details') }} →
                    </button>
                </div>
            </div>

            {{-- ============ 3. DETAILS ============ --}}
            <div x-show="tab === 'details'" style="display:none" class="space-y-4">
                <p class="text-xs text-gray-500">
                    {{ __('Date, schedule, texts, love story, dress code, gift. Fields save when you press Save.') }}
                </p>

                @php
                    $eventsKey = \App\Models\Invitation::listKey('events', $data);
                    $storyKey = \App\Models\Invitation::listKey('stories', $data);
                    $lbl = 'mb-1 block text-xs font-medium text-gray-600';
                    $rm = 'rounded-md px-2 py-1 text-xs text-red-600 hover:bg-red-50';
                    $add =
                        'rounded-xl border-2 border-dashed border-gray-300 px-3 py-2.5 text-sm font-medium text-gray-500 transition hover:border-amber-400 hover:bg-amber-50 hover:text-amber-700';
                @endphp

                <section class="{{ $card }}">
                    <h2 class="font-semibold">{{ __('Parents') }}</h2>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $lbl }}">{{ __("Groom's parents") }}</label>
                            <input type="text" wire:model.blur="data.groom.parents" class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label class="{{ $lbl }}">{{ __("Bride's parents") }}</label>
                            <input type="text" wire:model.blur="data.bride.parents" class="{{ $inputCls }}">
                        </div>
                    </div>
                </section>

                <section class="{{ $card }}">
                    <div>
                        <h2 class="font-semibold">{{ __('Date') }}</h2>
                        <p class="text-xs text-gray-500">
                            {{ __('Cambodia time (UTC+7). Leave the date text empty to generate it in Khmer.') }}</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $lbl }}">{{ __('Wedding date and time') }}</label>
                            <input type="datetime-local" wire:model.blur="dateLocal" class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label class="{{ $lbl }}">{{ __('Date text (optional)') }}</label>
                            <input type="text" wire:model.blur="data.dateText" class="{{ $inputCls }}">
                        </div>
                    </div>
                </section>

                <section class="{{ $card }}">
                    <h2 class="font-semibold">{{ __('Schedule') }}</h2>

                    @foreach ($data[$eventsKey] ?? [] as $i => $ev)
                        <div wire:key="ev-{{ $i }}"
                            class="space-y-3 rounded-xl border border-gray-200 bg-gray-50/70 p-3">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold">{{ __('Event') }} {{ $i + 1 }}</p>
                                <button type="button"
                                    wire:click="removeItem('{{ $eventsKey }}', {{ $i }})"
                                    class="{{ $rm }}">{{ __('Remove') }}</button>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="{{ $lbl }}">{{ __('Time') }}</label>
                                    <input type="time"
                                        wire:model.blur="data.{{ $eventsKey }}.{{ $i }}.time"
                                        class="{{ $inputCls }}">
                                </div>
                                <div>
                                    <label class="{{ $lbl }}">{{ __('Title') }}</label>
                                    <input type="text"
                                        wire:model.blur="data.{{ $eventsKey }}.{{ $i }}.title"
                                        class="{{ $inputCls }}">
                                </div>
                                <div>
                                    <label class="{{ $lbl }}">{{ __('Place') }}</label>
                                    <input type="text"
                                        wire:model.blur="data.{{ $eventsKey }}.{{ $i }}.place"
                                        class="{{ $inputCls }}">
                                </div>
                                <div>
                                    <label
                                        class="{{ $lbl }}">{{ __('Google Maps link (optional)') }}</label>
                                    <input type="text"
                                        wire:model.blur="data.{{ $eventsKey }}.{{ $i }}.mapUrl"
                                        placeholder="https://maps.google.com/..." class="{{ $inputCls }}">
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if (count($data[$eventsKey] ?? []) < 12)
                        <button type="button" wire:click="addEvent" class="{{ $add }} w-full">+
                            {{ __('Add event') }}</button>
                    @endif
                </section>

                <section class="{{ $card }}">
                    <h2 class="font-semibold">{{ __('Words') }}</h2>
                    <div>
                        <label class="{{ $lbl }}">{{ __('Invitation message') }}</label>
                        <textarea rows="3" wire:model.blur="data.intro" class="{{ $inputCls }}"></textarea>
                    </div>
                    <div>
                        <label class="{{ $lbl }}">{{ __('Quote') }}</label>
                        <input type="text" wire:model.blur="data.quote" class="{{ $inputCls }}">
                    </div>
                    <div>
                        <label class="{{ $lbl }}">{{ __('Apology note') }}</label>
                        <textarea rows="4" wire:model.blur="data.apology" class="{{ $inputCls }}"></textarea>
                    </div>
                    <div>
                        <label class="{{ $lbl }}">{{ __('Thank-you note') }}</label>
                        <textarea rows="4" wire:model.blur="data.thanks" class="{{ $inputCls }}"></textarea>
                    </div>
                </section>

                <section class="{{ $card }}">
                    <div>
                        <h2 class="font-semibold">{{ __('Love story') }}</h2>
                        <p class="text-xs text-gray-500">{{ __('Optional. Remove all items to hide this section.') }}
                        </p>
                    </div>

                    @foreach ($data[$storyKey] ?? [] as $i => $st)
                        <div wire:key="st-{{ $i }}"
                            class="space-y-3 rounded-xl border border-gray-200 bg-gray-50/70 p-3">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold">{{ __('Moment') }} {{ $i + 1 }}</p>
                                <button type="button"
                                    wire:click="removeItem('{{ $storyKey }}', {{ $i }})"
                                    class="{{ $rm }}">{{ __('Remove') }}</button>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="{{ $lbl }}">{{ __('Year') }}</label>
                                    <input type="text"
                                        wire:model.blur="data.{{ $storyKey }}.{{ $i }}.year"
                                        class="{{ $inputCls }}">
                                </div>
                                <div>
                                    <label class="{{ $lbl }}">{{ __('Title') }}</label>
                                    <input type="text"
                                        wire:model.blur="data.{{ $storyKey }}.{{ $i }}.title"
                                        class="{{ $inputCls }}">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="{{ $lbl }}">{{ __('Text') }}</label>
                                    <textarea rows="2" wire:model.blur="data.{{ $storyKey }}.{{ $i }}.text"
                                        class="{{ $inputCls }}"></textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if (count($data[$storyKey] ?? []) < 10)
                        <button type="button" wire:click="addStory" class="{{ $add }} w-full">+
                            {{ __('Add moment') }}</button>
                    @endif
                </section>

                <section class="{{ $card }}">
                    <div>
                        <h2 class="font-semibold">{{ __('Dress code') }}</h2>
                        <p class="text-xs text-gray-500">
                            {{ __('Optional. Remove all colors to hide this section.') }}</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $lbl }}">{{ __('Title') }}</label>
                            <input type="text" wire:model.blur="data.dressCode.title"
                                class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label class="{{ $lbl }}">{{ __('Note') }}</label>
                            <input type="text" wire:model.blur="data.dressCode.note"
                                class="{{ $inputCls }}">
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        @foreach ($data['dressCode']['colors'] ?? [] as $i => $c)
                            <div wire:key="color-{{ $i }}" class="flex items-center gap-1">
                                <input type="color" wire:model.blur="data.dressCode.colors.{{ $i }}"
                                    class="h-10 w-10 cursor-pointer rounded-lg border border-gray-300 bg-white p-1"
                                    aria-label="{{ __('Color') }} {{ $i + 1 }}">
                                <button type="button"
                                    wire:click="removeItem('dressCode.colors', {{ $i }})"
                                    class="text-xs text-red-600">✕</button>
                            </div>
                        @endforeach
                        @if (count($data['dressCode']['colors'] ?? []) < 8)
                            <button type="button" wire:click="addColor" class="{{ $add }}">+
                                {{ __('Color') }}</button>
                        @endif
                    </div>
                </section>

                <section class="{{ $card }}">
                    <div>
                        <h2 class="font-semibold">{{ __('Wedding gift') }}</h2>
                        <p class="text-xs text-gray-500">
                            {{ __('Optional. Leave the account number empty to hide this section.') }}</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="{{ $lbl }}">{{ __('Bank') }}</label>
                            <input type="text" wire:model.blur="data.gift.bank" class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label class="{{ $lbl }}">{{ __('Account name') }}</label>
                            <input type="text" wire:model.blur="data.gift.accountName"
                                class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label class="{{ $lbl }}">{{ __('Account number') }}</label>
                            <input type="text" wire:model.blur="data.gift.accountNumber"
                                class="{{ $inputCls }}">
                        </div>
                    </div>
                </section>

                @php
                    $covered = [
                        'groom',
                        'bride',
                        'date',
                        'dateText',
                        'cover',
                        'gallery',
                        'backgrounds',
                        'intro',
                        'quote',
                        'apology',
                        'thanks',
                        'dressCode',
                        'gift',
                        'music',
                        $eventsKey,
                        $storyKey,
                    ];
                    $other = collect($data)->except($covered)->filter(fn($v) => is_scalar($v) || is_null($v));
                @endphp
                @if ($other->isNotEmpty())
                    <section class="{{ $card }}">
                        <h2 class="font-semibold">{{ __('Other') }}</h2>
                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach ($other as $k => $v)
                                <div wire:key="other-{{ $k }}">
                                    <label
                                        class="{{ $lbl }}">{{ \Illuminate\Support\Str::headline((string) $k) }}</label>
                                    <input type="text" wire:model.blur="data.{{ $k }}"
                                        class="{{ $inputCls }}">
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <div class="flex justify-start">
                    <button type="button"
                        x-on:click="tab = 'photos'; window.scrollTo({ top: 0, behavior: 'smooth' })"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm hover:bg-gray-50">
                        ← {{ __('Back') }}
                    </button>
                </div>
            </div>

            {{-- ============ Sticky save bar ============ --}}
            <div class="fixed inset-x-0 bottom-4 z-30 mx-auto w-[calc(100%-2rem)] max-w-5xl">
                <div
                    class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-gray-200 bg-white/90 px-4 py-3 shadow-lg backdrop-blur">

                    <label class="flex cursor-pointer items-center gap-3 text-sm">
                        <span class="relative inline-flex items-center">
                            <input type="checkbox" wire:model.live="is_published" class="peer sr-only">
                            <span
                                class="h-6 w-11 rounded-full bg-gray-300 transition peer-checked:bg-green-500 peer-focus-visible:ring-2 peer-focus-visible:ring-green-500/40 after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-all after:content-[''] peer-checked:after:translate-x-5"></span>
                        </span>
                        <span>
                            <span class="font-medium">{{ $is_published ? __('Published') : __('Draft') }}</span>
                            <span class="hidden text-xs text-gray-500 sm:inline">
                                —
                                {{ $is_published ? __('anyone with the link can open it') : __('only you can preview it') }}
                            </span>
                        </span>
                    </label>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('weddings.index') }}" wire:navigate
                            class="text-sm text-gray-500 hover:underline">
                            {{ __('Cancel') }}
                        </a>

                        <button type="submit" wire:loading.attr="disabled" wire:target="save, uploads"
                            class="rounded-lg bg-amber-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-amber-700 disabled:opacity-60">
                            <span wire:loading.remove
                                wire:target="save">{{ $wedding ? __('Save changes') : __('Create wedding') }}</span>
                            <span wire:loading wire:target="save">{{ __('Saving…') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>

    @endif
</div>
