<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        $inputs = $this->all();
        if (isset($inputs['name'])) $inputs['name'] = strip_tags(trim($inputs['name']));
        if (isset($inputs['email'])) $inputs['email'] = strip_tags(trim($inputs['email']));
        $this->replace($inputs);
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore(Auth::id())],
            'foto'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
