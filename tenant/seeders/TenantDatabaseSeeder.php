<?php

namespace Tenant\Seeders;

use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AclSeeder::class,
            SettingsSeeder::class,
            GroupsSeeder::class,
        ]);
    }
}
