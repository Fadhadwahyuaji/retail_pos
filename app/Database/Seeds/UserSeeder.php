<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Get outlet IDs from the database
        $outlet1 = $this->db->table('outlets')->where('KdStore', '1')->get()->getRow();
        $outlet2 = $this->db->table('outlets')->where('KdStore', '2')->get()->getRow();
        $outlet3 = $this->db->table('outlets')->where('KdStore', '3')->get()->getRow();

        // Get role IDs from the database
        $adminRole = $this->db->table('roles')->where('KdRole', 'AD')->get()->getRow();
        $managerRole = $this->db->table('roles')->where('KdRole', 'MG')->get()->getRow();
        $kasirRole = $this->db->table('roles')->where('KdRole', 'KS')->get()->getRow();

        // Check if all required data exists
        if (!$outlet1 || !$outlet2 || !$outlet3) {
            throw new \RuntimeException('Some outlets are missing. Please run OutletSeeder first.');
        }

        if (!$adminRole || !$managerRole || !$kasirRole) {
            throw new \RuntimeException('Some roles are missing. Please run RoleSeeder first.');
        }

        $data = [
            // Admin Pusat (tidak perlu outlet_id)
            [
                'role_id'    => $adminRole->id,
                'nama'       => 'Super Admin',
                'email'      => 'admin@pos.com',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'outlet_id'  => null, // NULL untuk admin pusat
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Manajer Outlet 1
            [
                'role_id'    => $managerRole->id,
                'nama'       => 'Budi Santoso',
                'email'      => 'manager.cbpusat@pos.com',
                'password'   => password_hash('manager123', PASSWORD_DEFAULT),
                'outlet_id'  => $outlet1->id,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Manajer Outlet 2
            [
                'role_id'    => $managerRole->id,
                'nama'       => 'Siti Aminah',
                'email'      => 'manager.cbtimur@pos.com',
                'password'   => password_hash('manager123', PASSWORD_DEFAULT),
                'outlet_id'  => $outlet2->id,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Kasir Outlet 1
            [
                'role_id'    => $kasirRole->id,
                'nama'       => 'Andi Wijaya',
                'email'      => 'kasir1.cbpusat@pos.com',
                'password'   => password_hash('kasir123', PASSWORD_DEFAULT),
                'outlet_id'  => $outlet1->id,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Kasir Outlet 2
            [
                'role_id'    => $kasirRole->id,
                'nama'       => 'Dewi Lestari',
                'email'      => 'kasir1.cbtimur@pos.com',
                'password'   => password_hash('kasir123', PASSWORD_DEFAULT),
                'outlet_id'  => $outlet2->id,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Kasir Outlet 3
            [
                'role_id'    => $kasirRole->id,
                'nama'       => 'Rudi Hermawan',
                'email'      => 'kasir1.bandung@pos.com',
                'password'   => password_hash('kasir123', PASSWORD_DEFAULT),
                'outlet_id'  => $outlet3->id,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert data
        $this->db->table('users')->insertBatch($data);

        echo "\n✅ Users created successfully!\n";
        echo "Admin: admin@pos.com / admin123\n";
        echo "Manager: manager.cbpusat@pos.com / manager123\n";
        echo "Kasir: kasir1.cbpusat@pos.com / kasir123\n";
    }
}
