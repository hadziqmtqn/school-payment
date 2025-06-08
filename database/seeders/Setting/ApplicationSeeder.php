<?php

namespace Database\Seeders\Setting;

use App\Models\Application;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $application = new Application();
        $application->name = 'Pembayaran SPP';
        $application->school_name = 'Sekolah Nusantara';
        $application->notification_method = 'whatsapp';
        $application->save();
    }
}
