<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OutletSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'KdStore'     => '1',
                'nama_outlet' => 'Outlet Cirebon Pusat',
                'alamat'      => 'Jl. Siliwangi No. 123, Cirebon',
                'telepon'     => '0231-1234567',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'KdStore'     => '2',
                'nama_outlet' => 'Outlet Cirebon Timur',
                'alamat'      => 'Jl. Sunan Kalijaga No. 45, Cirebon',
                'telepon'     => '0231-7654321',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'KdStore'     => '3',
                'nama_outlet' => 'Outlet Bandung',
                'alamat'      => 'Jl. Braga No. 78, Bandung',
                'telepon'     => '022-1234567',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert data
        $this->db->table('outlets')->insertBatch($data);
    }
}
