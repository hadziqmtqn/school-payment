<?php

namespace App\Jobs;

use App\Models\ClassLevel;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentLevel;
use App\Models\SubClassLevel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class ImportNewStudentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $row;

    /**
     * @param array $row
     */
    public function __construct(array $row)
    {
        $this->row = $row;
    }

    public function handle(): void
    {
        $row = $this->row;

        if (User::filterByEmail($row['email'])->exists()) {
            return;
        }

        $user = new User();
        $user->name = $row['nama_lengkap'];
        $user->email = $row['email'];
        $user->password = Hash::make($row['kata_sandi']);
        $user->email_verified_at = now();
        $user->save();

        $user->assignRole('student');

        $student = new Student();
        $student->user_id = $user->id;
        $student->reg_number = $row['no_registrasi'];
        $student->whatsapp_number = $row['no_whatsapp'];
        $student->gender = $row['jenis_kelamin'];
        $student->save();

        $studentLevel = new StudentLevel();
        $studentLevel->student_id = $student->id;
        $studentLevel->school_year_id = SchoolYear::active()->first()->id;
        $studentLevel->class_level_id = ClassLevel::filterByName($row['level_kelas'])->active()->first()->id;
        $studentLevel->sub_class_level_id = SubClassLevel::filterByName($row['sub_level_kelas'])->first()->id;
        $studentLevel->save();
    }
}
