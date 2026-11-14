@php
    $total = max(count($this->allNames), 1);
    $percent = round(($this->selectedCount / $total) * 100);
@endphp

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
                    <span class="font-semibold text-white">{{ trans_json('Apply permission') }}</span>
                </nav>
                <h1 class="text-2xl font-bold sm:text-3xl">{{ trans_json('Apply permission') }}</h1>
                <p class="mt-1.5 max-w-xl text-sm text-indigo-100">
                    {{ trans_json('Choose which menus and features this role can access.') }}
                </p>
            </div>

            <div class="flex items-center gap-3 rounded-2xl bg-white/15 px-4 py-3 backdrop-blur">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20 text-lg font-bold">
                    {{ mb_strtoupper(mb_substr($role->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold leading-tight">{{ $role->name }}</p>
                    <p class="text-xs uppercase text-indigo-100">{{ $role->guard_name }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Menu tree --}}
        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                {{-- Toolbar --}}
                <div class="flex flex-col gap-3 border-b border-slate-100 p-4 sm:flex-row sm:items-center">
                    <div class="relative flex-1">
                        <x-heroicon-o-magnifying-glass
                            class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                        <input type="search" wire:model.live.debounce.300ms="search"
                            placeholder="{{ trans_json('Search menus...') }}"
                            class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                    </div>

                    <button type="button" wire:click="toggleAll"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 active:scale-95">
                        <x-heroicon-o-check-circle class="h-5 w-5" />
                        {{ $this->selectedCount === count($this->allNames) ? trans_json('Clear all') : trans_json('Select all') }}
                    </button>
                </div>

                <div class="h-1 w-full overflow-hidden bg-slate-100">
                    <div wire:loading wire:target="search,toggleGroup,toggleAll"
                        class="h-full w-1/3 animate-pulse rounded-full bg-gradient-to-r from-indigo-500 to-fuchsia-500">
                    </div>
                </div>

                {{-- Groups --}}
                <div class="divide-y divide-slate-100">
                    @forelse ($this->filteredMenus as $row)
                        @php
                            $menu = $row['menu'];
                            $children = $row['children'];
                            $groupNames = collect([$menu->permission_name])
                                ->merge($menu->children->pluck('permission_name'))
                                ->filter()
                                ->unique()
                                ->values()
                                ->all();
                            $groupOn = count(array_intersect($groupNames, $selected));
                            $groupAll = count($groupNames) > 0 && $groupOn === count($groupNames);
                        @endphp

                        <div wire:key="menu-{{ $menu->id }}" class="p-5">

                            {{-- Parent --}}
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 text-white shadow-md">
                                    <x-heroicon-o-folder class="h-5 w-5" />
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-semibold text-slate-900">{{ $menu->name }}</p>
                                    @if ($menu->permission_name)
                                        <p class="truncate font-mono text-xs text-slate-400">
                                            {{ $menu->permission_name }}</p>
                                    @endif
                                </div>

                                @if (count($groupNames) > 0)
                                    <span
                                        class="hidden rounded-full px-2.5 py-1 text-xs font-semibold sm:inline {{ $groupAll ? 'bg-emerald-50 text-emerald-700' : ($groupOn > 0 ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-500') }}">
                                        {{ $groupOn }}/{{ count($groupNames) }}
                                    </span>

                                    <button type="button" wire:click="toggleGroup(@js($groupNames))"
                                        class="rounded-lg px-3 py-1.5 text-xs font-semibold transition active:scale-95 {{ $groupAll ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                                        {{ $groupAll ? trans_json('Clear') : trans_json('Select group') }}
                                    </button>
                                @endif

                                @if ($menu->permission_name)
                                    <input type="checkbox" value="{{ $menu->permission_name }}"
                                        wire:model.live="selected"
                                        class="h-5 w-5 shrink-0 cursor-pointer rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                @endif
                            </div>

                            {{-- Children --}}
                            @if ($children->isNotEmpty())
                                <div class="ml-5 mt-4 space-y-2 border-l-2 border-slate-100 pl-9">
                                    @foreach ($children as $child)
                                        @php $on = in_array($child->permission_name, $selected, true); @endphp

                                        <label wire:key="menu-{{ $menu->id }}-{{ $child->id }}"
                                            class="flex cursor-pointer items-center gap-3 rounded-xl border px-4 py-3 transition {{ $on ? 'border-indigo-200 bg-indigo-50/60' : 'border-slate-100 hover:border-slate-200 hover:bg-slate-50' }}">
                                            <div
                                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $on ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-400' }}">
                                                <x-heroicon-o-document-text class="h-4 w-4" />
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-medium text-slate-800">
                                                    {{ $child->name }}</p>
                                                @if ($child->permission_name)
                                                    <p class="truncate font-mono text-[11px] text-slate-400">
                                                        {{ $child->permission_name }}</p>
                                                @else
                                                    <p class="text-[11px] italic text-slate-400">
                                                        {{ trans_json('No permission linked') }}</p>
                                                @endif
                                            </div>

                                            @if ($child->permission_name)
                                                <input type="checkbox" value="{{ $child->permission_name }}"
                                                    wire:model.live="selected"
                                                    class="h-5 w-5 shrink-0 cursor-pointer rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="px-6 py-20 text-center">
                            <div
                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-50 to-fuchsia-50 text-indigo-400">
                                <x-heroicon-o-squares-2x2 class="h-8 w-8" />
                            </div>
                            <p class="mt-4 text-sm font-semibold text-slate-700">{{ trans_json('No menus found') }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ trans_json('Try a different search.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Summary + actions --}}
        <div class="lg:col-span-1">
            <div class="space-y-4 lg:sticky lg:top-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">{{ trans_json('Access coverage') }}</p>
                    <p class="mt-1 text-4xl font-bold text-slate-900">{{ $percent }}<span
                            class="text-xl text-slate-400">%</span></p>

                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-fuchsia-500 transition-all duration-500"
                            style="width: {{ $percent }}%"></div>
                    </div>

                    <p class="mt-3 text-xs text-slate-500">
                        <span class="font-semibold text-slate-700">{{ $this->selectedCount }}</span>
                        {{ trans_json('of') }} {{ count($this->allNames) }}
                        {{ trans_json('menu permissions selected') }}
                    </p>

                    @if ($this->hasChanges)
                        <p
                            class="mt-4 flex items-center gap-2 rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700">
                            <x-heroicon-o-exclamation-triangle class="h-4 w-4" />
                            {{ trans_json('You have unsaved changes.') }}
                        </p>
                    @endif
                </div>

                <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                    <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save"
                        @disabled(!$this->hasChanges)
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-fuchsia-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:shadow-xl active:scale-95 disabled:cursor-not-allowed disabled:opacity-50">
                        <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                            <x-heroicon-o-check class="h-5 w-5" />
                            {{ trans_json('Save permissions') }}
                        </span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                            <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                </path>
                            </svg>
                            {{ trans_json('Saving...') }}
                        </span>
                    </button>

                    <a href="{{ route('access-control.roles.index') }}" wire:navigate
                        class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 active:scale-95">
                        {{ trans_json('Back to roles') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
