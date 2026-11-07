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

    {{-- ============ HERO ============ --}}
    <section class="relative grid items-center gap-12 lg:grid-cols-2 lg:gap-8">

        {{-- Text --}}
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

    {{-- ============ FEATURES ============ --}}
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

    {{-- ============ TEMPLATES ============ --}}
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

        @if ($templates->isEmpty())
            <div class="mt-10 rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                <p class="text-lg font-semibold text-slate-700">{{ trans_json('No templates yet') }}</p>
                <p class="mt-1 text-sm text-slate-500">{{ trans_json('Please check back soon.') }}</p>
            </div>
        @else
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($templates as $template)
                    <article
                        x-show="filter === 'all' || filter === '{{ \Illuminate\Support\Str::slug($template['category'] ?? '') }}'"
                        x-transition.opacity
                        class="group overflow-hidden rounded-3xl border border-slate-200 bg-white transition duration-300 hover:-translate-y-1.5 hover:border-rose-200 hover:shadow-xl hover:shadow-rose-500/10">

                        {{-- Preview --}}
                        <div class="relative aspect-[3/4] overflow-hidden">
                            @if (!empty($template['image']))
                                <img src="{{ asset($template['image']) }}" alt="{{ $template['name'] }}"
                                    loading="lazy"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div
                                    class="flex h-full w-full flex-col items-center justify-center bg-gradient-to-br {{ $gradients[$loop->index % count($gradients)] }} p-6 text-center text-white transition duration-500 group-hover:scale-105">
                                    <svg class="h-10 w-10 opacity-90" viewBox="0 0 24 24" fill="currentColor"
                                        aria-hidden="true">
                                        <path
                                            d="M12 21s-7.5-4.6-9.6-9.2C1 8.6 2.8 5 6.3 5c2 0 3.5 1.1 4.3 2.6h2.8C14.2 6.1 15.7 5 17.7 5 21.2 5 23 8.6 21.6 11.8 19.5 16.4 12 21 12 21z" />
                                    </svg>
                                    <p class="mt-3 text-xs font-semibold uppercase tracking-[0.3em] opacity-90">
                                        {{ trans_json('Wedding') }}
                                    </p>
                                    <p class="mt-2 text-2xl font-bold">{{ $template['name'] }}</p>
                                </div>
                            @endif

                            @if (!empty($template['category']))
                                <span
                                    class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-rose-600 backdrop-blur">
                                    {{ trans_json($template['category']) }}
                                </span>
                            @endif

                            {{-- Hover overlay --}}
                            <div
                                class="absolute inset-0 flex items-end justify-center bg-gradient-to-t from-slate-900/70 via-slate-900/10 to-transparent p-4 opacity-0 transition duration-300 group-hover:opacity-100">
                                <a href="{{ $template['url'] ?? '#' }}"
                                    class="w-full rounded-full bg-white px-4 py-2.5 text-center text-sm font-semibold text-slate-900 transition hover:bg-rose-50">
                                    {{ trans_json('Preview') }}
                                </a>
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="flex items-center justify-between gap-3 p-5">
                            <h3 class="truncate text-base font-semibold text-slate-900">{{ $template['name'] }}</h3>
                            <a href="{{ $template['url'] ?? '#' }}"
                                class="inline-flex shrink-0 items-center gap-1 text-sm font-semibold text-rose-600 transition hover:gap-2">
                                {{ trans_json('Use') }}
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
