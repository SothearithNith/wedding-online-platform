@php
    $basicsError = $errors->hasAny(['template_key', 'groom_name', 'bride_name', 'slug', 'label']);

    $tabs = [
        'basics' => [trans_json('Basics'), 'heroicon-o-sparkles'],
        'photos' => [trans_json('Photos & music'), 'heroicon-o-photo'],
        'details' => [trans_json('Details'), 'heroicon-o-pencil-square'],
    ];

    $inputCls =
        'w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm placeholder:text-gray-400 transition focus:border-amber-500 focus:outline-none focus:ring-4 focus:ring-amber-500/15';
    $card = 'space-y-4 rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm sm:p-6';
    $ico =
        'flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 ring-1 ring-inset ring-amber-100';
    $h2 = 'text-base font-semibold text-gray-900';
    $hint = 'mt-0.5 text-xs leading-relaxed text-gray-500';
    $lbl = 'mb-1 block text-xs font-medium text-gray-600';
    $rm =
        'inline-flex items-center gap-1 rounded-lg px-2 py-1 text-xs font-medium text-red-600 transition hover:bg-red-50';
    $add =
        'inline-flex items-center justify-center gap-1.5 rounded-xl border-2 border-dashed border-gray-300 px-3 py-2.5 text-sm font-medium text-gray-500 transition hover:border-amber-400 hover:bg-amber-50 hover:text-amber-700';
    $next =
        'inline-flex items-center gap-2 rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-gray-700';
    $back =
        'inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50';
    $err = 'mt-1 flex items-center gap-1 text-sm text-red-600';
