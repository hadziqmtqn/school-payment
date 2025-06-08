<?php

namespace Database\Seeders\Setting;

use Illuminate\Database\Seeder;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\UnavailableStream;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * @throws UnavailableStream
     * @throws Exception
     */
    public function run(): void
    {
        $rows = Reader::createFromPath(database_path('import/permissions.csv'))
            ->setHeaderOffset(0);

        foreach ($rows as $row) {
            Permission::firstOrCreate(['name' => $row['name']]);
        }
    }
}
