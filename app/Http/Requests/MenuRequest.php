<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Menu;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class MenuRequest extends FormRequest
{
    public function authorize(): bool { return true; } // Otorisasi ditangani Gate di Controller

    protected function prepareForValidation(): void
    {
        $inputs = $this->all();
        if (isset($inputs['nama_menu'])) $inputs['nama_menu'] = strip_tags(trim($inputs['nama_menu']));
        if (isset($inputs['url_route'])) $inputs['url_route'] = strip_tags(trim($inputs['url_route']));
        $this->replace($inputs);
    }

    public function rules(): array
    {
        $routeNames = collect(Route::getRoutes()->getRoutesByName())
            ->filter(fn ($route) => $route->parameterNames() === [])
            ->keys()
            ->all();

        return [
            'nama_menu'   => ['required', 'string', 'max:100'],
            'url_route'   => ['nullable', 'string', 'max:150', Rule::in($routeNames)],
            'is_sidebar'  => ['required', 'boolean'],
            'parent_uuid' => ['nullable', Rule::exists('menus', 'uuid')->whereNull('parent_id')],
            'permission_id' => ['nullable', 'integer', 'exists:permissions,id'],
            'icon'        => ['nullable', 'string', 'max:50', 'regex:/^[a-zA-Z0-9\-\s]+$/'],
            'is_active'   => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return ['icon.regex' => 'Format icon tidak valid! Hanya gunakan huruf, angka, spasi, atau strip.'];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            /** @var \App\Models\Menu|null $currentMenu */
            $currentMenu = $this->route('menu'); // Hanya ada saat Update
            $parentUuid = $this->parent_uuid;

            if ($currentMenu && $parentUuid) {
                if ($currentMenu->uuid === $parentUuid) {
                    $validator->errors()->add('parent_uuid', 'Security Block: Menu tidak dapat menjadi Induk bagi dirinya sendiri!');
                } else {
                    $targetParent = Menu::where('uuid', $parentUuid)->first();
                    if ($targetParent && $targetParent->parent_id === $currentMenu->id) {
                        $validator->errors()->add('parent_uuid', 'Security Block: Tidak bisa memindahkan Menu Induk ke dalam Sub-Menunya sendiri!');
                    }
                }
            }
        });
    }
}
