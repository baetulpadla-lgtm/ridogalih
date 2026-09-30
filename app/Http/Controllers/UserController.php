<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Penduduk;
use App\Models\Group;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $query = User::with(['penduduk', 'group', 'role']);

        if ($request->filled('search')) {
            $search = trim(strip_tags((string)$request->search));
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('penduduk', fn($q2) => $q2->where('nik', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $allowedStatuses = ['Aktif', 'Nonaktif', 'Suspend'];
            if (in_array($request->status, $allowedStatuses)) {
                $query->where('status_akun', $request->status);
            }
        }

        $sortOption = $request->get('sort', 'terbaru');
        $query->when($sortOption === 'terlama', fn($q) => $q->oldest())
              ->when($sortOption === 'nama_asc', fn($q) => $q->orderBy('name', 'asc'))
              ->when($sortOption === 'nama_desc', fn($q) => $q->orderBy('name', 'desc'))
              ->when(!in_array($sortOption, ['terlama', 'nama_asc', 'nama_desc']), fn($q) => $q->latest());

        $limit = max(1, min((int) $request->get('limit', 10), 100));
        $users = $query->paginate($limit)->withQueryString();

        $totalUsers   = User::count();
        $totalAktif   = User::where('status_akun', 'Aktif')->count();
        $totalSuspend = User::whereIn('status_akun', ['Nonaktif', 'Suspend'])->count();

        return view('users.index', compact('users', 'totalUsers', 'totalAktif', 'totalSuspend'));
    }

    public function create(): View
    {
        Gate::authorize('create', User::class);

        $registeredPendudukIds = User::pluck('penduduk_id')->filter();
        $penduduks = Penduduk::whereNotIn('id', $registeredPendudukIds)->get();
        $groups = Group::with('roles')->get();

        return view('users.create', compact('penduduks', 'groups'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        Gate::authorize('create', User::class);
        $validated = $request->validated();
        $penduduk = Penduduk::findOrFail($validated['penduduk_id']);

        DB::transaction(function () use ($validated, $penduduk) {
            User::create([
                'penduduk_id' => $penduduk->id,
                'name'        => $penduduk->nama_lengkap,
                'email'       => $validated['email'],
                'password'    => Hash::make($validated['password']),
                'status_akun' => $validated['status_akun'],
                'is_active'   => ($validated['status_akun'] === 'Aktif'),
                'group_id'    => $validated['group_id'],
                'role_id'     => $validated['role_id'],
            ]);
        });

        return redirect()->route('users.index')->with('success', 'Akun Pengguna berhasil dibuat!');
    }

    public function show(User $user): View
    {
        Gate::authorize('users.view');
        $user->loadMissing(['penduduk', 'group', 'role']);
        Gate::authorize('view', $user);

        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        Gate::authorize('users.update');
        Gate::authorize('update', $user);
        $groups = Group::with('roles')->get();

        return view('users.edit', compact('user', 'groups'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $user) {
            $user->email       = $validated['email'];
            $user->group_id    = $validated['group_id'];
            $user->role_id     = $validated['role_id'];
            $user->status_akun = $validated['status_akun'];
            $user->is_active   = ($validated['status_akun'] === 'Aktif');

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();
        });

        return redirect()->route('users.index')->with('success', 'Data Akun Pengguna diperbarui!');
    }

    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Akses Ditolak: Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Akun berhasil dihapus permanen.');
    }

    public function print(User $user): View
    {
        Gate::authorize('users.view');
        $user->loadMissing(['penduduk', 'group', 'role']);
        Gate::authorize('view', $user);

        return view('users.print', compact('user'));
    }

    public function export(Request $request)
    {
        // ... (Kode export Anda tetap dipertahankan seperti aslinya karena sudah aman XSS dan Macro Injection) ...
        Gate::authorize('users.export');
        // ...
    }
}
