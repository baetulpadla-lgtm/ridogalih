<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneralSettingRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        // Daftar checkbox sistem yang dikelola pada panel pengaturan
        $checkboxes = [
            'password_require_uppercase',
            'password_require_symbols',
            'strict_single_session',
            'strict_single_tab'
        ];

        $inputs = $this->all();

        // Pastikan checkbox yang tidak dicentang bernilai '0', dan yang dicentang bernilai '1'
        foreach ($checkboxes as $cb) {
            $inputs[$cb] = $this->has($cb) ? '1' : '0';
        }

        // Sanitasi XSS untuk input string biasa
        foreach ($inputs as $key => $value) {
            if (is_string($value) && !in_array($key, $checkboxes)) {
                $inputs[$key] = strip_tags(trim($value));
            }
        }

        $this->replace($inputs);
    }

    public function rules(): array
    {
        return [
            'password_require_uppercase' => ['required', 'in:0,1'],
            'password_require_symbols'   => ['required', 'in:0,1'],
            'strict_single_session'      => ['required', 'in:0,1'],
            'strict_single_tab'          => ['required', 'in:0,1'],
            // Tambahkan validasi dinamis lainnya jika ada input teks umum
        ];
    }
}
