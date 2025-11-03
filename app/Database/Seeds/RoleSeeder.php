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
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'KdRole' => 'MG',
                'nama_role' => 'Manajer Outlet',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'KdRole' => 'KS',
                'nama_role' => 'Kasir',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // Memasukkan data ke tabel 'roles'
        $this->db->table('roles')->insertBatch($data);
    }
}
