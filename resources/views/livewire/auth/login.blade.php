<div class="flex min-h-screen items-center justify-center bg-neutral-100 p-3 sm:p-6" x-data="{ ready: false, mx: 0, my: 0 }"
    x-init="requestAnimationFrame(() => ready = true)">
    <div class="grid w-full max-w-5xl rounded-[2rem] bg-white p-3 shadow-sm transition-all duration-700 ease-out motion-reduce:transition-none sm:rounded-[2.5rem] sm:p-4 lg:min-h-[600px] lg:grid-cols-2"
        :class="ready ? 'translate-y-0 scale-100 opacity-100' : 'translate-y-6 scale-95 opacity-0'"
        @mousemove="mx = (($event.clientX / window.innerWidth) - .5) * -24; my = (($event.clientY / window.innerHeight) - .5) * -24">
        <div class="flex items-center justify-center px-5 py-8 sm:px-10">
            <div class="w-full max-w-sm" x-data="{ show: false }">
                <div class="group flex items-center justify-center gap-2.5 text-violet-800 transition-all delay-150 duration-700 ease-out motion-reduce:transition-none"
                    :class="ready ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                    <svg class="h-8 w-8 transition-transform duration-300 group-hover:rotate-12 group-hover:scale-110"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 21s-8-4.5-8-11a4.5 4.5 0 018-3 4.5 4.5 0 018 3c0 6.5-8 11-8 11z" />
                    </svg>
                    <span class="text-2xl font-semibold tracking-tight">{{ trans_json('Wedding Platform') }}</span>
                </div>
                <div class="mt-8 text-center transition-all delay-300 duration-700 ease-out motion-reduce:transition-none"
                    :class="ready ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                    <h1 class="text-3xl font-bold tracking-tight text-slate-950">{{ trans_json('Welcome Back') }}</h1>
                    <p class="mt-1.5 text-sm text-slate-600">
                        {{ trans_json('Sign in to access your administrator account.') }}</p>
                </div>

                <form wire:submit="login" class="mt-8 space-y-3.5">
                    <div class="transition-all delay-[450ms] duration-700 ease-out motion-reduce:transition-none"
                        :class="ready ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                        <div
                            class="group rounded-xl bg-slate-100 px-4 py-2.5 ring-2 transition duration-200 focus-within:-translate-y-0.5 focus-within:bg-white focus-within:shadow-lg focus-within:shadow-violet-600/10 hover:bg-slate-200/70 focus-within:hover:bg-white
                            {{ $errors->has('username') ? 'ring-red-400' : 'ring-transparent focus-within:ring-violet-600' }}">
                            <label for="username"
                                class="block text-xs text-slate-500 transition-colors group-focus-within:text-violet-700">
                                {{ trans_json('Username') }}
                            </label>
                            <input id="username" type="text" wire:model="username" autocomplete="username" autofocus
                                required
                                class="mt-0.5 block w-full border-0 bg-transparent p-0 text-base text-slate-900 outline-none focus:ring-0">
                        </div>
                        @error('username')
                            <p class="mt-1.5 px-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="transition-all delay-[550ms] duration-700 ease-out motion-reduce:transition-none"
                        :class="ready ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                        <div
                            class="group relative rounded-xl bg-slate-100 px-4 py-2.5 ring-2 transition duration-200 focus-within:-translate-y-0.5 focus-within:bg-white focus-within:shadow-lg focus-within:shadow-violet-600/10 hover:bg-slate-200/70 focus-within:hover:bg-white
                            {{ $errors->has('password') ? 'ring-red-400' : 'ring-transparent focus-within:ring-violet-600' }}">
                            <label for="password"
                                class="block text-xs text-slate-500 transition-colors group-focus-within:text-violet-700">
                                {{ trans_json('Password') }}
                            </label>
                            <input id="password" :type="show ? 'text' : 'password'" wire:model="password"
                                autocomplete="current-password" required
                                class="mt-0.5 block w-full border-0 bg-transparent p-0 pr-10 text-base text-slate-900 outline-none focus:ring-0">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-500 transition hover:scale-110 hover:text-violet-700 focus:outline-none focus-visible:text-violet-700"
                                aria-label="Toggle password visibility">
                                <svg x-show="!show" x-transition.opacity.duration.150ms class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg x-show="show" x-transition.opacity.duration.150ms style="display: none"
                                    class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 3l18 18M10.6 5.1A10 10 0 0112 5c6.4 0 10 7 10 7a17 17 0 01-3.2 4.1M6.5 6.6A17 17 0 002 12s3.6 7 10 7c1.5 0 2.9-.4 4.1-.9M9.9 9.9a3 3 0 004.2 4.2" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 px-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="transition-all delay-[650ms] duration-700 ease-out motion-reduce:transition-none"
                        :class="ready ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                        <label class="group flex w-fit cursor-pointer items-center gap-2.5 pt-1 text-slate-800">
                            <input type="checkbox" wire:model="remember"
                                class="h-4 w-4 rounded border-slate-400 text-violet-800 transition group-hover:scale-110 focus:ring-violet-600">
                            {{ trans_json('Remember me') }}
                        </label>
                    </div>
                    <div class="transition-all delay-[750ms] duration-700 ease-out motion-reduce:transition-none"
                        :class="ready ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                        <button type="submit" wire:loading.attr="disabled" wire:target="login"
                            class="group flex w-full items-center justify-center gap-2 rounded-xl bg-violet-800 px-4 py-4 font-semibold text-white transition duration-200 hover:-translate-y-0.5 hover:bg-violet-900 hover:shadow-xl hover:shadow-violet-800/30 focus:outline-none focus-visible:ring-4 focus-visible:ring-violet-300 active:translate-y-0 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:translate-y-0">
                            <span wire:loading.remove wire:target="login" class="flex items-center gap-2">
                                {{ trans_json('Sign In') }}
                                <svg class="h-4 w-4 -translate-x-1 opacity-0 transition duration-200 group-hover:translate-x-0 group-hover:opacity-100"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6" />
                                </svg>
                            </span>
                            <span wire:loading wire:target="login" class="flex items-center gap-2">
                                <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="9" stroke="currentColor"
                                        stroke-opacity=".3" stroke-width="3" />
                                    <path d="M21 12a9 9 0 00-9-9" stroke="currentColor" stroke-width="3"
                                        stroke-linecap="round" />
                                </svg>
                                {{ trans_json('Signing in...') }}
                            </span>
                        </button>
                    </div>
                </form>

                <p class="mt-8 text-center text-sm text-slate-400 transition-opacity delay-[900ms] duration-700 motion-reduce:transition-none"
                    :class="ready ? 'opacity-100' : 'opacity-0'">
                    &copy; {{ date('Y') }} {{ trans_json('Wedding Platform') }}
                </p>
            </div>
        </div>
        <div class="relative hidden overflow-hidden rounded-[2rem] bg-violet-950 lg:block">
            <div class="absolute -inset-6 transition-[translate] duration-300 ease-out"
                :style="`translate: ${mx}px ${my}px`">
                <div class="h-full w-full bg-cover bg-center transition-transform duration-[2500ms] ease-out motion-reduce:transition-none"
                    :class="ready ? 'scale-100' : 'scale-125'"
                    style="background-image: url('{{ asset('assets/images/bg-login.jpg') }}');"></div>
            </div>

            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/5 to-black/20"></div>
            <p class="absolute right-10 top-10 max-w-sm text-right text-2xl font-semibold leading-snug text-white transition-all delay-[1100ms] duration-1000 ease-out motion-reduce:transition-none"
                :class="ready ? 'translate-x-0 opacity-100' : 'translate-x-8 opacity-0'">
                {{ trans_json('Every invitation, guest and RSVP in one place.') }}
            </p>
            <div class="absolute left-0 top-0 h-16 w-32 rounded-br-[2rem] bg-white"></div>
            <div class="absolute left-32 top-0 h-8 w-8 rounded-tl-[2rem] shadow-[-1rem_-1rem_0_1rem_#fff]"></div>
            <div class="absolute left-0 top-16 h-8 w-8 rounded-tl-[2rem] shadow-[-1rem_-1rem_0_1rem_#fff]"></div>
            <div class="absolute bottom-0 right-0 h-16 w-32 rounded-tl-[2rem] bg-white"></div>
            <div class="absolute bottom-0 right-32 h-8 w-8 rounded-br-[2rem] shadow-[1rem_1rem_0_1rem_#fff]"></div>
            <div class="absolute bottom-16 right-0 h-8 w-8 rounded-br-[2rem] shadow-[1rem_1rem_0_1rem_#fff]"></div>
        </div>
    </div>
</div>
