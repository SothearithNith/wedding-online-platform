{{-- resources/views/livewire/admin/dashboard.blade.php --}}
@php
    // Placeholder data. Replace with public properties from your Livewire component.
    $coupleName = 'Sokha & Dara';
    $weddingDate = \Carbon\Carbon::now()->addDays(64)->setTime(16, 0);
    $daysLeft = max(
        0,
        (int) now()
            ->startOfDay()
            ->diffInDays($weddingDate->copy()->startOfDay(), false),
    );

    $guestsInvited = 180;
    $guestsAttending = 112;
    $guestsDeclined = 14;
    $guestsPending = $guestsInvited - $guestsAttending - $guestsDeclined;
    $rsvpRate = $guestsInvited ? round((($guestsAttending + $guestsDeclined) / $guestsInvited) * 100) : 0;

    $stats = [
        ['label' => 'Invited', 'value' => $guestsInvited, 'note' => 'guests on your list'],
        ['label' => 'Attending', 'value' => $guestsAttending, 'note' => 'confirmed so far'],
        ['label' => 'Declined', 'value' => $guestsDeclined, 'note' => 'unable to come'],
        ['label' => 'Waiting', 'value' => $guestsPending, 'note' => 'no reply yet'],
    ];

    $recent = [
        ['name' => 'Chan Vannak', 'status' => 'attending', 'party' => 2, 'time' => '12 min ago'],
        ['name' => 'Srey Pich', 'status' => 'attending', 'party' => 1, 'time' => '1 hour ago'],
        ['name' => 'Lim Sothea', 'status' => 'declined', 'party' => 0, 'time' => '3 hours ago'],
        ['name' => 'Keo Rithy', 'status' => 'attending', 'party' => 4, 'time' => 'Yesterday'],
        ['name' => 'Heng Sreyneang', 'status' => 'attending', 'party' => 2, 'time' => 'Yesterday'],
    ];

    $tasks = [
        ['label' => 'Choose an invitation template', 'done' => true],
        ['label' => 'Add the ceremony and reception details', 'done' => true],
        ['label' => 'Import your guest list', 'done' => true],
        ['label' => 'Send invitations', 'done' => false],
        ['label' => 'Confirm final headcount with the venue', 'done' => false],
    ];
    $tasksDone = collect($tasks)->where('done', true)->count();

    $actions = [
        ['label' => 'Add guests', 'hint' => 'Type in or import a list', 'href' => '#', 'icon' => 'M12 5v14M5 12h14'],
        [
            'label' => 'Edit invitation',
            'hint' => 'Change text, photos, music',
            'href' => '#',
            'icon' => 'M4 20h4L19 9l-4-4L4 16v4z',
        ],
        [
            'label' => 'Share link',
            'hint' => 'Copy your invitation URL',
            'href' => '#',
            'icon' => 'M10 14a4 4 0 005.7 0l3-3a4 4 0 00-5.7-5.7l-1 1M14 10a4 4 0 00-5.7 0l-3 3A4 4 0 0011 18.7l1-1',
        ],
        [
            'label' => 'Preview',
            'hint' => 'See what guests see',
            'href' => '#',
            'icon' => 'M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12zM12 15a3 3 0 100-6 3 3 0 000 6z',
        ],
    ];
@endphp

