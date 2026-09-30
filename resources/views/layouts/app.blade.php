<!DOCTYPE html>
<html lang="id" x-data="themeHandler()" :class="{ 'dark': isDark }" x-init="initTheme()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - e-Office Desa Ridogalih</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Mencegah FOUC (Flash of Unstyled Content) saat ganti tema
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }

        .bg-pattern {
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .dark .bg-pattern {
            background-image: radial-gradient(#334155 1px, transparent 1px);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        /* Scrollbar Elegan Level OS */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #64748b; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-sans antialiased overflow-hidden selection:bg-blue-600 selection:text-white"
      x-data="{ sidebarExpanded: true, sidebarMobileOpen: false, searchModalOpen: false }">

    <div class="flex h-screen overflow-hidden">

        @include('layouts.sidebar')

        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden bg-slate-50/50 dark:bg-slate-900/50">

            @include('layouts.header')

            <main class="p-4 md:p-6 2xl:p-10 mx-auto max-w-screen-2xl w-full animate-fade-in-up">
                @yield('content')
            </main>

            @include('layouts.footer')

        </div>
    </div>

    <script>
        function themeHandler() {
            return {
                theme: localStorage.getItem('theme') || 'system',
                isDark: document.documentElement.classList.contains('dark'),
                initTheme() {
                    this.applyTheme();
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                        if (this.theme === 'system') this.applyTheme();
                    });
                },
                setTheme(val) {
                    this.theme = val;
                    localStorage.setItem('theme', val);
                    this.applyTheme();
                },
                applyTheme() {
                    if (this.theme === 'dark') {
                        this.isDark = true;
                        document.documentElement.classList.add('dark');
                    } else if (this.theme === 'light') {
                        this.isDark = false;
                        document.documentElement.classList.remove('dark');
                    } else {
                        const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                        this.isDark = systemDark;
                        if (systemDark) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    }
                }
            }
        }


                // SISTEM PENGUNCI MULTI-TAB (E-Office Strict Security)
                    const channel = new BroadcastChannel('eoffice_session_channel');
                    const sessionId = Math.random().toString(36).substring(2);

                    // Beri tahu tab lain bahwa tab ini baru saja dibuka
                    channel.postMessage({ type: 'NEW_TAB_OPENED', id: sessionId });

                    // Dengarkan aktivitas tab lain
                    channel.onmessage = (event) => {
                        if (event.data.type === 'NEW_TAB_OPENED') {
                            // Jika ada tab baru dibuka, kunci layar tab saat ini
                            document.body.innerHTML = `
                                <div style="height: 100vh; width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: #0f172a; color: white; font-family: sans-serif;">
                                    <i class="fas fa-shield-alt" style="font-size: 5rem; color: #ef4444; margin-bottom: 20px;"></i>
                                    <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">Pelanggaran Keamanan Sesi</h1>
                                    <p style="color: #94a3b8;">Aplikasi E-Office ini tidak mengizinkan akses melalui banyak tab/jendela sekaligus.</p>
                                    <p style="color: #94a3b8;">Sistem mendeteksi tab baru telah dibuka. Harap gunakan tab yang baru.</p>
                                </div>
                            `;
                            // Informasikan kembali ke tab baru bahwa kita sudah menutup diri
                            channel.postMessage({ type: 'OLD_TAB_LOCKED' });
                        }
                    };

    </script>

    <script defer src="{{ asset('assets/vendor/alpine/collapse.min.js') }}"></script>
    <script defer src="{{ asset('assets/vendor/alpine/alpine.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
</body>
</html>
