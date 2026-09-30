<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', \App\Models\Permission::class);
    }

    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:120', 'regex:/^[a-z][a-z0-9_]*(\.[a-z][a-z0-9_]*)+$/', Rule::unique('permissions', 'key')],
            'name' => ['required', 'string', 'max:150'],
        ];
    }
}
