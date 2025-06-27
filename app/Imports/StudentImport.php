<?php

namespace App\Imports;

use App\Jobs\ImportNewStudentJob;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Throwable;

class StudentImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $collection
     * @throws Throwable
     */
    public function collection(Collection $collection): void
    {
        DB::transaction(function () use ($collection) {
            foreach ($collection as $row) {
                ImportNewStudentJob::dispatch($row->toArray());
            }
        });
    }

    public function rules(): array
    {
        // TODO: Implement rules() method.
        return [
            '*.nama_lengkap' => ['required', 'min:3'],
            '*.email' => ['required', 'email', 'unique:users,email'],
            '*.kata_sandi' => ['required', 'min:8'],
            '*.no_registrasi' => ['required', 'unique:students,reg_number'],
            '*.no_whatsapp' => ['required', 'min_digits:10', 'max_digits:13'],
            '*.jenis_kelamin' => ['required', 'in:L,P'],
            '*.level_kelas' => ['required', 'exists:class_levels,name'],
            '*.sub_level_kelas' => ['required', 'exists:sub_class_levels,name']
        ];
    }

    public function customValidationAttributes(): array
    {
        return [
            '*.nama_lengkap' => 'nama lengkap',
            '*.kata_sandi' => 'kata sandi',
            '*.no_registrasi' => 'no registrasi',
            '*.no_whatsapp' => 'no whatsapp',
            '*.jenis_kelamin' => 'jenis kelamin',
            '*.level_kelas' => 'level_kelas',
            '*.sub_level_kelas' => 'sub level kelas'
        ];
    }
}
