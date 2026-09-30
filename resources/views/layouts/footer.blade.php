<footer class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-t border-slate-200 dark:border-slate-800 py-4 px-4 sm:px-6 md:px-10 transition-colors duration-300 mt-auto">
    <div class="max-w-screen-2xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-xs sm:text-sm text-slate-500 dark:text-slate-400">

        <div class="flex items-center space-x-2 text-center sm:text-left">
            <span class="font-bold text-slate-700 dark:text-slate-200">E-Office Desa Ridogalih</span>
            <span>&copy; {{ date('Y') }} All rights reserved.</span>
        </div>

        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-1.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 px-2.5 py-1 rounded-full border border-emerald-100 dark:border-emerald-500/20 text-[11px] font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Sistem Normal</span>
            </div>

            <div class="hidden md:flex items-center space-x-2 text-[11px] font-medium text-slate-400">
                <span>PHP v{{ PHP_VERSION }}</span>
                <span>•</span>
                <span>Laravel v{{ app()->version() }}</span>
            </div>
        </div>

    </div>
</footer>
