@php
    $templates = collect($templates ?? []);
    $categories = $templates->pluck('category')->filter()->unique()->values();

    $features = [
        [
            'icon' => 'M4 5h16M4 12h16M4 19h10',
            'title' => 'Beautiful templates',
            'text' => 'Hand-picked designs for every style.',
        ],
        [
            'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
            'title' => 'Ready in minutes',
            'text' => 'Fill in your details and publish instantly.',
        ],
        [
            'icon' =>
                'M8.7 13.3l6.6 3.4M15.3 7.3l-6.6 3.4M18 8a3 3 0 100-6 3 3 0 000 6zM6 15a3 3 0 100-6 3 3 0 000 6zM18 22a3 3 0 100-6 3 3 0 000 6z',
            'title' => 'Share anywhere',
            'text' => 'One link for Telegram, Facebook, and more.',
        ],
    ];

    $steps = [
        ['Pick a template', 'Browse designs and choose the one that feels like you.'],
        ['Add your story', 'Names, date, photos, map and schedule. Everything is editable.'],
        ['Share one link', 'Send it to family and friends and track RSVPs live.'],
    ];

    $faqs = [
        [
            'Can I change the template later?',
            'Yes. You can edit all text, photos and details at any time, even after sharing.',
        ],
        ['How do guests RSVP?', 'Guests open your link and confirm in one tap. You see every reply in your dashboard.'],
        [
            'Does it work on phones?',
            'Every template is designed mobile-first, so it looks great in Telegram, Facebook and any browser.',
        ],
        ['How long does setup take?', 'Most couples finish in about 5 minutes.'],
    ];
@endphp

