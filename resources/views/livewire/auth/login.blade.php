<div class="min-h-screen flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">

        {{-- Logo / Brand --}}
        <div class="text-center mb-8">

            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-violet-600 text-white shadow-lg shadow-violet-200 mb-4">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.7">

                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 21s-8-4.5-8-11a4.5 4.5 0 018-3 4.5 4.5 0 018 3c0 6.5-8 11-8 11z" />

                </svg>
            </div>

            <h1 class="text-2xl font-bold text-slate-900">
                Wedding Platform
            </h1>

            <p class="text-sm text-slate-500 mt-2">
                Administration Management System
            </p>

        </div>

        {{-- Login Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/50 p-8">

            <div class="mb-7">

                <h2 class="text-xl font-semibold text-slate-900">
                    Welcome back
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    {{ trans_json('Sign in to access your administrator account.') }}
                </p>

            </div>

            <form wire:submit="login" class="space-y-5">

                {{-- username --}}
                <div>

                    <label for="username" class="block text-sm font-medium text-slate-700 mb-2">
                        {{ trans_json('Username') }}
                    </label>

                    <input id="username" type="text" wire:model="username" placeholder="nsr" autocomplete="username"
                        autofocus required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10">

                    @error('username')
                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Password --}}
                <div>

                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                        Password
                    </label>

                    <input id="password" type="password" wire:model="password" placeholder="Enter your password"
                        autocomplete="current-password" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10">

                    @error('password')
                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between">

                    <label class="flex items-center gap-2 text-sm text-slate-600">

                        <input type="checkbox" wire:model="remember"
                            class="rounded border-slate-300 text-violet-600 focus:ring-violet-500">

                        Remember me

                    </label>

                </div>

                {{-- Submit --}}
                <button type="submit" wire:loading.attr="disabled" wire:target="login"
                    class="w-full rounded-xl bg-violet-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-200 transition hover:bg-violet-700 disabled:cursor-not-allowed disabled:opacity-60">

                    <span wire:loading.remove wire:target="login">
                        {{ trans_json('Sign In') }}
                    </span>

                    <span wire:loading wire:target="login">
                        Signing in...
                    </span>

                </button>

            </form>

        </div>

        <p class="text-center text-xs text-slate-400 mt-7">
            &copy; {{ date('Y') }} Wedding Platform. All rights reserved.
        </p>

    </div>

</div>
