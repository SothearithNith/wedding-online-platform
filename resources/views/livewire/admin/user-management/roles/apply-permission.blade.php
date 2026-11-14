@php
    $total = max(count($this->allNames), 1);
    $percent = round(($this->selectedCount / $total) * 100);

    $actionStyles = [
        'view'   => ['on' => 'bg-sky-500 text-white border-sky-500',         'off' => 'border-slate-200 text-slate-500 hover:border-sky-300 hover:text-sky-600'],
        'create' => ['on' => 'bg-emerald-500 text-white border-emerald-500', 'off' => 'border-slate-200 text-slate-500 hover:border-emerald-300 hover:text-emerald-600'],
        'edit'   => ['on' => 'bg-amber-500 text-white border-amber-500',     'off' => 'border-slate-200 text-slate-500 hover:border-amber-300 hover:text-amber-600'],
        'delete' => ['on' => 'bg-rose-500 text-white border-rose-500',       'off' => 'border-slate-200 text-slate-500 hover:border-rose-300 hover:text-rose-600'],
        'manage' => ['on' => 'bg-violet-500 text-white border-violet-500',   'off' => 'border-slate-200 text-slate-500 hover:border-violet-300 hover:text-violet-600'],
    ];
@endphp

<div class="space-y-6">

    {{-- Hero header --}}
    <div
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 p-6 text-white shadow-xl shadow-indigo-500/20 sm:p-8">
        <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-16 left-1/3 h-48 w-48 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

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
                    {{ trans_json('Choose what this role can do in each part of the system.') }}
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

        {{-- Permission matrix --}}
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
                    <div wire:loading wire:target="search,toggle,toggleModule,toggleColumn,toggleAll"
                        class="h-full w-1/3 animate-pulse rounded-full bg-gradient-to-r from-indigo-500 to-fuchsia-500"></div>
                </div>

                {{-- Column shortcuts --}}
                <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/70 px-5 py-3">
                    <p class="flex-1 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        {{ trans_json('Menu') }}
                    </p>
                    <div class="flex gap-1.5">
                        @foreach (\App\Livewire\Admin\UserManagement\Roles\ApplyPermission::ACTIONS as $action)
                            <button type="button" wire:click="toggleColumn('{{ $action }}')"
                                title="{{ trans_json('Toggle') }} {{ $action }} {{ trans_json('for all') }}"
                                class="w-[68px] rounded-lg px-2 py-1 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500 transition hover:bg-white hover:text-indigo-600">
                                {{ trans_json(ucfirst($action)) }}
                            </button>
                        @endforeach
                    </div>
                    <span class="hidden w-14 sm:block"></span>
                </div>

                {{-- Groups --}}
                <div class="divide-y divide-slate-100">
                    @forelse ($this->tree as $group)
                        @php $menu = $group['menu']; @endphp

                        <div wire:key="group-{{ $menu->id }}" class="py-2">

                            {{-- Group header --}}
                            <div class="flex items-center gap-2 px-5 pb-1 pt-3">
                                <x-heroicon-o-folder class="h-4 w-4 text-indigo-500" />
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ $menu->name }}</p>
                            </div>

                            @foreach ($group['rows'] as $row)
                                @php
                                    $module = $row['module'];
                                    $names = array_map(fn ($a) => "{$module}.{$a}", \App\Livewire\Admin\UserManagement\Roles\ApplyPermission::ACTIONS);
                                    $onCount = count(array_intersect($names, $selected));
                                @endphp

                                <div wire:key="row-{{ $module }}"
                                    class="flex items-center gap-3 px-5 py-3 transition hover:bg-indigo-50/30 {{ $row['child'] ? 'pl-9 sm:pl-12' : '' }}">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $onCount > 0 ? 'bg-gradient-to-br from-indigo-500 to-violet-500 text-white' : 'bg-slate-100 text-slate-400' }}">
                                        <x-dynamic-component :component="$row['child'] ? 'heroicon-o-document-text' : 'heroicon-o-squares-2x2'" class="h-4 w-4" />
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-slate-800">{{ $row['label'] }}</p>
                                        <p class="truncate font-mono text-[11px] text-slate-400">{{ $module }}.*</p>
                                    </div>

                                    <div class="flex gap-1.5">
                                        @foreach (\App\Livewire\Admin\UserManagement\Roles\ApplyPermission::ACTIONS as $action)
                                            @php $on = in_array("{$module}.{$action}", $selected, true); @endphp
                                            <button type="button" wire:key="p-{{ $module }}-{{ $action }}"
                                                wire:click="toggle('{{ $module }}.{{ $action }}')"
                                                role="switch" aria-checked="{{ $on ? 'true' : 'false' }}"
                                                class="w-[68px] rounded-lg border px-2 py-1.5 text-center text-[11px] font-semibold transition active:scale-95 {{ $on ? $actionStyles[$action]['on'] : $actionStyles[$action]['off'] }}">
                                                {{ trans_json(ucfirst($action)) }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <button type="button" wire:click="toggleModule('{{ $module }}')"
                                        class="hidden w-14 rounded-lg px-2 py-1.5 text-xs font-semibold transition active:scale-95 sm:block {{ $onCount === count($names) ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                                        {{ $onCount === count($names) ? trans_json('Clear') : trans_json('All') }}
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <div class="px-6 py-20 text-center">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-50 to-fuchsia-50 text-indigo-400">
                                <x-heroicon-o-squares-2x2 class="h-8 w-8" />
                            </div>
                            <p class="mt-4 text-sm font-semibold text-slate-700">{{ trans_json('No menus found') }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ trans_json('Try a different search, or check that menus have a permission name.') }}</p>
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
                    <p class="mt-1 text-4xl font-bold text-slate-900">{{ $percent }}<span class="text-xl text-slate-400">%</span></p>

                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-fuchsia-500 transition-all duration-500"
                            style="width: {{ $percent }}%"></div>
                    </div>

                    <p class="mt-3 text-xs text-slate-500">
                        <span class="font-semibold text-slate-700">{{ $this->selectedCount }}</span>
                        {{ trans_json('of') }} {{ count($this->allNames) }} {{ trans_json('permissions selected') }}
                    </p>

                    <div class="mt-4 flex flex-wrap gap-1.5">
                        @foreach (\App\Livewire\Admin\UserManagement\Roles\ApplyPermission::ACTIONS as $action)
                            @php
                                $count = collect($this->modules)->filter(fn ($m) => in_array("{$m}.{$action}", $selected, true))->count();
                            @endphp
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                {{ trans_json(ucfirst($action)) }} {{ $count }}/{{ count($this->modules) }}
                            </span>
                        @endforeach
                    </div>

                    @if ($this->hasChanges)
                        <p class="mt-4 flex items-center gap-2 rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700">
                            <x-heroicon-o-exclamation-triangle class="h-4 w-4" />
                            {{ trans_json('You have unsaved changes.') }}
                        </p>
                    @endif

                    <p class="mt-4 flex items-start gap-2 rounded-xl bg-indigo-50/60 p-3 text-xs text-indigo-700">
                        <x-heroicon-o-information-circle class="mt-0.5 h-4 w-4 shrink-0" />
                        {{ trans_json('Turning on Create, Edit, Delete or Manage also turns on View. Turning off View clears the rest.') }}
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                    <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save"
                        @disabled(! $this->hasChanges)
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-fuchsia-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:shadow-xl active:scale-95 disabled:cursor-not-allowed disabled:opacity-50">
                        <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                            <x-heroicon-o-check class="h-5 w-5" /> {{ trans_json('Save permissions') }}
                        </span>
                        <span wire:loading wire:target="save">{{ trans_json('Saving...') }}</span>
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
