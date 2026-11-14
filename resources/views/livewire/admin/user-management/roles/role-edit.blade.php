<div class="space-y-6">

    {{-- Hero header --}}
    <div
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 p-6 text-white shadow-xl shadow-indigo-500/20 sm:p-8">
        <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-16 left-1/3 h-48 w-48 rounded-full bg-fuchsia-300/20 blur-3xl">
        </div>

        <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav class="mb-2 flex items-center gap-1.5 text-xs text-indigo-100">
                    <a href="{{ route('access-control.roles.index') }}" wire:navigate class="hover:text-white">
                        {{ trans_json('Role Management') }}
                    </a>
                    <x-heroicon-o-chevron-right class="h-3 w-3" />
                    <span class="font-semibold text-white">{{ trans_json('Edit role') }}</span>
                </nav>
                <h1 class="text-2xl font-bold sm:text-3xl">{{ trans_json('Edit role') }}</h1>
                <p class="mt-1.5 max-w-xl text-sm text-indigo-100">
                    {{ trans_json('Update the role details, visibility and availability.') }}
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('access-control.roles.index') }}" wire:navigate
                    class="group inline-flex items-center justify-center gap-2 rounded-2xl bg-white/15 px-5 py-3 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/25 active:scale-95">
                    <x-heroicon-o-arrow-left
                        class="h-5 w-5 transition-transform duration-300 group-hover:-translate-x-1" />
                    {{ trans_json('Back to roles') }}
                </a>
                <a href="{{ route('access-control.roles.permissions', $role) }}" wire:navigate
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-indigo-700 shadow-lg transition hover:scale-[1.03] active:scale-95">
                    <x-heroicon-o-key class="h-5 w-5" />
                    {{ trans_json('Apply permission') }}
                </a>
            </div>
        </div>
    </div>

    <form wire:submit="save" class="grid gap-6 lg:grid-cols-3">

        <div class="space-y-6 lg:col-span-2">

            {{-- Basic info --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <x-heroicon-o-identification class="h-5 w-5" />
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900">{{ trans_json('Basic information') }}</h2>
                        <p class="text-xs text-slate-500">{{ trans_json('Name and guard of the role.') }}</p>
                    </div>
                </div>

                <div class="grid gap-5">
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">
                            {{ trans_json('Role name') }} <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <x-heroicon-o-shield-check
                                class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                            <input id="name" type="text" wire:model.live.debounce.400ms="name" maxlength="50"
                                class="w-full rounded-xl border bg-white py-2.5 pl-10 pr-3 text-sm outline-none transition focus:ring-4 @error('name') border-rose-400 focus:border-rose-500 focus:ring-rose-500/10 @else border-slate-200 focus:border-indigo-500 focus:ring-indigo-500/10 @enderror">
                        </div>
                        @error('name')
                            <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-600">
                                <x-heroicon-s-exclamation-circle class="h-4 w-4" /> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="mb-1.5 block text-sm font-medium text-slate-700">{{ trans_json('Guard') }}</label>
                        <div class="relative">
                            <x-heroicon-o-lock-closed
                                class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                            <input type="text" value="{{ $role->guard_name }}" disabled
                                class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm text-slate-500">
                        </div>
                        <p class="mt-1.5 text-xs text-slate-400">
                            {{ trans_json('The guard cannot be changed because permissions are tied to it.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Settings --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                        <x-heroicon-o-adjustments-horizontal class="h-5 w-5" />
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900">{{ trans_json('Settings') }}</h2>
                        <p class="text-xs text-slate-500">{{ trans_json('Visibility and availability.') }}</p>
                    </div>
                </div>

                <p class="mb-2 text-sm font-medium text-slate-700">{{ trans_json('Visibility') }}</p>
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach ([['value' => true, 'label' => trans_json('Public'), 'desc' => trans_json('Visible and assignable by everyone with access.'), 'icon' => 'heroicon-o-globe-alt', 'color' => 'sky'], ['value' => false, 'label' => trans_json('Private'), 'desc' => trans_json('Only visible to administrators.'), 'icon' => 'heroicon-o-lock-closed', 'color' => 'slate']] as $option)
                        <button type="button"
                            wire:click="$set('is_public', {{ $option['value'] ? 'true' : 'false' }})"
                            class="flex items-start gap-3 rounded-2xl border-2 p-4 text-left transition {{ $is_public === $option['value'] ? 'border-indigo-500 bg-indigo-50/50 ring-4 ring-indigo-500/10' : 'border-slate-200 hover:border-slate-300' }}">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $option['color'] === 'sky' ? 'bg-sky-100 text-sky-600' : 'bg-slate-100 text-slate-600' }}">
                                <x-dynamic-component :component="$option['icon']" class="h-5 w-5" />
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-slate-900">{{ $option['label'] }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $option['desc'] }}</p>
                            </div>
                            @if ($is_public === $option['value'])
                                <x-heroicon-s-check-circle class="h-5 w-5 shrink-0 text-indigo-600" />
                            @endif
                        </button>
                    @endforeach
                </div>

                <div class="mt-5 flex items-center justify-between gap-4 rounded-2xl bg-slate-50 p-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ trans_json('Status') }}</p>
                        <p class="mt-0.5 text-xs text-slate-500">
                            {{ $status ? trans_json('Role is active and can be assigned to users.') : trans_json('Role is inactive and cannot be assigned.') }}
                        </p>
                    </div>
                    <button type="button" wire:click="$toggle('status')" role="switch"
                        aria-checked="{{ $status ? 'true' : 'false' }}"
                        class="relative inline-flex h-7 w-12 shrink-0 rounded-full transition-colors {{ $status ? 'bg-emerald-500' : 'bg-slate-300' }}">
                        <span
                            class="absolute top-1 h-5 w-5 rounded-full bg-white shadow transition-all {{ $status ? 'left-[26px]' : 'left-1' }}"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Summary + actions --}}
        <div class="lg:col-span-1">
            <div class="space-y-4 lg:sticky lg:top-6">
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="h-20 bg-gradient-to-br from-indigo-500 via-violet-500 to-fuchsia-500"></div>
                    <div class="-mt-8 px-6 pb-6">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-2xl border-4 border-white bg-gradient-to-br from-indigo-500 to-violet-500 text-2xl font-bold text-white shadow-md">
                            {{ $name !== '' ? mb_strtoupper(mb_substr(trim($name), 0, 1)) : '?' }}
                        </div>
                        <p class="mt-3 truncate text-lg font-bold text-slate-900">
                            {{ $name !== '' ? $name : trans_json('Role name') }}</p>
                        <p class="mt-0.5 flex items-center gap-2 text-xs text-slate-400">
                            <span
                                class="rounded bg-slate-100 px-1.5 py-0.5 font-mono text-[10px] uppercase text-slate-500">{{ $role->guard_name }}</span>
                            <span>{{ trans_json('Created') }} {{ $role->created_at?->diffForHumans() }}</span>
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @if ($is_public)
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 px-3 py-1 text-xs font-medium text-sky-700 ring-1 ring-inset ring-sky-200">
                                    <x-heroicon-o-globe-alt class="h-3.5 w-3.5" /> {{ trans_json('Public') }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">
                                    <x-heroicon-o-lock-closed class="h-3.5 w-3.5" /> {{ trans_json('Private') }}
                                </span>
                            @endif
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold {{ $status ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                <span
                                    class="h-2 w-2 rounded-full {{ $status ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                {{ $status ? trans_json('Active') : trans_json('Inactive') }}
                            </span>
                        </div>

                        <div class="mt-5 flex items-center justify-between rounded-xl bg-violet-50/70 px-4 py-3">
                            <span class="flex items-center gap-2 text-xs font-medium text-violet-700">
                                <x-heroicon-o-key class="h-4 w-4" /> {{ trans_json('Permissions applied') }}
                            </span>
                            <span class="text-sm font-bold text-violet-700">{{ $this->permissionsCount }}</span>
                        </div>
                    </div>
                </div>

                @if ($this->hasChanges)
                    <p
                        class="flex items-center gap-2 rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700">
                        <x-heroicon-o-exclamation-triangle class="h-4 w-4" />
                        {{ trans_json('You have unsaved changes.') }}
                    </p>
                @endif

                <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                    <button type="submit" wire:loading.attr="disabled" wire:target="save" @disabled(!$this->hasChanges)
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-fuchsia-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:shadow-xl active:scale-95 disabled:cursor-not-allowed disabled:opacity-50">
                        <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                            <x-heroicon-o-check class="h-5 w-5" /> {{ trans_json('Save changes') }}
                        </span>
                        <span wire:loading wire:target="save">{{ trans_json('Saving...') }}</span>
                    </button>
                    <a href="{{ route('access-control.roles.index') }}" wire:navigate
                        class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 active:scale-95">
                        {{ trans_json('Cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
