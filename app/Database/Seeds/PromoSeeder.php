<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PromoSeeder extends Seeder
{
    public function run()
    {
        // Get outlet IDs
        $outlet1 = $this->db->table('outlets')->where('KdStore', '1')->get()->getRow();
        $outlet2 = $this->db->table('outlets')->where('KdStore', '2')->get()->getRow();
        $outlet3 = $this->db->table('outlets')->where('KdStore', '3')->get()->getRow();

        // Promo Headers
        $promoHeaders = [
            // Promo 1: Diskon Indomie 10% - Berlaku di Outlet 1
            [
                'NoTrans'       => 'PROMO001',
                'TglTrans'      => date('Y-m-d H:i:s'),
                'Ketentuan'     => 'Diskon Indomie 10%',
                'TglAwal'       => date('Y-m-01'), // Awal bulan ini
                'TglAkhir'      => date('Y-m-t'),  // Akhir bulan ini
                'jam_mulai'     => '00:00:00',
                'jam_selesai'   => '23:59:59',
                'hari_berlaku'  => '1,2,3,4,5,6,7', // Setiap hari
                'Minimum'       => 0,
                'Status'        => '1',
                'exclude_promo' => '0',
                'berlaku'       => '1',
                'outlet_id'     => $outlet1->id
            ],
            // Promo 2: Diskon Minuman 5rb - Berlaku di semua outlet
            [
                'NoTrans'       => 'PROMO002',
                'TglTrans'      => date('Y-m-d H:i:s'),
                'Ketentuan'     => 'Diskon Minuman Rp 5.000',
                'TglAwal'       => date('Y-m-01'),
                'TglAkhir'      => date('Y-m-t'),
                'jam_mulai'     => '10:00:00',
                'jam_selesai'   => '18:00:00',
                'hari_berlaku'  => '1,2,3,4,5', // Senin - Jumat
                'Minimum'       => 0,
                'Status'        => '1',
                'exclude_promo' => '0',
                'berlaku'       => '1',
                'outlet_id'     => null // Berlaku semua outlet
            ],
            // Promo 3: Promo Weekend - Outlet 2
            [
                'NoTrans'       => 'PROMO003',
                'TglTrans'      => date('Y-m-d H:i:s'),
                'Ketentuan'     => 'Promo Weekend 15%',
                'TglAwal'       => date('Y-m-01'),
                'TglAkhir'      => date('Y-m-t'),
                'jam_mulai'     => '00:00:00',
                'jam_selesai'   => '23:59:59',
                'hari_berlaku'  => '6,7', // Weekend
                'Minimum'       => 50000,
                'Status'        => '1',
                'exclude_promo' => '0',
                'berlaku'       => '1',
                'outlet_id'     => $outlet2->id
            ],
        ];

        // Insert promo headers
        $this->db->table('discountheader')->insertBatch($promoHeaders);

        // Promo Details
        $promoDetails = [
            // PROMO001 - Diskon Indomie
            [
                'NoTrans' => 'PROMO001',
                'PCode'   => 'BRG001',
                'Jenis'   => 'P', // Percentage
                'Nilai'   => 10.00
            ],
            // PROMO002 - Diskon Minuman
            [
                'NoTrans' => 'PROMO002',
                'PCode'   => 'BRG002', // Teh Botol
                'Jenis'   => 'R', // Rupiah
                'Nilai'   => 5000.00
            ],
            [
                'NoTrans' => 'PROMO002',
                'PCode'   => 'BRG006', // Aqua
                'Jenis'   => 'R',
                'Nilai'   => 5000.00
            ],
            [
                'NoTrans' => 'PROMO002',
                'PCode'   => 'BRG008', // Ultra Milk
                'Jenis'   => 'R',
                'Nilai'   => 5000.00
            ],
            // PROMO003 - Weekend
            [
                'NoTrans' => 'PROMO003',
                'PCode'   => 'BRG003', // Beras
                'Jenis'   => 'P',
                'Nilai'   => 15.00
            ],
            [
                'NoTrans' => 'PROMO003',
                'PCode'   => 'BRG004', // Minyak
                'Jenis'   => 'P',
                'Nilai'   => 15.00
            ],
            [
                'NoTrans' => 'PROMO003',
                'PCode'   => 'BRG005', // Gula
                'Jenis'   => 'P',
                'Nilai'   => 15.00
            ],
        ];

        // Insert promo details
        $this->db->table('discountdetail')->insertBatch($promoDetails);

        echo "\n✅ Promos created: " . count($promoHeaders) . " headers, " . count($promoDetails) . " items\n";
    }
}
