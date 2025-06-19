<?php

namespace Database\Seeders\Setting;

use App\Models\WhatsappApiConfig;
use Illuminate\Database\Seeder;

class WhatsappApiConfigSeeder extends Seeder
{
    public function run(): void
    {
        $whatsappApiConfig = new WhatsappApiConfig();
        $whatsappApiConfig->endpoint = 'https://wanesia.com/api/send_message';
        $whatsappApiConfig->api_key = 'pebLsmU89hx1m1Kd61KTtqThdZK4u8W5swgqgEQW3Yt8KQnwHq';
        $whatsappApiConfig->save();
    }
}
