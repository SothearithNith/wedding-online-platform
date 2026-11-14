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
            {{-- @foreach ([[trans_json('Total'), $counts['all'], 'heroicon-o-heart'], [trans_json('Published'), $counts['published'], 'heroicon-o-check-circle'], [trans_json('Draft'), $counts['draft'], 'heroicon-o-globe-alt']] as [$label, $value, $icon])
                <div class="flex items-center gap-3 rounded-2xl bg-white/15 p-3 backdrop-blur sm:p-4">
                    <div class="hidden h-10 w-10 items-center justify-center rounded-xl bg-white/20 sm:flex">
                        <x-dynamic-component :component="$icon" class="h-5 w-5" />
                    </div>
                    <div>
                        <p class="text-xl font-bold leading-none sm:text-2xl">{{ $value }}</p>
                        <p class="mt-1 text-xs text-indigo-100">{{ $label }}</p>
                    </div>
                </div>
            @endforeach --}}
        </div>
    </div>
</div>
