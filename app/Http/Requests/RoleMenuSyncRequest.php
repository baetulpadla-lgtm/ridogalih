<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class RoleMenuSyncRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('assignPermissions', $this->route('role'));
    }

    public function rules(): array
    {
        return [
            'permissions' => ['nullable', 'array', 'max:100'],
            'permissions.*' => ['integer', 'distinct', 'exists:permissions,id'],
        ];
    }
}
