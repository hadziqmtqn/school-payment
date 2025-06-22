<?php

namespace Database\Factories;

use App\Models\ClassLevel;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentLevel;
use App\Models\SubClassLevel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StudentLevelFactory extends Factory
{
    protected $model = StudentLevel::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'school_year_id' => SchoolYear::active()->first()->id,
            'class_level_id' => ClassLevel::pluck('id')->random(),
            'sub_class_level_id' => SubClassLevel::pluck('id')->random(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
