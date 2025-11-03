<?php

namespace App\Models;

use CodeIgniter\Model;

class PromoModel extends Model
{
    protected $table            = 'discountheader';
    protected $primaryKey       = 'NoTrans';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'NoTrans',
        'TglTrans',
        'Ketentuan',
        'TglAwal',
        'TglAkhir',
        'jam_mulai',
        'jam_selesai',
        'hari_berlaku',
        'Minimum',
        'Status',
        'exclude_promo',
        'berlaku',
        'outlet_id'
    ];

    // Validation
    protected $validationRules = [
        'NoTrans'   => 'required|max_length[11]|is_unique[discountheader.NoTrans,NoTrans,{NoTrans}]',
        'Ketentuan' => 'required|max_length[25]',
        'TglAwal'   => 'required|valid_date',
        'TglAkhir'  => 'required|valid_date',
        'Status'    => 'in_list[0,1]'
    ];

    protected $validationMessages = [
        'NoTrans' => [
            'required'  => 'Kode Promo harus diisi',
            'is_unique' => 'Kode Promo sudah digunakan'
        ],
        'Ketentuan' => [
            'required' => 'Nama Promo harus diisi'
        ],
        'TglAwal' => [
            'required'   => 'Tanggal Mulai harus diisi',
            'valid_date' => 'Tanggal Mulai tidak valid'
        ],
        'TglAkhir' => [
            'required'   => 'Tanggal Selesai harus diisi',
            'valid_date' => 'Tanggal Selesai tidak valid'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Custom Methods

    /**
     * Get active promos (Status = 1, dalam periode berlaku)
     */
    public function getActivePromos()
    {
        $today = date('Y-m-d');

        return $this->where('Status', '1')
            ->where('TglAwal <=', $today)
            ->where('TglAkhir >=', $today)
            ->orderBy('TglAwal', 'DESC')
            ->findAll();
    }

    /**
     * Get promos with outlet info
     */
    public function getPromosWithDetails()
    {
        return $this->select('discountheader.*, outlets.nama_outlet, outlets.KdStore')
            ->join('outlets', 'outlets.id = discountheader.outlet_id', 'left')
            ->orderBy('discountheader.TglTrans', 'DESC')
            ->findAll();
    }

    /**
     * Get promo with items
     */
    public function getPromoWithItems($noTrans)
    {
        $promo = $this->find($noTrans);

        if ($promo) {
            $db = \Config\Database::connect();
            $builder = $db->table('discountdetail');
            $items = $builder->select('discountdetail.*, masterbarang.NamaLengkap')
                ->join('masterbarang', 'masterbarang.PCode = discountdetail.PCode')
                ->where('discountdetail.NoTrans', $noTrans)
                ->get()
                ->getResultArray();

            $promo['items'] = $items;
        }

        return $promo;
    }

    /**
     * Check if promo is currently valid (tanggal, jam, hari)
     */
    public function isPromoValid($noTrans, $datetime = null, $outletId = null)
    {
        $promo = $this->find($noTrans);

        if (!$promo || $promo['Status'] != '1') {
            return false;
        }

        if (!$datetime) {
            $datetime = date('Y-m-d H:i:s');
        }

        $currentDate = date('Y-m-d', strtotime($datetime));
        $currentTime = date('H:i:s', strtotime($datetime));
        $currentDay = date('N', strtotime($datetime)); // 1=Senin, 7=Minggu

        // Check tanggal berlaku
        if ($currentDate < $promo['TglAwal'] || $currentDate > $promo['TglAkhir']) {
            return false;
        }

        // Check jam berlaku
        if ($currentTime < $promo['jam_mulai'] || $currentTime > $promo['jam_selesai']) {
            return false;
        }

        // Check hari berlaku
        $hariBerlaku = explode(',', $promo['hari_berlaku']);
        if (!in_array($currentDay, $hariBerlaku)) {
            return false;
        }

        // Check outlet (jika specified)
        if ($outletId && $promo['outlet_id'] && $promo['outlet_id'] != $outletId) {
            return false;
        }

        return true;
    }

    /**
     * Get valid promo for specific product
     */
    public function getValidPromoForProduct($pcode, $datetime = null, $outletId = null)
    {
        $activePromos = $this->getActivePromos();

        foreach ($activePromos as $promo) {
            // Check if promo is valid (date, time, day, outlet)
            if (!$this->isPromoValid($promo['NoTrans'], $datetime, $outletId)) {
                continue;
            }

            // Check if product is in promo
            $db = \Config\Database::connect();
            $builder = $db->table('discountdetail');
            $item = $builder->where('NoTrans', $promo['NoTrans'])
                ->where('PCode', $pcode)
                ->get()
                ->getRowArray();

            if ($item) {
                return [
                    'promo' => $promo,
                    'discount' => $item
                ];
            }
        }

        return null;
    }

    /**
     * Calculate discount for product
     */
    public function calculateDiscount($pcode, $harga, $datetime = null, $outletId = null)
    {
        $promoData = $this->getValidPromoForProduct($pcode, $datetime, $outletId);

        if (!$promoData) {
            return [
                'has_promo' => false,
                'original_price' => $harga,
                'discount_amount' => 0,
                'final_price' => $harga
            ];
        }

        $discount = $promoData['discount'];
        $discountAmount = 0;

        if ($discount['Jenis'] == 'P') {
            // Percentage
            $discountAmount = ($harga * $discount['Nilai']) / 100;
        } else {
            // Rupiah
            $discountAmount = $discount['Nilai'];
        }

        $finalPrice = $harga - $discountAmount;
        if ($finalPrice < 0) $finalPrice = 0;

        return [
            'has_promo' => true,
            'promo_name' => $promoData['promo']['Ketentuan'],
            'promo_code' => $promoData['promo']['NoTrans'],
            'original_price' => $harga,
            'discount_type' => $discount['Jenis'],
            'discount_value' => $discount['Nilai'],
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice
        ];
    }

    /**
     * Toggle promo status
     */
    public function toggleStatus($noTrans)
    {
        $promo = $this->find($noTrans);
        if ($promo) {
            $newStatus = $promo['Status'] == '1' ? '0' : '1';
            return $this->update($noTrans, ['Status' => $newStatus]);
        }
        return false;
    }

    /**
     * Check if promo has transactions
     */
    public function hasTransactions($noTrans)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transaksi_detail');

        $count = $builder->where('Ketentuan1', $noTrans)
            ->orWhere('Ketentuan2', $noTrans)
            ->orWhere('Ketentuan3', $noTrans)
            ->orWhere('Ketentuan4', $noTrans)
            ->countAllResults();

        return $count > 0;
    }

    /**
     * Get promo statistics
     */
    public function getPromoStats($noTrans)
    {
        $db = \Config\Database::connect();

        // Count items in promo
        $itemCount = $db->table('discountdetail')
            ->where('NoTrans', $noTrans)
            ->countAllResults();

        // Count transactions using this promo
        $transCount = $db->table('transaksi_detail')
            ->groupStart()
            ->where('Ketentuan1', $noTrans)
            ->orWhere('Ketentuan2', $noTrans)
            ->orWhere('Ketentuan3', $noTrans)
            ->orWhere('Ketentuan4', $noTrans)
            ->groupEnd()
            ->countAllResults();

        return [
            'item_count' => $itemCount,
            'transaction_count' => $transCount
        ];
    }

    /**
     * Get day name from number
     */
    public function getDayName($dayNumber)
    {
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];

        return $days[$dayNumber] ?? '';
    }

    /**
     * Format hari berlaku for display
     */
    public function formatHariBerlaku($hariBerlaku)
    {
        if ($hariBerlaku == '1,2,3,4,5,6,7') {
            return 'Setiap Hari';
        }

        if ($hariBerlaku == '1,2,3,4,5') {
            return 'Senin - Jumat';
        }

        if ($hariBerlaku == '6,7') {
            return 'Weekend';
        }

        $days = explode(',', $hariBerlaku);
        $dayNames = array_map([$this, 'getDayName'], $days);

        return implode(', ', $dayNames);
    }
}
