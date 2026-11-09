{{-- resources/views/livewire/dashboard.blade.php --}}
@php
    $templates = collect($templates ?? []);
    $categories = $templates->pluck('category')->unique()->values();

    // Fallback gradients when a template has no preview image
    $gradients = [
        'from-rose-400 to-pink-500',
        'from-amber-300 to-rose-400',
        'from-fuchsia-400 to-purple-500',
        'from-emerald-300 to-teal-500',
        'from-sky-300 to-indigo-400',
        'from-orange-300 to-red-400',
    ];
@endphp

<div class="space-y-16 lg:space-y-24">
    <section class="relative grid items-center gap-12 lg:grid-cols-2 lg:gap-8">
        <div class="text-center lg:text-left">
            <span
                class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-white px-4 py-1.5 text-sm font-medium text-rose-600 shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span
                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-rose-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-rose-500"></span>
                </span>
                {{ trans_json('Online wedding invitations') }}
            </span>

            <h1
                class="title mt-6 text-4xl font-bold leading-tight tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">
                {{ trans_json('Invite your guests to your') }}
                <span class="bg-gradient-to-r from-rose-500 to-pink-500 bg-clip-text text-transparent">
                    {{ trans_json('dream wedding') }}
                </span>
            </h1>

            <p class="mx-auto mt-6 max-w-xl text-lg leading-relaxed text-slate-600 lg:mx-0">
                {{ trans_json('Choose a beautiful template, add your story, photos and event details, then share one link with family and friends.') }}
            </p>

            <div class="mt-8 flex flex-col items-center gap-3 sm:flex-row sm:justify-center lg:justify-start">
                <a href="#templates"
                    class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-rose-500 to-pink-500 px-7 py-3.5 text-base font-semibold text-white shadow-lg shadow-rose-500/30 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-rose-500/40">
                    {{ trans_json('Browse templates') }}
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M6 13l6 6 6-6" />
                    </svg>
                </a>
                <a href="mailto:hello@example.com"
                    class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-7 py-3.5 text-base font-semibold text-slate-700 transition hover:border-rose-300 hover:text-rose-600">
                    {{ trans_json('Contact us') }}
                </a>
            </div>

            {{-- Stats --}}
            <dl class="mt-12 grid grid-cols-3 gap-4 border-t border-slate-200 pt-8 text-center lg:text-left">
                <div>
                    <dt class="sr-only">{{ trans_json('Templates') }}</dt>
                    <dd class="text-2xl font-bold text-slate-900 sm:text-3xl">{{ max($templates->count(), 10) }}+</dd>
                    <p class="text-sm text-slate-500">{{ trans_json('Templates') }}</p>
                </div>
                <div>
                    <dt class="sr-only">{{ trans_json('Couples') }}</dt>
                    <dd class="text-2xl font-bold text-slate-900 sm:text-3xl">500+</dd>
                    <p class="text-sm text-slate-500">{{ trans_json('Happy couples') }}</p>
                </div>
                <div>
                    <dt class="sr-only">{{ trans_json('Setup time') }}</dt>
                    <dd class="text-2xl font-bold text-slate-900 sm:text-3xl">5 min</dd>
                    <p class="text-sm text-slate-500">{{ trans_json('To get started') }}</p>
                </div>
            </dl>
        </div>

        {{-- Visual: stacked invitation cards --}}
        <div class="relative mx-auto h-[26rem] w-full max-w-md sm:h-[30rem]" aria-hidden="true">
            <div class="absolute inset-0 -z-10 rounded-full bg-gradient-to-br from-rose-200/60 to-pink-200/60 blur-3xl">
            </div>

            {{-- Back card --}}
            <div
                class="absolute left-0 top-10 h-72 w-52 -rotate-6 rounded-3xl bg-gradient-to-br from-amber-200 to-rose-300 shadow-xl sm:h-80 sm:w-56">
            </div>
            {{-- Right card --}}
            <div
                class="absolute right-0 top-16 h-72 w-52 rotate-6 rounded-3xl bg-gradient-to-br from-fuchsia-300 to-purple-400 shadow-xl sm:h-80 sm:w-56">
            </div>
            {{-- Front card --}}
            <div
                class="absolute left-1/2 top-0 flex h-80 w-60 -translate-x-1/2 flex-col items-center justify-center rounded-3xl border border-white/60 bg-white p-6 text-center shadow-2xl shadow-rose-500/20 sm:h-96 sm:w-64">
                <span class="flex h-14 w-14 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 21s-7.5-4.6-9.6-9.2C1 8.6 2.8 5 6.3 5c2 0 3.5 1.1 4.3 2.6h2.8C14.2 6.1 15.7 5 17.7 5 21.2 5 23 8.6 21.6 11.8 19.5 16.4 12 21 12 21z" />
                    </svg>
                </span>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">
                    {{ trans_json('We are getting married') }}
                </p>
                <p class="mt-3 text-3xl font-bold text-slate-900">Sok <span class="text-rose-500">&amp;</span> Dara</p>
                <div class="my-5 h-px w-16 bg-rose-200"></div>
                <p class="text-sm text-slate-500">14 &middot; 02 &middot; 2027</p>
                <p class="mt-1 text-sm text-slate-500">Phnom Penh</p>
                <span
                    class="mt-6 rounded-full bg-gradient-to-r from-rose-500 to-pink-500 px-5 py-2 text-xs font-semibold text-white">
                    {{ trans_json('RSVP') }}
                </span>
            </div>
        </div>
    </section>

    <section class="grid gap-6 sm:grid-cols-3">
        @foreach ([['icon' => 'M4 5h16M4 12h16M4 19h10', 'title' => 'Beautiful templates', 'text' => 'Hand-picked designs for every style.'], ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Ready in minutes', 'text' => 'Fill in your details and publish instantly.'], ['icon' => 'M8.7 13.3l6.6 3.4M15.3 7.3l-6.6 3.4M18 8a3 3 0 100-6 3 3 0 000 6zM6 15a3 3 0 100-6 3 3 0 000 6zM18 22a3 3 0 100-6 3 3 0 000 6z', 'title' => 'Share anywhere', 'text' => 'One link for Telegram, Facebook, and more.']] as $feature)
            <div
                class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-rose-200 hover:shadow-lg hover:shadow-rose-500/10">
                <span
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-500 transition group-hover:bg-rose-500 group-hover:text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}" />
                    </svg>
                </span>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ trans_json($feature['title']) }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ trans_json($feature['text']) }}</p>
            </div>
        @endforeach
    </section>

    <section id="templates" class="scroll-mt-28" x-data="{ filter: 'all' }">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-rose-500">
                    {{ trans_json('Our collection') }}
                </p>
                <h2 class="title mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    {{ trans_json('Choose your template') }}
                </h2>
                <p class="mt-2 max-w-xl text-slate-500">
                    {{ trans_json('Pick a design you love. You can customize everything later.') }}
                </p>
            </div>

            {{-- Category filter --}}
            @if ($categories->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="filter = 'all'"
                        :class="filter === 'all' ? 'bg-rose-500 text-white shadow-md shadow-rose-500/30' :
                            'bg-white text-slate-600 hover:bg-slate-100'"
                        class="rounded-full border border-slate-200 px-4 py-2 text-sm font-medium transition">
                        {{ trans_json('All') }}
                    </button>
                    @foreach ($categories as $category)
                        <button type="button" @click="filter = '{{ \Illuminate\Support\Str::slug($category) }}'"
                            :class="filter === '{{ \Illuminate\Support\Str::slug($category) }}' ?
                                'bg-rose-500 text-white shadow-md shadow-rose-500/30' :
                                'bg-white text-slate-600 hover:bg-slate-100'"
                            class="rounded-full border border-slate-200 px-4 py-2 text-sm font-medium transition">
                            {{ trans_json($category) }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>


        <div wire:loading.remove wire:target="search,status,visibility,gotoPage,nextPage,previousPage"
            class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 mt-4">
            @forelse ($templates as $template)
                <div wire:key="template-{{ $template->id }}"
                    class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition-all duration-300 hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-500/10">
                    <div class="relative aspect-[4/5] overflow-hidden bg-slate-100">

                        @if ($template->preview_src)
                            <img src="{{ asset('storage/templates/' . basename($template->preview_src)) }}"loading="lazy"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden')">

                            <div
                                class="hidden absolute inset-0 flex flex-col items-center justify-center gap-3 text-slate-300">
                                <x-heroicon-o-photo class="h-12 w-12" />
                                <span class="text-sm font-medium">{{ __('Preview unavailable') }}</span>
                            </div>
                        @else
                            <div class="flex h-full flex-col items-center justify-center gap-3 text-slate-300">
                                <x-heroicon-o-photo class="h-12 w-12" />
                                <span class="text-sm font-medium">{{ __('No preview available') }}</span>
                            </div>
                        @endif

                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-950/50 via-transparent to-slate-950/10 opacity-60 transition-opacity group-hover:opacity-100">
                        </div>
                        <div class="absolute inset-x-3 top-3 flex items-center justify-between">
                            <span
                                class="inline-flex items-center gap-1.5 rounded-lg border border-white/20 bg-white/95 px-2.5 py-1.5 text-xs font-semibold shadow-sm backdrop-blur">
                                <span
                                    class="h-1.5 w-1.5 rounded-full {{ $template->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                <span class="{{ $template->is_active ? 'text-emerald-700' : 'text-slate-500' }}">
                                    {{ $template->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </span>

                            <span
                                class="inline-flex items-center gap-1 rounded-lg bg-slate-950/60 px-2.5 py-1.5 text-xs font-medium text-white backdrop-blur-md">
                                @if ($template->is_public)
                                    <x-heroicon-o-globe-alt class="h-3.5 w-3.5" />
                                    {{ __('Public') }}
                                @else
                                    <x-heroicon-o-lock-closed class="h-3.5 w-3.5" />
                                    {{ __('Private') }}
                                @endif
                            </span>
                        </div>

                        <div
                            class="absolute inset-x-0 bottom-0 flex justify-center p-5 opacity-0 translate-y-3 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100">
                            <a href="{{ route('templates.preview', $template) }}" target="_blank"
                                class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-800 shadow-lg transition hover:bg-indigo-50 hover:text-indigo-700">
                                <x-heroicon-o-eye class="h-4 w-4" />
                                {{ __('Preview template') }}
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-1 flex-col p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <h3 class="truncate text-base font-bold tracking-tight text-slate-900"
                                    title="{{ $template->name }}">
                                    {{ $template->name }}
                                </h3>
                                <p class="mt-1 truncate font-mono text-xs text-slate-400">
                                    {{ $template->key }}
                                </p>
                            </div>
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 transition group-hover:bg-indigo-600 group-hover:text-white">
                                <x-heroicon-o-swatch class="h-5 w-5" />
                            </span>
                        </div>

                        @if ($template->description)
                            <p class="mt-3 line-clamp-2 min-h-10 text-sm leading-5 text-slate-500">
                                {{ $template->description }}
                            </p>
                        @else
                            <div class="min-h-10"></div>
                        @endif
                        <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
                            <x-heroicon-o-envelope class="h-4 w-4 text-indigo-400" />
                            <span>
                                {{ trans_choice('{0} No invitations|{1} :count invitation|[2,*] :count invitations', $template->invitations_count, ['count' => $template->invitations_count]) }}
                            </span>
                        </div>
                    </div>
                </div>

            @empty
                <div
                    class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-20 text-center">

                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-500">
                        <x-heroicon-o-swatch class="h-8 w-8" />
                    </div>

                    <h3 class="mt-5 text-base font-bold text-slate-800">
                        {{ __('No templates found') }}
                    </h3>

                    <p class="mt-1 max-w-sm text-sm text-slate-500">
                        {{ __('Try another search, or create your first template.') }}
                    </p>

                    <a href="{{ route('templates.create') }}" wire:navigate
                        class="mt-5 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                        <x-heroicon-o-plus class="h-5 w-5" />
                        {{ __('Create template') }}
                    </a>
                </div>
            @endforelse
        </div>
    </section>
</div>
