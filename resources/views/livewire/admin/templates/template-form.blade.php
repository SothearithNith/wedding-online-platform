<div class="mx-auto max-w-6xl space-y-6">
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('templates.index') }}" wire:navigate
            class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-800">
            <x-heroicon-o-arrow-left class="h-5 w-5" />
        </a>
        <div class="flex items-center gap-3">
            <span
                class="hidden h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-lg shadow-indigo-500/30 sm:flex">
                <x-heroicon-o-swatch class="h-6 w-6" />
            </span>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    {{ $template ? trans_json('Edit template') : trans_json('Create template') }}
                </h1>
                <p class="text-sm text-slate-500">{{ trans_json('Fill in the details, structure and preview image.') }}
                </p>
            </div>
        </div>

        @if ($template)
            <a href="{{ route('admin.templates.preview', $template) }}" target="_blank"
                class="ml-auto inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <x-heroicon-o-eye class="h-5 w-5" /> {{ trans_json('Preview') }}
            </a>
        @endif
    </div>

    <form wire:submit="save" class="grid items-start gap-6 lg:grid-cols-5">
        <div class="space-y-6 lg:col-span-3">
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/60 px-6 py-4">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <x-heroicon-o-identification class="h-5 w-5" />
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">{{ trans_json('Basic details') }}</h2>
                        <p class="text-xs text-slate-500">{{ trans_json('Name, key and visibility.') }}</p>
                    </div>
                </div>

                <div class="space-y-5 p-6">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="name"
                                class="mb-2 block text-sm font-medium text-slate-700">{{ trans_json('Name') }}</label>
                            <input id="name" type="text" wire:model="name" placeholder="Classic Gold"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                            @error('name')
                                <p class="mt-2 flex items-center gap-1 text-sm text-red-600">
                                    <x-heroicon-s-exclamation-circle class="h-4 w-4 shrink-0" />{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="key"
                                class="mb-2 block text-sm font-medium text-slate-700">{{ trans_json('Key') }}</label>
                            <div class="relative">
                                <x-heroicon-o-hashtag
                                    class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                <input id="key" type="text" wire:model="key" placeholder="classic-gold"
                                    class="w-full rounded-xl border border-slate-300 py-3 pl-10 pr-4 font-mono text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                            </div>
                            <p class="mt-1.5 text-xs text-slate-400">
                                {{ trans_json('Unique identifier. Letters, numbers, dashes, underscores.') }}</p>
                            @error('key')
                                <p class="mt-1 flex items-center gap-1 text-sm text-red-600">
                                    <x-heroicon-s-exclamation-circle class="h-4 w-4 shrink-0" />{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description"
                            class="mb-2 block text-sm font-medium text-slate-700">{{ trans_json('Description') }}</label>
                        <textarea id="description" rows="3" wire:model="description"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"></textarea>
                        @error('description')
                            <p class="mt-2 flex items-center gap-1 text-sm text-red-600">
                                <x-heroicon-s-exclamation-circle class="h-4 w-4 shrink-0" />{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Toggles --}}
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label
                            class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 transition hover:border-emerald-300 hover:bg-emerald-50/40">
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                <x-heroicon-o-bolt class="h-5 w-5" />
                            </span>
                            <span class="min-w-0 flex-1">
                                <span
                                    class="block text-sm font-medium text-slate-800">{{ trans_json('Active') }}</span>
                                <span class="block text-xs text-slate-500">{{ trans_json('Can be used.') }}</span>
                            </span>
                            <input type="checkbox" wire:model="is_active" class="peer sr-only">
                            <span
                                class="relative h-6 w-11 shrink-0 rounded-full bg-slate-300 transition peer-checked:bg-emerald-500 peer-focus-visible:ring-4 peer-focus-visible:ring-emerald-500/20 after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-all peer-checked:after:translate-x-5"></span>
                        </label>

                        <label
                            class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 transition hover:border-indigo-300 hover:bg-indigo-50/40">
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                                <x-heroicon-o-globe-alt class="h-5 w-5" />
                            </span>
                            <span class="min-w-0 flex-1">
                                <span
                                    class="block text-sm font-medium text-slate-800">{{ trans_json('Public') }}</span>
                                <span
                                    class="block text-xs text-slate-500">{{ trans_json('Visible to everyone.') }}</span>
                            </span>
                            <input type="checkbox" wire:model="is_public" class="peer sr-only">
                            <span
                                class="relative h-6 w-11 shrink-0 rounded-full bg-slate-300 transition peer-checked:bg-indigo-500 peer-focus-visible:ring-4 peer-focus-visible:ring-indigo-500/20 after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-all peer-checked:after:translate-x-5"></span>
                        </label>
                    </div>

                    <div class="sm:w-40">
                        <label for="sort"
                            class="mb-2 block text-sm font-medium text-slate-700">{{ trans_json('Sort order') }}</label>
                        <input id="sort" type="number" min="0" wire:model="sort"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                        @error('sort')
                            <p class="mt-2 flex items-center gap-1 text-sm text-red-600">
                                <x-heroicon-s-exclamation-circle class="h-4 w-4 shrink-0" />{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- Sample images --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/60 px-6 py-4">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <x-heroicon-o-photo class="h-5 w-5" />
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">{{ trans_json('Sample images') }}</h2>
                        <p class="text-xs text-slate-500">
                            {{ trans_json('Click an image to replace it. Click Save, then Preview.') }}</p>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-3 gap-4 sm:grid-cols-4">
                        @foreach ($this->slots as $s)
                            <div wire:key="slot-{{ $s['slot'] }}" class="relative">
                                <label
                                    class="group relative block aspect-square cursor-pointer overflow-hidden rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 transition hover:border-indigo-400 hover:shadow-md">
                                    @if ($s['url'])
                                        <img src="{{ $s['url'] }}" alt=""
                                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                            onerror="this.style.display='none'">
                                    @else
                                        <span
                                            class="absolute inset-0 flex items-center justify-center text-slate-300 transition group-hover:text-indigo-400">
                                            <x-heroicon-o-plus class="h-7 w-7" />
                                        </span>
                                    @endif

                                    <span
                                        class="absolute inset-0 flex flex-col items-center justify-center gap-1 bg-slate-900/0 text-xs font-semibold text-transparent opacity-0 backdrop-blur-0 transition group-hover:bg-slate-900/45 group-hover:text-white group-hover:opacity-100 group-hover:backdrop-blur-[2px]">
                                        <x-heroicon-o-arrow-up-tray class="h-5 w-5" />
                                        {{ $s['url'] ? trans_json('Change') : trans_json('Upload') }}
                                    </span>

                                    <input type="file" accept="image/*" class="hidden"
                                        wire:model="imageUploads.{{ $s['slot'] }}">

                                    <span wire:loading wire:target="imageUploads.{{ $s['slot'] }}"
                                        class="absolute inset-0 flex items-center justify-center bg-white/85 text-indigo-600">
                                        <svg class="h-6 w-6 animate-spin" viewBox="0 0 24 24" fill="none">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4" />
                                            <path class="opacity-90" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                                        </svg>
                                    </span>
                                </label>

                                <p class="mt-1.5 truncate text-center text-[11px] font-medium text-slate-500">
                                    {{ $s['label'] }}</p>

                                @if ($s['removable'])
                                    <button type="button"
                                        wire:click="removeGallery({{ (int) str_replace('gallery_', '', $s['slot']) }})"
                                        aria-label="{{ trans_json('Remove') }}"
                                        class="absolute -right-1.5 -top-1.5 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-white shadow-md ring-2 ring-white transition hover:bg-red-600">
                                        <x-heroicon-s-x-mark class="h-3.5 w-3.5" />
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @error('imageUploads.*')
                        <p class="mt-3 flex items-center gap-1 text-sm text-red-600">
                            <x-heroicon-s-exclamation-circle class="h-4 w-4 shrink-0" />{{ $message }}
                        </p>
                    @enderror
                </div>
            </section>

            {{-- JSON --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/60 px-6 py-4">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                        <x-heroicon-o-code-bracket class="h-5 w-5" />
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">{{ trans_json('Structure') }}</h2>
                        <p class="text-xs text-slate-500">{{ trans_json('Schema and sample data in JSON.') }}</p>
                    </div>
                </div>

                <div class="space-y-5 p-6">
                    <div>
                        <label for="schema"
                            class="mb-2 flex items-center gap-1.5 text-sm font-medium text-slate-700">
                            <x-heroicon-o-command-line class="h-4 w-4 text-slate-400" />
                            {{ trans_json('Schema (JSON)') }}
                        </label>
                        <textarea id="schema" rows="8" wire:model="schema" spellcheck="false"
                            class="w-full rounded-xl border border-slate-700 bg-slate-900 px-4 py-3 font-mono text-xs leading-relaxed text-emerald-300 outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20"></textarea>
                        @error('schema')
                            <p class="mt-2 flex items-center gap-1 text-sm text-red-600">
                                <x-heroicon-s-exclamation-circle
                                    class="h-4 w-4 shrink-0" />{{ trans_json('Schema must be valid JSON.') }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="sample_data"
                            class="mb-2 flex items-center gap-1.5 text-sm font-medium text-slate-700">
                            <x-heroicon-o-circle-stack class="h-4 w-4 text-slate-400" />
                            {{ trans_json('Sample data (JSON)') }}
                        </label>
                        <textarea id="sample_data" rows="8" wire:model="sample_data" spellcheck="false"
                            class="w-full rounded-xl border border-slate-700 bg-slate-900 px-4 py-3 font-mono text-xs leading-relaxed text-sky-300 outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20"></textarea>
                        @error('sample_data')
                            <p class="mt-2 flex items-center gap-1 text-sm text-red-600">
                                <x-heroicon-s-exclamation-circle class="h-4 w-4 shrink-0" />
                                {{ $message === 'The sample data field must be a valid JSON string.' ? trans_json('Sample data must be valid JSON.') : $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </section>
        </div>
        <div class="lg:col-span-2 lg:sticky lg:top-28">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="mb-3 flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-800">{{ trans_json('Preview image') }}</p>
                    <span class="text-xs text-slate-400">{{ trans_json('Live card preview') }}</span>
                </div>

                <label for="preview"
                    class="group relative block aspect-[3/4] cursor-pointer overflow-hidden rounded-2xl bg-gradient-to-b from-slate-200 via-slate-300 to-slate-500 shadow-md ring-1 ring-slate-200 transition hover:shadow-xl">

                    @if ($preview)
                        <img src="{{ $preview->temporaryUrl() }}"
                            class="absolute inset-0 h-full w-full object-cover object-top" alt="">
                    @elseif ($template?->preview_src)
                        <img src="{{ $template->preview_src }}"
                            class="absolute inset-0 h-full w-full object-cover object-top" alt="">
                    @else
                        <div
                            class="absolute inset-0 flex flex-col items-center justify-center gap-2 px-4 pb-24 text-center text-white/80">
                            <span
                                class="flex h-16 w-16 items-center justify-center rounded-2xl border border-white/30 bg-white/20 backdrop-blur-md transition group-hover:scale-110">
                                <x-heroicon-o-cloud-arrow-up class="h-8 w-8" />
                            </span>
                            <span class="text-sm font-semibold">{{ trans_json('Click to upload') }}</span>
                            <span class="text-xs text-white/70">PNG, JPG, WEBP &bull; max 4 MB</span>
                        </div>
                    @endif

                    <div
                        class="pointer-events-none absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-slate-950/50 to-transparent">
                    </div>

                    {{-- Replace hint on hover --}}
                    @if ($preview || $template?->preview_src)
                        <span
                            class="absolute right-3 top-3 inline-flex items-center gap-1.5 rounded-full border border-white/30 bg-slate-950/40 px-3 py-1.5 text-xs font-semibold text-white opacity-0 backdrop-blur-md transition group-hover:opacity-100">
                            <x-heroicon-o-arrow-path class="h-3.5 w-3.5" />
                            {{ trans_json('Replace') }}
                        </span>
                    @endif

                    {{-- Status badges (live) --}}
                    <div class="absolute left-3 top-3">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full border border-white/30 bg-white/20 px-3 py-1 text-xs font-semibold text-white backdrop-blur-md">
                            <span class="h-2 w-2 rounded-full" x-data
                                x-bind:class="$wire.is_active ? 'bg-emerald-400' : 'bg-slate-300'"></span>
                            <span x-data
                                x-text="$wire.is_active ? @js(trans_json('Active')) : @js(trans_json('Inactive'))"></span>
                        </span>
                    </div>

                    {{-- Glass info panel (live) --}}
                    <div x-data
                        class="absolute inset-x-3 bottom-3 rounded-2xl border border-white/30 bg-white/10 p-3.5 text-white shadow-xl shadow-black/20 backdrop-blur-lg">
                        <h3 class="truncate text-base font-bold drop-shadow-sm"
                            x-text="$wire.name || @js(trans_json('Template name'))"></h3>
                        <p class="mt-0.5 truncate font-mono text-[11px] text-white/60"
                            x-text="$wire.key || 'template-key'"></p>
                        <p class="mt-2 line-clamp-2 text-xs leading-relaxed text-white/85"
                            x-text="$wire.description || @js(trans_json('Your description appears here.'))"></p>
                    </div>

                    {{-- Uploading overlay --}}
                    <div wire:loading wire:target="preview"
                        class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-white/85 text-sm font-medium text-indigo-600 backdrop-blur-sm">
                        <svg class="h-7 w-7 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                        </svg>
                        {{ trans_json('Uploading...') }}
                    </div>
                </label>

                <input id="preview" type="file" wire:model="preview" accept="image/*" class="hidden">
                @error('preview')
                    <p class="mt-2 flex items-center gap-1 text-sm text-red-600">
                        <x-heroicon-s-exclamation-circle class="h-4 w-4 shrink-0" />{{ $message }}
                    </p>
                @enderror

                {{-- Actions --}}
                <div class="mt-4 flex gap-3">
                    <a href="{{ route('templates.index') }}" wire:navigate
                        class="flex-1 rounded-xl border border-slate-300 bg-white px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        {{ trans_json('Cancel') }}
                    </a>

                    <button type="submit" wire:loading.attr="disabled" wire:target="save,preview,imageUploads"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:from-indigo-700 hover:to-violet-700 disabled:cursor-not-allowed disabled:opacity-60">
                        <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                            <x-heroicon-o-check class="h-5 w-5" />
                            {{ trans_json('Save') }}
                        </span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4" />
                                <path class="opacity-90" fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                            </svg>
                            {{ trans_json('Saving...') }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
