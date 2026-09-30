<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\JenisKelamin;
use App\Enums\Agama;
use App\Enums\GolonganDarah;
use App\Enums\StatusPerkawinan;
use App\Enums\StatusKependudukan;
use App\Enums\StatusHubunganKeluarga;
use App\Enums\Dusun;
use App\Enums\Pendidikan;
use App\Enums\Pekerjaan;
use App\Enums\Rt;
use App\Enums\Rw;

class PendudukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $inputs = $this->all();

        foreach ($inputs as $key => $value) {
            // Abaikan input file (foto)
            if (is_string($value) && $key !== 'foto') {
                $inputs[$key] = strip_tags(trim($value));
            }
        }

        if (!empty($inputs['nik'])) {
            $inputs['nik'] = preg_replace('/[^0-9]/', '', $inputs['nik']);
        }
        if (!empty($inputs['no_kk'])) {
            $inputs['no_kk'] = preg_replace('/[^0-9]/', '', $inputs['no_kk']);
        }

        // FIXED: Menggunakan !empty() mencegah error konversi huruf pada string kosong
        if (!empty($inputs['nama_lengkap'])) {
            $inputs['nama_lengkap'] = mb_convert_case($inputs['nama_lengkap'], MB_CASE_TITLE, "UTF-8");
        }

        $this->replace($inputs);
    }

    public function rules(): array
    {
        // Karena Route Model Binding kita set ke UUID di Model, maka $this->route('penduduk')
        // akan menghasilkan Object Model Penduduk utuh. Kita ambil ID integer-nya untuk validasi Unique.
        $pendudukId = $this->route('penduduk') ? $this->route('penduduk')->id : null;

        return [
            'nik'                      => ['required', 'numeric', 'digits:16', Rule::unique('penduduk', 'nik')->ignore($pendudukId)],
            'no_kk'                    => ['required', 'numeric', 'digits:16'],
            'nama_lengkap'             => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\,\'\-]+$/'],
            'foto'                     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'tempat_lahir'             => ['required', 'string', 'max:150'],
            'tanggal_lahir'            => ['required', 'date', 'before:today'],
            'jenis_kelamin'            => ['required', Rule::enum(JenisKelamin::class)],
            'agama'                    => ['required', Rule::enum(Agama::class)],
            'pendidikan'               => ['required', Rule::enum(Pendidikan::class)],
            'pekerjaan'                => ['required', Rule::enum(Pekerjaan::class)],
            'golongan_darah'           => ['nullable', Rule::enum(GolonganDarah::class)],
            'status_perkawinan'        => ['required', Rule::enum(StatusPerkawinan::class)],
            'status_hubungan_keluarga' => ['required', Rule::enum(StatusHubunganKeluarga::class)],
            'kewarganegaraan'          => ['required', Rule::in(['WNI', 'WNA'])],
            'nama_ayah'                => ['required', 'string', 'max:255'],
            'nama_ibu'                 => ['required', 'string', 'max:255'],
            'alamat_lengkap'           => ['required', 'string', 'max:500'],
            'dusun'                    => ['nullable', Rule::enum(Dusun::class)],
            'rt'                       => ['required', Rule::enum(Rt::class)],
            'rw'                       => ['required', Rule::enum(Rw::class)],
            'desa'                     => ['required', 'string', 'max:100'],
            'kecamatan'                => ['required', 'string', 'max:100'],
            'kabupaten'                => ['required', 'string', 'max:100'],
            'provinsi'                 => ['required', 'string', 'max:100'],
            'kode_pos'                 => ['nullable', 'numeric', 'digits:5'],
            'status_kependudukan'      => ['required', Rule::enum(StatusKependudukan::class)],
        ];
    }
}
