@php
    $basicsError = $errors->hasAny(['template_key', 'groom_name', 'bride_name', 'slug', 'label']);

    $tabs = [
        'basics' => ['1', __('Basics'), '✦'],
        'photos' => ['2', __('Photos & music'), '❖'],
        'details' => ['3', __('Details'), '✎'],
    ];

    $inputCls =
        'w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm placeholder:text-gray-400 focus:border-amber-500 focus:outline-none focus:ring-4 focus:ring-amber-500/15';
    $card = 'space-y-4 rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm sm:p-6';
    $h2 = 'text-base font-semibold text-gray-900';
    $hint = 'mt-0.5 text-xs leading-relaxed text-gray-500';
    $lbl = 'mb-1 block text-xs font-medium text-gray-600';
    $rm = 'rounded-lg px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50';
    $add =
        'rounded-xl border-2 border-dashed border-gray-300 px-3 py-2.5 text-sm font-medium text-gray-500 transition hover:border-amber-400 hover:bg-amber-50 hover:text-amber-700';
    $next = 'rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-gray-700';
    $back =
        'rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50';
@endphp

{{-- Upload helper: shrinks big photos in the browser, checks the server limit, shows progress. --}}
@assets
    <script>
        window.weddingUploader = function(slot, isMusic, limit, isNew) {
            const TARGET = 900 * 1024; // aim for <= 900 KB per image (safe for 1 MB proxy limits)
            const MAX_SIDE = 1600; // longest side in pixels
            const mb = (b) => (b / 1048576).toFixed(1) + ' MB';

            return {
                busy: false,
                pct: 0,
                status: '',
                err: null,
                imgErr: false,
                linkOpen: false,

                async pick(event) {
                    const input = event.target;
                    const files = Array.from(input.files || []);
                    if (!files.length) return;

                    this.err = null;
                    this.busy = true;

                    try {
                        for (let i = 0; i < files.length; i++) {
                            this.pct = 0;
                            this.status = files.length > 1 ? `Uploading ${i + 1} / ${files.length}…` : '';

                            let out = files[i];

                            if (!isMusic && out.type.startsWith('image/') && out.type !== 'image/gif') {
                                this.status = 'Preparing photo…';
                                out = await this.shrink(out);
                                this.status = files.length > 1 ? `Uploading ${i + 1} / ${files.length}…` : '';
                            }

                            if (limit && out.size > limit) {
                                throw new Error(
                                    `This file is ${mb(out.size)} but the server accepts at most ${mb(limit)}. ` +
                                    `Use a smaller file, or raise upload_max_filesize and post_max_size in php.ini.`
                                );
                            }

                            await this.send(out);
                        }
                    } catch (e) {
                        this.err = e && e.message ? e.message : 'Upload failed.';
                    } finally {
                        this.busy = false;
                        this.status = '';
                        input.value = '';
                    }
                },

                send(file) {
                    return new Promise((resolve, reject) => {
                        this.$wire.upload(
                            'uploads.' + slot,
                            file,
                            resolve,
                            () => reject(new Error(
                                `The server refused the file (${mb(file.size)}). ` +
                                `Check php.ini upload_max_filesize / post_max_size${limit ? ' (now ' + mb(limit) + ')' : ''}, ` +
                                `the web server body limit (nginx client_max_body_size) and that you are still logged in.`
                            )),
                            (e) => {
                                this.pct = e.detail.progress;
                            }
                        );
                    });
                },

                async shrink(file) {
                    const target = limit ? Math.min(TARGET, limit * 0.9) : TARGET;

                    if (file.size <= target && /^image\/(jpeg|png|webp)$/.test(file.type)) return file;

                    let bmp;
                    try {
                        bmp = await createImageBitmap(file);
                    } catch (e) {
                        return file;
                    } // e.g. HEIC: send as is

                    const scale = Math.min(1, MAX_SIDE / Math.max(bmp.width, bmp.height));
                    const c = document.createElement('canvas');
                    c.width = Math.max(1, Math.round(bmp.width * scale));
                    c.height = Math.max(1, Math.round(bmp.height * scale));

                    const ctx = c.getContext('2d');
                    ctx.fillStyle = '#fff'; // PNG transparency -> white (QR codes stay readable)
                    ctx.fillRect(0, 0, c.width, c.height);
                    ctx.drawImage(bmp, 0, 0, c.width, c.height);

                    let q = 0.88,
                        blob = null;
                    do {
                        blob = await new Promise((r) => c.toBlob(r, 'image/jpeg', q));
                        q -= 0.1;
                    } while (blob && blob.size > target && q > 0.45);

                    if (!blob || blob.size >= file.size) return file;

                    return new File([blob], file.name.replace(/\.[^.]+$/, '') + '.jpg', {
                        type: 'image/jpeg'
                    });
                },

                applyLink(value) {
                    value = (value || '').trim();

                    if (isNew) {
                        if (!value) return;
                        this.$wire.addGalleryUrl(value).then(() => {
                            this.$refs.link.value = '';
                            this.linkOpen = false;
                        });
                    } else {
                        this.$wire.setSlotUrl(slot, value);
                    }
                },
            };
        };
    </script>
