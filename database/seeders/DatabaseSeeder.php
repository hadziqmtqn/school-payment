<?php

namespace Database\Seeders;

use Database\Seeders\References\ClassLevelSeeder;
use Database\Seeders\References\SchoolYearSeeder;
use Database\Seeders\Setting\AdminSeeder;
use Database\Seeders\Setting\ApplicationSeeder;
use Database\Seeders\Setting\MenuSeeder;
use Database\Seeders\Setting\PermissionSeeder;
use Database\Seeders\Setting\WhatsappApiConfigSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            MenuSeeder::class,
            AdminSeeder::class,
            ApplicationSeeder::class,
            WhatsappApiConfigSeeder::class,
            SchoolYearSeeder::class,
            ClassLevelSeeder::class
        ]);
    }
}
