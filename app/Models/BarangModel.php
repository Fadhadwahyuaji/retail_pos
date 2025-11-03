<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table            = 'masterbarang';
    protected $primaryKey       = 'PCode';
    protected $useAutoIncrement = false; // PCode is manual input
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'PCode',
        'NamaLengkap',
        'NamaStruk',
        'NamaInitial',
        'SatuanSt',
        'Barcode1',
        'Barcode2',
        'Barcode3',
        'Harga1c',      // Harga Jual
        'Harga1b',      // Harga Beli
        'HargaBeli',
        'HPP',
        'Status',
        'FlagReady',
        'Jenis',
        'JenisBarang',
        'KdDivisi',
        'KdKategori',
        'KdBrand',
    ];

    // Dates
    protected $useTimestamps = false; // Menggunakan AddDate & EditDate custom
    protected $dateFormat    = 'datetime';

    // Validation
    protected $validationRules = [
        'PCode'        => 'required|max_length[15]|is_unique[masterbarang.PCode]',
        'NamaLengkap'  => 'required|max_length[75]',
        'NamaStruk'    => 'permit_empty|max_length[30]',
        'Barcode1'     => 'permit_empty|max_length[20]',
        'Harga1c'      => 'required|decimal|greater_than[0]',
        'Harga1b'      => 'permit_empty|decimal',
        'Status'       => 'in_list[T,F]',
    ];

    protected $validationMessages = [
        'PCode' => [
            'required'   => 'Kode Produk harus diisi',
            'max_length' => 'Kode Produk maksimal 15 karakter',
            'is_unique'  => 'Kode Produk sudah digunakan'
        ],
        'NamaLengkap' => [
            'required'   => 'Nama Produk harus diisi',
            'max_length' => 'Nama Produk maksimal 75 karakter'
        ],
        'Harga1c' => [
            'required'      => 'Harga Jual harus diisi',
            'decimal'       => 'Harga Jual harus berupa angka',
            'greater_than'  => 'Harga Jual harus lebih dari 0'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['setTimestamps'];
    protected $beforeUpdate = ['setTimestamps'];

    /**
     * Set timestamps (AddDate & EditDate)
     */
    protected function setTimestamps(array $data)
    {
        $now = date('Y-m-d H:i:s');

        if ($data['data']) {
            if (!isset($data['data']['AddDate'])) {
                $data['data']['AddDate'] = $now;
            }
            $data['data']['EditDate'] = $now;
        }

        return $data;
    }

    /**
     * Get validation rules for CREATE
     */
    public function getCreateValidationRules()
    {
        return [
            'PCode'        => 'required|max_length[15]|is_unique[masterbarang.PCode]',
            'NamaLengkap'  => 'required|max_length[75]',
            'NamaStruk'    => 'permit_empty|max_length[30]',
            'Barcode1'     => 'permit_empty|max_length[20]',
            'Harga1c'      => 'required|decimal|greater_than[0]',
            'Harga1b'      => 'permit_empty|decimal',
            'Status'       => 'in_list[T,F]',
            'FlagReady'    => 'in_list[Y,N]',
        ];
    }

    /**
     * Get validation rules for UPDATE
     */
    public function getUpdateValidationRules($pcode)
    {
        return [
            // PCode tidak perlu validasi karena readonly di form dan tidak diupdate
            'NamaLengkap'  => 'required|max_length[75]',
            'NamaStruk'    => 'permit_empty|max_length[30]',
            'Barcode1'     => 'permit_empty|max_length[20]',
            'Barcode2'     => 'permit_empty|max_length[20]',
            'Barcode3'     => 'permit_empty|max_length[20]',
            'Harga1c'      => 'required|decimal|greater_than[0]',
            'Harga1b'      => 'permit_empty|decimal',
            'Status'       => 'in_list[T,F]',
            'FlagReady'    => 'in_list[Y,N]',
            'SatuanSt'     => 'permit_empty|max_length[10]',
        ];
    }

    /**
     * Custom update method dengan better error handling
     */
    public function updateProduct($pcode, $data)
    {
        try {
            // Set EditDate
            $data['EditDate'] = date('Y-m-d H:i:s');

            // Update data
            $result = $this->update($pcode, $data);

            if (!$result) {
                log_message('error', 'Failed to update product: ' . $pcode . ' - ' . json_encode($this->errors()));
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception in updateProduct: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all active products (FlagReady = Y)
     */
    public function getActiveProducts()
    {
        return $this->where('FlagReady', 'Y')
            ->where('Status', 'T')
            ->findAll();
    }

    /**
     * Search products by keyword (PCode, NamaLengkap, or Barcode)
     */
    public function searchProducts($keyword, $limit = 10)
    {
        return $this->groupStart()
            ->like('PCode', $keyword)
            ->orLike('NamaLengkap', $keyword)
            ->orLike('Barcode1', $keyword)
            ->orLike('Barcode2', $keyword)
            ->orLike('Barcode3', $keyword)
            ->groupEnd()
            ->where('FlagReady', 'Y')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get product by barcode
     */
    public function getByBarcode($barcode)
    {
        return $this->groupStart()
            ->where('Barcode1', $barcode)
            ->orWhere('Barcode2', $barcode)
            ->orWhere('Barcode3', $barcode)
            ->groupEnd()
            ->where('FlagReady', 'Y')
            ->first();
    }

    /**
     * Get product by PCode
     */
    public function getByPCode($pcode)
    {
        return $this->where('PCode', $pcode)->first();
    }

    /**
     * Check if product has transactions
     */
    public function hasTransactions($pcode)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transaksi_detail');
        $count = $builder->where('PCode', $pcode)->countAllResults();
        return $count > 0;
    }

    /**
     * Check if product is in active promo
     */
    public function hasActivePromo($pcode)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('discountdetail');

        $count = $builder->select('discountdetail.*')
            ->join('discountheader', 'discountheader.NoTrans = discountdetail.NoTrans')
            ->where('discountdetail.PCode', $pcode)
            ->where('discountheader.Status', '1')
            ->where('discountheader.TglAkhir >=', date('Y-m-d'))
            ->countAllResults();

        return $count > 0;
    }

    /**
     * Get products with pagination and search
     */
    public function getProductsPaginated($perPage = 10, $keyword = '')
    {
        if ($keyword) {
            $this->groupStart()
                ->like('PCode', $keyword)
                ->orLike('NamaLengkap', $keyword)
                ->orLike('Barcode1', $keyword)
                ->groupEnd();
        }

        return [
            'data' => $this->orderBy('AddDate', 'DESC')
                ->paginate($perPage),
            'pager' => $this->pager
        ];
    }

    /**
     * Toggle product status (Active/Inactive)
     */
    public function toggleStatus($pcode)
    {
        $product = $this->find($pcode);
        if ($product) {
            $newStatus = $product['Status'] == 'T' ? 'F' : 'T';
            return $this->update($pcode, ['Status' => $newStatus]);
        }
        return false;
    }

    /**
     * Toggle FlagReady (Ready/Not Ready)
     */
    public function toggleReady($pcode)
    {
        $product = $this->find($pcode);
        if ($product) {
            $newFlag = $product['FlagReady'] == 'Y' ? 'N' : 'Y';
            return $this->update($pcode, ['FlagReady' => $newFlag]);
        }
        return false;
    }

    /**
     * Get products for dropdown/select
     */
    public function getDropdownOptions()
    {
        $products = $this->getActiveProducts();
        $options = ['' => '-- Pilih Produk --'];

        foreach ($products as $product) {
            $options[$product['PCode']] = $product['PCode'] . ' - ' . $product['NamaLengkap'] . ' (Rp ' . number_format($product['Harga1c'], 0, ',', '.') . ')';
        }

        return $options;
    }

    /**
     * Calculate profit margin
     */
    public function getProfit($pcode)
    {
        $product = $this->find($pcode);
        if ($product) {
            $hargaJual = $product['Harga1c'];
            $hargaBeli = $product['Harga1b'] ?: $product['HargaBeli'];

            if ($hargaBeli > 0) {
                $profit = $hargaJual - $hargaBeli;
                $margin = ($profit / $hargaBeli) * 100;

                return [
                    'profit' => $profit,
                    'margin' => round($margin, 2)
                ];
            }
        }

        return ['profit' => 0, 'margin' => 0];
    }

    /**
     * Get products with low stock (for future stock management)
     */
    public function getLowStockProducts($threshold = 10)
    {
        // Placeholder untuk future stock management
        // Saat ini return empty karena tidak ada field stock
        return [];
    }
}
