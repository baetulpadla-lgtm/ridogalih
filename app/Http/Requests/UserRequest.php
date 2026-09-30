<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Role;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');
        $targetUser = $this->route('user');
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if ($isUpdate && $targetUser) {
            if (!Gate::allows('update', $targetUser)) {
                return false;
            }
        } elseif (!Gate::allows('users.create')) {
            return false;
        }

        $requestedRole = Role::with('permissions')->find($this->role_id);
        if ($requestedRole?->permissions->contains(fn ($permission) => $permission->is_protected) && !$currentUser->isSuperAdmin()) {
            $this->throwSecurityBlock('Hanya System Admin yang berhak memberikan role dengan protected permission.');
        }

        if ($isUpdate && $targetUser) {
            if ($targetUser->isSuperAdmin() && !$currentUser->isSuperAdmin()) {
                $this->throwSecurityBlock('Akses ditolak untuk memodifikasi akun yang memiliki system.admin.');
            }

            if ($targetUser->id === $currentUser->id) {
                if ($this->status_akun !== 'Aktif') {
                    $this->throwSecurityBlock('Anda tidak diperbolehkan menonaktifkan akun Anda sendiri.');
                }
                if ($this->role_id != $targetUser->role_id) {
                    $this->throwSecurityBlock('Anda tidak dapat mengubah jabatan (Role) Anda sendiri.');
                }
            }
        }

        return true;
    }

    protected function throwSecurityBlock(string $message): void
    {
        throw new HttpResponseException(
            redirect()->route('users.index')->with('error', 'Security Block: ' . $message)
        );
    }

    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        $rules = [
            'email'       => ['nullable', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'group_id'    => ['required', 'exists:groups,id'],
            'role_id'     => ['required', 'exists:roles,id'],
            'status_akun' => ['required', 'in:Aktif,Nonaktif,Suspend'],
        ];

        if ($this->isMethod('post')) {
            $rules['penduduk_id'] = ['required', 'exists:penduduk,id', Rule::unique('users', 'penduduk_id')];
            $rules['password']    = ['required', 'min:8', 'confirmed'];
        } else {
            $rules['password']    = ['nullable', 'min:8', 'confirmed'];
        }

        return $rules;
    }
}
