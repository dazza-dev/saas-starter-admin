<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Siembra la base de datos central; los datos de cada tenant los siembra TenantDatabaseSeeder al provisionarla.
     */
    public function run(): void
    {
        $this->call([
            TenantTypesSeeder::class,
            ModulesSeeder::class,
            PermissionsSeeder::class,
        ]);
    }
}
