<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Password hash dari '123456'
        $passwordHash = password_hash('123456', PASSWORD_DEFAULT);

        $data = [
            // User 1: Admin Pusat (role_id = 1)
            [
                'role_id'    => 1, // Asumsi ID Role 'AD' adalah 1 dari RoleSeeder
                'nama'       => 'Admin Pusat',
                'email'      => 'admin@pusat.com',
                'password'   => $passwordHash,
                'outlet_id'  => 0, // Tidak terikat pada outlet tertentu
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // User 2: Manajer Outlet (role_id = 2)
            [
                'role_id'    => 2, // Asumsi ID Role 'MG' adalah 2
                'nama'       => 'Manajer Outlet A',
                'email'      => 'manager@outletA.com',
                'password'   => $passwordHash,
                'outlet_id'  => 1, // Asumsi ID Outlet adalah 1
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // User 3: Kasir (role_id = 3)
            [
                'role_id'    => 3, // Asumsi ID Role 'KS' adalah 3
                'nama'       => 'Kasir Outlet B',
                'email'      => 'kasir@outletB.com',
                'password'   => $passwordHash,
                'outlet_id'  => 1, // Asumsi ID Outlet adalah 2
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // Memasukkan data ke tabel 'users'
        $this->db->table('users')->insertBatch($data);
    }
}
