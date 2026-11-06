<footer class="border-t border-slate-200 bg-white px-4 py-4 sm:px-6 lg:px-8">
    <div class="flex flex-col items-center justify-between gap-2 text-center sm:flex-row sm:text-left">
        <p class="text-xs text-slate-500">
            &copy; {{ date('Y') }} <span class="font-semibold text-slate-700">Wedding Platform</span>.
            {{ __('All rights reserved.') }}
        </p>

        <p class="flex items-center gap-1.5 text-xs text-slate-400">
            {{ __('Made with') }}
            <x-heroicon-s-heart class="h-3.5 w-3.5 animate-pulse text-rose-400" />
            {{ __('for better wedding management.') }}
        </p>
    </div>
</footer>
