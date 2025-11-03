<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    public function run()
    {
        // OPTIONAL: Clear existing transactions (comment out if you want to keep old data)
        echo "🗑️  Clearing old transaction data...\n";
        $this->db->table('transaksi_detail')->truncate();
        $this->db->table('transaksi_header')->truncate();
        echo "✅ Old data cleared\n\n";

        // Get outlets
        $outlets = $this->db->table('outlets')->get()->getResultArray();

        // Get users (kasir)
        $kasirs = $this->db->table('users')
            ->join('roles', 'roles.id = users.role_id')
            ->where('roles.KdRole', 'KS')
            ->select('users.*, outlets.KdStore')
            ->join('outlets', 'outlets.id = users.outlet_id')
            ->get()
            ->getResultArray();

        if (empty($kasirs)) {
            echo "⚠️  No cashiers found. Please run UserSeeder first.\n";
            return;
        }

        // Check transaksi_detail table structure
        $fields = $this->db->getFieldNames('transaksi_detail');
        echo "📋 Table columns: " . implode(', ', $fields) . "\n\n";

        // Generate transactions for last 7 days
        $transaksiHeaders = [];
        $transaksiDetails = [];
        $noStrukCounter = 1;

        echo "🔄 Generating transactions...\n";

        for ($day = 6; $day >= 0; $day--) {
            $tanggal = date('Y-m-d', strtotime("-$day days"));
            $datePrefix = date('ymd', strtotime($tanggal));

            foreach ($kasirs as $kasir) {
                $kdStore = $kasir['KdStore'];

                // 3-5 transaksi per hari per kasir
                $jumlahTransaksi = rand(3, 5);

                for ($i = 1; $i <= $jumlahTransaksi; $i++) {
                    $noKassa = 'K' . str_pad($kasir['id'], 3, '0', STR_PAD_LEFT);
                    $noStruk = $datePrefix . '-' . str_pad($noStrukCounter, 5, '0', STR_PAD_LEFT);
                    $waktu = sprintf('%02d:%02d:%02d', rand(8, 20), rand(0, 59), rand(0, 59));

                    // Random items (2-6 items per transaction)
                    $jumlahItem = rand(2, 6);
                    $items = $this->getRandomItems($jumlahItem);

                    if (empty($items)) {
                        continue; // Skip if no items found
                    }

                    $totalItem = 0;
                    $totalNilaiPem = 0;
                    $totalNilai = 0;
                    $totalDiscount = 0;

                    // Create detail items
                    foreach ($items as $item) {
                        $qty = rand(1, 5);
                        $hargaSatuan = $item['Harga1c'];
                        $subtotal = $qty * $hargaSatuan;

                        // Check if item has promo (30% chance)
                        $disc1 = 0;
                        $jenis1 = '';
                        $ketentuan1 = '';

                        if (rand(1, 100) <= 30) {
                            $disc1 = $subtotal * 0.10; // 10% discount
                            $jenis1 = 'P'; // Percentage
                            $ketentuan1 = 'PROMO001';
                        }

                        $netto = $subtotal - $disc1;

                        // Build detail data - sesuaikan dengan struktur tabel
                        $detailData = [
                            'NoKassa'     => $noKassa,
                            'Gudang'      => $kdStore,
                            'NoStruk'     => $noStruk,
                            'Tanggal'     => $tanggal,
                            'Waktu'       => $waktu,
                            'Kasir'       => $kasir['nama'],
                            'KdStore'     => $kdStore,
                            'PCode'       => $item['PCode'],
                            'Qty'         => $qty,
                            'Berat'       => 0,
                            'Harga'       => $hargaSatuan,
                            'Ketentuan1'  => $ketentuan1,
                            'Disc1'       => $disc1,
                            'Jenis1'      => $jenis1,
                            'Ketentuan2'  => '',
                            'Disc2'       => 0,
                            'Jenis2'      => '',
                            'Ketentuan3'  => '',
                            'Disc3'       => 0,
                            'Jenis3'      => '',
                            'Ketentuan4'  => '',
                            'Disc4'       => 0,
                            'Jenis4'      => '',
                            'Netto'       => $netto,
                            'Hpp'         => $item['HPP'] ?? $item['HargaBeli'],
                            'Status'      => 'T',
                            'Keterangan'  => '',
                            'Service_charge' => 0,
                            'Komisi'      => 0,
                            'PPN'         => 0,
                            'Printer'     => '',
                            'KdMeja'      => '',
                            'KdAgent'     => ''
                        ];

                        $transaksiDetails[] = $detailData;

                        $totalItem += $qty;
                        $totalNilaiPem += $qty * ($item['HargaBeli'] ?? 0);
                        $totalNilai += $subtotal;
                        $totalDiscount += $disc1;
                    }

                    $totalBayar = $totalNilai - $totalDiscount;

                    // Random payment method
                    $paymentMethods = ['tunai', 'kkredit', 'kdebit', 'gopay'];
                    $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

                    $tunai = $kkredit = $kdebit = $gopay = 0;
                    $kembali = 0;

                    if ($paymentMethod == 'tunai') {
                        $tunai = ceil($totalBayar / 1000) * 1000; // Round up
                        $kembali = $tunai - $totalBayar;
                    } else {
                        ${$paymentMethod} = $totalBayar;
                    }

                    // Calculate DPP and TAX (PPN 11%)
                    $dpp = $totalBayar / 1.11;
                    $tax = $totalBayar - $dpp;

                    $transaksiHeaders[] = [
                        'NoKassa'       => $noKassa,
                        'Gudang'        => $kdStore,
                        'NoStruk'       => $noStruk,
                        'Tanggal'       => $tanggal,
                        'Waktu'         => $waktu,
                        'Kasir'         => $kasir['nama'],
                        'KdStore'       => $kdStore,
                        'TotalItem'     => $totalItem,
                        'TotalNilaiPem' => $totalNilaiPem,
                        'TotalNilai'    => $totalNilai,
                        'TotalBayar'    => $totalBayar,
                        'Kembali'       => $kembali,
                        'Point'         => floor($totalBayar / 10000),
                        'Tunai'         => $tunai,
                        'KKredit'       => $kkredit,
                        'KDebit'        => $kdebit,
                        'GoPay'         => $gopay,
                        'Voucher'       => 0,
                        'VoucherTravel' => 0,
                        'Discount'      => $totalDiscount,
                        'BankDebet'     => $kdebit > 0 ? 'BCA' : '',
                        'EDCBankDebet'  => $kdebit > 0 ? 'EDC001' : '',
                        'BankKredit'    => $kkredit > 0 ? 'BCA' : '',
                        'EDCBankKredit' => $kkredit > 0 ? 'EDC001' : '',
                        'Status'        => 'T',
                        'KdCustomer'    => '',
                        'Ttl_Charge'    => 0,
                        'DPP'           => $dpp,
                        'TAX'           => $tax,
                        'KdMeja'        => '',
                        'userdisc'      => '',
                        'KdMember'      => '',
                        'NoCard'        => '',
                        'NamaCard'      => '',
                        'nilaidisc'     => 0,
                        'statuskomisi'  => '0',
                        'statuskomisi_khusus' => '0'
                    ];

                    $noStrukCounter++;
                }
            }

            echo "  ✓ Day -$day ($tanggal): " . count($transaksiHeaders) . " transactions\n";
        }

        // Insert in batches
        if (!empty($transaksiHeaders)) {
            $chunks = array_chunk($transaksiHeaders, 50);
            foreach ($chunks as $chunk) {
                $this->db->table('transaksi_header')->insertBatch($chunk);
            }
            echo "\n✅ Transaction headers created: " . count($transaksiHeaders) . "\n";
        }

        if (!empty($transaksiDetails)) {
            $chunks = array_chunk($transaksiDetails, 100);
            $totalInserted = 0;

            foreach ($chunks as $chunk) {
                $this->db->table('transaksi_detail')->insertBatch($chunk);
                $totalInserted += count($chunk);
            }

            echo "✅ Transaction details created: " . $totalInserted . " items\n";
        }

        $totalValue = array_sum(array_column($transaksiHeaders, 'TotalBayar'));
        echo "✅ Total transactions value: Rp " . number_format($totalValue, 0, ',', '.') . "\n";

        // Summary by outlet
        echo "\n📊 Summary by Outlet:\n";
        foreach ($outlets as $outlet) {
            $outletTrans = array_filter($transaksiHeaders, function ($t) use ($outlet) {
                return $t['KdStore'] == $outlet['KdStore'];
            });
            $outletValue = array_sum(array_column($outletTrans, 'TotalBayar'));
            echo "  • {$outlet['nama_outlet']}: " . count($outletTrans) . " transactions (Rp " . number_format($outletValue, 0, ',', '.') . ")\n";
        }
    }

    private function getRandomItems($count)
    {
        return $this->db->table('masterbarang')
            ->where('Status', 'T')
            ->where('FlagReady', 'Y')
            ->orderBy('RAND()')
            ->limit($count)
            ->get()
            ->getResultArray();
    }
}
