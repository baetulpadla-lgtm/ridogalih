<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;

class SuratKeluarRequest extends FormRequest
{
    public function authorize(): bool
    {
        $targetSurat = $this->route('surat_keluar');
        if ($targetSurat && !Gate::allows('update', $targetSurat)) {
            throw new HttpResponseException(redirect()->route('surat-keluar.index')->with('error', 'Akses Ditolak: Dokumen bukan milik Anda.'));
        }
        return true;
    }

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
        if ($this->isMethod('post')) {
            return [
                'jenis_surat_id' => ['required', 'exists:jenis_surats,id'],
                'keperluan'      => ['required', 'string', 'max:500'],
                'penduduk_id'    => ['nullable', 'exists:penduduk,id'], // Nullable untuk warga biasa
            ];
        }

        $suratId = $this->route('surat_keluar')->id;
        return [
            'status'            => ['required', Rule::in(['Menunggu', 'Diproses', 'Disetujui', 'Ditolak', 'Selesai'])],
            'nomor_surat'       => ['nullable', 'string', 'max:100', Rule::unique('surat_keluars')->ignore($suratId)],
            'keterangan_status' => ['nullable', 'string', 'max:500']
        ];
    }
}
