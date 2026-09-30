<div x-show="sidebarMobileOpen"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     x-cloak
     @click="sidebarMobileOpen = false"
     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden">
</div>

<aside :class="{
        'w-64': sidebarExpanded,
        'w-20': !sidebarExpanded,
        'translate-x-0': sidebarMobileOpen,
        '-translate-x-full lg:translate-x-0': !sidebarMobileOpen
       }"
       class="fixed lg:static inset-y-0 left-0 z-50 bg-white dark:bg-slate-900 flex flex-col transition-all duration-300 ease-in-out border-r border-slate-200 dark:border-slate-800 shadow-2xl lg:shadow-none">

    <!-- HEADER LOGO -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-slate-100 dark:border-slate-800">
        <a href="{{ route('dashboard') ?? '/dashboard' }}" class="flex items-center space-x-3 overflow-hidden group">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30 shrink-0 group-hover:scale-105 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <span class="font-extrabold text-base sm:text-lg tracking-tight text-slate-900 dark:text-white block leading-none">e-Office</span>
                <span class="text-[11px] sm:text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Ridogalih</span>
            </div>
        </a>
    </div>

    <!-- NAVIGATION MENU -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1.5 scrollbar-hide">

        <!-- SEARCH BAR MOBILE -->
        <div class="lg:hidden pb-4 mb-2 border-b border-slate-100 dark:border-slate-800">
            <button @click="searchModalOpen = true; sidebarMobileOpen = false" class="w-full flex items-center justify-between bg-slate-50 dark:bg-slate-800/80 text-slate-500 dark:text-slate-400 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition-all shadow-sm focus:outline-none">
                <div class="flex items-center space-x-2.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span class="text-sm font-medium">Cari menu, fitur...</span>
                </div>
            </button>
        </div>

        <!-- RENDER MENU DINAMIS -->
        @foreach($menuNavigations as $menu)
            <div x-data="{ openMenu: {{ $menu['active'] && $menu['children'] !== [] ? 'true' : 'false' }} }" class="space-y-1">
                <a href="{{ $menu['children'] !== [] ? '#' : ($menu['url'] ?? '#') }}"
                   @if($menu['children'] !== []) @click.prevent="openMenu = !openMenu; sidebarExpanded = true" @endif
                   class="flex items-center justify-between px-3 py-3 rounded-xl transition-all duration-200 group {{ $menu['active'] ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-blue-600 dark:hover:text-blue-400' }}"
                   title="{{ $menu['name'] }}">

                    <div class="flex items-center">
                        <div class="w-6 h-6 flex-shrink-0 flex items-center justify-center text-[1.15rem]">
                            <i class="{{ $menu['icon'] }}"></i>
                        </div>
                        <span x-show="sidebarExpanded" class="ml-3 font-semibold text-sm whitespace-nowrap">{{ $menu['name'] }}</span>
                    </div>

                    @if($menu['children'] !== [])
                    <svg x-show="sidebarExpanded" :class="{'rotate-90': openMenu}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    @endif
                </a>

                @if($menu['children'] !== [])
                    <div x-show="openMenu && sidebarExpanded" x-collapse.duration.300ms class="ml-4 pl-3 border-l-2 border-slate-100 dark:border-slate-800 space-y-1 mt-1">
                        @foreach($menu['children'] as $child)
                            <a href="{{ $child['url'] }}"
                               class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors {{ $child['active'] ? 'text-blue-600 dark:text-blue-400 font-bold bg-blue-50 dark:bg-blue-900/20' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/30' }}">

                                @if($child['icon'] !== 'fas fa-folder')
                                    <div class="w-4 h-4 mr-2 flex items-center justify-center">
                                        <i class="{{ $child['icon'] }}"></i>
                                    </div>
                                @else
                                    <span class="w-1.5 h-1.5 rounded-full mr-2 shrink-0 {{ $child['active'] ? 'bg-blue-600 dark:bg-blue-400' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                                @endif

                                <span>{{ $child['name'] }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

        <!-- FALLBACK EMPTY STATE -->
        @if($menuNavigations === [])
            <div x-show="sidebarExpanded" class="px-3 py-6 text-xs text-slate-400 text-center italic bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-800">
                <i class="fas fa-lock text-lg mb-2 block opacity-50"></i>
                Belum ada menu tersedia untuk hak akses akun Anda.
            </div>
        @endif

    </nav>
</aside>
