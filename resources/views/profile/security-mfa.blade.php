@extends('layouts.app')

@section('title', 'Aktivasi MFA Administrator')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm p-6 sm:p-8">
        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">Aktifkan MFA untuk administrator</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Tambahkan akun ini di aplikasi authenticator menggunakan kunci setup berikut. Kunci hanya ditampilkan untuk sesi administrator ini dan disimpan terenkripsi.</p>

        @if($errors->any())
            <div role="alert" class="mt-5 rounded-xl bg-red-50 border border-red-200 p-3 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif

        <div class="mt-6 rounded-xl border border-amber-300 bg-amber-50 p-4 text-amber-950">
            <p class="text-xs font-bold uppercase tracking-wide">Kunci setup manual</p>
            <code class="mt-2 block break-all select-all text-lg font-black">{{ $user->mfa_secret }}</code>
            <p class="mt-3 text-xs">Issuer URI untuk aplikasi yang mendukung setup melalui URI:</p>
            <code class="mt-1 block break-all text-xs">{{ $provisioningUri }}</code>
        </div>

        <form action="{{ route('profile.security-mfa.enable') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <label for="code" class="block text-sm font-bold text-slate-700 dark:text-slate-200">Masukkan kode 6 digit untuk mengonfirmasi setup</label>
            <input id="code" name="code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required class="w-full sm:max-w-xs rounded-xl border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 px-4 py-3 tracking-[0.3em] text-lg">
            <button class="block rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3">Konfirmasi dan aktifkan MFA</button>
        </form>
    </div>
</div>
@endsection