@endphp

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

    @if ($savedUrl)
        <div class="space-y-6" x-data="{ copied: false }">
            <div role="status"
                class="flex items-start gap-3 rounded-2xl border border-green-500/30 bg-gradient-to-br from-green-50 to-emerald-50 p-5 text-green-800 shadow-sm">
                <span
                    class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-green-500 text-white shadow-md shadow-green-500/30">
                    <x-heroicon-s-check class="h-5 w-5" />
                </span>
                <div>
                    <p class="font-semibold">
                        {{ $savedIsNew ? trans_json('Invitation created successfully') : trans_json('Changes saved successfully') }}
                    </p>
                    <p class="text-sm opacity-80">
                        {{ $is_published
                            ? trans_json('Your invitation is live. Copy the link below and send it to your guests.')
                            : trans_json('Saved as a draft. Only you can preview it until you publish.') }}
                    </p>
                </div>
            </div>

            <div class="{{ $card }}">
                <p class="flex items-center gap-2 text-sm font-medium text-gray-600">
                    <x-heroicon-o-link class="h-4 w-4 text-gray-400" />
                    {{ trans_json('Invitation link:') }}
                </p>
                <div class="flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 p-2 pl-3.5 text-sm">
                    <a href="{{ $savedUrl }}" target="_blank"
                        class="min-w-0 flex-1 truncate font-medium text-amber-700 hover:underline">
                        {{ urldecode($savedUrl) }}
                    </a>
                    <button type="button"
                        x-on:click="navigator.clipboard.writeText(@js($savedUrl)); copied = true; setTimeout(() => copied = false, 1500)"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-lg border px-3 py-1.5 font-medium transition"
                        x-bind:class="copied ? 'border-green-300 bg-green-100 text-green-700' :
                            'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'">
                        <span x-show="!copied" class="inline-flex items-center gap-1.5">
                            <x-heroicon-o-clipboard-document class="h-4 w-4" />
                            {{ trans_json('Copy') }}
                        </span>
                        <span x-show="copied" x-cloak class="inline-flex items-center gap-1.5">
                            <x-heroicon-o-check class="h-4 w-4" />
                            {{ trans_json('Copied') }}
                        </span>
                    </button>
                </div>

                <div class="flex flex-wrap gap-3 pt-1 text-sm">
                    <a href="{{ route('weddings.index') }}" wire:navigate
                        class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-5 py-2.5 font-semibold text-white shadow-sm transition hover:bg-amber-700">
                        <x-heroicon-o-squares-2x2 class="h-4 w-4" />
                        {{ trans_json('All weddings') }}
                    </a>
                    <a href="{{ route('weddings.edit', $wedding) }}" wire:navigate class="{{ $back }}">
                        <x-heroicon-o-pencil-square class="h-4 w-4" />
                        {{ trans_json('Keep editing') }}
                    </a>
                    @if ($savedIsNew)
                        <a href="{{ route('weddings.create', ['template' => $template_key]) }}" wire:navigate
                            class="{{ $back }}">
                            <x-heroicon-o-plus class="h-4 w-4" />
                            {{ trans_json('Create another') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @else
        <header
            class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-amber-500 via-amber-500 to-orange-600 p-5 text-white shadow-lg shadow-amber-500/20 sm:p-7">
            <div class="pointer-events-none absolute -right-10 -top-12 h-44 w-44 rounded-full bg-white/15 blur-2xl">
            </div>
            <div
                class="pointer-events-none absolute -bottom-16 left-1/3 h-40 w-40 rounded-full bg-rose-300/25 blur-3xl">
            </div>

            <div class="relative flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <a href="{{ route('weddings.index') }}" wire:navigate
                        class="inline-flex items-center gap-1.5 text-sm text-white/80 transition hover:text-white">
                        <x-heroicon-o-arrow-left class="h-4 w-4" />
                        {{ trans_json('Back to weddings') }}
                    </a>

                    <h1 class="mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">
                        {{ $wedding ? trans_json('Edit wedding') : trans_json('New wedding') }}
                    </h1>

                    <p class="mt-1 flex items-center gap-2 truncate text-sm text-white/90">
                        @if ($groom_name !== '' || $bride_name !== '')
                            <span class="font-semibold">{{ $groom_name ?: '…' }}</span>
                            <x-heroicon-s-heart class="h-4 w-4 shrink-0 text-rose-200" />
                            <span class="font-semibold">{{ $bride_name ?: '…' }}</span>
                        @else
                            {{ trans_json('Pick a template, type the names, add photos. The link is made for you.') }}
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-white/30 px-3 py-1 text-xs font-semibold backdrop-blur-md {{ $is_published ? 'bg-green-500/90 text-white' : 'bg-white/20 text-white' }}">
                        <span
                            class="h-1.5 w-1.5 rounded-full bg-white {{ $is_published ? 'animate-pulse' : 'opacity-70' }}"></span>
                        {{ $is_published ? trans_json('Published') : trans_json('Draft') }}
                    </span>

                    @if ($wedding)
                        <a href="{{ $wedding->url }}" target="_blank"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-white/95 px-3.5 py-1.5 text-sm font-medium text-amber-800 shadow-sm transition hover:bg-white">
                            {{ trans_json('View live page') }}
                            <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" />
                        </a>
                    @endif
                </div>
            </div>
        </header>

        <nav class="grid grid-cols-3 gap-2 rounded-2xl bg-gray-100 p-1.5">
            @foreach ($tabs as $key => [$name, $icon])
                <button type="button"
                    x-on:click="tab = '{{ $key }}'; window.scrollTo({ top: 0, behavior: 'smooth' })"
                    x-bind:class="tab === '{{ $key }}'
                        ?
                        'bg-white text-gray-900 shadow-sm ring-1 ring-gray-200' :
                        'text-gray-500 hover:bg-white/60 hover:text-gray-800'"
                    class="relative flex items-center justify-center gap-2 rounded-xl px-3 py-2.5 text-sm font-medium transition">
                    <span
                        x-bind:class="tab === '{{ $key }}' ?
                            'bg-gradient-to-br from-amber-400 to-amber-600 text-white shadow shadow-amber-500/30' :
                            'bg-gray-300 text-white'"
                        class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full transition">
                        <x-dynamic-component :component="$icon" class="h-3.5 w-3.5" />
                    </span>
                    <span class="truncate">{{ $name }}</span>
                    @if ($key === 'basics' && $basicsError)
                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500"></span>
                    @endif
                </button>
            @endforeach
        </nav>

        <form wire:submit="save" class="space-y-6" novalidate>
            <div x-show="tab === 'basics'" class="space-y-6">
                <section class="{{ $card }}">
                    <div class="flex items-start gap-3">
                        <span class="{{ $ico }}"><x-heroicon-o-tag class="h-5 w-5" /></span>
                        <div>
                            <h2 class="{{ $h2 }}">{{ trans_json('Custom name') }} <span
                                    class="text-red-500">*</span></h2>
                            <p class="{{ $hint }}">
                                {{ trans_json('The custom name is only for you. Guests never see it.') }}</p>
                        </div>
                    </div>

                    <div>
                        <input type="text" wire:model="label" maxlength="80" required
                            placeholder="{{ trans_json('e.g. សុខា & ស្រីនាង – ភ្នំពេញ') }}"
                            class="{{ $inputCls }}">
                        @error('label')
                            <p class="{{ $err }}">
                                <x-heroicon-s-exclamation-circle class="h-4 w-4 shrink-0" />{{ $message }}
                            </p>
                        @enderror
                    </div>
                </section>

                <section class="{{ $card }}">
                    <div class="flex items-start gap-3">
                        <span class="{{ $ico }}"><x-heroicon-o-swatch class="h-5 w-5" /></span>
                        <div>
                            <h2 class="{{ $h2 }}">{{ trans_json('Template') }}</h2>
                            <p class="{{ $hint }}">
                                {{ $wedding
                                    ? trans_json('You can switch template. Your content stays; only the design changes.')
                                    : trans_json('Choosing a template fills the form with its sample content. Switching later resets what you typed.') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($this->templates as $t)
                            @php
                                $selected = $template_key === $t->key;
                                $src = $t->preview_src ? asset('storage/templates/' . basename($t->preview_src)) : null;
                            @endphp

                            <label wire:key="tpl-{{ $t->key }}" class="group block cursor-pointer">
                                <input type="radio" class="peer sr-only" wire:model.live="template_key"
                                    value="{{ $t->key }}">
                                <div
                                    class="relative aspect-[3/5] overflow-hidden rounded-3xl bg-gradient-to-b from-amber-200 via-amber-300 to-amber-500 shadow-md ring-2 ring-transparent transition duration-300 hover:-translate-y-1 hover:shadow-xl peer-checked:ring-4 peer-checked:ring-amber-500 peer-focus-visible:ring-amber-400">
                                    @if ($src)
                                        <img src="{{ $src }}" alt="{{ $t->name }}" loading="lazy"
                                            class="absolute inset-0 h-full w-full object-cover object-top transition duration-700 group-hover:scale-105"
                                            onerror="this.style.display='none'">
                                    @else
                                        <div
                                            class="absolute inset-0 flex items-center justify-center pb-24 text-white/70">
                                            <x-heroicon-o-photo class="h-12 w-12" />
                                        </div>
                                    @endif
                                    <div
                                        class="pointer-events-none absolute inset-x-0 bottom-0 h-2/5 bg-gradient-to-t from-black/45 to-transparent">
                                    </div>
                                    @if ($selected)
                                        <span
                                            class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-amber-500 text-white shadow-lg ring-2 ring-white">
                                            <x-heroicon-s-check class="h-5 w-5" />
                                        </span>
                                    @endif 
                                    <div
                                        class="absolute inset-x-3 bottom-3 rounded-2xl border border-white/30 bg-white/10 p-3.5 text-white shadow-xl shadow-black/20 backdrop-blur-lg">
                                        <div class="flex items-center justify-between gap-2">
                                            <p class="truncate text-base font-semibold drop-shadow-sm">
                                                {{ $t->name }}</p>

                                            @if ($selected)
                                                <span
                                                    class="inline-flex shrink-0 items-center gap-1 rounded-full bg-amber-500 px-2.5 py-0.5 text-[10px] font-semibold text-white shadow">
                                                    <x-heroicon-s-check class="h-3 w-3" />
                                                    {{ trans_json('Selected') }}
                                                </span>
                                            @endif
                                        </div>

                                        @if ($t->description)
                                            <p class="mt-1 line-clamp-2 text-xs leading-relaxed text-white/85">
                                                {{ $t->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error('template_key')
                        <p class="{{ $err }}">
                            <x-heroicon-s-exclamation-circle class="h-4 w-4 shrink-0" />{{ $message }}
                        </p>
                    @enderror
                </section>

                <section class="{{ $card }}">
                    <div class="flex items-center gap-3">
                        <span class="{{ $ico }}"><x-heroicon-o-heart class="h-5 w-5" /></span>
                        <h2 class="{{ $h2 }}">{{ trans_json('The couple') }}</h2>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $lbl }}">{{ trans_json('Groom name') }}</label>
                            <input type="text" wire:model.live.debounce.400ms="groom_name"
                                class="{{ $inputCls }}">
                            @error('groom_name')
                                <p class="{{ $err }}">
                                    <x-heroicon-s-exclamation-circle class="h-4 w-4 shrink-0" />{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="{{ $lbl }}">{{ trans_json('Bride name') }}</label>
                            <input type="text" wire:model.live.debounce.400ms="bride_name"
                                class="{{ $inputCls }}">
                            @error('bride_name')
                                <p class="{{ $err }}">
                                    <x-heroicon-s-exclamation-circle class="h-4 w-4 shrink-0" />{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </section>

                <section class="{{ $card }}">
                    <div class="flex items-start gap-3">
                        <span class="{{ $ico }}"><x-heroicon-o-link class="h-5 w-5" /></span>
                        <div>
                            <h2 class="{{ $h2 }}">{{ trans_json('Public link') }}</h2>
                            <p class="{{ $hint }}">
                                {{ trans_json('Made from the groom\'s name (Khmer letters work). If it is taken, a number is added. After you save, the link stays the same even if you change the name.') }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="flex items-center overflow-hidden rounded-xl border border-gray-300 bg-white shadow-sm transition focus-within:border-amber-500 focus-within:ring-4 focus-within:ring-amber-500/15">
                        <span
                            class="hidden bg-gray-50 px-3 py-2.5 text-sm text-gray-500 sm:block">{{ rtrim(url('/'), '/') }}/</span>
                        <input type="text" wire:model.blur="slug"
                            placeholder="{{ trans_json('made-from-the-groom-name') }}"
                            class="min-w-0 flex-1 bg-transparent px-3.5 py-2.5 text-sm outline-none">
                    </div>
                    @error('slug')
                        <p class="{{ $err }}">
                            <x-heroicon-s-exclamation-circle class="h-4 w-4 shrink-0" />{{ $message }}
                        </p>
                    @enderror

                    @if ($slug !== '')
                        <p
                            class="flex items-center gap-2 break-all rounded-xl bg-amber-50 px-3.5 py-2.5 text-xs font-medium text-amber-800">
                            <x-heroicon-o-link class="h-4 w-4 shrink-0" />
                            {{ rtrim(url('/'), '/') }}/{{ $slug }}
                        </p>
                    @endif
                </section>

                <div class="flex justify-end">
                    <button type="button"
                        x-on:click="tab = 'photos'; window.scrollTo({ top: 0, behavior: 'smooth' })"
                        class="{{ $next }}">
                        {{ trans_json('Next: Photos') }}
                        <x-heroicon-o-arrow-right class="h-4 w-4" />
                    </button>
                </div>
            </div>

            {{-- ================= TAB 2: PHOTOS ================= --}}
            <div x-show="tab === 'photos'" style="display:none" class="space-y-6">
                @forelse ($this->photoGroups as $group)
                    <section wire:key="group-{{ $group['key'] }}" class="{{ $card }}">
                        <div class="flex items-start gap-3">
                            <span class="{{ $ico }}">
                                @if ($group['key'] === 'gallery')
                                    <x-heroicon-o-rectangle-stack class="h-5 w-5" />
                                @elseif ($group['key'] === 'backgrounds')
                                    <x-heroicon-o-square-3-stack-3d class="h-5 w-5" />
                                @else
                                    <x-heroicon-o-photo class="h-5 w-5" />
                                @endif
                            </span>
                            <div>
                                <h2 class="{{ $h2 }}">{{ $group['title'] }}</h2>

                                @if ($group['key'] === 'main')
                                    <p class="{{ $hint }}">
                                        {{ trans_json('Upload a photo, or paste a link. Large photos are resized automatically before they are sent.') }}
                                    </p>
                                @elseif ($group['key'] === 'backgrounds')
                                    <p class="{{ $hint }}">
                                        {{ trans_json('Upload your own photo to replace the background of any section. "Shared" is used for every section without its own photo. Empty slots use the template\'s default.') }}
                                    </p>
                                @elseif ($group['key'] === 'gallery')
                                    <p class="{{ $hint }}">
                                        {{ trans_json('Up to 12 photos. You can pick several files at once.') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-4">
                            @foreach ($group['slots'] as $s)
                                @include('livewire.admin.wedding.partials.slot', ['s' => $s])
                            @endforeach
                        </div>
                    </section>
                @empty
                    <div
                        class="flex flex-col items-center gap-2 rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center text-sm text-gray-500">
                        <span class="{{ $ico }}"><x-heroicon-o-photo class="h-5 w-5" /></span>
                        {{ trans_json('No photo slots yet. Choose a template on the Basics tab first.') }}
                    </div>
                @endforelse

                <p
                    class="flex items-start gap-2 rounded-xl bg-gray-50 px-4 py-3 text-xs leading-relaxed text-gray-500">
                    <x-heroicon-o-information-circle class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" />
                    <span>
                        {{ trans_json('Files are uploaded straight away and linked to this wedding when you press Save. Images and music up to 20 MB.') }}
                        {{ trans_json('New files are stored on: :disk.', ['disk' => $this->storageDisk]) }}
                        @if ($this->uploadLimit)
                            {{ trans_json('Your server accepts files up to :size.', ['size' => number_format($this->uploadLimit / 1048576, 1) . ' MB']) }}
                        @endif
                    </span>
                </p>

                <div class="flex justify-between">
                    <button type="button"
                        x-on:click="tab = 'basics'; window.scrollTo({ top: 0, behavior: 'smooth' })"
                        class="{{ $back }}">
                        <x-heroicon-o-arrow-left class="h-4 w-4" />
                        {{ trans_json('Back') }}
                    </button>
                    <button type="button"
                        x-on:click="tab = 'details'; window.scrollTo({ top: 0, behavior: 'smooth' })"
                        class="{{ $next }}">
                        {{ trans_json('Next: Details') }}
                        <x-heroicon-o-arrow-right class="h-4 w-4" />
                    </button>
                </div>
            </div>

            {{-- ================= TAB 3: DETAILS ================= --}}
            <div x-show="tab === 'details'" style="display:none" class="space-y-6">
                <p class="flex items-center gap-2 text-xs text-gray-500">
                    <x-heroicon-o-information-circle class="h-4 w-4 shrink-0 text-gray-400" />
                    {{ trans_json('Date, schedule, texts, love story, dress code, gift. Fields save when you press Save.') }}
                </p>

                @php
                    $eventsKey = \App\Models\Invitation::listKey('events', $data);
                    $storyKey = \App\Models\Invitation::listKey('stories', $data);
                @endphp

                <section class="{{ $card }}">
                    <div class="flex items-center gap-3">
                        <span class="{{ $ico }}"><x-heroicon-o-users class="h-5 w-5" /></span>
                        <h2 class="{{ $h2 }}">{{ trans_json('Parents') }}</h2>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $lbl }}">{{ trans_json("Groom's Father") }}</label>
                            <input type="text" wire:model.blur="data.groom.dad" class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label class="{{ $lbl }}">{{ trans_json("Groom's Mother") }}</label>
                            <input type="text" wire:model.blur="data.groom.mom" class="{{ $inputCls }}">
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $lbl }}">{{ trans_json("Bride's Father") }}</label>
                            <input type="text" wire:model.blur="data.bride.dad" class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label class="{{ $lbl }}">{{ trans_json("Bride's Mother") }}</label>
                            <input type="text" wire:model.blur="data.bride.mom" class="{{ $inputCls }}">
                        </div>
                    </div>
                </section>

                <section class="{{ $card }}">
                    <div class="flex items-start gap-3">
                        <span class="{{ $ico }}"><x-heroicon-o-calendar-days class="h-5 w-5" /></span>
                        <div>
                            <h2 class="{{ $h2 }}">{{ trans_json('Date') }}</h2>
                            <p class="{{ $hint }}">
                                {{ trans_json('Cambodia time (UTC+7). Leave the date text empty to generate it in Khmer.') }}
                            </p>
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $lbl }}">{{ trans_json('Wedding date and time') }}</label>
                            <input type="datetime-local" wire:model.blur="dateLocal" class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label class="{{ $lbl }}">{{ trans_json('Date text (optional)') }}</label>
                            <input type="text" wire:model.blur="data.dateText" class="{{ $inputCls }}">
                        </div>
                    </div>
                </section>

                <section class="{{ $card }}">
                    <div class="flex items-center gap-3">
                        <span class="{{ $ico }}"><x-heroicon-o-clock class="h-5 w-5" /></span>
                        <h2 class="{{ $h2 }}">{{ trans_json('Schedule') }}</h2>
                    </div>

                    @foreach ($data[$eventsKey] ?? [] as $i => $ev)
                        <div wire:key="ev-{{ $i }}"
                            class="space-y-3 rounded-xl border border-gray-200 bg-gray-50/70 p-3.5">
                            <div class="flex items-center justify-between">
                                <p class="flex items-center gap-2 text-sm font-semibold">
                                    <span
                                        class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-500 text-[11px] font-bold text-white">{{ $i + 1 }}</span>
                                    {{ trans_json('Event') }}
                                </p>
                                <button type="button"
                                    wire:click="removeItem('{{ $eventsKey }}', {{ $i }})"
                                    class="{{ $rm }}">
                                    <x-heroicon-o-trash class="h-3.5 w-3.5" />
                                    {{ trans_json('Remove') }}
                                </button>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="{{ $lbl }}">{{ trans_json('Time') }}</label>
                                    <input type="time"
                                        wire:model.blur="data.{{ $eventsKey }}.{{ $i }}.time"
                                        class="{{ $inputCls }}">
                                </div>
                                <div>
                                    <label class="{{ $lbl }}">{{ trans_json('Title') }}</label>
                                    <input type="text"
                                        wire:model.blur="data.{{ $eventsKey }}.{{ $i }}.title"
                                        class="{{ $inputCls }}">
                                </div>
                                <div>
                                    <label class="{{ $lbl }}">{{ trans_json('Place') }}</label>
                                    <input type="text"
                                        wire:model.blur="data.{{ $eventsKey }}.{{ $i }}.place"
                                        class="{{ $inputCls }}">
                                </div>
                                <div>
                                    <label
                                        class="{{ $lbl }}">{{ trans_json('Google Maps link (optional)') }}</label>
                                    <input type="text"
                                        wire:model.blur="data.{{ $eventsKey }}.{{ $i }}.mapUrl"
                                        placeholder="https://maps.google.com/..." class="{{ $inputCls }}">
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if (count($data[$eventsKey] ?? []) < 12)
                        <button type="button" wire:click="addEvent" class="{{ $add }} w-full">
                            <x-heroicon-o-plus class="h-4 w-4" />
                            {{ trans_json('Add event') }}
                        </button>
                    @endif
                </section>

                <section class="{{ $card }}">
                    <div class="flex items-center gap-3">
                        <span class="{{ $ico }}"><x-heroicon-o-chat-bubble-bottom-center-text
                                class="h-5 w-5" /></span>
                        <h2 class="{{ $h2 }}">{{ trans_json('Words') }}</h2>
                    </div>
                    <div>
                        <label class="{{ $lbl }}">{{ trans_json('Invitation message') }}</label>
                        <textarea rows="3" wire:model.blur="data.intro" class="{{ $inputCls }}"></textarea>
                    </div>
                    <div>
                        <label class="{{ $lbl }}">{{ trans_json('Quote') }}</label>
                        <input type="text" wire:model.blur="data.quote" class="{{ $inputCls }}">
                    </div>
                    <div>
                        <label class="{{ $lbl }}">{{ trans_json('Apology note') }}</label>
                        <textarea rows="4" wire:model.blur="data.apology" class="{{ $inputCls }}"></textarea>
                    </div>
                    <div>
                        <label class="{{ $lbl }}">{{ trans_json('Thank-you note') }}</label>
                        <textarea rows="4" wire:model.blur="data.thanks" class="{{ $inputCls }}"></textarea>
                    </div>
                </section>

                <section class="{{ $card }}">
                    <div class="flex items-start gap-3">
                        <span class="{{ $ico }}"><x-heroicon-o-book-open class="h-5 w-5" /></span>
                        <div>
                            <h2 class="{{ $h2 }}">{{ trans_json('Love story') }}</h2>
                            <p class="{{ $hint }}">
                                {{ trans_json('Optional. Remove all items to hide this section.') }}</p>
                        </div>
                    </div>

                    @foreach ($data[$storyKey] ?? [] as $i => $st)
                        <div wire:key="st-{{ $i }}"
                            class="space-y-3 rounded-xl border border-gray-200 bg-gray-50/70 p-3.5">
                            <div class="flex items-center justify-between">
                                <p class="flex items-center gap-2 text-sm font-semibold">
                                    <span
                                        class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-500 text-[11px] font-bold text-white">{{ $i + 1 }}</span>
                                    {{ trans_json('Moment') }}
                                </p>
                                <button type="button"
                                    wire:click="removeItem('{{ $storyKey }}', {{ $i }})"
                                    class="{{ $rm }}">
                                    <x-heroicon-o-trash class="h-3.5 w-3.5" />
                                    {{ trans_json('Remove') }}
                                </button>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="{{ $lbl }}">{{ trans_json('Year') }}</label>
                                    <input type="text"
                                        wire:model.blur="data.{{ $storyKey }}.{{ $i }}.year"
                                        class="{{ $inputCls }}">
                                </div>
                                <div>
                                    <label class="{{ $lbl }}">{{ trans_json('Title') }}</label>
                                    <input type="text"
                                        wire:model.blur="data.{{ $storyKey }}.{{ $i }}.title"
                                        class="{{ $inputCls }}">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="{{ $lbl }}">{{ trans_json('Text') }}</label>
                                    <textarea rows="2" wire:model.blur="data.{{ $storyKey }}.{{ $i }}.text"
                                        class="{{ $inputCls }}"></textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if (count($data[$storyKey] ?? []) < 10)
                        <button type="button" wire:click="addStory" class="{{ $add }} w-full">
                            <x-heroicon-o-plus class="h-4 w-4" />
                            {{ trans_json('Add moment') }}
                        </button>
                    @endif
                </section>

                <section class="{{ $card }}">
                    <div class="flex items-start gap-3">
                        <span class="{{ $ico }}"><x-heroicon-o-swatch class="h-5 w-5" /></span>
                        <div>
                            <h2 class="{{ $h2 }}">{{ trans_json('Dress code') }}</h2>
                            <p class="{{ $hint }}">
                                {{ trans_json('Optional. Remove all colors to hide this section.') }}</p>
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $lbl }}">{{ trans_json('Title') }}</label>
                            <input type="text" wire:model.blur="data.dressCode.title"
                                class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label class="{{ $lbl }}">{{ trans_json('Note') }}</label>
                            <input type="text" wire:model.blur="data.dressCode.note"
                                class="{{ $inputCls }}">
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        @foreach ($data['dressCode']['colors'] ?? [] as $i => $c)
                            <div wire:key="color-{{ $i }}"
                                class="flex items-center gap-1 rounded-xl border border-gray-200 bg-gray-50 p-1 pr-1.5">
                                <input type="color" wire:model.blur="data.dressCode.colors.{{ $i }}"
                                    class="h-9 w-9 cursor-pointer rounded-lg border border-gray-300 bg-white p-1"
                                    aria-label="{{ trans_json('Color') }} {{ $i + 1 }}">
                                <button type="button"
                                    wire:click="removeItem('dressCode.colors', {{ $i }})"
                                    aria-label="{{ trans_json('Remove') }}"
                                    class="flex h-6 w-6 items-center justify-center rounded-full text-gray-400 transition hover:bg-red-50 hover:text-red-600">
                                    <x-heroicon-o-x-mark class="h-4 w-4" />
                                </button>
                            </div>
                        @endforeach
                        @if (count($data['dressCode']['colors'] ?? []) < 8)
                            <button type="button" wire:click="addColor" class="{{ $add }}">
                                <x-heroicon-o-plus class="h-4 w-4" />
                                {{ trans_json('Color') }}
                            </button>
                        @endif
                    </div>
                </section>

                <section class="{{ $card }}">
                    <div class="flex items-start gap-3">
                        <span class="{{ $ico }}"><x-heroicon-o-gift class="h-5 w-5" /></span>
                        <div>
                            <h2 class="{{ $h2 }}">{{ trans_json('Wedding gift') }}</h2>
                            <p class="{{ $hint }}">
                                {{ trans_json('Optional. Leave the account number empty to hide this section. The QR image is on the Photos tab.') }}
                            </p>
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="{{ $lbl }}">{{ trans_json('Bank') }}</label>
                            <input type="text" wire:model.blur="data.gift.bank" class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label class="{{ $lbl }}">{{ trans_json('Account name') }}</label>
                            <input type="text" wire:model.blur="data.gift.accountName"
                                class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label class="{{ $lbl }}">{{ trans_json('Account number') }}</label>
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
                        <div class="flex items-center gap-3">
                            <span class="{{ $ico }}"><x-heroicon-o-adjustments-horizontal
                                    class="h-5 w-5" /></span>
                            <h2 class="{{ $h2 }}">{{ trans_json('Other') }}</h2>
                        </div>
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
                        <x-heroicon-o-arrow-left class="h-4 w-4" />
                        {{ trans_json('Back') }}
                    </button>
                </div>
            </div>

            {{-- ================= FLOATING GLASS SAVE BAR ================= --}}
            <div class="fixed inset-x-0 bottom-0 z-40 transition-[left] duration-300 ease-out lg:left-20"
                :class="collapsed ? 'lg:left-20' : 'lg:left-72'">

                <div class="mx-auto w-full max-w-7xl px-3 pb-4 sm:px-6 lg:px-8">

                    <div
                        class="relative overflow-hidden rounded-2xl border border-white/70 bg-white/65 shadow-[0_8px_40px_rgba(15,23,42,0.10)] ring-1 ring-gray-900/[0.04] backdrop-blur-2xl backdrop-saturate-150">

                        <div
                            class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white to-transparent">
                        </div>

                        <div
                            class="relative flex flex-col gap-4 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                            {{-- Publication status --}}
                            <label class="group flex cursor-pointer items-center gap-3">
                                <span class="relative inline-flex shrink-0 items-center">
                                    <input type="checkbox" wire:model.live="is_published" class="peer sr-only">
                                    <span
                                        class="relative h-7 w-12 rounded-full bg-slate-300/80 shadow-inner transition-all duration-300 after:absolute after:left-1 after:top-1 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow-md after:transition-all after:duration-300 after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-5 peer-focus-visible:ring-4 peer-focus-visible:ring-emerald-500/20"></span>
                                </span>

                                <span class="flex flex-col gap-0.5">
                                    <span class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                                        {{ $is_published ? trans_json('Published') : trans_json('Draft') }}
                                        <span
                                            class="h-1.5 w-1.5 rounded-full {{ $is_published ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                    </span>
                                    <span class="text-xs text-slate-500">
                                        {{ $is_published ? trans_json('Anyone with the link can open it') : trans_json('Only you can preview it') }}
                                    </span>
                                </span>
                            </label>

                            {{-- Actions --}}
                            <div class="flex items-center justify-end gap-2 sm:gap-3">
                                <a href="{{ route('weddings.index') }}" wire:navigate
                                    class="rounded-xl px-4 py-2.5 text-sm font-medium text-slate-500 transition-all duration-200 hover:bg-white/70 hover:text-slate-800">
                                    {{ trans_json('Cancel') }}
                                </a>

                                <button type="submit" wire:loading.attr="disabled" wire:target="save"
                                    class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-xl border border-amber-500/20 bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-2.5 text-sm font-semibold text-white shadow-[0_4px_14px_rgba(217,119,6,0.25)] transition-all duration-300 hover:-translate-y-0.5 hover:from-amber-600 hover:to-amber-700 hover:shadow-[0_7px_20px_rgba(217,119,6,0.30)] active:translate-y-0 disabled:cursor-not-allowed disabled:opacity-60">

                                    <span wire:loading.remove wire:target="save"
                                        class="inline-flex items-center gap-2">
                                        <x-heroicon-o-check-circle class="h-5 w-5" />
                                        {{ $wedding ? trans_json('Save changes') : trans_json('Create wedding') }}
                                    </span>

                                    <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                                        <x-heroicon-o-arrow-path class="h-5 w-5 animate-spin" />
                                        {{ trans_json('Saving…') }}
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
