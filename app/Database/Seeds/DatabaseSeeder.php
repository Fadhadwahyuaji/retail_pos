<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Run seeders in correct order
        $this->call('RoleSeeder');
        $this->call('OutletSeeder');
        $this->call('UserSeeder');
        $this->call('BarangSeeder');
        $this->call('PromoSeeder');
        $this->call('TransaksiSeeder');

        echo "\n✅ All seeders completed successfully!\n";
    }
}