<div class="space-y-8">

    {{-- ============ HEADER ============ --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm text-slate-500">Welcome back</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                {{ $coupleName }}
            </h1>
        </div>

        <a href="#"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-500 focus-visible:ring-offset-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
            </svg>
            Add guests
        </a>
    </div>

    {{-- ============ HERO: COUNTDOWN ============ --}}
    <section aria-labelledby="countdown-title"
        class="relative overflow-hidden rounded-2xl bg-slate-900 p-6 text-white sm:p-8">
        {{-- soft rings, decorative --}}
        <svg class="pointer-events-none absolute -right-10 -top-10 h-64 w-64 text-rose-500/20" viewBox="0 0 200 200"
            fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <circle cx="100" cy="100" r="95" />
            <circle cx="100" cy="100" r="70" />
            <circle cx="100" cy="100" r="45" />
        </svg>

        <div class="relative grid gap-6 sm:grid-cols-[auto,1fr] sm:items-center sm:gap-10">
            <div class="flex items-baseline gap-3">
                <span class="text-6xl font-bold leading-none tracking-tight sm:text-7xl">{{ $daysLeft }}</span>
                <span class="text-lg text-slate-300">days to go</span>
            </div>

            <div class="sm:border-l sm:border-white/15 sm:pl-10">
                <h2 id="countdown-title" class="text-lg font-semibold">
                    {{ $weddingDate->translatedFormat('l, j F Y') }}
                </h2>
                <p class="mt-1 text-sm text-slate-300">
                    Ceremony at {{ $weddingDate->format('g:i A') }}
                </p>

                <div class="mt-5">
                    <div class="flex items-center justify-between text-xs text-slate-300">
                        <span>{{ $rsvpRate }}% of guests have replied</span>
                        <span>{{ $guestsAttending + $guestsDeclined }} of {{ $guestsInvited }}</span>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-white/15" role="progressbar"
                        aria-valuenow="{{ $rsvpRate }}" aria-valuemin="0" aria-valuemax="100"
                        aria-label="RSVP replies">
                        <div class="h-full rounded-full bg-rose-400" style="width: {{ $rsvpRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ STATS ============ --}}
    <section aria-label="Guest summary" class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ($stats as $stat)
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <p class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</p>
                <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $stat['value'] }}</p>
                <p class="mt-1 text-xs text-slate-400">{{ $stat['note'] }}</p>
            </div>
        @endforeach
    </section>

    {{-- ============ MAIN GRID ============ --}}
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Recent replies --}}
        <section class="rounded-xl border border-slate-200 bg-white lg:col-span-2" aria-labelledby="replies-title">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 id="replies-title" class="font-semibold text-slate-900">Latest replies</h2>
                <a href="#" class="text-sm font-medium text-rose-600 hover:text-rose-700">View all guests</a>
            </div>

            <ul class="divide-y divide-slate-100">
                @forelse ($recent as $guest)
                    <li class="flex items-center gap-4 px-5 py-3.5">
                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-50 text-sm font-semibold text-rose-600">
                            {{ mb_strtoupper(mb_substr($guest['name'], 0, 1)) }}
                        </span>

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-slate-900">{{ $guest['name'] }}</p>
                            <p class="text-xs text-slate-500">
                                @if ($guest['status'] === 'attending')
                                    Coming with {{ $guest['party'] }} {{ Str::plural('guest', $guest['party']) }}
                                @else
                                    Can't make it
                                @endif
                            </p>
                        </div>

                        <div class="flex flex-col items-end gap-1">
                            <span
                                class="rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $guest['status'] === 'attending' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $guest['status'] === 'attending' ? 'Attending' : 'Declined' }}
                            </span>
                            <span class="text-xs text-slate-400">{{ $guest['time'] }}</span>
                        </div>
                    </li>
                @empty
                    <li class="px-5 py-10 text-center text-sm text-slate-500">
                        No replies yet. Share your invitation link to get started.
                    </li>
                @endforelse
            </ul>
        </section>

        {{-- Checklist --}}
        <section class="rounded-xl border border-slate-200 bg-white" aria-labelledby="tasks-title">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 id="tasks-title" class="font-semibold text-slate-900">Setup checklist</h2>
                <span class="text-sm text-slate-500">{{ $tasksDone }}/{{ count($tasks) }}</span>
            </div>

            <ul class="space-y-1 p-3">
                @foreach ($tasks as $task)
                    <li class="flex items-start gap-3 rounded-lg px-2 py-2.5">
                        @if ($task['done'])
                            <span
                                class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-rose-500 text-white">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="3" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            <span class="text-sm text-slate-400 line-through">{{ $task['label'] }}</span>
                            <span class="sr-only">(done)</span>
                        @else
                            <span class="mt-0.5 h-5 w-5 shrink-0 rounded-full border-2 border-slate-300"></span>
                            <span class="text-sm font-medium text-slate-800">{{ $task['label'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </section>
    </div>

    {{-- ============ QUICK ACTIONS ============ --}}
    <section aria-labelledby="actions-title">
        <h2 id="actions-title" class="mb-3 font-semibold text-slate-900">Quick actions</h2>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($actions as $action)
                <a href="{{ $action['href'] }}"
                    class="group flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-4 transition hover:border-rose-300 hover:bg-rose-50/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-500">
                    <span
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-700 transition group-hover:bg-rose-100 group-hover:text-rose-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $action['icon'] }}" />
                        </svg>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-sm font-semibold text-slate-900">{{ $action['label'] }}</span>
                        <span class="block truncate text-xs text-slate-500">{{ $action['hint'] }}</span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>
</div>
