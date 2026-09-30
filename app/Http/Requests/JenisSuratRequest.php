<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisSuratRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        $inputs = $this->all();
        foreach ($inputs as $key => $value) {
            if (is_string($value)) $inputs[$key] = strip_tags(trim($value));
        }
        $this->replace($inputs);
    }

    public function rules(): array
    {
        $jenisId = $this->route('jenis_surat') ? $this->route('jenis_surat')->id : null;
        return [
            'kode_surat' => ['required', 'string', 'max:50', Rule::unique('jenis_surats')->ignore($jenisId)],
            'nama_surat' => ['required', 'string', 'max:150'],
            'deskripsi'  => ['nullable', 'string', 'max:500'],
            'is_active'  => ['required', 'boolean']
        ];
    }
}
