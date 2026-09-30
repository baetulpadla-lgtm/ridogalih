<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaporanSuratRequest extends FormRequest
{
    public function authorize(): bool 
    { 
        return true; // Otorisasi ditangani Gate di Controller
    }

    public function rules(): array
    {
        return [
            'jenis_laporan' => ['nullable', 'in:masuk,keluar'],
            'start_date'    => ['nullable', 'date_format:Y-m-d'],
            'end_date'      => ['nullable', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'end_date.after_or_equal' => 'Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.',
        ];
    }
}