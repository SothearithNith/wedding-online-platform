<div class="space-y-6" x-data="{
    confirmDelete(id, name) {
            Swal.fire({
                title: @js(trans_json('Delete feature?')),
                html: `<b>
${name}</b>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: @js(trans_json('Yes, delete')),
                cancelButtonText: @js(trans_json('Cancel')),
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
                focusCancel: true,
            }).then((r) => { if (r.isConfirmed) $wire.delete(id); });
        },
        confirmToggle(id, name, active) {
            Swal.fire({
                title: active ? @js(trans_json('Hide this feature?')) : @js(trans_json('Show this feature?')),
                html: `<b>${name}</b>` + (active ? '<br><small>' + @js(trans_json('It disappears from the sidebar and permission list.')) + '</small>' : ''),
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: active ? @js(trans_json('Yes, hide')) : @js(trans_json('Yes, show')),
                cancelButtonText: @js(trans_json('Cancel')),
                confirmButtonColor: active ? '#e11d48' : '#059669',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
                focusCancel: true,
            }).then((r) => { if (r.isConfirmed) $wire.toggleActive(id); });
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
                <h1 class="text-2xl font-bold sm:text-3xl">{{ trans_json('Feature Management') }}</h1>
                <p class="mt-1.5 max-w-xl text-sm text-indigo-100">
                    {{ trans_json('Manage the system menus. Each feature is linked to a permission that roles can be given.') }}
                </p>
            </div>

            <button type="button" wire:click="openCreate"
                class="group inline-flex shrink-0 items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-indigo-700 shadow-lg transition hover:scale-[1.03] hover:shadow-xl active:scale-95">
                <x-heroicon-o-plus class="h-5 w-5 transition-transform duration-300 group-hover:rotate-90" />
                {{ trans_json('Create feature') }}
            </button>
        </div>

        <div class="relative mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
            @foreach ([[trans_json('Total'), $this->total, 'heroicon-o-squares-2x2'], [trans_json('Active'), $this->activeCount, 'heroicon-o-check-circle'], [trans_json('Hidden'), $this->total - $this->activeCount, 'heroicon-o-eye-slash'], [trans_json('Groups'), $this->parentCount, 'heroicon-o-folder']] as [$label, $value, $icon])
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
                placeholder="{{ trans_json('Search by name, slug, route or permission...') }}"
                class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
        </div>

        <div class="inline-flex rounded-xl bg-slate-100 p-1 text-sm font-medium">
            @foreach (['all' => trans_json('All'), 'active' => trans_json('Active'), 'inactive' => trans_json('Hidden')] as $key => $label)
                <button type="button" wire:click="$set('status', '{{ $key }}')"
                    class="flex-1 rounded-lg px-3.5 py-1.5 transition {{ $status === $key ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Tree --}}
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="h-1 w-full overflow-hidden bg-slate-100">
            <div wire:loading wire:target="search,status,move,toggleActive,delete,save"
                class="h-full w-1/3 animate-pulse rounded-full bg-gradient-to-r from-indigo-500 to-fuchsia-500"></div>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($tree as $group)
                @php
                    $root = $group['menu'];
                    $kids = $group['kids'];
                    $rootIndex = $loop->index;
                    $rootLast = $loop->last;
                @endphp

                <div wire:key="menu-{{ $root->id }}">

                    {{-- Parent row --}}
                    @include('livewire.admin.user-management.feature.partials.row', [
                        'item' => $root,
                        'isChild' => false,
                        'canUp' => !$filtering && $rootIndex > 0,
                        'canDown' => !$filtering && !$rootLast,
                        'kidCount' => $group['total_kids'],
                    ])

                    {{-- Children --}}
                    @foreach ($kids as $kid)
                        @include('livewire.admin.user-management.feature.partials.row', [
                            'item' => $kid,
                            'isChild' => true,
                            'canUp' => !$filtering && !$loop->first,
                            'canDown' => !$filtering && !$loop->last,
                            'kidCount' => 0,
                        ])
                    @endforeach
                </div>
            @empty
                <div class="px-6 py-20 text-center">
                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-50 to-fuchsia-50 text-indigo-400">
                        <x-heroicon-o-squares-2x2 class="h-8 w-8" />
                    </div>
                    <p class="mt-4 text-sm font-semibold text-slate-700">{{ trans_json('No features found') }}</p>
                    <p class="mt-1 text-xs text-slate-500">
                        {{ $filtering ? trans_json('Try changing your search or filters.') : trans_json('Create your first feature to get started.') }}
                    </p>
                    @if ($filtering)
                        <button type="button" wire:click="resetFilters"
                            class="mt-4 rounded-xl bg-indigo-50 px-4 py-2 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-100">
                            {{ trans_json('Clear filters') }}
                        </button>
                    @endif
                </div>
            @endforelse
        </div>

        @if ($filtering)
            <p class="border-t border-slate-100 bg-slate-50/50 px-6 py-3 text-xs text-slate-500">
                {{ trans_json('Reordering is turned off while a filter is active.') }}
            </p>
        @endif
    </div>

    {{-- Create / edit modal --}}
    <div x-show="$wire.showForm" x-cloak x-transition.opacity @keydown.escape.window="$wire.closeForm()"
        class="fixed inset-0 z-50 flex items-end justify-center p-4 sm:items-center">

        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="$wire.closeForm()"></div>

        <form wire:submit="save"
            class="relative max-h-[92vh] w-full max-w-2xl overflow-y-auto rounded-3xl bg-white shadow-2xl">

            <div
                class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-100 bg-white/95 px-6 py-4 backdrop-blur">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 text-white">
                        <x-dynamic-component :component="$this->icon($icon)" class="h-5 w-5" />
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900">
                            {{ $editingId ? trans_json('Edit feature') : trans_json('Create feature') }}
                        </h2>
                        <p class="text-xs text-slate-500">
                            {{ trans_json('Menu entry and the permission linked to it.') }}</p>
                    </div>
                </div>
                <button type="button" wire:click="closeForm"
                    class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                    <x-heroicon-o-x-mark class="h-5 w-5" />
                </button>
            </div>

            <div class="grid gap-5 p-6 sm:grid-cols-2">

                {{-- Parent --}}
                <div class="sm:col-span-2">
                    <label
                        class="mb-1.5 block text-sm font-medium text-slate-700">{{ trans_json('Parent menu') }}</label>
                    <select wire:model="parent_id"
                        class="w-full rounded-xl border bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:ring-4 @error('parent_id') border-rose-400 focus:border-rose-500 focus:ring-rose-500/10 @else border-slate-200 focus:border-indigo-500 focus:ring-indigo-500/10 @enderror">
                        <option value="">{{ trans_json('None (top level)') }}</option>
                        @foreach ($this->parents as $p)
                            <option value="{{ $p->id }}">{{ $p->name_en }}@if ($p->name_km)
                                    · {{ $p->name_km }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Names --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">
                        {{ trans_json('English name') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" wire:model.live.debounce.300ms="name_en" maxlength="100"
                        class="w-full rounded-xl border bg-white px-3 py-2.5 text-sm outline-none transition focus:ring-4 @error('name_en') border-rose-400 focus:border-rose-500 focus:ring-rose-500/10 @else border-slate-200 focus:border-indigo-500 focus:ring-indigo-500/10 @enderror">
                    @error('name_en')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label
                        class="mb-1.5 block text-sm font-medium text-slate-700">{{ trans_json('Khmer name') }}</label>
                    <input type="text" wire:model="name_km" maxlength="100"
                        class="w-full rounded-xl border bg-white px-3 py-2.5 text-sm outline-none transition focus:ring-4 @error('name_km') border-rose-400 focus:border-rose-500 focus:ring-rose-500/10 @else border-slate-200 focus:border-indigo-500 focus:ring-indigo-500/10 @enderror">
                    @error('name_km')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">
                        {{ trans_json('Slug') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" wire:model.blur="slug" maxlength="100" autocapitalize="none"
                        spellcheck="false"
                        class="w-full rounded-xl border bg-white px-3 py-2.5 font-mono text-sm outline-none transition focus:ring-4 @error('slug') border-rose-400 focus:border-rose-500 focus:ring-rose-500/10 @else border-slate-200 focus:border-indigo-500 focus:ring-indigo-500/10 @enderror">
                    @error('slug')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Icon --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">{{ trans_json('Icon') }}</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <x-dynamic-component :component="$this->icon($icon)" class="h-5 w-5" />
                        </span>
                        <input type="text" wire:model.live.debounce.400ms="icon" placeholder="heroicon-o-users"
                            autocapitalize="none" spellcheck="false"
                            class="w-full rounded-xl border bg-white py-2.5 pl-10 pr-3 font-mono text-sm outline-none transition focus:ring-4 @error('icon') border-rose-400 focus:border-rose-500 focus:ring-rose-500/10 @else border-slate-200 focus:border-indigo-500 focus:ring-indigo-500/10 @enderror">
                    </div>
                    @error('icon')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @else
                        <p class="mt-1.5 text-xs text-slate-400">
                            {{ trans_json('Any Heroicon name, for example heroicon-o-users.') }}</p>
                    @enderror
                </div>

                {{-- Route --}}
                <div>
                    <label
                        class="mb-1.5 block text-sm font-medium text-slate-700">{{ trans_json('Route name') }}</label>
                    <input type="text" wire:model.blur="route_name" placeholder="access-control.users.index"
                        autocapitalize="none" spellcheck="false"
                        class="w-full rounded-xl border bg-white px-3 py-2.5 font-mono text-sm outline-none transition focus:ring-4 @error('route_name') border-rose-400 focus:border-rose-500 focus:ring-rose-500/10 @else border-slate-200 focus:border-indigo-500 focus:ring-indigo-500/10 @enderror">
                    @error('route_name')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @else
                        <p class="mt-1.5 text-xs text-slate-400">
                            {{ trans_json('Leave empty for a group that only holds sub-menus.') }}</p>
                    @enderror
                </div>

                {{-- Permission --}}
                <div>
                    <label
                        class="mb-1.5 block text-sm font-medium text-slate-700">{{ trans_json('Permission name') }}</label>
                    <input type="text" wire:model.blur="permission_name" list="permission-suggestions"
                        placeholder="users.view" autocapitalize="none" spellcheck="false"
                        class="w-full rounded-xl border bg-white px-3 py-2.5 font-mono text-sm outline-none transition focus:ring-4 @error('permission_name') border-rose-400 focus:border-rose-500 focus:ring-rose-500/10 @else border-slate-200 focus:border-indigo-500 focus:ring-indigo-500/10 @enderror">
                    <datalist id="permission-suggestions">
                        @foreach ($this->permissionSuggestions as $suggestion)
                            <option value="{{ $suggestion }}"></option>
                        @endforeach
                    </datalist>
                    @error('permission_name')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @else
                        <p class="mt-1.5 text-xs text-slate-400">
                            {{ trans_json('Format module.view. Roles get create, edit, delete and manage for the same module.') }}
                        </p>
                    @enderror
                </div>

                {{-- Active --}}
                <div class="flex items-center justify-between gap-4 rounded-2xl bg-slate-50 p-4 sm:col-span-2">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ trans_json('Active') }}</p>
                        <p class="mt-0.5 text-xs text-slate-500">
                            {{ $is_active ? trans_json('Shown in the sidebar and in Apply permission.') : trans_json('Hidden from the sidebar and from Apply permission.') }}
                        </p>
                    </div>
                    <button type="button" wire:click="$toggle('is_active')" role="switch"
                        aria-checked="{{ $is_active ? 'true' : 'false' }}"
                        class="relative inline-flex h-7 w-12 shrink-0 rounded-full transition-colors {{ $is_active ? 'bg-emerald-500' : 'bg-slate-300' }}">
                        <span
                            class="absolute top-1 h-5 w-5 rounded-full bg-white shadow transition-all {{ $is_active ? 'left-[26px]' : 'left-1' }}"></span>
                    </button>
                </div>
            </div>

            <div
                class="sticky bottom-0 flex flex-col-reverse gap-3 border-t border-slate-100 bg-white/95 px-6 py-4 backdrop-blur sm:flex-row sm:justify-end">
                <button type="button" wire:click="closeForm"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 active:scale-95">
                    {{ trans_json('Cancel') }}
                </button>
                <button type="submit" wire:loading.attr="disabled" wire:target="save"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-fuchsia-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:shadow-xl active:scale-95 disabled:opacity-60">
                    <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                        <x-heroicon-o-check class="h-5 w-5" />
                        {{ $editingId ? trans_json('Save changes') : trans_json('Create feature') }}
                    </span>
                    <span wire:loading wire:target="save">{{ trans_json('Saving...') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
