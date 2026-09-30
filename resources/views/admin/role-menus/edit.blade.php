@extends('layouts.app')

@section('title', 'Role permissions - ' . $role->name)

@section('content')
<div class="mx-auto max-w-6xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
    <header class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Permissions: {{ $role->name }}</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Permissions mengotorisasi aksi server-side. Menu hanya mengatur navigasi.</p>
        </div>
        <a href="{{ route('admin.role-menus.index') }}" class="rounded-lg border px-4 py-2 text-sm">Kembali</a>
    </header>

    @if($errors->any())
        <div role="alert" class="rounded-lg border border-red-300 bg-red-50 p-4 text-red-800">
            <ul class="list-inside list-disc">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div role="status" class="rounded-lg border border-emerald-300 bg-emerald-50 p-4 text-emerald-800">{{ session('success') }}</div>
    @endif

    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Hak akses role</h2>
        <p class="mb-5 mt-1 text-sm text-slate-600 dark:text-slate-300">Permission yang ditandai protected hanya dapat diberikan oleh System Admin.</p>

        <form action="{{ route('admin.role-menus.update', $role->uuid) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($permissions as $permission)
                    <label class="flex items-start gap-3 rounded-lg border border-slate-200 p-3 dark:border-slate-700">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                               @checked(in_array($permission->id, old('permissions', $assignedPermissions)))
                               class="mt-1 rounded border-slate-300 text-blue-600">
                        <span>
                            <span class="block font-mono text-sm font-semibold text-slate-900 dark:text-white">{{ $permission->key }}</span>
                            <span class="block text-xs text-slate-600 dark:text-slate-300">{{ $permission->name }}</span>
                            @if($permission->is_protected)
                                <span class="mt-1 inline-block rounded bg-amber-100 px-2 py-0.5 text-xs text-amber-800">Protected</span>
                            @endif
                        </span>
                    </label>
                @endforeach
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 font-semibold text-white hover:bg-blue-700">Simpan permissions</button>
            </div>
        </form>
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Daftarkan permission</h2>
        <p class="mb-4 mt-1 text-sm text-slate-600 dark:text-slate-300">Permission security/system baru hanya dapat dibuat oleh System Admin.</p>
        <form action="{{ route('admin.role-menus.permissions.store') }}" method="POST" class="mb-8 grid gap-4 border-b border-slate-200 pb-6 sm:grid-cols-3 dark:border-slate-700">
            @csrf
            <label class="text-sm font-medium">Permission key
                <input name="key" required maxlength="120" pattern="[a-z][a-z0-9_]*(\.[a-z][a-z0-9_]*)+" placeholder="contoh: arsip.export" class="mt-1 w-full rounded-lg border-slate-300 font-mono dark:bg-slate-900">
            </label>
            <label class="text-sm font-medium">Nama permission
                <input name="name" required maxlength="150" class="mt-1 w-full rounded-lg border-slate-300 dark:bg-slate-900">
            </label>
            <div class="flex items-end">
                <button class="rounded-lg bg-slate-800 px-5 py-2.5 font-semibold text-white hover:bg-slate-700">Tambah permission</button>
            </div>
        </form>

        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Metadata navigasi</h2>
        <p class="mb-5 mt-1 text-sm text-slate-600 dark:text-slate-300">Route name dipilih dari route Laravel yang terdaftar. Menu tidak memberikan hak akses.</p>

        <form action="{{ route('admin.role-menus.storeMenu') }}" method="POST" class="grid gap-4 md:grid-cols-2">
            @csrf
            <label class="text-sm font-medium">Label
                <input name="nama_menu" required maxlength="100" class="mt-1 w-full rounded-lg border-slate-300 dark:bg-slate-900">
            </label>
            <label class="text-sm font-medium">Route name
                <select name="url_route" class="mt-1 w-full rounded-lg border-slate-300 dark:bg-slate-900">
                    <option value="">Tanpa route</option>
                    @foreach($routeNames as $routeName)
                        <option value="{{ $routeName }}">{{ $routeName }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-medium">Permission untuk visibilitas menu
                <select name="permission_id" class="mt-1 w-full rounded-lg border-slate-300 dark:bg-slate-900">
                    <option value="">Tidak dibatasi permission</option>
                    @foreach($permissions as $permission)
                        <option value="{{ $permission->id }}">{{ $permission->key }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-medium">Menu induk
                <select name="parent_uuid" class="mt-1 w-full rounded-lg border-slate-300 dark:bg-slate-900">
                    <option value="">Menu utama</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->uuid }}">{{ $menu->nama_menu }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-medium">Ikon
                <input name="icon" maxlength="50" pattern="[a-zA-Z0-9\-\s]+" class="mt-1 w-full rounded-lg border-slate-300 dark:bg-slate-900">
            </label>
            <label class="text-sm font-medium">Status sidebar
                <select name="is_sidebar" required class="mt-1 w-full rounded-lg border-slate-300 dark:bg-slate-900">
                    <option value="1">Tampil</option>
                    <option value="0">Tersembunyi</option>
                </select>
            </label>
            <label class="text-sm font-medium">Status aktif
                <select name="is_active" required class="mt-1 w-full rounded-lg border-slate-300 dark:bg-slate-900">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </label>
            <div class="md:col-span-2 flex justify-end">
                <button class="rounded-lg bg-slate-800 px-5 py-2.5 font-semibold text-white hover:bg-slate-700">Tambah menu</button>
            </div>
        </form>

        <div class="mt-6 divide-y divide-slate-200 dark:divide-slate-700">
            @foreach($menus as $menu)
                @foreach(collect([$menu])->concat($menu->children) as $item)
                    <div class="flex flex-wrap items-center justify-between gap-3 py-3">
                        <div>
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $item->nama_menu }}</p>
                            <p class="font-mono text-xs text-slate-600 dark:text-slate-300">{{ $item->url_route ?: 'Tanpa route' }} · {{ $item->permission?->key ?? 'Tanpa filter navigasi' }}</p>
                        </div>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.role-menus.updateMenu', $item->uuid) }}" method="POST" class="flex flex-wrap items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input name="nama_menu" value="{{ $item->nama_menu }}" required maxlength="100" aria-label="Label menu" class="w-36 rounded border-slate-300 text-sm dark:bg-slate-900">
                                <select name="url_route" aria-label="Route name" class="max-w-52 rounded border-slate-300 text-sm dark:bg-slate-900">
                                    <option value="">Tanpa route</option>
                                    @foreach($routeNames as $routeName)
                                        <option value="{{ $routeName }}" @selected($item->url_route === $routeName)>{{ $routeName }}</option>
                                    @endforeach
                                </select>
                                <select name="permission_id" aria-label="Navigation permission" class="max-w-48 rounded border-slate-300 text-sm dark:bg-slate-900">
                                    <option value="">Tanpa filter</option>
                                    @foreach($permissions as $permission)
                                        <option value="{{ $permission->id }}" @selected($item->permission_id === $permission->id)>{{ $permission->key }}</option>
                                    @endforeach
                                </select>
                                <select name="is_sidebar" aria-label="Sidebar visibility" class="rounded border-slate-300 text-sm dark:bg-slate-900">
                                    <option value="1" @selected($item->is_sidebar)>Sidebar</option>
                                    <option value="0" @selected(!$item->is_sidebar)>Hidden</option>
                                </select>
                                <select name="is_active" aria-label="Active status" class="rounded border-slate-300 text-sm dark:bg-slate-900">
                                    <option value="1" @selected($item->is_active)>Aktif</option>
                                    <option value="0" @selected(!$item->is_active)>Nonaktif</option>
                                </select>
                                <input type="hidden" name="parent_uuid" value="{{ $item->parent?->uuid }}">
                                <input type="hidden" name="icon" value="{{ $item->icon }}">
                                <button class="rounded border px-3 py-1.5 text-sm">Simpan</button>
                            </form>
                            <form action="{{ route('admin.role-menus.destroyMenu', $item->uuid) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded border border-red-300 px-3 py-1.5 text-sm text-red-700">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </section>
</div>
@endsection
