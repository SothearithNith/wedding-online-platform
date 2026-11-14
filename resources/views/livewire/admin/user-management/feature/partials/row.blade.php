<div wire:key="row-{{ $item->id }}"
    class="group flex items-center gap-3 px-4 py-3.5 transition-colors hover:bg-indigo-50/30 sm:gap-4 sm:px-6 {{ $isChild ? 'bg-slate-50/40 pl-8 sm:pl-14' : '' }} {{ $item->is_active ? '' : 'opacity-60' }}">

    {{-- Icon --}}
    <div
        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl text-white shadow-md transition-transform group-hover:scale-105 {{ $isChild ? 'bg-gradient-to-br from-sky-500 to-indigo-500' : 'bg-gradient-to-br from-indigo-500 to-violet-500' }}">
        <x-dynamic-component :component="$this->icon($item->icon)" class="h-5 w-5" />
    </div>

    {{-- Names --}}
    <div class="min-w-0 flex-1">
        <div class="flex flex-wrap items-center gap-2">
            <p class="truncate font-semibold text-slate-900">{{ $item->name_en }}</p>
            @if ($item->name_km)
                <span class="truncate text-sm text-slate-500">{{ $item->name_km }}</span>
            @endif
            @if (!$isChild && $kidCount > 0)
                <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-semibold text-indigo-600">
                    {{ $kidCount }} {{ trans_json('sub-menus') }}
                </span>
            @endif
            @unless ($item->is_active)
                <span
                    class="rounded-full bg-slate-200 px-2 py-0.5 text-[10px] font-semibold text-slate-600">{{ trans_json('Hidden') }}</span>
            @endunless
        </div>

        <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs">
            <span class="font-mono text-slate-400">{{ $item->slug }}</span>

            @if ($item->route_name)
                <span class="hidden items-center gap-1 text-slate-400 md:inline-flex">
                    <x-heroicon-o-link class="h-3.5 w-3.5" /> {{ $item->route_name }}
                </span>
            @endif

            @if ($item->permission_name)
                <span
                    class="inline-flex items-center gap-1 rounded-md bg-violet-50 px-1.5 py-0.5 font-mono text-[11px] font-medium text-violet-700">
                    <x-heroicon-o-key class="h-3 w-3" /> {{ $item->permission_name }}
                </span>
            @elseif ($kidCount === 0)
                <span
                    class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-1.5 py-0.5 text-[11px] font-medium text-amber-700">
                    <x-heroicon-o-exclamation-triangle class="h-3 w-3" /> {{ trans_json('No permission') }}
                </span>
            @endif
        </div>
    </div>

    {{-- Reorder --}}
    <div class="hidden flex-col sm:flex">
        <button type="button" wire:click="move({{ $item->id }}, 'up')" @disabled(!$canUp)
            class="rounded p-0.5 text-slate-400 transition hover:bg-slate-100 hover:text-indigo-600 disabled:cursor-not-allowed disabled:opacity-25 disabled:hover:bg-transparent disabled:hover:text-slate-400"
            title="{{ trans_json('Move up') }}">
            <x-heroicon-s-chevron-up class="h-4 w-4" />
        </button>
        <button type="button" wire:click="move({{ $item->id }}, 'down')" @disabled(!$canDown)
            class="rounded p-0.5 text-slate-400 transition hover:bg-slate-100 hover:text-indigo-600 disabled:cursor-not-allowed disabled:opacity-25 disabled:hover:bg-transparent disabled:hover:text-slate-400"
            title="{{ trans_json('Move down') }}">
            <x-heroicon-s-chevron-down class="h-4 w-4" />
        </button>
    </div>

    {{-- Active switch --}}
    <button type="button"
        x-on:click="confirmToggle({{ $item->id }}, @js($item->name_en), {{ $item->is_active ? 'true' : 'false' }})"
        role="switch" aria-checked="{{ $item->is_active ? 'true' : 'false' }}"
        title="{{ $item->is_active ? trans_json('Active') : trans_json('Hidden') }}"
        class="relative hidden h-6 w-11 shrink-0 rounded-full transition-colors sm:inline-flex {{ $item->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}">
        <span
            class="absolute top-0.5 h-5 w-5 rounded-full bg-white shadow transition-all {{ $item->is_active ? 'left-[22px]' : 'left-0.5' }}"></span>
    </button>

    {{-- Actions --}}
    <div class="flex items-center gap-1.5">
        @unless ($isChild)
            <button type="button" wire:click="openCreate({{ $item->id }})" title="{{ trans_json('Add sub-menu') }}"
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 active:scale-95">
                <x-heroicon-o-plus class="h-4 w-4" />
            </button>
        @endunless

        <button type="button" wire:click="openEdit({{ $item->id }})" title="{{ trans_json('Edit') }}"
            class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 active:scale-95">
            <x-heroicon-o-pencil-square class="h-4 w-4" />
        </button>

        <button type="button" x-on:click="confirmDelete({{ $item->id }}, @js($item->name_en))"
            title="{{ trans_json('Delete') }}"
            class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-rose-200 bg-rose-50 text-rose-500 transition hover:bg-rose-100 active:scale-95">
            <x-heroicon-o-trash class="h-4 w-4" />
        </button>
    </div>
</div>
