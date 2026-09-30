<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Warga | E-Office Desa Ridogalih</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .bg-pattern { background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 24px 24px; }
        .dark .bg-pattern { background-image: radial-gradient(#334155 1px, transparent 1px); }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeInUp 0.6s ease-out forwards; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased min-h-screen flex items-center justify-center relative overflow-hidden">

    <div class="absolute inset-0 z-0 bg-pattern opacity-50"></div>
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-500/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-emerald-500/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>

    <div class="relative z-10 w-full max-w-md px-4 py-8 animate-fade-in">

        <div class="text-center mb-8">
            <a href="{{ route('welcome') }}" class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 rounded-2xl text-white shadow-lg shadow-blue-500/30 mb-4 transition transform hover:scale-105">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Daftar Akun Warga</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Verifikasi NIK dan amankan akun layanan digital Anda.</p>
        </div>

        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 p-8">

            @if ($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 p-4 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div class="text-sm text-red-600 dark:text-red-400 font-medium">{{ $errors->first() }}</div>
                </div>
            @endif

            @if (session('success') && !session('show_otp_modal'))
                <div class="mb-6 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 p-4 rounded-xl text-sm text-emerald-700 dark:text-emerald-400 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="space-y-5" x-data="{ showPass: false, showConf: false }">
                @csrf

                <!-- INPUT NIK -->
                <div>
                    <label for="nik" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nomor Induk Kependudukan (NIK)</label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required autofocus minlength="16" maxlength="16" pattern="\d{16}" autocomplete="off"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all font-mono"
                        placeholder="Masukkan 16 digit NIK Anda">
                </div>

                <!-- [BUG FIXED]: INPUT EMAIL UNTUK OTP -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Alamat Email Aktif</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all"
                        placeholder="Untuk pengiriman kode OTP">
                </div>

                <!-- INPUT KATA SANDI -->
                <div class="relative">
                    <label for="password" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Kata Sandi</label>
                    <input :type="showPass ? 'text' : 'password'" name="password" id="password" required minlength="8" autocomplete="new-password"
                        class="w-full px-4 py-3 pr-12 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all"
                        placeholder="Minimal 8 karakter">
                    <button type="button" @click="showPass = !showPass" tabindex="-1" class="absolute bottom-0 right-0 py-3 pr-4 flex items-center text-slate-400 hover:text-blue-500">
                        <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="showPass" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>

                <!-- KONFIRMASI KATA SANDI -->
                <div class="relative">
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Konfirmasi Kata Sandi</label>
                    <input :type="showConf ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required minlength="8" autocomplete="new-password"
                        class="w-full px-4 py-3 pr-12 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all"
                        placeholder="Ulangi kata sandi">
                    <button type="button" @click="showConf = !showConf" tabindex="-1" class="absolute bottom-0 right-0 py-3 pr-4 flex items-center text-slate-400 hover:text-blue-500">
                        <svg x-show="!showConf" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="showConf" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>

                <!-- CAPTCHA -->
                <div>
                    <label for="captcha" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Kode Keamanan (Ketik ulang teks di bawah)</label>
                    <div class="flex items-center justify-between bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-3 mb-2 select-none relative overflow-hidden">
                        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#475569_1px,transparent_1px)] [background-size:8px_8px]"></div>
                        <span class="font-mono font-extrabold text-2xl tracking-widest text-blue-600 dark:text-blue-400 italic skew-x-6 relative z-10">{{ $captchaString }}</span>
                        <button type="button" onclick="window.location.reload();" class="text-xs text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 font-semibold flex items-center gap-1 z-10 transition" title="Acak ulang kode">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Muat Ulang
                        </button>
                    </div>
                    <input type="text" name="captcha" id="captcha" required autocomplete="off"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all font-mono uppercase"
                        placeholder="Masukkan kode di atas (Case-Sensitive)">
                </div>

                <button type="submit" class="w-full py-3.5 mt-2 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-xl shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5">
                    Kirim Kode OTP & Daftar
                </button>
            </form>

            <div class="mt-8 text-center text-sm font-medium text-slate-500">
                Sudah memiliki akun? <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-bold">Masuk di sini</a>
            </div>
        </div>
    </div>

    <!-- OTP MODAL -->
    @if(session('show_otp_modal'))
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-fade-in">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-md p-8 text-center relative">
            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">Verifikasi Email OTP</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                Kode verifikasi 6 digit telah dikirimkan ke alamat email yang Anda daftarkan. Silakan masukkan di bawah ini.
            </p>

            @if($errors->has('otp_code'))
                <div class="mb-4 p-3 bg-red-50 text-red-600 text-xs rounded-xl font-bold">
                    {{ $errors->first('otp_code') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.verify-otp') }}" class="space-y-4">
                @csrf
                <div>
                    <input type="text" name="otp_code" required maxlength="6" pattern="\d{6}" autofocus
                        class="w-full text-center tracking-widest text-2xl font-mono py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="------">
                </div>
                <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-lg shadow-emerald-500/30 transition">
                    Verifikasi & Aktifkan Akun
                </button>
            </form>
        </div>
    </div>
    @endif
</body>
</html>
