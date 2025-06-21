<?php

namespace Database\Seeders\Setting;

use App\Models\SchoolYear;
use Illuminate\Database\Seeder;

class SchoolYearSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([2025, 2026, 2027] as $index => $year) {
            $schoolYear = new SchoolYear();
            $schoolYear->first_year = $year;
            $schoolYear->last_year = $year + 1;
            $schoolYear->is_active = $index == 0;
            $schoolYear->save();
        }
    }
}
