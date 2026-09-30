<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Office | Pemerintah Desa Ridogalih</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
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
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
    </style>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased relative overflow-x-hidden selection:bg-blue-600 selection:text-white">

    <div id="welcomePopup" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md transition-opacity duration-500">
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl shadow-2xl max-w-md w-full p-6 sm:p-8 relative text-center transform transition-all scale-100 animate-fade-in-up">
            <button onclick="closeWelcomePopup()" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-600 dark:hover:text-white rounded-full transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 text-xs font-bold mb-4">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-blue-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                Portal Resmi Digital Desa
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-3">Selamat Datang di Ridogalih</h3>
            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                Pemerintah Desa Ridogalih, Kecamatan Cibarusah, Kabupaten Bekasi, berkomitmen menghadirkan pelayanan publik modern, transparan, dan terintegrasi melalui platform E-Office.
            </p>
            <div class="grid grid-cols-2 gap-3 mb-6 text-left">
                <div class="p-3 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-700/60">
                    <span class="block text-xs text-slate-400 font-semibold uppercase">Wilayah</span>
                    <span class="text-sm font-bold text-slate-800 dark:text-slate-200">Cibarusah, Bekasi</span>
                </div>
                <div class="p-3 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-700/60">
                    <span class="block text-xs text-slate-400 font-semibold uppercase">Layanan</span>
                    <span class="text-sm font-bold text-slate-800 dark:text-slate-200">E-Surat & Kependudukan</span>
                </div>
            </div>
            <button onclick="closeWelcomePopup()" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-2xl shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5">
                Jelajahi Portal Desa &rarr;
            </button>
        </div>
    </div>

    <div class="absolute inset-0 z-0 bg-pattern opacity-50 pointer-events-none"></div>
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-400/20 dark:bg-blue-600/20 rounded-full blur-3xl opacity-70 pointer-events-none"></div>
    <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-emerald-400/20 dark:bg-emerald-600/20 rounded-full blur-3xl opacity-70 pointer-events-none"></div>

    <nav class="sticky top-0 z-40 w-full backdrop-blur-md bg-white/90 dark:bg-slate-900/90 border-b border-slate-200 dark:border-slate-800 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20 relative">

                <div class="flex items-center lg:hidden">
                    <button id="mobileMenuButton" onclick="toggleMobileMenu()" class="p-2 text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none rounded-xl bg-slate-100 dark:bg-slate-800 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                </div>

                <div class="absolute left-1/2 -translate-x-1/2 lg:static lg:left-auto lg:translate-x-0 flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <span class="font-extrabold text-base sm:text-lg tracking-tight text-slate-900 dark:text-white block leading-none">E-Office</span>
                        <span class="text-[11px] sm:text-xs font-semibold text-blue-600 dark:text-blue-400">Desa Ridogalih</span>
                    </div>
                </div>

                <div class="hidden lg:flex items-center gap-6 text-sm font-semibold text-slate-600 dark:text-slate-300">
                    <a href="#berita" class="hover:text-blue-600 transition">Berita</a>
                    <a href="#potensi" class="hover:text-blue-600 transition">Potensi Desa</a>
                    <a href="#loker" class="hover:text-blue-600 transition">Lowongan Kerja</a>
                    <a href="#statistik" class="hover:text-blue-600 transition">Infografis</a>
                    <a href="#lembaga" class="hover:text-blue-600 transition">Bagan Lembaga</a>
                    <a href="#hiburan" class="hover:text-blue-600 transition">Pojok Warga</a>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="relative">
                        <button onclick="toggleThemeDropdown()" id="themeMenuButton" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition flex items-center justify-center shadow-sm" title="Pilih Tema">
                            <svg id="iconLight" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <svg id="iconDark" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        </button>
                        <div id="themeDropdown" class="hidden absolute right-0 mt-2 w-36 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-xl py-2 z-50 text-xs font-semibold">
                            <button onclick="setTheme('light')" class="w-full px-4 py-2 text-left flex items-center gap-2.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition"><svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg> Terang</button>
                            <button onclick="setTheme('dark')" class="w-full px-4 py-2 text-left flex items-center gap-2.5 text-slate-700 dark:text-slate-300 hover:text-blue-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition"><svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg> Gelap</button>
                            <button onclick="setTheme('system')" class="w-full px-4 py-2 text-left flex items-center gap-2.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition border-t border-slate-100 dark:border-slate-700"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> Sistem</button>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md transition">Dashboard &rarr;</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 dark:text-slate-200 hover:text-blue-600 px-3 py-2 transition">Masuk</a>
                            <a href="{{ route('register') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-500/30 transition">Daftar Warga</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <div id="mobileMenu" class="hidden lg:hidden bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-6 py-4 space-y-3 shadow-xl">
            <a href="#berita" onclick="toggleMobileMenu()" class="block text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-blue-600">Berita</a>
            <a href="#potensi" onclick="toggleMobileMenu()" class="block text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-blue-600">Potensi Desa</a>
            <a href="#loker" onclick="toggleMobileMenu()" class="block text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-blue-600">Lowongan Kerja</a>
            <a href="#statistik" onclick="toggleMobileMenu()" class="block text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-blue-600">Infografis</a>
            <a href="#lembaga" onclick="toggleMobileMenu()" class="block text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-blue-600">Bagan Lembaga</a>
            <a href="#hiburan" onclick="toggleMobileMenu()" class="block text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-blue-600">Pojok Warga</a>
            <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex flex-col gap-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="w-full py-2.5 bg-blue-600 text-white text-center text-sm font-bold rounded-xl shadow">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="w-full py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-white text-center text-sm font-bold rounded-xl">Masuk Sistem</a>
                    <a href="{{ route('register') }}" class="w-full py-2.5 bg-blue-600 text-white text-center text-sm font-bold rounded-xl shadow">Daftar Akun Warga</a>
                @endauth
            </div>
        </div>
    </nav>

    <header class="relative z-10 flex flex-col items-center justify-center min-h-[80vh] px-4 sm:px-6 py-16 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800 text-blue-600 dark:text-blue-400 text-xs font-bold mb-8 animate-fade-in-up">
            Pusat Informasi & Pelayanan Terpadu Kabupaten Bekasi
        </div>
        <h1 class="max-w-4xl text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white mb-6 animate-fade-in-up leading-tight">
            Membangun Desa Ridogalih yang <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-emerald-500">Maju, Mandiri & Digital</span>
        </h1>
        <p class="max-w-2xl text-sm sm:text-lg text-slate-600 dark:text-slate-400 mb-10 animate-fade-in-up leading-relaxed">
            Akses layanan administrasi surat menyurat, informasi potensi pertanian & UMKM, lowongan pekerjaan, hingga data statistik demografi kependudukan dalam satu genggaman.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4 w-full sm:w-auto animate-fade-in-up">
            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-2xl shadow-xl shadow-blue-500/30 transition transform hover:-translate-y-1">
                Buat Akun Warga Sekarang
            </a>
            <a href="#potensi" class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-800 dark:text-white font-extrabold rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm transition">
                Jelajahi Potensi Desa
            </a>
        </div>
    </header>

    <section id="berita" class="py-20 px-4 sm:px-6 max-w-7xl mx-auto relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block mb-2">Warta Desa Terkini</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Berita & Pengumuman Resmi</h2>
            </div>
            <p class="text-sm text-slate-500 max-w-md">Informasi seputar kegiatan aparatur desa, musyawarah warga, program pembangunan, dan agenda penting lainnya.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm hover:shadow-lg transition group">
                <div class="h-48 bg-gradient-to-br from-blue-600 to-indigo-700 relative overflow-hidden flex items-center justify-center text-white font-bold text-lg p-6 text-center">
                    <span class="relative z-10">Penyaluran Bantuan Langsung Tunai (BLT) Desa Ridogalih</span>
                </div>
                <div class="p-6">
                    <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 block mb-2">10 Agustus 2026 • Pemerintahan</span>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 group-hover:text-blue-600 transition">Pemerintah Desa Salurkan BLT Dana Desa Tahap III</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-4">Pemerintah Desa Ridogalih kembali menyalurkan bantuan kepada Keluarga Penerima Manfaat (KPM) di aula kantor desa...</p>
                    <span class="text-sm font-bold text-blue-600 flex items-center gap-1">Baca Selengkapnya &rarr;</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm hover:shadow-lg transition group">
                <div class="h-48 bg-gradient-to-br from-emerald-600 to-teal-700 relative overflow-hidden flex items-center justify-center text-white font-bold text-lg p-6 text-center">
                    <span class="relative z-10">Program Ketahanan Pangan & Pertanian Modern</span>
                </div>
                <div class="p-6">
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 block mb-2">05 Agustus 2026 • Pertanian</span>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 group-hover:text-emerald-600 transition">Pelatihan Kelompok Tani Peningkatan Hasil Panen Padi</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-4">Dinas Pertanian bersama perangkat desa mengadakan sosialisasi penggunaan pupuk organik dan teknik pengairan efisien...</p>
                    <span class="text-sm font-bold text-emerald-600 flex items-center gap-1">Baca Selengkapnya &rarr;</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm hover:shadow-lg transition group">
                <div class="h-48 bg-gradient-to-br from-amber-600 to-orange-700 relative overflow-hidden flex items-center justify-center text-white font-bold text-lg p-6 text-center">
                    <span class="relative z-10">Pekan Olahraga & Seni Pemuda Desa</span>
                </div>
                <div class="p-6">
                    <span class="text-xs font-semibold text-amber-600 dark:text-amber-400 block mb-2">01 Agustus 2026 • Pemuda & Olahraga</span>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 group-hover:text-amber-600 transition">Karang Taruna Gelar Turnamen Sepak Bola Antar RW</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-4">Ajang silaturahmi antar pemuda dusun dalam rangka menyambut HUT RI diselenggarakan meriah di lapangan utama desa...</p>
                    <span class="text-sm font-bold text-amber-600 flex items-center gap-1">Baca Selengkapnya &rarr;</span>
                </div>
            </div>
        </div>
    </section>

    <section id="potensi" class="py-20 bg-white/60 dark:bg-slate-800/40 border-y border-slate-200 dark:border-slate-800 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block mb-2">Kekayaan & Keunggulan Wilayah</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mb-4">Potensi Unggulan Desa Ridogalih</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">Desa Ridogalih memiliki sumber daya alam subur, sektor UMKM yang berkembang aktif, serta SDM produktif.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition">
                    <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Pertanian & SDA</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Lahan persawahan teknis yang luas menjadikan komoditas padi dan hortikultura sebagai tulang punggung ekonomi agraris desa.</p>
                </div>
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition">
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">UMKM & Kreatif</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Ratusan pelaku usaha mikro mandiri mulai dari kuliner tradisional, konveksi rumahan, hingga produk kerajinan tangan lokal.</p>
                </div>
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition">
                    <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Pendidikan & SDM</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Fasilitas pendidikan dasar hingga menengah yang memadai, didukung generasi muda produktif berorientasi teknologi digital.</p>
                </div>
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition">
                    <div class="w-14 h-14 bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Olahraga & Kepemudaan</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Wadah pembinaan bakat olahraga pemuda desa melalui fasilitas lapangan sepak bola, voli, dan badminton yang aktif.</p>
                </div>
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition">
                    <div class="w-14 h-14 bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Kesehatan & Posyandu</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Layanan posyandu aktif di setiap dusun untuk memastikan kesehatan balita dan lansia terpelihara dengan baik.</p>
                </div>
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition">
                    <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Infrastruktur & Digital</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Akses jalan lingkungan yang tertata serta digitalisasi birokrasi pemerintahan desa melalui sistem E-Office.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="loker" class="py-20 px-4 sm:px-6 max-w-7xl mx-auto relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block mb-2">Bursa Kerja Warga</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Info Lowongan Pekerjaan (Loker)</h2>
            </div>
            <p class="text-sm text-slate-500 max-w-md">Informasi lowongan kerja dari perusahaan mitra, kawasan industri sekitar, maupun peluang wirausaha bagi warga lokal.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-800 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start gap-4 mb-4">
                        <div>
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950 px-3 py-1 rounded-full">Full Time</span>
                            <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white mt-2">Operator Produksi & Gudang</h3>
                            <p class="text-sm text-slate-500 font-medium">PT. Mitra Industri Cikarang</p>
                        </div>
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center text-slate-600 dark:text-slate-300 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Dibutuhkan segera tenaga kerja muda berdomisili lokal untuk penempatan di kawasan industri terdekat. Pendidikan min. SMA/SMK sederajat.</p>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-700 text-xs font-bold text-slate-500">
                    <span>Batas: 25 Agustus 2026</span>
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition">Lamar / Info &rarr;</a>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start gap-4 mb-4">
                        <div>
                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 px-3 py-1 rounded-full">Kemitraan UMKM</span>
                            <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white mt-2">Tenaga Pemasaran Digital & Admin</h3>
                            <p class="text-sm text-slate-500 font-medium">BUMDes Ridogalih Mandiri</p>
                        </div>
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center text-slate-600 dark:text-slate-300 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Pengelolaan toko digital BUMDes dan pemasaran produk unggulan pertanian lokal secara online. Menguasai media sosial & komputer dasar.</p>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-700 text-xs font-bold text-slate-500">
                    <span>Batas: 30 Agustus 2026</span>
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition">Lamar / Info &rarr;</a>
                </div>
            </div>
        </div>
    </section>

    <section id="statistik" class="py-20 bg-blue-900 text-white relative z-10 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-300 block mb-2">Data Kependudukan Real-Time</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white mb-4">Infografis Statistik & Demografi</h2>
                <p class="text-sm text-blue-200">Gambaran umum pertumbuhan penduduk, kelompok usia, dan pembagian wilayah dusun di Desa Ridogalih.</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/10">
                    <span class="block text-3xl sm:text-4xl font-extrabold text-white mb-1">8,450</span>
                    <span class="text-xs text-blue-200 font-semibold uppercase">Total Jiwa Penduduk</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/10">
                    <span class="block text-3xl sm:text-4xl font-extrabold text-white mb-1">2,310</span>
                    <span class="text-xs text-blue-200 font-semibold uppercase">Kepala Keluarga (KK)</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/10">
                    <span class="block text-3xl sm:text-4xl font-extrabold text-white mb-1">4</span>
                    <span class="text-xs text-blue-200 font-semibold uppercase">Wilayah Dusun Utama</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/10">
                    <span class="block text-3xl sm:text-4xl font-extrabold text-white mb-1">98%</span>
                    <span class="text-xs text-blue-200 font-semibold uppercase">Validitas Data NIK</span>
                </div>
            </div>
        </div>
    </section>

    <section id="lembaga" class="py-20 px-4 sm:px-6 max-w-7xl mx-auto relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block mb-2">Struktur Organisasi</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mb-4">Bagan Lembaga & Pemerintahan Desa</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400">Susunan kepengurusan lembaga resmi yang menaungi pelayanan dan pembangunan masyarakat Desa Ridogalih.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm text-center">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">Pemerintahan Desa</h3>
                <p class="text-xs text-slate-500 mb-4">Kepala Desa, Sekretaris Desa, Kasi & Kaur Pelayanan.</p>
                <span class="inline-block px-4 py-2 bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 text-xs font-bold rounded-xl">Eksekutif Pelayanan</span>
            </div>
            <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm text-center">
                <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">BPD Desa</h3>
                <p class="text-xs text-slate-500 mb-4">Badan Permusyawaratan Desa sebagai pengawas & legislatif desa.</p>
                <span class="inline-block px-4 py-2 bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 text-xs font-bold rounded-xl">Pengawas & Aspirasi</span>
            </div>
            <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm text-center">
                <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">Lembaga Kemasyarakatan</h3>
                <p class="text-xs text-slate-500 mb-4">Karang Taruna, PKK, RT/RW, dan BUMDes Ridogalih.</p>
                <span class="inline-block px-4 py-2 bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 text-xs font-bold rounded-xl">Pemberdayaan Warga</span>
            </div>
        </div>
    </section>

    <section id="hiburan" class="py-20 bg-slate-100 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block mb-2">Ruang Interaksi Warga</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mb-4">Pojok Inspirasi & Komunitas</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400 max-w-2xl mx-auto mb-10">
                "Desa yang maju adalah desa yang warganya rukun, kreatif, dan bahagia." Nikmati kutipan inspiratif minggu ini bersama warga.
            </p>
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-8 sm:p-12 rounded-3xl shadow-xl max-w-4xl mx-auto">
                <blockquote class="text-base sm:text-xl font-medium italic mb-6">
                    &ldquo;Gotong royong adalah nadi kehidupan masyarakat Ridogalih. Mari bersama kita wujudkan pelayanan desa yang cepat, bersih, dan melayani dengan sepenuh hati.&rdquo;
                </blockquote>
                <span class="font-extrabold block text-xs sm:text-sm tracking-widest uppercase text-blue-200">— Tokoh Masyarakat & Pemerintah Desa</span>
            </div>
        </div>
    </section>

    <footer class="relative z-10 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-12 text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <span class="font-extrabold text-slate-900 dark:text-white">Pemerintah Desa Ridogalih</span>
            </div>
            <p class="text-sm text-slate-500 font-medium">
                &copy; {{ date('Y') }} E-Office Desa Ridogalih. Seluruh Hak Cipta Dilindungi.
            </p>
        </div>
    </footer>

    <script>
        function closeWelcomePopup() {
            const popup = document.getElementById('welcomePopup');
            popup.classList.add('opacity-0', 'scale-95');
            setTimeout(() => { popup.style.display = 'none'; }, 300);
        }
        function toggleMobileMenu() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        }
        function toggleThemeDropdown() {
            document.getElementById('themeDropdown').classList.toggle('hidden');
        }
        function setTheme(mode) {
            const root = document.documentElement;
            if (mode === 'dark') {
                localStorage.theme = 'dark';
                root.classList.add('dark');
            } else if (mode === 'light') {
                localStorage.theme = 'light';
                root.classList.remove('dark');
            } else {
                localStorage.removeItem('theme');
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) root.classList.add('dark');
                else root.classList.remove('dark');
            }
            document.getElementById('themeDropdown').classList.add('hidden');
        }
        window.addEventListener('click', function(e) {
            const button = document.getElementById('themeMenuButton');
            const dropdown = document.getElementById('themeDropdown');
            if (button && dropdown && !button.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
