<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PromoModel;
use App\Models\PromoDetailModel;
use App\Models\OutletModel;

class PromoController extends BaseController
{
    protected $promoModel;
    protected $promoDetailModel;
    protected $outletModel;
    protected $validation;

    public function __construct()
    {
        $this->promoModel = new PromoModel();
        $this->promoDetailModel = new PromoDetailModel();
        $this->outletModel = new OutletModel();
        $this->validation = \Config\Services::validation();

        // TODO: Add middleware untuk check role Admin only
    }

    /**
     * Display list of promos
     */
    public function index()
    {
        $promos = $this->promoModel->getPromosWithDetails();

        // Add stats to each promo
        foreach ($promos as &$promo) {
            $stats = $this->promoModel->getPromoStats($promo['NoTrans']);
            $promo['item_count'] = $stats['item_count'];
            $promo['transaction_count'] = $stats['transaction_count'];
        }

        $data = [
            'title'  => 'Promo Management',
            'promos' => $promos
        ];

        return view('admin/promo/index', $data);
    }

    /**
     * Show create promo form
     */
    public function create()
    {
        $data = [
            'title'      => 'Tambah Promo',
            'validation' => $this->validation,
            'outlets'    => $this->outletModel->getActiveOutlets()
        ];

        return view('admin/promo/form', $data);
    }

    /**
     * Store new promo
     */
    public function store()
    {
        // Get validation rules for creation (without id)
        $rules = $this->promoModel->getValidationRules();

        // Validation
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Additional validation
        $tglAwal = $this->request->getPost('TglAwal');
        $tglAkhir = $this->request->getPost('TglAkhir');

        if (strtotime($tglAkhir) < strtotime($tglAwal)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal Akhir harus lebih besar dari Tanggal Awal');
        }

        // Prepare hari_berlaku
        $hariBerlaku = $this->request->getPost('hari_berlaku');
        if (is_array($hariBerlaku)) {
            $hariBerlaku = implode(',', $hariBerlaku);
        } else {
            $hariBerlaku = '1,2,3,4,5,6,7'; // Default all days
        }

        // Prepare data
        $data = [
            'NoTrans'      => strtoupper($this->request->getPost('NoTrans')),
            'TglTrans'     => date('Y-m-d'),
            'Ketentuan'    => $this->request->getPost('Ketentuan'),
            'TglAwal'      => $tglAwal,
            'TglAkhir'     => $tglAkhir,
            'jam_mulai'    => $this->request->getPost('jam_mulai') ?: '00:00:00',
            'jam_selesai'  => $this->request->getPost('jam_selesai') ?: '23:59:59',
            'hari_berlaku' => $hariBerlaku,
            'Minimum'      => $this->request->getPost('Minimum') ?: 0,
            'Status'       => $this->request->getPost('Status') ?? '1',
            'outlet_id'    => $this->request->getPost('outlet_id') ?: null
        ];

        if ($this->promoModel->insert($data)) {
            return redirect()->to('/admin/promo/items/' . $data['NoTrans'])
                ->with('success', 'Promo berhasil ditambahkan. Silakan tambahkan produk yang dapat promo.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan promo');
        }
    }

    /**
     * Show edit promo form
     */
    public function edit($noTrans)
    {
        $promo = $this->promoModel->find($noTrans);

        if (!$promo) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Promo tidak ditemukan');
        }

        $data = [
            'title'      => 'Edit Promo',
            'promo'      => $promo,
            'validation' => $this->validation,
            'outlets'    => $this->outletModel->getActiveOutlets()
        ];

