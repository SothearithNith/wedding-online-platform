@php
    $gradients = [
        'from-indigo-500 to-violet-500',
        'from-sky-500 to-indigo-500',
        'from-emerald-500 to-teal-500',
        'from-fuchsia-500 to-pink-500',
        'from-amber-500 to-orange-500',
        'from-rose-500 to-red-500',
    ];
@endphp

<div class="space-y-6" x-data="{
    confirmToggle(id, name, active) {
        Swal.fire({
            title: active ? @js(trans_json('Deactivate role?')) : @js(trans_json('Activate role?')),
            html: `<b>${name}</b>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: active ? @js(trans_json('Yes, deactivate')) : @js(trans_json('Yes, activate')),
            cancelButtonText: @js(trans_json('Cancel')),
            confirmButtonColor: active ? '#e11d48' : '#059669',
            cancelButtonColor: '#64748b',
            reverseButtons: true,
            focusCancel: true,
        }).then((result) => {
            if (result.isConfirmed) $wire.toggleActive(id);
        });
    }
}">

    {{-- Hero header --}}
    <div
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 p-6 text-white shadow-xl shadow-indigo-500/20 sm:p-8">

        <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-16 left-1/3 h-48 w-48 rounded-full bg-fuchsia-300/20 blur-3xl">
        </div>

        <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold sm:text-3xl">{{ trans_json('Role Management') }}</h1>
                <p class="mt-1.5 max-w-xl text-sm text-indigo-100">
                    {{ trans_json('Manage user roles and control access to system features.') }}
                </p>
            </div>

            <a href="{{ route('access-control.roles.create') }}" wire:navigate
                class="group inline-flex shrink-0 items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-indigo-700 shadow-lg transition hover:scale-[1.03] hover:shadow-xl active:scale-95">
                <x-heroicon-o-plus class="h-5 w-5 transition-transform duration-300 group-hover:rotate-90" />
                {{ trans_json('Create role') }}
            </a>
        </div>

        <div class="relative mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
            @foreach ([[trans_json('Total'), $this->total, 'heroicon-o-shield-check'], [trans_json('Active'), $this->activeCount, 'heroicon-o-check-circle'], [trans_json('Inactive'), $this->total - $this->activeCount, 'heroicon-o-x-circle']] as [$label, $value, $icon])
                <div class="flex items-center gap-3 rounded-2xl bg-white/15 p-3 backdrop-blur sm:p-4">
                    <div class="hidden h-10 w-10 items-center justify-center rounded-xl bg-white/20 sm:flex">
                        <x-dynamic-component :component="$icon" class="h-5 w-5" />
                    </div>
                    <div>
                        <p class="text-xl font-bold leading-none sm:text-2xl">{{ $value }}</p>
                        <p class="mt-1 text-xs text-indigo-100">{{ $label }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Filters --}}
    <div
        class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm lg:flex-row lg:items-center">
        <div class="relative flex-1">
            <x-heroicon-o-magnifying-glass
                class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
            <input type="search" wire:model.live.debounce.300ms="search"
                placeholder="{{ trans_json('Search by role name...') }}"
                class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
        </div>

        <select wire:model.live="visibility"
            class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
            <option value="all">{{ trans_json('All visibility') }}</option>
            <option value="public">{{ trans_json('Public') }}</option>
            <option value="private">{{ trans_json('Private') }}</option>
        </select>

        <div class="inline-flex rounded-xl bg-slate-100 p-1 text-sm font-medium">
            @foreach (['all' => trans_json('All'), 'active' => trans_json('Active'), 'inactive' => trans_json('Inactive')] as $key => $label)
                <button type="button" wire:click="$set('status', '{{ $key }}')"
                    class="flex-1 rounded-lg px-3.5 py-1.5 transition {{ $status === $key ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        <div class="h-1 w-full overflow-hidden bg-slate-100">
            <div wire:loading wire:target="search,status,visibility,gotoPage,nextPage,previousPage,toggleActive"
                class="h-full w-1/3 animate-pulse rounded-full bg-gradient-to-r from-indigo-500 to-fuchsia-500"></div>
        </div>

        <div wire:loading.class="opacity-60" wire:target="search,status,visibility,gotoPage,nextPage,previousPage"
            class="overflow-x-auto transition-opacity">
            <table class="min-w-full text-sm">
                <thead>
                    <tr
                        class="bg-gradient-to-r from-slate-50 to-indigo-50/40 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-4">{{ trans_json('Role') }}</th>
                        <th class="hidden px-6 py-4 sm:table-cell">{{ trans_json('Permissions') }}</th>
                        <th class="hidden px-6 py-4 lg:table-cell">{{ trans_json('Users') }}</th>
                        <th class="hidden px-6 py-4 md:table-cell">{{ trans_json('Visibility') }}</th>
                        <th class="px-6 py-4">{{ trans_json('Status') }}</th>
                        <th class="px-6 py-4 text-right">{{ trans_json('Actions') }}</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($roles as $role)
                        @php
                            $percent = min(100, round(($role->permissions_count / $this->totalPermissions) * 100));
                            $gradient = $gradients[$role->id % count($gradients)];
                        @endphp

                        <tr wire:key="role-{{ $role->id }}" class="group transition-colors hover:bg-indigo-50/30">

                            {{-- Role --}}
                            <td class="relative px-6 py-4">
                                <span
                                    class="absolute inset-y-2 left-0 w-1 rounded-r-full bg-gradient-to-b {{ $gradient }} opacity-0 transition-opacity group-hover:opacity-100"></span>

                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br {{ $gradient }} text-lg font-bold text-white shadow-md transition-transform group-hover:scale-105">
                                        {{ mb_strtoupper(mb_substr($role->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-slate-900">{{ $role->name }}</p>
                                        <div class="mt-0.5 flex items-center gap-2 text-xs text-slate-400">
                                            <span
                                                class="rounded bg-slate-100 px-1.5 py-0.5 font-mono text-[10px] uppercase text-slate-500">{{ $role->guard_name }}</span>
                                            <span>{{ $role->created_at?->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Permissions --}}
                            <td class="hidden px-6 py-4 sm:table-cell">
                                <div class="w-56 max-w-full">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-semibold text-slate-700">
                                            {{ $role->permissions_count }}
                                            <span class="font-normal text-slate-400">/
                                                {{ $this->totalPermissions }}</span>
                                        </span>
                                        <span class="text-slate-400">{{ $percent }}%</span>
                                    </div>
                                    <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full bg-gradient-to-r {{ $gradient }}"
                                            style="width: {{ $percent }}%"></div>
                                    </div>

                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach ($role->permissions->take(2) as $permission)
                                            <span
                                                class="max-w-[100px] truncate rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-medium text-slate-600"
                                                title="{{ $permission->name }}">{{ $permission->name }}</span>
                                        @endforeach
                                        @if ($role->permissions_count > 2)
                                            <span
                                                class="rounded-md bg-indigo-50 px-1.5 py-0.5 text-[10px] font-semibold text-indigo-600">+{{ $role->permissions_count - 2 }}</span>
                                        @elseif ($role->permissions_count === 0)
                                            <span
                                                class="text-[10px] italic text-slate-400">{{ trans_json('No permissions yet') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Users --}}
                            <td class="hidden px-6 py-4 lg:table-cell">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200">
                                    <x-heroicon-o-users class="h-3.5 w-3.5" />
                                    {{ $role->users_count }}
                                </span>
                            </td>

                            {{-- Visibility --}}
                            <td class="hidden px-6 py-4 md:table-cell">
                                @if ($role->is_public)
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
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                <button type="button"
                                    x-on:click="confirmToggle({{ $role->id }}, @js($role->name), {{ $role->status ? 'true' : 'false' }})"
                                    wire:loading.attr="disabled" wire:target="toggleActive({{ $role->id }})"
                                    role="switch" aria-checked="{{ $role->status ? 'true' : 'false' }}"
                                    class="inline-flex items-center gap-3 disabled:opacity-50">
                                    <span
                                        class="relative inline-flex h-6 w-11 shrink-0 rounded-full transition-colors {{ $role->status ? 'bg-emerald-500' : 'bg-slate-300' }}">
                                        <span
                                            class="absolute top-0.5 h-5 w-5 rounded-full bg-white shadow transition-all {{ $role->status ? 'left-[22px]' : 'left-0.5' }}"></span>
                                    </span>
                                    <span
                                        class="hidden items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold lg:inline-flex {{ $role->status ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        <span class="relative flex h-2 w-2">
                                            @if ($role->status)
                                                <span
                                                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                            @endif
                                            <span
                                                class="relative inline-flex h-2 w-2 rounded-full {{ $role->status ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        </span>
                                        {{ $role->status ? trans_json('Active') : trans_json('Inactive') }}
                                    </span>
                                </button>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('access-control.roles.permissions', $role) }}" wire:navigate
                                        title="{{ trans_json('Apply permission') }}"
                                        class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-violet-500 to-fuchsia-500 px-3.5 py-2 text-xs font-semibold text-white shadow-sm shadow-violet-500/30 transition hover:shadow-md hover:shadow-violet-500/40 active:scale-95">
                                        <x-heroicon-o-key class="h-4 w-4" />
                                        <span class="hidden xl:inline">{{ trans_json('Apply permission') }}</span>
                                    </a>

                                    <a href="{{ route('access-control.roles.edit', $role) }}" wire:navigate
                                        title="{{ trans_json('Edit') }}"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 active:scale-95">
                                        <x-heroicon-o-pencil-square class="h-4 w-4" />
                                        <span class="hidden xl:inline">{{ trans_json('Edit') }}</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div
                                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-50 to-fuchsia-50 text-indigo-400">
                                    <x-heroicon-o-shield-exclamation class="h-8 w-8" />
                                </div>
                                <p class="mt-4 text-sm font-semibold text-slate-700">
                                    {{ trans_json('No roles found') }}</p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ trans_json('Try changing your search or filters.') }}</p>
                                @if ($search !== '' || $visibility !== 'all' || $status !== 'all')
                                    <button type="button" wire:click="resetFilters"
                                        class="mt-4 rounded-xl bg-indigo-50 px-4 py-2 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-100">
                                        {{ trans_json('Clear filters') }}
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-3">
            @if ($roles->hasPages())
                {{ $roles->links() }}
            @else
                <p class="text-xs text-slate-500">
                    {{ trans_choice(':count role|:count roles', $roles->total(), ['count' => $roles->total()]) }}
                </p>
            @endif
        </div>
    </div>
</div>
