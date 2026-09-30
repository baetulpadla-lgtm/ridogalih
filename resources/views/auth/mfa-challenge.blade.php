<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi MFA - E-Office Ridogalih</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 min-h-screen flex items-center justify-center">
    <main class="w-full max-w-md p-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-8">
            <h1 class="text-2xl font-extrabold">Verifikasi administrator</h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Masukkan kode 6 digit dari aplikasi authenticator atau satu kode pemulihan 24 karakter.</p>

            @if($errors->any())
                <div role="alert" class="mt-5 rounded-xl bg-red-50 border border-red-200 p-3 text-sm text-red-700">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login.mfa.verify') }}" method="POST" class="mt-6 space-y-5">
                @csrf
                <label for="code" class="block text-sm font-bold">Kode authenticator</label>
                <input id="code" name="code" inputmode="text" autocomplete="one-time-code" pattern="([0-9]{6}|[A-Fa-f0-9]{24})" maxlength="24" required autofocus class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 px-4 py-3 text-center text-2xl tracking-[0.2em]" aria-describedby="code-help">
                <p id="code-help" class="text-xs text-slate-500">Sesi verifikasi berlaku 5 menit dan maksimal 5 percobaan. Kode pemulihan hanya dapat dipakai sekali.</p>
                <button class="w-full rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-3">Verifikasi dan masuk</button>
            </form>
        </div>
    </main>
</body>
</html>
