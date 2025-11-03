<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table            = 'transaksi_header';
    protected $primaryKey       = ['NoKassa', 'NoStruk']; // Composite key
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'NoKassa',
        'Gudang',
        'NoStruk',
        'Tanggal',
        'Waktu',
        'Kasir',
        'KdStore',
        'TotalItem',
        'TotalNilaiPem',
        'TotalNilai',
        'TotalBayar',
        'Kembali',
        'Point',
        'Tunai',
        'KKredit',
        'KDebit',
        'GoPay',
        'Voucher',
        'VoucherTravel',
        'Discount',
        'BankDebet',
        'EDCBankDebet',
        'BankKredit',
        'EDCBankKredit',
        'Status',
        'KdCustomer',
        'Ttl_Charge',
        'DPP',
        'TAX',
        'KdMeja',
        'userdisc',
        'KdMember',
        'NoCard',
        'NamaCard',
        'nilaidisc',
        'statuskomisi',
        'statuskomisi_khusus'
    ];

    /**
     * Generate NoStruk (Format: YYMMDD-XXXXX)
     */
    public function generateNoStruk($kdStore)
    {
        $db = \Config\Database::connect();

        // Lock table untuk mencegah race condition
        $db->query("LOCK TABLES transaksi_header WRITE");

        try {
            $date = date('ymd'); // 251103
            $prefix = $date . '-';

            log_message('info', "=== GENERATE NOSTRUK START ===");
            log_message('info', "KdStore: $kdStore, Date: $date, Prefix: $prefix");

            // PENTING: Query ALL NoStruk hari ini untuk outlet (semua NoKassa)
            $query = "SELECT NoStruk, NoKassa 
                  FROM transaksi_header 
                  WHERE KdStore = ? 
                  AND DATE(Tanggal) = ?
                  AND NoStruk LIKE ?
                  ORDER BY NoStruk DESC";

            $struks = $db->query($query, [
                $kdStore,
                date('Y-m-d'),
                $prefix . '%'
            ])->getResultArray();

            log_message('info', "Found " . count($struks) . " existing NoStruk today for KdStore: $kdStore");

            if (!empty($struks)) {
                log_message('debug', "Existing NoStruk: " . json_encode($struks));
            }

            $maxNumber = 0;

            // Loop untuk cari nomor terbesar
            foreach ($struks as $struk) {
                log_message('debug', "Processing: " . json_encode($struk));

                $parts = explode('-', $struk['NoStruk']);
                if (count($parts) == 2) {
                    $number = intval($parts[1]);

                    log_message('debug', "NoStruk: {$struk['NoStruk']}, NoKassa: {$struk['NoKassa']}, Extracted Number: $number");

                    if ($number > $maxNumber) {
                        $maxNumber = $number;
                        log_message('debug', "New max number: $maxNumber from NoStruk: {$struk['NoStruk']}");
                    }
                } else {
                    log_message('warning', "Invalid NoStruk format: {$struk['NoStruk']}");
                }
            }

            $newNumber = $maxNumber + 1;
            $newNoStruk = $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

            log_message('info', "Generated NoStruk: $newNoStruk (Previous max: $maxNumber, New: $newNumber)");
            log_message('info', "=== GENERATE NOSTRUK END ===");

            return $newNoStruk;
        } catch (\Exception $e) {
            log_message('error', "Error in generateNoStruk: " . $e->getMessage());
            throw $e;
        } finally {
            $db->query("UNLOCK TABLES");
        }
    }

    /**
     * Save transaction (header + details)
     */
    public function saveTransaction($headerData, $detailsData)
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Log data yang akan disimpan
            log_message('debug', 'Header Data: ' . json_encode($headerData));
            log_message('debug', 'Details Data Count: ' . count($detailsData));

            // Check duplicate BEFORE insert
            $builder = $db->table($this->table);
            $exists = $builder->where('NoKassa', $headerData['NoKassa'])
                ->where('NoStruk', $headerData['NoStruk'])
                ->countAllResults();

            if ($exists > 0) {
                log_message('error', "DUPLICATE NoStruk: {$headerData['NoStruk']} - NoKassa: {$headerData['NoKassa']}");
                throw new \Exception('Duplicate transaction number detected');
            }

            // Insert header
            $builder = $db->table($this->table);
            $insertResult = $builder->insert($headerData);

            if (!$insertResult) {
                log_message('error', 'Failed to insert header');
                throw new \Exception('Failed to insert transaction header');
            }

            log_message('debug', 'Header inserted successfully');

            // Insert details
            if (!empty($detailsData)) {
                $builderDetail = $db->table('transaksi_detail');
                $detailResult = $builderDetail->insertBatch($detailsData);

                if (!$detailResult) {
                    log_message('error', 'Failed to insert details');
                    throw new \Exception('Failed to insert transaction details');
                }

                log_message('debug', 'Details inserted successfully: ' . count($detailsData) . ' items');
            }

            $db->transCommit();
            log_message('info', "Transaction saved: NoStruk={$headerData['NoStruk']}, NoKassa={$headerData['NoKassa']}");

            return true;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Transaction save error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Get transactions by outlet
     */
    public function getTransactionsByOutlet($kdStore, $startDate = null, $endDate = null)
    {
        $builder = $this->builder();
        $builder->where('KdStore', $kdStore);

        if ($startDate) {
            $builder->where('Tanggal >=', $startDate);
        }

        if ($endDate) {
            $builder->where('Tanggal <=', $endDate);
        }

        return $builder->orderBy('Tanggal', 'DESC')
            ->orderBy('Waktu', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get transactions by date range
     */
    public function getTransactionsByDateRange($startDate, $endDate, $filters = [])
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        $builder->select('transaksi_header.*, outlets.nama_outlet')
            ->join('outlets', 'outlets.KdStore = transaksi_header.KdStore', 'left')
            ->where('Tanggal >=', $startDate)
            ->where('Tanggal <=', $endDate);

        // Apply filters
        if (!empty($filters['kdStore'])) {
            $builder->where('transaksi_header.KdStore', $filters['kdStore']);
        }

        if (!empty($filters['kasir'])) {
            $builder->like('Kasir', $filters['kasir']);
        }

        if (!empty($filters['status'])) {
            $builder->where('Status', $filters['status']);
        }

        return $builder->orderBy('Tanggal', 'DESC')
            ->orderBy('Waktu', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get transaction details
     */
    public function getTransactionDetails($noKassa, $noStruk)
    {
        $db = \Config\Database::connect();

        // Get header
        $header = $this->where('NoKassa', $noKassa)
            ->where('NoStruk', $noStruk)
            ->first();

        if (!$header) return null;

        // Get details
        $builder = $db->table('transaksi_detail');
        $details = $builder->select('transaksi_detail.*, masterbarang.NamaLengkap, masterbarang.NamaStruk')
            ->join('masterbarang', 'masterbarang.PCode = transaksi_detail.PCode', 'left')
            ->where('NoKassa', $noKassa)
            ->where('NoStruk', $noStruk)
            ->get()
            ->getResultArray();

        return [
            'header' => $header,
            'details' => $details
        ];
    }

    /**
     * Get sales summary by outlet
     */
    public function getSalesSummaryByOutlet($startDate, $endDate)
    {
        $db = \Config\Database::connect();

        return $db->query("
            SELECT 
                th.KdStore,
                o.nama_outlet,
                COUNT(DISTINCT th.NoStruk) as total_transaksi,
                SUM(th.TotalItem) as total_item,
                SUM(th.TotalNilai) as total_penjualan,
                SUM(th.Discount) as total_discount,
                SUM(th.TotalBayar) as total_bayar
            FROM transaksi_header th
            LEFT JOIN outlets o ON o.KdStore = th.KdStore
            WHERE th.Tanggal BETWEEN ? AND ?
            AND th.Status = 'T'
            GROUP BY th.KdStore, o.nama_outlet
            ORDER BY total_penjualan DESC
        ", [$startDate, $endDate])->getResultArray();
    }

    /**
     * Get daily sales
     */
    public function getDailySales($kdStore, $date)
    {
        $db = \Config\Database::connect();

        return $db->query("
            SELECT 
                DATE(Tanggal) as tanggal,
                COUNT(*) as jumlah_transaksi,
                SUM(TotalItem) as total_item,
                SUM(TotalNilai) as total_nilai,
                SUM(Discount) as total_discount,
                SUM(TotalBayar) as total_bayar,
                SUM(Tunai) as tunai,
                SUM(KKredit) as kkredit,
                SUM(KDebit) as kdebit,
                SUM(GoPay) as gopay
            FROM transaksi_header
            WHERE KdStore = ?
            AND DATE(Tanggal) = ?
            AND Status = 'T'
            GROUP BY DATE(Tanggal)
        ", [$kdStore, $date])->getRowArray();
    }

    /**
     * Get top selling products
     */
    public function getTopSellingProducts($kdStore, $startDate, $endDate, $limit = 10)
    {
        $db = \Config\Database::connect();

        return $db->query("
            SELECT 
                td.PCode,
                mb.NamaLengkap,
                mb.NamaStruk,
                SUM(td.Qty) as total_qty,
                SUM(td.Netto) as total_netto,
                COUNT(DISTINCT td.NoStruk) as jumlah_transaksi
            FROM transaksi_detail td
            INNER JOIN transaksi_header th ON th.NoKassa = td.NoKassa AND th.NoStruk = td.NoStruk
            LEFT JOIN masterbarang mb ON mb.PCode = td.PCode
            WHERE th.KdStore = ?
            AND th.Tanggal BETWEEN ? AND ?
            AND th.Status = 'T'
            GROUP BY td.PCode, mb.NamaLengkap, mb.NamaStruk
            ORDER BY total_qty DESC
            LIMIT ?
        ", [$kdStore, $startDate, $endDate, $limit])->getResultArray();
    }

    /**
     * Get transaction count by payment method
     */
    public function getPaymentMethodStats($kdStore, $startDate, $endDate)
    {
        $db = \Config\Database::connect();

        return $db->query("
            SELECT 
                SUM(CASE WHEN Tunai > 0 THEN 1 ELSE 0 END) as count_tunai,
                SUM(Tunai) as total_tunai,
                SUM(CASE WHEN KKredit > 0 THEN 1 ELSE 0 END) as count_kkredit,
                SUM(KKredit) as total_kkredit,
                SUM(CASE WHEN KDebit > 0 THEN 1 ELSE 0 END) as count_kdebit,
                SUM(KDebit) as total_kdebit,
                SUM(CASE WHEN GoPay > 0 THEN 1 ELSE 0 END) as count_gopay,
                SUM(GoPay) as total_gopay,
                SUM(CASE WHEN Voucher > 0 THEN 1 ELSE 0 END) as count_voucher,
                SUM(Voucher) as total_voucher
            FROM transaksi_header
            WHERE KdStore = ?
            AND Tanggal BETWEEN ? AND ?
            AND Status = 'T'
        ", [$kdStore, $startDate, $endDate])->getRowArray();
    }

    /**
     * Cancel transaction (void)
     */
    public function cancelTransaction($noKassa, $noStruk)
    {
        return $this->where('NoKassa', $noKassa)
            ->where('NoStruk', $noStruk)
            ->set(['Status' => 'V']) // V = Void
            ->update();
    }
}
