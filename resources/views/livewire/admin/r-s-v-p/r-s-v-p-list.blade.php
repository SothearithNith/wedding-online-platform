<div>
    <div
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 p-6 text-white shadow-xl shadow-indigo-500/20 sm:p-8">

        <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-16 left-1/3 h-48 w-48 rounded-full bg-fuchsia-300/20 blur-3xl">
        </div>

        <div class="relative">
            <h1 class="text-2xl font-bold sm:text-3xl">
                {{ trans_json('RSVP Management') }}
            </h1>

            <p class="mt-1.5 max-w-xl text-sm text-indigo-100">
                {{ trans_json('Manage and track guest responses to wedding invitations.') }}
            </p>
        </div>

        <div class="relative mt-6 grid grid-cols-3 gap-3">
            @foreach ([[trans_json('Total'), $counts['all'], 'heroicon-o-heart'], [trans_json('Published'), $counts['published'], 'heroicon-o-check-circle'], [trans_json('Draft'), $counts['draft'], 'heroicon-o-globe-alt']] as [$label, $value, $icon])
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

    <div class="mt-4 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-slate-50/50 p-3">
            <div class="relative">
                <x-heroicon-o-magnifying-glass
                    class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />

                <input type="search" wire:model.live.debounce.300ms="search"
                    placeholder="{{ trans_json('Search wedding by name...') }}"
                    class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
            </div>
        </div>
        <div class="h-1 w-full overflow-hidden bg-slate-100">
            <div wire:loading wire:target="search,gotoPage,nextPage,previousPage"
                class="h-full w-1/3 animate-pulse rounded-full bg-gradient-to-r from-indigo-500 to-fuchsia-500">
            </div>
        </div>
        <div wire:loading.class="opacity-60" wire:target="search,gotoPage,nextPage,previousPage"
            class="overflow-x-auto transition-opacity">
            <table class="min-w-full text-sm">
                <thead>
                    <tr
                        class="bg-gradient-to-r from-slate-50 to-indigo-50/40 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-4">{{ trans_json('Weddings') }}</th>
                        <th class="px-6 py-4">{{ trans_json('Groom name') }}</th>
                        <th class="px-6 py-4">{{ trans_json('Bride name') }}</th>
                        <th class="px-6 py-4">{{ trans_json('Date') }}</th>
                        <th class="px-6 py-4 text-right">{{ trans_json('Actions') }}</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($weddings as $wedding)
                        <tr wire:key="wedding-{{ $wedding->id }}"
                            class="group transition-colors hover:bg-indigo-50/30">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 text-white shadow-md transition-transform group-hover:scale-105">
                                        <x-heroicon-o-heart class="h-5 w-5" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-slate-900"> {{ $wedding->label }} </p>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden px-6 py-4 md:table-cell">
                                {{ $wedding->people->firstWhere('role', 'groom')?->name ?? '-' }}
                            </td>
                            <td class="hidden px-6 py-4 md:table-cell">
                                {{ $wedding->people->firstWhere('role', 'bride')?->name ?? '-' }}
                            </td>
                            <td class="hidden px-6 py-4 md:table-cell">
                                {{ $wedding->event_date }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end">
                                    <a href="{{ route('rsvp.detail', $wedding->id) }}" wire:navigate
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-indigo-200 bg-indigo-50 px-3.5 py-2 text-xs font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100 active:scale-95">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                        {{ trans_json('View Detail') }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-20 text-center">
                                <div
                                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-50 to-fuchsia-50 text-indigo-400">
                                    <x-heroicon-o-clipboard-document-list class="h-8 w-8" />
                                </div>

                                <p class="mt-4 text-sm font-semibold text-slate-700">
                                    {{ trans_json('No weddings found') }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ trans_json('Try changing your search.') }}
                                </p>

                                @if ($search !== '')
                                    <button type="button" wire:click="$set('search', '')"
                                        class="mt-4 rounded-xl bg-indigo-50 px-4 py-2 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-100">
                                        {{ trans_json('Clear search') }}
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-3">
            @if ($weddings->hasPages())
                {{ $weddings->links() }}
            @else
                <p class="text-xs text-slate-500">
                    {{ trans_choice(':count wedding|:count weddings', $weddings->total(), ['count' => $weddings->total()]) }}
                </p>
            @endif
        </div>
    </div>
</div>
