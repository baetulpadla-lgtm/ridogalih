<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratMasukRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        $inputs = $this->all();
        foreach ($inputs as $key => $value) {
            if (is_string($value) && $key !== 'file_scan') $inputs[$key] = strip_tags(trim($value));
        }
        $this->replace($inputs);
    }

    public function rules(): array
    {
        return [
            'nomor_surat'      => ['required', 'string', 'max:100'],
            'asal_surat'       => ['required', 'string', 'max:150'],
            'perihal'          => ['required', 'string', 'max:500'],
            'tanggal_surat'    => ['required', 'date'],
            'tanggal_diterima' => ['required', 'date'],
            'file_scan'        => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
