<header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800 sticky top-0 z-40 transition-colors duration-300 shadow-sm">
    <div class="relative flex items-center justify-between px-4 py-3 md:px-6 h-16">

        <button @click="sidebarMobileOpen = !sidebarMobileOpen" aria-label="Toggle Sidebar" class="lg:hidden p-2 -ml-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>

        <div x-show="!sidebarMobileOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             class="absolute left-1/2 -translate-x-1/2 flex lg:hidden items-center space-x-2.5">
             <a href="{{ route('dashboard') ?? '/dashboard' }}" class="flex items-center space-x-3 overflow-hidden group">
                <div class="w-9 h-9 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30 shrink-0 group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <span class="font-extrabold text-base tracking-tight text-slate-900 dark:text-white block leading-none">E-Office</span>
                    <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Ridogalih</span>
                </div>
            </a>
        </div>

        <div class="hidden lg:flex items-center space-x-4">
            <button @click="sidebarExpanded = !sidebarExpanded" aria-label="Toggle Desktop Sidebar" class="text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 hover:border-blue-300 dark:hover:border-blue-700 transition-all shadow-sm focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path></svg>
            </button>

            <button @click="searchModalOpen = true" class="group flex items-center justify-between bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 px-4 py-2.5 rounded-xl border border-transparent hover:border-slate-300 dark:hover:border-slate-600 hover:bg-white dark:hover:bg-slate-800 w-72 text-sm transition-all shadow-inner focus:outline-none">
                <div class="flex items-center space-x-2.5">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Cari menu, fitur...</span>
                </div>
                <kbd class="hidden sm:inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-400 shadow-sm">ESC</kbd>
            </button>
        </div>

        <div class="flex items-center space-x-1 sm:space-x-3">

            <div x-data="{ openTheme: false }" class="relative hidden sm:block">
                <button @click="openTheme = !openTheme" aria-label="Toggle Theme" class="p-2.5 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    <svg x-show="theme === 'light'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <svg x-show="theme === 'dark'" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    <svg x-show="theme === 'system'" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </button>

                <div x-show="openTheme" @click.away="openTheme = false" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="absolute right-0 mt-2 w-40 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 py-1.5 text-sm z-50 font-medium">
                    <button @click="setTheme('light'); openTheme = false" class="flex items-center w-full px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 transition" :class="theme === 'light' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : ''">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg> Terang
                    </button>
                    <button @click="setTheme('dark'); openTheme = false" class="flex items-center w-full px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 transition" :class="theme === 'dark' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : ''">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg> Gelap
                    </button>
                    <button @click="setTheme('system'); openTheme = false" class="flex items-center w-full px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 transition" :class="theme === 'system' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : ''">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> Sistem
                    </button>
                </div>
            </div>

            <div x-data="{ notifOpen: false }" class="relative">
                <button @click="notifOpen = !notifOpen" aria-label="Notifications" class="p-2.5 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors relative focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-2 right-2.5 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500 border border-white dark:border-slate-900"></span>
                    </span>
                </button>

                <div x-show="notifOpen" @click.away="notifOpen = false" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                     class="absolute right-0 mt-3 w-80 sm:w-96 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden z-50 origin-top-right">

                    <div class="px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white">Notifikasi</h3>
                        <span class="text-xs text-blue-600 dark:text-blue-400 font-medium cursor-pointer hover:underline">Tandai dibaca</span>
                    </div>

                    <div class="max-h-72 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700/50">
                        <a href="#" class="flex p-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition bg-blue-50/50 dark:bg-blue-900/10">
                            <div class="flex-shrink-0 w-9 h-9 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Surat Masuk Baru</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">Ada surat masuk dari Kecamatan Cibarusah menunggu disposisi Anda.</p>
                                <p class="text-[10px] font-medium text-slate-400 mt-1">10 menit yang lalu</p>
                            </div>
                        </a>
                    </div>
                    <a href="#" class="block px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 text-center text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-blue-600 transition">
                        Lihat Semua Notifikasi
                    </a>
                </div>
            </div>

            <div class="hidden sm:block h-6 w-px bg-slate-200 dark:bg-slate-700 mx-1"></div>

            <div x-data="{ openProfile: false }" class="relative">
                <button @click="openProfile = !openProfile" aria-label="User Profile" class="flex items-center p-1 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md ring-2 ring-white dark:ring-slate-800">
                        {{ strtoupper(substr(Auth::user()->penduduk->nama_lengkap ?? Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                </button>

                <div x-show="openProfile" @click.away="openProfile = false" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                     class="absolute right-0 mt-3 w-64 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden z-50 origin-top-right">

                    <div class="sm:hidden px-4 py-3 bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700/80">
                        <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mb-2">Tema Tampilan</p>
                        <div class="flex items-center p-1 bg-slate-200/70 dark:bg-slate-950 rounded-lg">
                            <button @click="setTheme('light')" :class="theme === 'light' ? 'bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500'" class="flex justify-center px-2 py-1.5 rounded-md flex-1 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></button>
                            <button @click="setTheme('dark')" :class="theme === 'dark' ? 'bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500'" class="flex justify-center px-2 py-1.5 rounded-md flex-1 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg></button>
                            <button @click="setTheme('system')" :class="theme === 'system' ? 'bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500'" class="flex justify-center px-2 py-1.5 rounded-md flex-1 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></button>
                        </div>
                    </div>

                    <div class="px-4 py-4 border-b border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800">
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400 uppercase tracking-wider mb-1.5 border border-indigo-100 dark:border-indigo-500/20">
                            Group: {{ Auth::user()->group?->name ?? 'Sistem Utama' }}
                        </span>
                        <p class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ Auth::user()->penduduk->nama_lengkap ?? Auth::user()->name ?? 'User' }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium flex items-center">
                            Role: {{ Auth::user()->role?->name ?? 'Admin' }}
                        </p>
                    </div>

                    <div class="py-2 bg-white dark:bg-slate-800">
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Pengaturan Profil
                        </a>
                        <div class="h-px bg-slate-100 dark:bg-slate-700 my-1 mx-3"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar (Logout)
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>

<div x-show="searchModalOpen" x-cloak style="display: none;"
     class="fixed inset-0 z-[100] overflow-y-auto p-4 sm:p-6 md:p-20" role="dialog" aria-modal="true"
     x-init="$watch('searchModalOpen', val => { if(val) setTimeout(() => $refs.searchInput.focus(), 50) })"
     x-data="{
         searchQuery: '',
         menus: @js($authorizedSearchMenus),
         get filteredMenus() {
             if (this.searchQuery === '') return [];
             return this.menus.filter(menu => menu.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
         }
     }">

    <div x-show="searchModalOpen" @click="searchModalOpen = false"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

    <div x-show="searchModalOpen"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="mx-auto max-w-2xl transform divide-y divide-slate-100 dark:divide-slate-700 overflow-hidden rounded-2xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-black ring-opacity-5 transition-all relative">

        <div class="relative">
            <svg class="pointer-events-none absolute top-3.5 left-4 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input x-ref="searchInput" id="globalSearchInput" name="searchQuery" aria-label="Cari menu atau halaman" x-model="searchQuery" type="text" class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-0 sm:text-sm focus:outline-none" placeholder="Ketik nama menu atau halaman (dinamis)..." autocomplete="off">
            <button @click="searchModalOpen = false" class="absolute right-3 top-3 px-2 py-0.5 text-xs font-bold text-slate-400 bg-slate-100 dark:bg-slate-700 rounded shadow-sm hover:text-slate-600 dark:hover:text-slate-200">ESC</button>
        </div>

        <ul x-show="filteredMenus.length > 0" class="max-h-72 scroll-py-2 overflow-y-auto py-2 text-sm text-slate-800 dark:text-slate-200">
            <template x-for="menu in filteredMenus" :key="menu.name">
                <li>
                    <a :href="menu.url" class="flex items-center cursor-pointer select-none px-4 py-3 hover:bg-blue-600 hover:text-white group transition-colors">
                        <svg class="h-5 w-5 flex-none text-slate-400 group-hover:text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="menu.icon"></path>
                        </svg>
                        <span x-text="menu.name"></span>
                    </a>
                </li>
            </template>
        </ul>

        <div x-show="searchQuery !== '' && filteredMenus.length === 0" class="py-14 px-6 text-center text-sm sm:px-14">
            <svg class="mx-auto h-6 w-6 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="font-semibold text-slate-900 dark:text-white">Tidak ada hasil ditemukan</p>
            <p class="mt-1 text-slate-500">Coba gunakan kata kunci lain.</p>
        </div>
        <div x-show="searchQuery === ''" class="py-8 px-6 text-center text-sm text-slate-500 dark:text-slate-400">
            Mulai mengetik untuk mencari menu navigasi yang tersedia untuk akses Anda secara cepat.
        </div>
    </div>
</div>
