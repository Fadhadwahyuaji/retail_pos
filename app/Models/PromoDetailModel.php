<?php

namespace App\Models;

use CodeIgniter\Model;

class PromoDetailModel extends Model
{
    protected $table            = 'discountdetail';
    protected $primaryKey       = 'NoTrans'; // Composite key with PCode
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'NoTrans',
        'PCode',
        'Jenis',
        'Nilai'
    ];

    // Validation
    protected $validationRules = [
        'NoTrans' => 'required|max_length[11]',
        'PCode'   => 'required|max_length[15]',
        'Jenis'   => 'required|in_list[P,R]',
        'Nilai'   => 'required|decimal|greater_than[0]'
    ];

    protected $validationMessages = [
        'PCode' => [
            'required' => 'Produk harus dipilih'
        ],
        'Jenis' => [
            'required' => 'Jenis diskon harus dipilih',
            'in_list'  => 'Jenis diskon tidak valid (P=Persen, R=Rupiah)'
        ],
        'Nilai' => [
            'required'     => 'Nilai diskon harus diisi',
            'greater_than' => 'Nilai diskon harus lebih dari 0'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Custom Methods



    /**
     * Get items by promo
     */
    public function getItemsByPromo($noTrans)
    {
        return $this->select('discountdetail.*, masterbarang.NamaLengkap, masterbarang.Harga1c')
            ->join('masterbarang', 'masterbarang.PCode = discountdetail.PCode')
            ->where('discountdetail.NoTrans', $noTrans)
            ->findAll();
    }

    /**
     * Add item to promo
     */
    public function addItem($noTrans, $pcode, $jenis, $nilai)
    {
        $data = [
            'NoTrans' => $noTrans,
            'PCode'   => $pcode,
            'Jenis'   => $jenis,
            'Nilai'   => $nilai
        ];

        return $this->insert($data);
    }

    /**
     * Check if item exists in promo
     */
    public function itemExists($noTrans, $pcode)
    {
        $count = $this->where('NoTrans', $noTrans)
            ->where('PCode', $pcode)
            ->countAllResults();

        return $count > 0;
    }

    /**
     * Remove item from promo
     */
    public function removeItem($noTrans, $pcode)
    {
        return $this->where('NoTrans', $noTrans)
            ->where('PCode', $pcode)
            ->delete();
    }

    /**
     * Update item discount
     */
    public function updateItem($noTrans, $pcode, $jenis, $nilai)
    {
        return $this->where('NoTrans', $noTrans)
            ->where('PCode', $pcode)
            ->set([
                'Jenis' => $jenis,
                'Nilai' => $nilai
            ])
            ->update();
    }

    /**
     * Delete all items for promo
     */
    public function deleteItemsByPromo($noTrans)
    {
        return $this->where('NoTrans', $noTrans)->delete();
    }

    /**
     * Get item count for promo
     */
    public function getItemCount($noTrans)
    {
        return $this->where('NoTrans', $noTrans)->countAllResults();
    }

    /**
     * Bulk insert items
     */
    public function bulkInsertItems($items)
    {
        if (empty($items)) return false;

        return $this->insertBatch($items);
    }

    /**
     * Get products not in promo (for selection)
     */
    public function getAvailableProducts($noTrans)
    {
        $db = \Config\Database::connect();

        // Get products already in promo
        $usedProducts = $this->select('PCode') // mendapatkan PCode/barang dari item promo
            ->where('NoTrans', $noTrans) // filter berdasarkan NoTrans
            ->findColumn('PCode');

        // Get all active products not in promo
        $builder = $db->table('masterbarang');
        $builder->select('PCode, NamaLengkap, Harga1c, Barcode1')
            ->where('Status', 'T')
            ->where('FlagReady', 'Y');

        if (!empty($usedProducts)) {
            $builder->whereNotIn('PCode', $usedProducts); // dikecualikan yang sudah ada di promo
        }

        return $builder->orderBy('NamaLengkap', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Calculate discount preview for item
     */
    public function calculateDiscountPreview($harga, $jenis, $nilai)
    {
        if ($jenis == 'P') {
            // Percentage
            $discount = ($harga * $nilai) / 100;
        } else {
            // Rupiah
            $discount = $nilai;
        }

        $finalPrice = $harga - $discount;
        if ($finalPrice < 0) $finalPrice = 0;

        return [
            'original_price' => $harga,
            'discount_amount' => $discount,
            'final_price' => $finalPrice,
            'percentage' => ($harga > 0) ? round(($discount / $harga) * 100, 2) : 0
        ];
    }
}
