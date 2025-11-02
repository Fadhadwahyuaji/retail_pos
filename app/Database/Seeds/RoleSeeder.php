<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'KdRole' => 'AD',
                'nama_role' => 'Admin Pusat',
            ],
            [
                'KdRole' => 'MG',
                'nama_role' => 'Manajer Outlet',
            ],
            [
                'KdRole' => 'KS',
                'nama_role' => 'Kasir',
            ]
        ];

        // Memasukkan data ke tabel 'roles'
        $this->db->table('roles')->insertBatch($data);
    }
}
