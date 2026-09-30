@extends('layouts.app')

@section('title', 'Kode Pemulihan MFA')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="rounded-2xl bg-white dark:bg-slate-800 border border-amber-300 shadow-sm p-6 sm:p-8">
        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">Simpan kode pemulihan MFA</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Kode ini hanya ditampilkan sekali. Simpan di tempat aman yang terpisah dari perangkat authenticator. Setiap kode hanya berlaku satu kali.</p>

        <ol class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($codes as $code)
                <li class="rounded-lg bg-slate-100 dark:bg-slate-900 px-4 py-3 font-mono font-bold tracking-wider select-all">{{ $code }}</li>
            @endforeach
        </ol>

        <a href="{{ route('profile.edit') }}" class="mt-6 inline-flex rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3">Kode sudah saya simpan</a>
    </div>
</div>
@endsection
