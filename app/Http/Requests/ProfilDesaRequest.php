<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilDesaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Otorisasi tetap dijaga oleh Gate di Controller
    }

    protected function prepareForValidation(): void
    {
        $inputs = $this->all();

        foreach ($inputs as $key => $value) {
            if (is_string($value) && $key !== 'logo') {
                $inputs[$key] = strip_tags(trim($value));
            }
        }

        // Sanitasi khusus NIP dan Telepon agar hanya berisi angka
        if (!empty($inputs['nip_kepala_desa'])) {
            $inputs['nip_kepala_desa'] = preg_replace('/[^0-9\s]/', '', $inputs['nip_kepala_desa']);
        }

        if (!empty($inputs['telepon'])) {
            $inputs['telepon'] = preg_replace('/[^0-9\+\-\s]/', '', $inputs['telepon']);
        }

        $this->replace($inputs);
    }

    public function rules(): array
    {
        return [
            'nama_desa'        => ['required', 'string', 'max:100'],
            'kecamatan'        => ['required', 'string', 'max:100'],
            'kabupaten'        => ['required', 'string', 'max:100'],
            'alamat'           => ['required', 'string', 'max:500'],
            'nama_kepala_desa' => ['required', 'string', 'max:150'],
            'nip_kepala_desa'  => ['nullable', 'string', 'max:25'],
            'telepon'          => ['nullable', 'string', 'max:20'],
            'email'            => ['nullable', 'email', 'max:100'],
            'kode_pos'         => ['nullable', 'string', 'max:5'],
            'logo'             => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
