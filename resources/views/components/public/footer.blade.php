{{-- resources/views/components/public/footer.blade.php --}}
<footer class="mt-8">

    {{-- CTA band --}}
    <section class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        <div
            class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-rose-500 via-pink-500 to-rose-600 px-6 py-12 text-center text-white shadow-xl shadow-rose-500/20 sm:px-12">
            <div class="pointer-events-none absolute -left-10 -top-10 h-40 w-40 rounded-full bg-white/10"></div>
            <div class="pointer-events-none absolute -bottom-12 -right-8 h-52 w-52 rounded-full bg-white/10"></div>

            <div class="relative mx-auto max-w-2xl">
                <span
                    class="inline-flex items-center gap-1.5 rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wider">
                    &hearts; Your special day
                </span>
                <h2 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">
                    Ready to create your wedding invitation?
                </h2>
                <p class="mt-3 text-rose-50">
                    Pick a beautiful template, add your details, and share it with your guests in minutes.
                </p>
                <div class="mt-7 flex flex-col items-center justify-center gap-3 sm:flex-row">
                    <a href="{{ url('/templates') }}"
                        class="inline-flex items-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-rose-600 shadow-lg transition hover:-translate-y-0.5 hover:bg-rose-50">
                        Browse templates
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </a>
                    <a href="mailto:hello@example.com"
                        class="inline-flex items-center rounded-full border border-white/50 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        Talk to us
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Main footer --}}
    <div class="mt-12 border-t border-slate-200 bg-white">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 md:grid-cols-2 lg:grid-cols-12 lg:px-8">

            {{-- Brand --}}
            <div class="lg:col-span-5">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 text-slate-900">
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-rose-500 to-pink-500 text-white shadow-lg shadow-rose-500/30">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path
                                d="M12 21s-7.5-4.6-9.6-9.2C1 8.6 2.8 5 6.3 5c2 0 3.5 1.1 4.3 2.6h2.8C14.2 6.1 15.7 5 17.7 5 21.2 5 23 8.6 21.6 11.8 19.5 16.4 12 21 12 21z" />
                        </svg>
                    </span>
                    <span class="text-lg font-bold tracking-tight">Wedding<span class="text-rose-500">Platform</span></span>
                </a>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-slate-500">
                    Create elegant online wedding invitations, share them with family and friends, and make your
                    celebration unforgettable.
                </p>

                {{-- Social --}}
                <div class="mt-6 flex items-center gap-3">
                    <a href="#" aria-label="Facebook"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition hover:-translate-y-0.5 hover:bg-rose-500 hover:text-white">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M13.5 21v-7.5h2.6l.4-3h-3V8.6c0-.9.3-1.5 1.6-1.5h1.5V4.4c-.3 0-1.2-.1-2.2-.1-2.3 0-3.9 1.4-3.9 4v2.2H8v3h2.5V21h3z" />
                        </svg>
                    </a>
                    <a href="#" aria-label="Telegram"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition hover:-translate-y-0.5 hover:bg-rose-500 hover:text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 3L3 10.5l6 2.5 2.5 6L21 3zM9 13l5-4" />
                        </svg>
                    </a>
                    <a href="#" aria-label="Instagram"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition hover:-translate-y-0.5 hover:bg-rose-500 hover:text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <rect x="3" y="3" width="18" height="18" rx="5" />
                            <circle cx="12" cy="12" r="4" />
                            <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="lg:col-span-3">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-900">Explore</h3>
                <ul class="mt-4 space-y-3 text-sm text-slate-500">
                    <li><a href="{{ url('/') }}" class="transition hover:text-rose-600">Home</a></li>
                    <li><a href="{{ url('/templates') }}" class="transition hover:text-rose-600">Templates</a></li>
                    <li>
                        @auth
                            <a href="{{ route('admin') }}" class="transition hover:text-rose-600">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="transition hover:text-rose-600">Login</a>
                        @endauth
                    </li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="lg:col-span-4">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-900">Contact us</h3>
                <ul class="mt-4 space-y-3 text-sm text-slate-500">
                    <li>
                        <a href="mailto:hello@example.com"
                            class="group inline-flex items-center gap-3 transition hover:text-rose-600">
                            <span
                                class="flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-500 transition group-hover:bg-rose-500 group-hover:text-white">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                    viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 7l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                </svg>
                            </span>
                            hello@example.com
                        </a>
                    </li>
                    <li>
                        <a href="tel:+85500000000"
                            class="group inline-flex items-center gap-3 transition hover:text-rose-600">
                            <span
                                class="flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-500 transition group-hover:bg-rose-500 group-hover:text-white">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                    viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 5a2 2 0 012-2h2.3a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.5 1.2l-1.6.8a11 11 0 005.3 5.3l.8-1.6a1 1 0 011.2-.5l3.3 1.1a1 1 0 01.7.95V19a2 2 0 01-2 2A16 16 0 013 5z" />
                                </svg>
                            </span>
                            +855 00 000 000
                        </a>
                    </li>
                    <li class="inline-flex items-center gap-3">
                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 21s7-5.5 7-11a7 7 0 10-14 0c0 5.5 7 11 7 11z" />
                                <circle cx="12" cy="10" r="2.5" />
                            </svg>
                        </span>
                        Phnom Penh, Cambodia
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-slate-100">
            <div
                class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-5 text-sm text-slate-500 sm:flex-row sm:px-6 lg:px-8">
                <p>&copy; {{ date('Y') }} Wedding Platform. All rights reserved.</p>
                <p class="flex items-center gap-1">
                    Made with <span class="text-rose-500">&hearts;</span> for your special day
                </p>
                <button type="button" onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 px-3.5 py-1.5 font-medium text-slate-600 transition hover:border-rose-300 hover:text-rose-600">
                    Back to top
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5M6 11l6-6 6 6" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</footer>
