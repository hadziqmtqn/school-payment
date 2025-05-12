<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Setting\AdminSeeder;
use Database\Seeders\Setting\ApplicationSeeder;
use Database\Seeders\Setting\MenuSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MenuSeeder::class,
            AdminSeeder::class,
            ApplicationSeeder::class
        ]);
    }
}
