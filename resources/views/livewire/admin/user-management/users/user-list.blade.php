<div class="space-y-6" x-data="{
    confirmDelete(id, name) {
        Swal.fire({
            title: @js(trans_json('Delete user?')),
            html: `<b>${name}</b>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: @js(trans_json('Yes, delete')),
            cancelButtonText: @js(trans_json('Cancel')),
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            reverseButtons: true,
            focusCancel: true,
        }).then((r) => { if (r.isConfirmed) $wire.delete(id); });
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
                <h1 class="text-2xl font-bold sm:text-3xl">{{ trans_json('User Management') }}</h1>
                <p class="mt-1.5 max-w-xl text-sm text-indigo-100">
                    {{ trans_json('Manage user accounts and the role each user belongs to.') }}
                </p>
                <p
                    class="mt-3 inline-flex items-center gap-2 rounded-xl bg-white/15 px-3 py-1.5 text-xs font-semibold backdrop-blur">
                    <x-heroicon-o-users class="h-4 w-4" /> {{ $this->total }} {{ trans_json('users') }}
                </p>
            </div>
            <a href="{{ route('access-control.users.create') }}" wire:navigate
                class="group inline-flex shrink-0 items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-indigo-700 shadow-lg transition hover:scale-[1.03] hover:shadow-xl active:scale-95">
                <x-heroicon-o-plus class="h-5 w-5 transition-transform duration-300 group-hover:rotate-90" />
                {{ trans_json('Create user') }}
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div
        class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm lg:flex-row lg:items-center">
        <div class="relative flex-1">
            <x-heroicon-o-magnifying-glass
                class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
            <input type="search" wire:model.live.debounce.300ms="search"
                placeholder="{{ trans_json('Search by name, username or email...') }}"
                class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
        </div>

        <select wire:model.live="role"
            class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
            <option value="all">{{ trans_json('All roles') }}</option>
            @foreach ($this->roles as $r)
                <option value="{{ $r->id }}">{{ $r->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="h-1 w-full overflow-hidden bg-slate-100">
            <div wire:loading wire:target="search,role,gotoPage,nextPage,previousPage,delete"
                class="h-full w-1/3 animate-pulse rounded-full bg-gradient-to-r from-indigo-500 to-fuchsia-500"></div>
        </div>

        <div wire:loading.class="opacity-60" wire:target="search,role,gotoPage,nextPage,previousPage"
            class="overflow-x-auto transition-opacity">
            <table class="min-w-full text-sm">
                <thead>
                    <tr
                        class="bg-gradient-to-r from-slate-50 to-indigo-50/40 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-4">{{ trans_json('User') }}</th>
                        <th class="hidden px-6 py-4 md:table-cell">{{ trans_json('Username') }}</th>
                        <th class="px-6 py-4">{{ trans_json('Role') }}</th>
                        <th class="hidden px-6 py-4 lg:table-cell">{{ trans_json('Created') }}</th>
                        <th class="px-6 py-4 text-right">{{ trans_json('Actions') }}</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        @php $locked = $user->role_id === 1; @endphp
                        <tr wire:key="user-{{ $user->id }}" class="group transition-colors hover:bg-indigo-50/30">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 text-lg font-bold text-white shadow-md transition-transform group-hover:scale-105">
                                        {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-slate-900">{{ $user->name }}</p>
                                        <p class="truncate text-xs text-slate-400">
                                            {{ $user->email ?: trans_json('No email') }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="hidden px-6 py-4 md:table-cell">
                                <span
                                    class="rounded-lg bg-slate-100 px-2 py-1 font-mono text-xs text-slate-600">{{ '@' . $user->username }}</span>
                            </td>

                            <td class="px-6 py-4">
                                @if ($user->role)
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $user->role->status ? 'bg-violet-50 text-violet-700 ring-violet-200' : 'bg-slate-50 text-slate-500 ring-slate-200' }}">
                                        <x-heroicon-o-shield-check class="h-3.5 w-3.5" />
                                        {{ $user->role->name }}
                                        @unless ($user->role->status)
                                            <span class="text-[10px] font-normal">({{ trans_json('inactive') }})</span>
                                        @endunless
                                    </span>
                                @else
                                    <span class="text-xs italic text-slate-400">{{ trans_json('No role') }}</span>
                                @endif
                            </td>

                            <td class="hidden px-6 py-4 text-xs text-slate-500 lg:table-cell">
                                {{ $user->created_at?->diffForHumans() }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($locked)
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-500">
                                            <x-heroicon-o-lock-closed class="h-4 w-4" /> {{ trans_json('Protected') }}
                                        </span>
                                    @else
                                        <a href="{{ route('access-control.users.edit', $user) }}" wire:navigate
                                            title="{{ trans_json('Edit') }}"
                                            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 active:scale-95">
                                            <x-heroicon-o-pencil-square class="h-4 w-4" />
                                            <span class="hidden xl:inline">{{ trans_json('Edit') }}</span>
                                        </a>

                                        @if ($user->id !== auth()->id())
                                            <button type="button"
                                                x-on:click="confirmDelete({{ $user->id }}, @js($user->name))"
                                                title="{{ trans_json('Delete') }}"
                                                class="inline-flex items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-2 text-xs font-semibold text-rose-600 transition hover:bg-rose-100 active:scale-95">
                                                <x-heroicon-o-trash class="h-4 w-4" />
                                                <span class="hidden xl:inline">{{ trans_json('Delete') }}</span>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div
                                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-50 to-fuchsia-50 text-indigo-400">
                                    <x-heroicon-o-users class="h-8 w-8" />
                                </div>
                                <p class="mt-4 text-sm font-semibold text-slate-700">{{ trans_json('No users found') }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ trans_json('Try changing your search or filters.') }}</p>
                                @if ($search !== '' || $role !== 'all')
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
            @if ($users->hasPages())
                {{ $users->links() }}
            @else
                <p class="text-xs text-slate-500">
                    {{ trans_choice(':count user|:count users', $users->total(), ['count' => $users->total()]) }}
                </p>
            @endif
        </div>
    </div>
</div>
