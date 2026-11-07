<div class="mx-auto max-w-5xl space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('templates.index') }}" wire:navigate
            class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 hover:text-slate-800">
            <x-heroicon-o-arrow-left class="h-5 w-5" />
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                {{ $template ? __('Edit template') : __('Create template') }}
            </h1>
            <p class="text-sm text-slate-500">{{ __('Fill in the details, structure and preview image.') }}</p>
        </div>

        @if ($template)
            <a href="{{ route('templates.preview', $template) }}" target="_blank"
                class="ml-auto inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                <x-heroicon-o-eye class="h-5 w-5" /> {{ __('Preview') }}
            </a>
        @endif
    </div>

    <form wire:submit="save" class="grid gap-6 lg:grid-cols-5">

        {{-- Left: details --}}
        <div class="space-y-6 lg:col-span-3">

            <div class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name"
                            class="mb-2 block text-sm font-medium text-slate-700">{{ __('Name') }}</label>
                        <input id="name" type="text" wire:model="name" placeholder="Classic Gold"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="key"
                            class="mb-2 block text-sm font-medium text-slate-700">{{ __('Key') }}</label>
                        <input id="key" type="text" wire:model="key" placeholder="classic-gold"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 font-mono text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                        <p class="mt-1 text-xs text-slate-400">
                            {{ __('Unique identifier. Letters, numbers, dashes, underscores.') }}</p>
                        @error('key')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description"
                        class="mb-2 block text-sm font-medium text-slate-700">{{ __('Description') }}</label>
                    <textarea id="description" rows="3" wire:model="description"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"></textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <label class="flex cursor-pointer items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                        <span>
                            <span class="block text-sm font-medium text-slate-800">{{ __('Active') }}</span>
                            <span class="block text-xs text-slate-500">{{ __('Can be used.') }}</span>
                        </span>
                        <input type="checkbox" wire:model="is_active"
                            class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    </label>

                    <label class="flex cursor-pointer items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                        <span>
                            <span class="block text-sm font-medium text-slate-800">{{ __('Public') }}</span>
                            <span class="block text-xs text-slate-500">{{ __('Visible to everyone.') }}</span>
                        </span>
                        <input type="checkbox" wire:model="is_public"
                            class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    </label>
                </div>

                <div class="sm:w-40">
                    <label for="sort"
                        class="mb-2 block text-sm font-medium text-slate-700">{{ __('Sort order') }}</label>
                    <input id="sort" type="number" min="0" wire:model="sort"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                    @error('sort')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Sample images (every image used by the gold view) --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="mb-1 text-sm font-medium text-slate-700">{{ __('Sample images') }}</p>
                <p class="mb-4 text-xs text-slate-400">{{ __('Click an image to replace it. Click Save, then Preview.') }}</p>

                <div class="grid grid-cols-3 gap-3 sm:grid-cols-4">
                    @foreach ($this->slots as $s)
                        <div wire:key="slot-{{ $s['slot'] }}" class="relative">
                            <label
                                class="group relative block aspect-square cursor-pointer overflow-hidden rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 hover:border-indigo-400">
                                @if ($s['url'])
                                    <img src="{{ $s['url'] }}" alt="" class="h-full w-full object-cover"
                                        onerror="this.style.display='none'">
                                @endif
                                <span
                                    class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-xs font-semibold text-transparent transition group-hover:bg-slate-900/50 group-hover:text-white">
                                    {{ $s['url'] ? __('Change') : __('Upload') }}
                                </span>
                                <input type="file" accept="image/*" class="hidden"
                                    wire:model="imageUploads.{{ $s['slot'] }}">
                                <span wire:loading wire:target="imageUploads.{{ $s['slot'] }}"
                                    class="absolute inset-0 flex items-center justify-center bg-white/80 text-xs text-indigo-600">…</span>
                            </label>
                            <p class="mt-1 truncate text-center text-[11px] text-slate-500">{{ $s['label'] }}</p>

                            @if ($s['removable'])
                                <button type="button"
                                    wire:click="removeGallery({{ (int) str_replace('gallery_', '', $s['slot']) }})"
                                    class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white">×</button>
                            @endif
                        </div>
                    @endforeach
                </div>

                @error('imageUploads.*')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- JSON --}}
            <div class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div>
                    <label for="schema"
                        class="mb-2 block text-sm font-medium text-slate-700">{{ __('Schema (JSON)') }}</label>
                    <textarea id="schema" rows="8" wire:model="schema" spellcheck="false"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-mono text-xs outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"></textarea>
                    @error('schema')
                        <p class="mt-2 text-sm text-red-600">{{ __('Schema must be valid JSON.') }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sample_data"
                        class="mb-2 block text-sm font-medium text-slate-700">{{ __('Sample data (JSON)') }}</label>
                    <textarea id="sample_data" rows="8" wire:model="sample_data" spellcheck="false"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-mono text-xs outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"></textarea>
                    @error('sample_data')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message === 'The sample data field must be a valid JSON string.' ? __('Sample data must be valid JSON.') : $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Right: image + actions --}}
        <div class="space-y-4 lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:sticky lg:top-28">
                <p class="mb-3 text-sm font-medium text-slate-700">{{ __('Preview image') }}</p>

                <label for="preview"
                    class="group relative flex aspect-[3/4] cursor-pointer items-center justify-center overflow-hidden rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 transition hover:border-indigo-400 hover:bg-indigo-50/40">

                    @if ($preview)
                        <img src="{{ $preview->temporaryUrl() }}" class="h-full w-full object-cover" alt="">
                    @elseif ($template?->preview_src)
                        <img src="{{ $template->preview_src }}" class="h-full w-full object-cover" alt="">
                    @else
                        <div
                            class="flex flex-col items-center gap-2 px-4 text-center text-slate-400 transition group-hover:text-indigo-500">
                            <x-heroicon-o-cloud-arrow-up class="h-10 w-10" />
                            <span class="text-sm font-medium">{{ __('Click to upload') }}</span>
                            <span class="text-xs">PNG, JPG, WEBP · max 4 MB</span>
                        </div>
                    @endif

                    <div wire:loading wire:target="preview"
                        class="absolute inset-0 flex items-center justify-center bg-white/80 text-sm font-medium text-indigo-600">
                        {{ __('Uploading...') }}
                    </div>
                </label>

                <input id="preview" type="file" wire:model="preview" accept="image/*" class="hidden">
                @error('preview')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="mt-4 flex gap-3">
                    <a href="{{ route('templates.index') }}" wire:navigate
                        class="flex-1 rounded-xl border border-slate-300 bg-white px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        {{ __('Cancel') }}
                    </a>

                    <button type="submit" wire:loading.attr="disabled" wire:target="save,preview,imageUploads"
                        class="flex-1 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60">
                        <span wire:loading.remove wire:target="save">{{ __('Save') }}</span>
                        <span wire:loading wire:target="save">{{ __('Saving...') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