@endassets

<div class="mx-auto max-w-5xl space-y-6 p-4 pb-36" x-data="{ tab: 'basics', picker: null }"
    x-on:scroll-top.window="window.scrollTo({ top: 0, behavior: 'smooth' })"
    x-on:goto-tab.window="tab = $event.detail.tab; window.scrollTo({ top: 0, behavior: 'smooth' })">

    @include('livewire.admin.wedding.partials.swal')

    {{-- ============ SAVED SCREEN ============ --}}
    @if ($savedUrl)
        <div class="space-y-6" x-data="{ copied: false }">
            <div role="status"
                class="flex items-start gap-3 rounded-2xl border border-green-500/30 bg-green-50 p-5 text-green-800">
                <span
                    class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-green-500 text-sm font-bold text-white">✓</span>
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

            <div class="{{ $card }}">
                <p class="text-sm font-medium text-gray-600">{{ __('Invitation link:') }}</p>

                <div class="flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 p-3 text-sm">
                    <a href="{{ $savedUrl }}" target="_blank"
                        class="min-w-0 flex-1 truncate font-medium text-amber-700 hover:underline">
                        {{ urldecode($savedUrl) }}
                    </a>
                    <button type="button"
                        x-on:click="navigator.clipboard.writeText(@js($savedUrl)); copied = true; setTimeout(() => copied = false, 1500)"
                        class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 font-medium hover:bg-gray-50">
                        <span x-show="!copied">{{ __('Copy') }}</span>
                        <span x-show="copied" x-cloak>✓ {{ __('Copied') }}</span>
                    </button>
                </div>

                <div class="flex flex-wrap gap-3 pt-1 text-sm">
                    <a href="{{ route('weddings.index') }}" wire:navigate
                        class="rounded-xl bg-amber-600 px-5 py-2.5 font-semibold text-white shadow-sm hover:bg-amber-700">
                        {{ __('All weddings') }}
                    </a>
                    <a href="{{ route('weddings.edit', $wedding) }}" wire:navigate class="{{ $back }}">
                        {{ __('Keep editing') }}
                    </a>
                    @if ($savedIsNew)
                        <a href="{{ route('weddings.create', ['template' => $template_key]) }}" wire:navigate
                            class="{{ $back }}">
                            {{ __('Create another') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @else
        {{-- ============ HEADER ============ --}}
        <header
            class="overflow-hidden rounded-3xl bg-gradient-to-br from-amber-500 via-amber-500 to-amber-600 p-5 text-white shadow-md sm:p-7">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <a href="{{ route('weddings.index') }}" wire:navigate
                        class="text-sm text-white/80 hover:text-white">
                        ← {{ __('Back to weddings') }}
                    </a>
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">
                        {{ $wedding ? __('Edit wedding') : __('New wedding') }}
                    </h1>

                    <p class="mt-1 truncate text-sm text-white/90">
                        @if ($groom_name !== '' || $bride_name !== '')
                            <span class="font-semibold">{{ $groom_name ?: '…' }}</span>
                            <span class="mx-1 opacity-80">&</span>
                            <span class="font-semibold">{{ $bride_name ?: '…' }}</span>
                        @else
                            {{ __('Pick a template, type the names, add photos. The link is made for you.') }}
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span
                        class="rounded-full px-3 py-1 text-xs font-semibold {{ $is_published ? 'bg-green-500 text-white' : 'bg-white/20 text-white' }}">
                        {{ $is_published ? __('Published') : __('Draft') }}
                    </span>

                    @if ($wedding)
                        <a href="{{ $wedding->url }}" target="_blank"
                            class="rounded-xl bg-white/95 px-3.5 py-1.5 text-sm font-medium text-amber-800 shadow-sm hover:bg-white">
                            {{ __('View live page') }} ↗
                        </a>
                    @endif
                </div>
            </div>
        </header>

        {{-- ============ STEPPER ============ --}}
        <nav class="grid grid-cols-3 gap-2 rounded-2xl bg-gray-100 p-1.5">
            @foreach ($tabs as $key => [$num, $name, $icon])
                <button type="button"
                    x-on:click="tab = '{{ $key }}'; window.scrollTo({ top: 0, behavior: 'smooth' })"
                    x-bind:class="tab === '{{ $key }}'
                        ?
                        'bg-white text-gray-900 shadow-sm ring-1 ring-gray-200' :
                        'text-gray-500 hover:bg-white/60 hover:text-gray-800'"
                    class="relative flex items-center justify-center gap-2 rounded-xl px-3 py-2.5 text-sm font-medium transition">
                    <span
                        x-bind:class="tab === '{{ $key }}' ? 'bg-amber-500 text-white' : 'bg-gray-300 text-white'"
                        class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[11px] font-semibold transition">{{ $num }}</span>
                    <span class="truncate">{{ $name }}</span>
                    @if ($key === 'basics' && $basicsError)
                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500"></span>
                    @endif
                </button>
            @endforeach
        </nav>

        {{-- novalidate: the server validates; native "required" on a hidden tab would block the submit silently --}}
        <form wire:submit="save" class="space-y-6" novalidate>

            {{-- ============ 1. BASICS ============ --}}
            <div x-show="tab === 'basics'" class="space-y-6">

                <section class="{{ $card }}">
                    <div>
                        <h2 class="{{ $h2 }}">{{ __('Custom name') }} <span class="text-red-500">*</span>
                        </h2>
                        <p class="{{ $hint }}">
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
                        <h2 class="{{ $h2 }}">{{ __('Template') }}</h2>
                        <p class="{{ $hint }}">
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
                                    class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white transition hover:border-amber-300 peer-checked:border-amber-500 peer-checked:ring-4 peer-checked:ring-amber-500/20">
                                    <div class="aspect-[4/3] bg-gradient-to-br from-amber-200 to-amber-500">
                                        @if ($t->preview_src)
                                            <img src="{{ $t->preview_src }}" alt=""
                                                class="h-full w-full object-cover" loading="lazy">
                                        @endif
                                    </div>
                                    <div class="p-3">
                                        <div class="flex items-center justify-between gap-2">
                                            <p class="truncate text-sm font-semibold">{{ $t->name }}</p>
                                            @if ($template_key === $t->key)
                                                <span
                                                    class="shrink-0 rounded-full bg-amber-500 px-2 py-0.5 text-[10px] font-semibold text-white">✓
                                                    {{ __('Selected') }}</span>
                                            @endif
                                        </div>
                                        @if ($t->description)
                                            <p class="mt-0.5 line-clamp-2 text-xs text-gray-500">{{ $t->description }}
                                            </p>
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
                    <h2 class="{{ $h2 }}">{{ __('The couple') }}</h2>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $lbl }}">{{ __('Groom name') }}</label>
                            <input type="text" wire:model.live.debounce.400ms="groom_name"
                                class="{{ $inputCls }}">
                            @error('groom_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="{{ $lbl }}">{{ __('Bride name') }}</label>
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
                        <h2 class="{{ $h2 }}">{{ __('Public link') }}</h2>
                        <p class="{{ $hint }}">
                            {{ __('Made from the groom\'s name (Khmer letters work). If it is taken, a number is added. After you save, the link stays the same even if you change the name.') }}
                        </p>
                    </div>

                    <div
                        class="flex items-center overflow-hidden rounded-xl border border-gray-300 bg-white shadow-sm focus-within:border-amber-500 focus-within:ring-4 focus-within:ring-amber-500/15">
                        <span
                            class="hidden bg-gray-50 px-3 py-2.5 text-sm text-gray-500 sm:block">{{ rtrim(url('/'), '/') }}/</span>
                        <input type="text" wire:model.blur="slug"
                            placeholder="{{ __('made-from-the-groom-name') }}"
                            class="min-w-0 flex-1 bg-transparent px-3.5 py-2.5 text-sm outline-none">
                    </div>
                    @error('slug')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if ($slug !== '')
                        <p class="break-all rounded-xl bg-amber-50 px-3.5 py-2.5 text-xs font-medium text-amber-800">
                            🔗 {{ rtrim(url('/'), '/') }}/{{ $slug }}
                        </p>
                    @endif
                </section>

                <div class="flex justify-end">
                    <button type="button"
                        x-on:click="tab = 'photos'; window.scrollTo({ top: 0, behavior: 'smooth' })"
                        class="{{ $next }}">
                        {{ __('Next: Photos') }} →
                    </button>
                </div>
            </div>

            {{-- ============ 2. PHOTOS & MUSIC ============ --}}
            <div x-show="tab === 'photos'" style="display:none" class="space-y-6">

                @forelse ($this->photoGroups as $group)
                    <section wire:key="group-{{ $group['key'] }}" class="{{ $card }}">
                        <div>
                            <h2 class="{{ $h2 }}">{{ $group['title'] }}</h2>

                            @if ($group['key'] === 'main')
                                <p class="{{ $hint }}">
                                    {{ __('Upload a photo, or paste a link. Large photos are resized automatically before they are sent.') }}
                                </p>
                            @elseif ($group['key'] === 'backgrounds')
                                <p class="{{ $hint }}">
                                    {{ __('Upload your own photo to replace the background of any section. "Shared" is used for every section without its own photo. Empty slots use the template\'s default.') }}
                                </p>
                            @elseif ($group['key'] === 'gallery')
                                <p class="{{ $hint }}">
                                    {{ __('Up to 12 photos. You can pick several files at once.') }}</p>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-4">
                            @foreach ($group['slots'] as $s)
                                @include('livewire.admin.wedding.partials.slot', ['s' => $s])
                            @endforeach
                        </div>
                    </section>
                @empty
                    <div
                        class="rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center text-sm text-gray-500">
                        {{ __('No photo slots yet. Choose a template on the Basics tab first.') }}
                    </div>
                @endforelse

                <p class="rounded-xl bg-gray-50 px-4 py-3 text-xs leading-relaxed text-gray-500">
                    {{ __('Files are uploaded straight away and linked to this wedding when you press Save. Images and music up to 20 MB.') }}
                    {{ __('New files are stored on: :disk.', ['disk' => $this->storageDisk]) }}
                    @if ($this->uploadLimit)
                        {{ __('Your server accepts files up to :size.', ['size' => number_format($this->uploadLimit / 1048576, 1) . ' MB']) }}
                    @endif
                </p>

                <div class="flex justify-between">
                    <button type="button"
                        x-on:click="tab = 'basics'; window.scrollTo({ top: 0, behavior: 'smooth' })"
                        class="{{ $back }}">
                        ← {{ __('Back') }}
                    </button>
                    <button type="button"
                        x-on:click="tab = 'details'; window.scrollTo({ top: 0, behavior: 'smooth' })"
                        class="{{ $next }}">
                        {{ __('Next: Details') }} →
                    </button>
                </div>
            </div>

            {{-- ============ 3. DETAILS ============ --}}
            <div x-show="tab === 'details'" style="display:none" class="space-y-6">
                <p class="text-xs text-gray-500">
                    {{ __('Date, schedule, texts, love story, dress code, gift. Fields save when you press Save.') }}
                </p>

                @php
                    $eventsKey = \App\Models\Invitation::listKey('events', $data);
                    $storyKey = \App\Models\Invitation::listKey('stories', $data);
                @endphp

                <section class="{{ $card }}">
                    <h2 class="{{ $h2 }}">{{ __('Parents') }}</h2>
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
                        <h2 class="{{ $h2 }}">{{ __('Date') }}</h2>
                        <p class="{{ $hint }}">
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
                    <h2 class="{{ $h2 }}">{{ __('Schedule') }}</h2>

                    @foreach ($data[$eventsKey] ?? [] as $i => $ev)
                        <div wire:key="ev-{{ $i }}"
                            class="space-y-3 rounded-xl border border-gray-200 bg-gray-50/70 p-3.5">
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
                    <h2 class="{{ $h2 }}">{{ __('Words') }}</h2>
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
                        <h2 class="{{ $h2 }}">{{ __('Love story') }}</h2>
                        <p class="{{ $hint }}">{{ __('Optional. Remove all items to hide this section.') }}
                        </p>
                    </div>

                    @foreach ($data[$storyKey] ?? [] as $i => $st)
                        <div wire:key="st-{{ $i }}"
                            class="space-y-3 rounded-xl border border-gray-200 bg-gray-50/70 p-3.5">
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
                        <h2 class="{{ $h2 }}">{{ __('Dress code') }}</h2>
                        <p class="{{ $hint }}">
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
                        <h2 class="{{ $h2 }}">{{ __('Wedding gift') }}</h2>
                        <p class="{{ $hint }}">
                            {{ __('Optional. Leave the account number empty to hide this section. The QR image is on the Photos tab.') }}
                        </p>
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
                        <h2 class="{{ $h2 }}">{{ __('Other') }}</h2>
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
                        class="{{ $back }}">
                        ← {{ __('Back') }}
                    </button>
                </div>
            </div>

            {{-- Premium Glass Bottom Action Bar --}}
            <div class="fixed inset-x-0 bottom-0 z-40 transition-[left] duration-300 ease-out lg:left-20"
                :class="collapsed ? 'lg:left-20' : 'lg:left-72'">

                <div class="mx-auto w-full max-w-7xl px-3 pb-4 sm:px-6 lg:px-8">

                    <div
                        class="relative overflow-hidden rounded-2xl border border-white/70
                    bg-white/65 shadow-[0_8px_40px_rgba(15,23,42,0.10)]
                    backdrop-blur-2xl backdrop-saturate-150
                    ring-1 ring-gray-900/[0.04]">

                        {{-- Subtle glass highlight --}}
                        <div
                            class="pointer-events-none absolute inset-x-0 top-0 h-px
                        bg-gradient-to-r from-transparent via-white to-transparent">
                        </div>

                        <div
                            class="relative flex flex-col gap-4 px-4 py-4
                        sm:flex-row sm:items-center sm:justify-between
                        sm:px-6">

                            {{-- Publication Status --}}
                            <label class="group flex cursor-pointer items-center gap-3">

                                <span class="relative inline-flex shrink-0 items-center">
                                    <input type="checkbox" wire:model.live="is_published" class="peer sr-only">

                                    <span
                                        class="relative h-7 w-12 rounded-full
                                     bg-slate-300/80 shadow-inner
                                     transition-all duration-300
                                     after:absolute after:left-1 after:top-1
                                     after:h-5 after:w-5 after:rounded-full
                                     after:bg-white after:shadow-md
                                     after:transition-all after:duration-300
                                     after:content-['']
                                     peer-checked:bg-emerald-500
                                     peer-checked:after:translate-x-5
                                     peer-focus-visible:ring-4
                                     peer-focus-visible:ring-emerald-500/20">
                                    </span>
                                </span>

                                <span class="flex flex-col gap-0.5">
                                    <span class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                                        {{ $is_published ? __('Published') : __('Draft') }}

                                        <span
                                            class="h-1.5 w-1.5 rounded-full
                                {{ $is_published ? 'bg-emerald-500' : 'bg-amber-500' }}">
                                        </span>
                                    </span>

                                    <span class="text-xs text-slate-500">
                                        {{ $is_published ? __('Anyone with the link can open it') : __('Only you can preview it') }}
                                    </span>
                                </span>

                            </label>

                            {{-- Action Buttons --}}
                            <div class="flex items-center justify-end gap-2 sm:gap-3">

                                <a href="{{ route('weddings.index') }}" wire:navigate
                                    class="rounded-xl px-4 py-2.5 text-sm font-medium
                               text-slate-500 transition-all duration-200
                               hover:bg-white/70 hover:text-slate-800">
                                    {{ __('Cancel') }}
                                </a>

                                <button type="submit" wire:loading.attr="disabled" wire:target="save"
                                    class="group relative inline-flex items-center justify-center
                               gap-2 overflow-hidden rounded-xl
                               border border-amber-500/20
                               bg-gradient-to-r from-amber-500 to-amber-600
                               px-6 py-2.5 text-sm font-semibold text-white
                               shadow-[0_4px_14px_rgba(217,119,6,0.25)]
                               transition-all duration-300
                               hover:-translate-y-0.5 hover:from-amber-600
                               hover:to-amber-700
                               hover:shadow-[0_7px_20px_rgba(217,119,6,0.30)]
                               active:translate-y-0
                               disabled:cursor-not-allowed disabled:opacity-60">

                                    <span wire:loading.remove wire:target="save"
                                        class="inline-flex items-center gap-2">
                                        <i class="bi bi-check2-circle text-base"></i>
                                        {{ $wedding ? __('Save changes') : __('Create wedding') }}
                                    </span>

                                    <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                                        <i class="bi bi-arrow-repeat animate-spin"></i>
                                        {{ __('Saving…') }}
                                    </span>

                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </form>

    @endif
</div>