        return view('admin/promo/form', $data);
    }

    /**
     * Update promo
     */
    public function update($noTrans)
    {
        $promo = $this->promoModel->find($noTrans);

        if (!$promo) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Promo tidak ditemukan');
        }

        // Get validation rules for update (with id)
        $rules = $this->promoModel->getValidationRules(['id' => $noTrans]);

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Additional validation
        $tglAwal = $this->request->getPost('TglAwal');
        $tglAkhir = $this->request->getPost('TglAkhir');

        if (strtotime($tglAkhir) < strtotime($tglAwal)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal Akhir harus lebih besar dari Tanggal Awal');
        }

        // Prepare hari_berlaku
        $hariBerlaku = $this->request->getPost('hari_berlaku');
        if (is_array($hariBerlaku)) {
            $hariBerlaku = implode(',', $hariBerlaku);
        }

        // Prepare data
        $data = [
            'Ketentuan'    => $this->request->getPost('Ketentuan'),
            'TglAwal'      => $tglAwal,
            'TglAkhir'     => $tglAkhir,
            'jam_mulai'    => $this->request->getPost('jam_mulai'),
            'jam_selesai'  => $this->request->getPost('jam_selesai'),
            'hari_berlaku' => $hariBerlaku,
            'Minimum'      => $this->request->getPost('Minimum'),
            'Status'       => $this->request->getPost('Status'),
            'outlet_id'    => $this->request->getPost('outlet_id') ?: null
        ];

        if ($this->promoModel->update($noTrans, $data)) {
            return redirect()->to('/admin/promo')->with('success', 'Promo berhasil diupdate');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate promo');
        }
    }

    /**
     * Delete promo
     */
    public function delete($noTrans)
    {
        $promo = $this->promoModel->find($noTrans);

        if (!$promo) {
            return redirect()->to('/admin/promo')->with('error', 'Promo tidak ditemukan');
        }

        // Check if promo has transactions
        if ($this->promoModel->hasTransactions($noTrans)) {
            return redirect()->to('/admin/promo')->with('error', 'Promo tidak dapat dihapus karena sudah digunakan dalam transaksi');
        }

        // Delete items first
        $this->promoDetailModel->deleteItemsByPromo($noTrans);

        // Delete promo
        if ($this->promoModel->delete($noTrans)) {
            return redirect()->to('/admin/promo')->with('success', 'Promo berhasil dihapus');
        } else {
            return redirect()->to('/admin/promo')->with('error', 'Gagal menghapus promo');
        }
    }

    /**
     * Toggle promo status
     */
    public function toggleStatus($noTrans)
    {
        if ($this->promoModel->toggleStatus($noTrans)) {
            return redirect()->to('/admin/promo')->with('success', 'Status promo berhasil diubah');
        } else {
            return redirect()->to('/admin/promo')->with('error', 'Gagal mengubah status promo');
        }
    }

    /**
     * Manage promo items
     */
    public function items($noTrans)
    {
        $promo = $this->promoModel->find($noTrans);

        if (!$promo) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Promo tidak ditemukan');
        }

        $items = $this->promoDetailModel->getItemsByPromo($noTrans);
        $availableProducts = $this->promoDetailModel->getAvailableProducts($noTrans);

        $data = [
            'title'             => 'Kelola Item Promo',
            'promo'             => $promo,
            'items'             => $items,
            'availableProducts' => $availableProducts
        ];

        return view('admin/promo/items', $data);
    }


    /**
     * Add item to promo (AJAX)
     */
    public function addItem()
    {
        // Set response headers
        $this->response->setContentType('application/json');

        try {
            // Validate request method
            if ($this->request->getMethod() !== 'POST') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Method tidak valid'
                ]);
            }

            // Validate AJAX request (optional untuk keamanan)
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Request tidak valid'
                ]);
            }

            // Get form data
            $noTrans = $this->request->getPost('NoTrans');
            $pcode   = $this->request->getPost('PCode');
            $jenis   = $this->request->getPost('Jenis');
            $nilai   = $this->request->getPost('Nilai');

            // Validate required fields
            if (empty($noTrans)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Kode promo tidak boleh kosong'
                ]);
            }

            if (empty($pcode)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Produk harus dipilih'
                ]);
            }

            if (empty($jenis) || !in_array($jenis, ['P', 'R'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Jenis diskon harus dipilih (P/R)'
                ]);
            }

            if (empty($nilai) || !is_numeric($nilai) || $nilai <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Nilai diskon harus berupa angka lebih dari 0'
                ]);
            }

            // Validate percentage
            if ($jenis == 'P' && $nilai > 100) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Diskon persen maksimal 100%'
                ]);
            }

            // Check if promo exists
            $promo = $this->promoModel->find($noTrans);
            if (!$promo) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Promo tidak ditemukan'
                ]);
            }

            // Check if product exists
            $db = \Config\Database::connect();
            $product = $db->table('masterbarang')
                ->where('PCode', $pcode)
                ->get()
                ->getRowArray();

            if (!$product) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ]);
            }

            // Check if item already exists in promo
            if ($this->promoDetailModel->itemExists($noTrans, $pcode)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Produk sudah ada dalam promo'
                ]);
            }

            // Validate discount against product price
            if ($jenis == 'R' && $nilai > $product['Harga1c']) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Diskon rupiah tidak boleh lebih dari harga produk (Rp ' . number_format($product['Harga1c'], 0, ',', '.') . ')'
                ]);
            }

            // Prepare insert data
            $insertData = [
                'NoTrans' => $noTrans,
                'PCode'   => $pcode,
                'Jenis'   => $jenis,
                'Nilai'   => floatval($nilai)
            ];

            // Insert item
            if ($this->promoDetailModel->insert($insertData)) {
                // Log successful addition for monitoring
                log_message('info', "Promo item added - NoTrans: $noTrans, PCode: $pcode, User: " . session()->get('username'));

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Produk "' . $product['NamaLengkap'] . '" berhasil ditambahkan ke promo'
                ]);
            } else {
                $errors = $this->promoDetailModel->errors();

                // Log validation errors
                log_message('error', "Failed to add promo item - Validation: " . json_encode($errors));

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menambahkan item: ' . implode(', ', $errors)
                ]);
            }
        } catch (\Exception $e) {
            // Log critical errors
            log_message('critical', "Exception in addItem - Message: {$e->getMessage()}, File: {$e->getFile()}, Line: {$e->getLine()}");

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Remove item from promo (AJAX)
     */
    public function removeItem()
    {
        if ($this->request->isAJAX()) {
            $noTrans = $this->request->getPost('NoTrans');
            $pcode   = $this->request->getPost('PCode');

            if ($this->promoDetailModel->removeItem($noTrans, $pcode)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Item berhasil dihapus'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus item'
                ]);
            }
        }

        return $this->response->setStatusCode(403);
    }

    /**
     * Calculate discount preview (AJAX)
     */
    public function calculateDiscount()
    {
        if ($this->request->isAJAX()) {
            $harga = $this->request->getPost('harga');
            $jenis = $this->request->getPost('jenis');
            $nilai = $this->request->getPost('nilai');

            $result = $this->promoDetailModel->calculateDiscountPreview($harga, $jenis, $nilai);

            return $this->response->setJSON($result);
        }

        return $this->response->setStatusCode(403);
    }

    /**
     * View promo detail
     */
    public function detail($noTrans)
    {
        // Gunakan method khusus untuk detail view
        $promo = $this->promoModel->getPromoWithItemsForDetail($noTrans);

        if (!$promo) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Promo tidak ditemukan');
        }

        // Get stats
        $stats = $this->promoModel->getPromoStats($noTrans);

        // Check for orphaned items (products that no longer exist)
        $orphanedItems = [];
        foreach ($promo['items'] as $item) {
            if (!$item['NamaLengkap']) {
                $orphanedItems[] = $item['PCode'];
            }
        }

        if (!empty($orphanedItems)) {
            log_message('warning', "Promo $noTrans has orphaned items: " . implode(', ', $orphanedItems));
        }

        $data = [
            'title' => 'Detail Promo',
            'promo' => $promo,
            'stats' => $stats,
            'orphanedItems' => $orphanedItems
        ];

        return view('admin/promo/detail', $data);
    }
}
