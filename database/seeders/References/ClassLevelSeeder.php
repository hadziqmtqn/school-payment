<?php

namespace Database\Seeders\References;

use App\Models\ClassLevel;
use App\Models\SubClassLevel;
use Illuminate\Database\Seeder;

class ClassLevelSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 6) as $level) {
            $classLevel = new ClassLevel();
            $classLevel->serial_number = $level;
            $classLevel->name = 'Kelas ' . $level;
            $classLevel->save();
        }

        foreach ([1, 2, 3, 4, 'A', 'B', 'C'] as $item) {
            $subClassLevel = new SubClassLevel();
            $subClassLevel->name = $item;
            $subClassLevel->save();
        }
    }
}