<div class="space-y-20 lg:space-y-28">

    {{-- ============ HERO ============ --}}
    <section class="relative grid items-center gap-14 lg:grid-cols-2 lg:gap-10">
        {{-- soft dotted backdrop --}}
        <div class="pointer-events-none absolute -inset-x-6 -top-10 -z-10 h-[120%] opacity-60 [mask-image:radial-gradient(ellipse_at_center,black,transparent_70%)]"
            style="background-image: radial-gradient(#fda4af 1px, transparent 1px); background-size: 22px 22px;"></div>

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
                class="title mt-6 text-4xl font-bold leading-[1.1] tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">
                {{ trans_json('Invite your guests to your') }}
                <span class="relative whitespace-nowrap">
                    <span
                        class="bg-gradient-to-r from-rose-500 via-pink-500 to-fuchsia-500 bg-clip-text text-transparent">
                        {{ trans_json('dream wedding') }}
                    </span>
                    <svg class="absolute -bottom-2 left-0 h-3 w-full text-rose-300" viewBox="0 0 200 12"
                        preserveAspectRatio="none" aria-hidden="true">
                        <path d="M2 8c40-6 100-6 196 0" fill="none" stroke="currentColor" stroke-width="4"
                            stroke-linecap="round" />
                    </svg>
                </span>
            </h1>

            <p class="mx-auto mt-7 max-w-xl text-lg leading-relaxed text-slate-600 lg:mx-0">
                {{ trans_json('Choose a beautiful template, add your story, photos and event details, then share one link with family and friends.') }}
            </p>

            <div class="mt-9 flex flex-col items-center gap-3 sm:flex-row sm:justify-center lg:justify-start">
                <a href="#templates"
                    class="group inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-rose-500 to-pink-500 px-7 py-3.5 text-base font-semibold text-white shadow-lg shadow-rose-500/30 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-rose-500/40">
                    {{ trans_json('Browse templates') }}
                    <svg class="h-5 w-5 transition group-hover:translate-y-0.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M6 13l6 6 6-6" />
                    </svg>
                </a>
                <a href="mailto:hello@example.com"
                    class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-7 py-3.5 text-base font-semibold text-slate-700 transition hover:border-rose-300 hover:text-rose-600">
                    {{ trans_json('Contact us') }}
                </a>
            </div>

            <dl
                class="mt-12 grid grid-cols-3 divide-x divide-slate-200 rounded-2xl border border-slate-200 bg-white/80 py-5 text-center shadow-sm backdrop-blur">
                <div class="px-2">
                    <dd class="text-2xl font-bold text-slate-900 sm:text-3xl">{{ max($templates->count(), 10) }}+</dd>
                    <dt class="text-xs text-slate-500 sm:text-sm">{{ trans_json('Templates') }}</dt>
                </div>
                <div class="px-2">
                    <dd class="text-2xl font-bold text-slate-900 sm:text-3xl">500+</dd>
                    <dt class="text-xs text-slate-500 sm:text-sm">{{ trans_json('Happy couples') }}</dt>
                </div>
                <div class="px-2">
                    <dd class="text-2xl font-bold text-slate-900 sm:text-3xl">5 min</dd>
                    <dt class="text-xs text-slate-500 sm:text-sm">{{ trans_json('To get started') }}</dt>
                </div>
            </dl>
        </div>

        {{-- Hero cards --}}
        <div id="hero-cards" class="relative mx-auto h-[30rem] w-full max-w-md select-none sm:h-[33rem]"
            aria-hidden="true" style="perspective: 1200px">

            <div class="absolute inset-0 -z-10">
                <div class="absolute -left-6 top-6 h-56 w-56 rounded-full bg-rose-300/50 blur-3xl"></div>
                <div class="absolute -right-4 top-28 h-56 w-56 rounded-full bg-fuchsia-300/40 blur-3xl"></div>
            </div>

            {{-- Hearts (kept subtle) --}}
            <svg class="hc-rise absolute bottom-10 left-[20%] h-4 w-4 text-rose-400" style="animation-delay:0s"
                viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M12 21s-7.5-4.6-9.6-9.2C1 8.6 2.8 5 6.3 5c2 0 3.5 1.1 4.3 2.6h2.8C14.2 6.1 15.7 5 17.7 5 21.2 5 23 8.6 21.6 11.8 19.5 16.4 12 21 12 21z" />
            </svg>
            <svg class="hc-rise absolute bottom-12 right-[18%] h-5 w-5 text-fuchsia-400" style="animation-delay:3.5s"
                viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M12 21s-7.5-4.6-9.6-9.2C1 8.6 2.8 5 6.3 5c2 0 3.5 1.1 4.3 2.6h2.8C14.2 6.1 15.7 5 17.7 5 21.2 5 23 8.6 21.6 11.8 19.5 16.4 12 21 12 21z" />
            </svg>
            <svg class="hc-twinkle absolute left-2 top-6 h-5 w-5 text-amber-400" viewBox="0 0 24 24"
                fill="currentColor">
                <path d="M12 0l2.4 9.6L24 12l-9.6 2.4L12 24l-2.4-9.6L0 12l9.6-2.4z" />
            </svg>
            <svg class="hc-twinkle absolute right-4 top-2 h-4 w-4 text-rose-400" style="animation-delay:1s"
                viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 0l2.4 9.6L24 12l-9.6 2.4L12 24l-2.4-9.6L0 12l9.6-2.4z" />
            </svg>

            <div id="hc-stage" class="absolute inset-0 transition-transform duration-200 ease-out">

                {{-- Back-left --}}
                <div class="hc-float-rev absolute left-0 top-14" style="animation-delay:.4s">
                    <div
                        class="h-60 w-40 -rotate-[8deg] overflow-hidden rounded-3xl bg-gradient-to-br from-amber-200 to-rose-300 shadow-xl ring-4 ring-white/80 transition duration-500 hover:-rotate-3 sm:h-72 sm:w-48">
                        <img src="{{ $heroBack }}" alt="" loading="lazy"
                            class="h-full w-full object-cover object-top" onerror="this.style.display='none'">
                    </div>
                </div>

                {{-- Back-right --}}
                <div class="hc-float-rev absolute right-0 top-20" style="animation-delay:1.2s">
                    <div
                        class="h-60 w-40 rotate-[8deg] overflow-hidden rounded-3xl bg-gradient-to-br from-fuchsia-300 to-purple-400 shadow-xl ring-4 ring-white/80 transition duration-500 hover:rotate-3 sm:h-72 sm:w-48">
                        <img src="{{ asset($heroRight) }}" alt="" loading="lazy"
                            class="h-full w-full object-cover object-top" onerror="this.style.display='none'">
                    </div>
                </div>

                {{-- Front (wrapper centers it so the float animation can't break centering) --}}
                <div class="hc-float absolute inset-x-0 top-0 mx-auto w-60 sm:w-64">
                    <div
                        class="hc-in relative h-[22rem] overflow-hidden rounded-[2rem] bg-gradient-to-br from-rose-200 to-pink-300 shadow-2xl shadow-rose-500/40 ring-4 ring-white sm:h-[27rem]">
                        <img src="{{ asset($heroFront) }}" alt="" loading="lazy"
                            class="absolute inset-0 h-full w-full object-cover object-top"
                            onerror="this.style.display='none'">
                        <div
                            class="pointer-events-none absolute inset-0 bg-gradient-to-b from-black/10 via-transparent to-black/60">
                        </div>
                        <div class="pointer-events-none absolute inset-0 overflow-hidden">
                            <div
                                class="hc-shine absolute inset-y-0 w-1/3 bg-gradient-to-r from-transparent via-white/35 to-transparent">
                            </div>
                        </div>

                        <span
                            class="absolute left-1/2 top-3 inline-flex -translate-x-1/2 items-center gap-1.5 whitespace-nowrap rounded-full border border-white/40 bg-white/20 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-white backdrop-blur-md">
                            <svg class="h-3 w-3 text-rose-200" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 21s-7.5-4.6-9.6-9.2C1 8.6 2.8 5 6.3 5c2 0 3.5 1.1 4.3 2.6h2.8C14.2 6.1 15.7 5 17.7 5 21.2 5 23 8.6 21.6 11.8 19.5 16.4 12 21 12 21z" />
                            </svg>
                            {{ trans_json('We are getting married') }}
                        </span>

                        <div
                            class="absolute inset-x-3 bottom-3 rounded-2xl border border-white/30 bg-white/10 p-3.5 text-center text-white shadow-xl shadow-black/20 backdrop-blur-lg">
                            <p class="text-2xl font-bold drop-shadow-sm">{{ trans_json('Sok') }} <span
                                    class="text-rose-300">&amp;</span> {{ trans_json('Dara') }}</p>
                            <p class="mt-1 text-xs text-white/85">14 &middot; 02 &middot; 2027 &middot;
                                {{ trans_json('Phnom Penh') }}</p>

                            <div
                                class="mt-2.5 grid grid-cols-4 gap-1.5 text-[9px] uppercase tracking-wider text-white/70">
                                <div class="rounded-lg bg-white/15 py-1"><b id="hc-d"
                                        class="block text-sm font-bold text-white">--</b>{{ trans_json('Days') }}
                                </div>
                                <div class="rounded-lg bg-white/15 py-1"><b id="hc-h"
                                        class="block text-sm font-bold text-white">--</b>{{ trans_json('Hrs') }}</div>
                                <div class="rounded-lg bg-white/15 py-1"><b id="hc-m"
                                        class="block text-sm font-bold text-white">--</b>{{ trans_json('Min') }}</div>
                                <div class="rounded-lg bg-white/15 py-1"><b id="hc-s"
                                        class="block text-sm font-bold text-white">--</b>{{ trans_json('Sec') }}</div>
                            </div>

                            <span class="relative mt-3 inline-block">
                                <span class="hc-ring absolute inset-0 rounded-full bg-rose-400"></span>
                                <span
                                    class="relative inline-block rounded-full bg-gradient-to-r from-rose-500 to-pink-500 px-6 py-1.5 text-xs font-semibold text-white shadow-lg shadow-rose-500/40">{{ trans_json('RSVP') }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Badge: guests --}}
                <div class="hc-float-rev absolute -left-1 bottom-20 sm:-left-5" style="animation-delay:.9s">
                    <div
                        class="flex items-center gap-2 rounded-2xl border border-white/70 bg-white/90 py-2 pl-2 pr-3.5 shadow-xl backdrop-blur-md">
                        <div class="flex -space-x-2">
                            <span
                                class="h-7 w-7 rounded-full border-2 border-white bg-gradient-to-br from-rose-300 to-pink-400"></span>
                            <span
                                class="h-7 w-7 rounded-full border-2 border-white bg-gradient-to-br from-amber-300 to-orange-400"></span>
                            <span
                                class="h-7 w-7 rounded-full border-2 border-white bg-gradient-to-br from-fuchsia-300 to-purple-400"></span>
                        </div>
                        <div class="leading-tight">
                            <p class="text-xs font-bold text-slate-800">+128</p>
                            <p class="text-[10px] text-slate-500">{{ trans_json('Guests confirmed') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Badge: share --}}
                <div class="hc-float absolute -right-1 top-12 sm:-right-4" style="animation-delay:1.6s">
                    <div
                        class="flex items-center gap-2 rounded-2xl border border-white/70 bg-white/90 px-3 py-2 shadow-xl backdrop-blur-md">
                        <span
                            class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <p class="text-[11px] font-semibold leading-tight text-slate-700">
                            {{ trans_json('Link shared') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ FEATURES ============ --}}
    <section class="grid gap-5 sm:grid-cols-3">
        @foreach ($features as $feature)
            <div
                class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-rose-200 hover:shadow-lg hover:shadow-rose-500/10">
                <span
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-500 transition group-hover:bg-rose-500 group-hover:text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}" />
                    </svg>
                </span>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ trans_json($feature['title']) }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ trans_json($feature['text']) }}</p>
            </div>
        @endforeach
    </section>

    {{-- ============ HOW IT WORKS ============ --}}
    <section>
        <div class="mx-auto max-w-2xl text-center">
            <p class="text-sm font-semibold uppercase tracking-widest text-rose-500">{{ trans_json('How it works') }}
            </p>
            <h2 class="title mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                {{ trans_json('Three simple steps') }}</h2>
        </div>

        <ol class="relative mt-12 grid gap-10 sm:grid-cols-3 sm:gap-6">
            <div class="absolute left-[16%] right-[16%] top-7 hidden border-t-2 border-dashed border-rose-200 sm:block"
                aria-hidden="true"></div>
            @foreach ($steps as $i => $step)
                <li class="relative text-center">
                    <span
                        class="relative mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-rose-500 to-pink-500 text-xl font-bold text-white shadow-lg shadow-rose-500/30 ring-8 ring-white">
                        {{ $i + 1 }}
                    </span>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ trans_json($step[0]) }}</h3>
                    <p class="mx-auto mt-1 max-w-xs text-sm text-slate-500">{{ trans_json($step[1]) }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    {{-- ============ TEMPLATES ============ --}}
    <section id="templates" class="scroll-mt-28" x-data="{ filter: 'all' }">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-rose-500">
                    {{ trans_json('Our collection') }}</p>
                <h2 class="title mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    {{ trans_json('Choose your template') }}</h2>
                <p class="mt-2 max-w-xl text-slate-500">
                    {{ trans_json('Pick a design you love. You can customize everything later.') }}</p>
            </div>

            @if ($categories->isNotEmpty())
                <div
                    class="-mx-4 flex gap-2 overflow-x-auto px-4 pb-1 sm:mx-0 sm:flex-wrap sm:overflow-visible sm:px-0">
                    <button type="button" @click="filter = 'all'"
                        :class="filter === 'all' ? 'bg-rose-500 text-white shadow-md shadow-rose-500/30 border-rose-500' :
                            'bg-white text-slate-600 hover:bg-slate-50 border-slate-200'"
                        class="shrink-0 rounded-full border px-4 py-2 text-sm font-medium transition">
                        {{ trans_json('All') }}
                    </button>
                    @foreach ($categories as $category)
                        @php $slug = \Illuminate\Support\Str::slug($category); @endphp
                        <button type="button" @click="filter = '{{ $slug }}'"
                            :class="filter === '{{ $slug }}' ?
                                'bg-rose-500 text-white shadow-md shadow-rose-500/30 border-rose-500' :
                                'bg-white text-slate-600 hover:bg-slate-50 border-slate-200'"
                            class="shrink-0 rounded-full border px-4 py-2 text-sm font-medium transition">
                            {{ trans_json($category) }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
            @forelse ($templates as $template)
                @php $slug = \Illuminate\Support\Str::slug($template->category ?? ''); @endphp
                <article wire:key="template-{{ $template->id }}"
                    x-show="filter === 'all' || filter === '{{ $slug }}'"
                    x-transition:enter="transition duration-300" x-transition:enter-start="opacity-0 scale-95"
                    class="group flex flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white transition duration-300 hover:-translate-y-1.5 hover:border-rose-200 hover:shadow-2xl hover:shadow-rose-500/10">

                    <div class="relative aspect-[4/5] overflow-hidden bg-gradient-to-br from-rose-50 to-pink-100">
                        <img src="{{ asset('storage/templates/' . basename($template->preview_src)) }}"
                            alt="{{ $template->name }}" loading="lazy"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                            onerror="this.onerror=null; ">
                        <div class="absolute inset-0 hidden
                            flex-col items-center justify-center gap-2 text-rose-200">
                        <x-heroicon-o-photo class="h-12 w-12" />
                        <span class="text-sm font-medium">{{ __('Preview unavailable') }}</span>
                    </div>

                    <div
                        class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                    </div>

                    @if ($template->category)
                        <span
                            class="absolute left-3 top-3 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-rose-600 shadow-sm backdrop-blur">
                            {{ trans_json($template->category) }}
                        </span>
                    @endif

                    <div
                        class="absolute inset-x-0 bottom-0 flex justify-center p-5 opacity-0 translate-y-3 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100">
                        <a href="{{ route('admin.templates.preview', $template) }}" target="_blank"
                            class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-slate-800 shadow-lg transition hover:bg-rose-50 hover:text-rose-600">
                            <x-heroicon-o-eye class="h-4 w-4" />
                            {{ __('Preview template') }}
                        </a>
                    </div>
        </div>

        <div class="flex flex-1 flex-col p-5">
            <h3 class="truncate text-lg font-bold tracking-tight text-slate-900" title="{{ $template->name }}">
                {{ $template->name }}</h3>

            @if ($template->description)
                <p class="mt-1.5 line-clamp-2 text-sm leading-5 text-slate-500">
                    {{ $template->description }}</p>
            @endif

            @if (($template->invitations_count ?? 0) > 0)
                <p class="mt-auto flex items-center gap-1.5 pt-4 text-xs text-slate-400">
                    <x-heroicon-o-heart class="h-4 w-4 text-rose-400" />
                    {{ trans_choice('{1} Used by :count couple|[2,*] Used by :count couples', $template->invitations_count, ['count' => $template->invitations_count]) }}
                </p>
            @endif
        </div>
        </article>
    @empty
        <div
            class="col-span-full flex flex-col items-center justify-center rounded-3xl border border-dashed border-rose-200 bg-rose-50/40 px-6 py-20 text-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-rose-400 shadow-sm">
                <x-heroicon-o-swatch class="h-8 w-8" />
            </div>
            <h3 class="mt-5 text-base font-bold text-slate-800">{{ __('No templates yet') }}</h3>
            <p class="mt-1 max-w-sm text-sm text-slate-500">
                {{ __('New designs are coming soon. Please check back shortly.') }}</p>
        </div>
        @endforelse
</div>
</section>

<section class="mx-auto w-full max-w-3xl">
    <div class="text-center">
        <p class="text-sm font-semibold uppercase tracking-widest text-rose-500">{{ trans_json('FAQ') }}</p>
        <h2 class="title mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
            {{ trans_json('Questions? We have answers') }}</h2>
    </div>

    <div class="mt-10 space-y-3">
        @foreach ($faqs as $faq)
            <details
                class="group rounded-2xl border border-slate-200 bg-white px-5 py-4 transition open:border-rose-200 open:shadow-md open:shadow-rose-500/5">
                <summary
                    class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-800 [&::-webkit-details-marker]:hidden">
                    {{ trans_json($faq[0]) }}
                    <span
                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-rose-50 text-rose-500 transition group-open:rotate-45">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" d="M12 5v14M5 12h14" />
                        </svg>
                    </span>
                </summary>
                <p class="mt-3 text-sm leading-relaxed text-slate-500">{{ trans_json($faq[1]) }}</p>
            </details>
        @endforeach
    </div>
</section>

<section
    class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-rose-500 via-pink-500 to-fuchsia-500 px-6 py-14 text-center text-white shadow-2xl shadow-rose-500/30 sm:px-12 sm:py-16">
    <div class="pointer-events-none absolute -left-10 -top-10 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
    <div class="pointer-events-none absolute -bottom-12 -right-8 h-56 w-56 rounded-full bg-white/10 blur-2xl">
    </div>
    <h2 class="title relative text-3xl font-bold tracking-tight sm:text-4xl">
        {{ trans_json('Ready to invite your guests?') }}</h2>
    <p class="relative mx-auto mt-3 max-w-xl text-white/85">
        {{ trans_json('Create your invitation in minutes and share it with everyone you love.') }}</p>
    <a href="#templates"
        class="relative mt-8 inline-flex items-center gap-2 rounded-full bg-white px-8 py-3.5 text-base font-semibold text-rose-600 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
        {{ trans_json('Browse templates') }}
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
        </svg>
    </a>
</section>

<style>
    @keyframes hc-float {

        0%,
        100% {
            transform: translateY(0)
        }

        50% {
            transform: translateY(-12px)
        }
    }

    @keyframes hc-float-rev {

        0%,
        100% {
            transform: translateY(0)
        }

        50% {
            transform: translateY(10px)
        }
    }

    @keyframes hc-twinkle {

        0%,
        100% {
            opacity: .2;
            transform: scale(.6)
        }

        50% {
            opacity: 1;
            transform: scale(1.1)
        }
    }

    @keyframes hc-rise {
        0% {
            transform: translateY(0) scale(.7);
            opacity: 0
        }

        15% {
            opacity: .9
        }

        100% {
            transform: translateY(-20rem) scale(1.1);
            opacity: 0
        }
    }

    @keyframes hc-pulse-ring {
        0% {
            transform: scale(1);
            opacity: .6
        }

        100% {
            transform: scale(1.8);
            opacity: 0
        }
    }

    @keyframes hc-shine {
        0% {
            transform: translateX(-120%) skewX(-20deg)
        }

        60%,
        100% {
            transform: translateX(260%) skewX(-20deg)
        }
    }

    @keyframes hc-in {
        from {
            opacity: 0;
            transform: translateY(30px) scale(.95)
        }

        to {
            opacity: 1;
            transform: none
        }
    }

    .hc-float {
        animation: hc-float 6s ease-in-out infinite
    }

    .hc-float-rev {
        animation: hc-float-rev 7s ease-in-out infinite
    }

    .hc-twinkle {
        animation: hc-twinkle 2.6s ease-in-out infinite
    }

    .hc-rise {
        animation: hc-rise 7s ease-in infinite
    }

    .hc-ring {
        animation: hc-pulse-ring 2s ease-out infinite
    }

    .hc-shine {
        animation: hc-shine 4.5s ease-in-out infinite
    }

    .hc-in {
        animation: hc-in .9s cubic-bezier(.2, .8, .2, 1) both
    }

    @media (prefers-reduced-motion: reduce) {

        .hc-float,
        .hc-float-rev,
        .hc-twinkle,
        .hc-rise,
        .hc-ring,
        .hc-shine,
        .hc-in {
            animation: none !important
        }
    }
</style>

<script>
    (function() {
        var root = document.getElementById('hero-cards');
        if (!root) return;

        // Countdown to the wedding day
        var target = new Date('2027-02-14T09:00:00+07:00').getTime();
        var els = {
            d: 'hc-d',
            h: 'hc-h',
            m: 'hc-m',
            s: 'hc-s'
        };
        Object.keys(els).forEach(function(k) {
            els[k] = document.getElementById(els[k]);
        });

        function pad(n) {
            return String(n).padStart(2, '0');
        }

        function tick() {
            var diff = Math.max(0, target - Date.now());
            els.d.textContent = Math.floor(diff / 864e5);
            els.h.textContent = pad(Math.floor(diff % 864e5 / 36e5));
            els.m.textContent = pad(Math.floor(diff % 36e5 / 6e4));
            els.s.textContent = pad(Math.floor(diff % 6e4 / 1e3));
        }
        tick();
        setInterval(tick, 1000);

        // Gentle mouse tilt (desktop only, respects reduced motion)
        var stage = document.getElementById('hc-stage');
        if (window.matchMedia('(hover: hover)').matches &&
            !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            root.addEventListener('mousemove', function(e) {
                var r = root.getBoundingClientRect();
                var x = (e.clientX - r.left) / r.width - .5;
                var y = (e.clientY - r.top) / r.height - .5;
                stage.style.transform = 'rotateY(' + (x * 10) + 'deg) rotateX(' + (-y * 8) + 'deg)';
            });
            root.addEventListener('mouseleave', function() {
                stage.style.transform = '';
            });
        }
    })();
</script>
</div>
