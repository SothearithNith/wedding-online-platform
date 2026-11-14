@php $isEdit = $mode === 'edit'; @endphp

<div class="space-y-6" x-data="{
    saved(name) {
        Swal.fire({
            icon: 'success',
            title: @js($isEdit ? trans_json('User updated') : trans_json('User created')),
            html: `<b>${name}</b>`,
            confirmButtonText: @js(trans_json('Back to users')),
            confirmButtonColor: '#4f46e5',
        }).then(() => Livewire.navigate(@js(route('access-control.users.index'))));
    }
}" x-on:user-saved.window="saved($event.detail.name)">

    {{-- Hero header --}}
    <div
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 p-6 text-white shadow-xl shadow-indigo-500/20 sm:p-8">
        <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-16 left-1/3 h-48 w-48 rounded-full bg-fuchsia-300/20 blur-3xl">
        </div>

        <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav class="mb-2 flex items-center gap-1.5 text-xs text-indigo-100">
                    <a href="{{ route('access-control.users.index') }}" wire:navigate class="hover:text-white">
                        {{ trans_json('User Management') }}
                    </a>
                    <x-heroicon-o-chevron-right class="h-3 w-3" />
                    <span
                        class="font-semibold text-white">{{ $isEdit ? trans_json('Edit user') : trans_json('Create user') }}</span>
                </nav>
                <h1 class="text-2xl font-bold sm:text-3xl">
                    {{ $isEdit ? trans_json('Edit user') : trans_json('Create user') }}</h1>
                <p class="mt-1.5 max-w-xl text-sm text-indigo-100">
                    {{ trans_json('Set the username, role and password used to sign in.') }}
                </p>
            </div>

            <a href="{{ route('access-control.users.index') }}" wire:navigate
                class="group inline-flex shrink-0 items-center justify-center gap-2 rounded-2xl bg-white/15 px-5 py-3 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/25 active:scale-95">
                <x-heroicon-o-arrow-left class="h-5 w-5 transition-transform duration-300 group-hover:-translate-x-1" />
                {{ trans_json('Back to users') }}
            </a>
        </div>
    </div>

    <form wire:submit="save" class="grid gap-6 lg:grid-cols-3">

        <div class="space-y-6 lg:col-span-2">

            {{-- Account --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <x-heroicon-o-user-circle class="h-5 w-5" />
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900">{{ trans_json('Account') }}</h2>
                        <p class="text-xs text-slate-500">{{ trans_json('Identity and sign-in details.') }}</p>
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            {{ trans_json('Full name') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" wire:model.live.debounce.300ms="name" maxlength="100" autofocus
                            class="w-full rounded-xl border bg-white px-3 py-2.5 text-sm outline-none transition focus:ring-4 @error('name') border-rose-400 focus:border-rose-500 focus:ring-rose-500/10 @else border-slate-200 focus:border-indigo-500 focus:ring-indigo-500/10 @enderror">
                        @error('name')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            {{ trans_json('Username') }} <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400">@</span>
                            <input type="text" wire:model.blur="username" maxlength="50" autocapitalize="none"
                                spellcheck="false" autocomplete="off"
                                class="w-full rounded-xl border bg-white py-2.5 pl-8 pr-3 text-sm outline-none transition focus:ring-4 @error('username') border-rose-400 focus:border-rose-500 focus:ring-rose-500/10 @else border-slate-200 focus:border-indigo-500 focus:ring-indigo-500/10 @enderror">
                        </div>
                        @error('username')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @else
                            <p class="mt-1.5 text-xs text-slate-400">
                                {{ trans_json('Letters, numbers, dot, dash and underscore.') }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            {{ trans_json('Email') }} <span
                                class="text-xs font-normal text-slate-400">({{ trans_json('optional') }})</span>
                        </label>
                        <input type="email" wire:model.blur="email" autocomplete="off"
                            class="w-full rounded-xl border bg-white px-3 py-2.5 text-sm outline-none transition focus:ring-4 @error('email') border-rose-400 focus:border-rose-500 focus:ring-rose-500/10 @else border-slate-200 focus:border-indigo-500 focus:ring-indigo-500/10 @enderror">
                        @error('email')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{ show: false }">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            {{ trans_json('Password') }}
                            @unless ($isEdit)
                                <span class="text-rose-500">*</span>
                            @endunless
                        </label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="password" autocomplete="new-password"
                                placeholder="{{ $isEdit ? trans_json('Leave blank to keep current') : '' }}"
                                class="w-full rounded-xl border bg-white py-2.5 pl-3 pr-10 text-sm outline-none transition focus:ring-4 @error('password') border-rose-400 focus:border-rose-500 focus:ring-rose-500/10 @else border-slate-200 focus:border-indigo-500 focus:ring-indigo-500/10 @enderror">
                            <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="mb-1.5 block text-sm font-medium text-slate-700">{{ trans_json('Confirm password') }}</label>
                        <input type="password" wire:model="password_confirmation" autocomplete="new-password"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                    </div>
                </div>
            </div>

            {{-- Role --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                        <x-heroicon-o-shield-check class="h-5 w-5" />
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900">{{ trans_json('Role') }} <span
                                class="text-rose-500">*</span></h2>
                        <p class="text-xs text-slate-500">{{ trans_json('Only active roles can be assigned.') }}</p>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    @forelse ($roles as $r)
                        <button type="button" wire:key="role-{{ $r->id }}"
                            wire:click="$set('role_id', {{ $r->id }})"
                            class="flex items-center gap-3 rounded-2xl border-2 p-4 text-left transition {{ $role_id === $r->id ? 'border-indigo-500 bg-indigo-50/50 ring-4 ring-indigo-500/10' : 'border-slate-200 hover:border-slate-300' }}">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 font-bold text-white">
                                {{ mb_strtoupper(mb_substr($r->name, 0, 1)) }}
                            </div>
                            <p class="flex-1 truncate text-sm font-semibold text-slate-900">{{ $r->name }}</p>
                            @if ($role_id === $r->id)
                                <x-heroicon-s-check-circle class="h-5 w-5 shrink-0 text-indigo-600" />
                            @endif
                        </button>
                    @empty
                        <p class="col-span-2 rounded-xl bg-amber-50 px-4 py-3 text-sm text-amber-700">
                            {{ trans_json('No active roles yet. Create a role first.') }}
                        </p>
                    @endforelse
                </div>
                @error('role_id')
                    <p class="mt-3 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Preview + actions --}}
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
                            {{ $name !== '' ? $name : trans_json('Full name') }}</p>
                        <p class="truncate text-sm text-slate-500">
                            {{ $username !== '' ? '@' . $username : '@username' }}</p>

                        <div class="mt-4">
                            @php $picked = $roles->firstWhere('id', $role_id); @endphp
                            @if ($picked)
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700 ring-1 ring-inset ring-violet-200">
                                    <x-heroicon-o-shield-check class="h-3.5 w-3.5" /> {{ $picked->name }}
                                </span>
                            @else
                                <span
                                    class="text-xs italic text-slate-400">{{ trans_json('No role selected') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                    <button type="submit" wire:loading.attr="disabled" wire:target="save"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-fuchsia-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:shadow-xl active:scale-95 disabled:opacity-60">
                        <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                            <x-heroicon-o-check class="h-5 w-5" />
                            {{ $isEdit ? trans_json('Save changes') : trans_json('Create user') }}
                        </span>
                        <span wire:loading wire:target="save">{{ trans_json('Saving...') }}</span>
                    </button>
                    <a href="{{ route('access-control.users.index') }}" wire:navigate
                        class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 active:scale-95">
                        {{ trans_json('Cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
