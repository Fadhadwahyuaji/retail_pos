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
        // Validation
        if (!$this->validate($this->promoModel->getValidationRules())) {
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

        // Validation rules
        $rules = $this->promoModel->getValidationRules();
        $rules['NoTrans'] = "required|max_length[11]|is_unique[discountheader.NoTrans,NoTrans,{$noTrans}]";

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
        if ($this->request->isAJAX()) {
            $noTrans = $this->request->getPost('NoTrans');
            $pcode   = $this->request->getPost('PCode');
            $jenis   = $this->request->getPost('Jenis');
            $nilai   = $this->request->getPost('Nilai');

            // Check if item already exists
            if ($this->promoDetailModel->itemExists($noTrans, $pcode)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Produk sudah ada dalam promo'
                ]);
            }

            if ($this->promoDetailModel->addItem($noTrans, $pcode, $jenis, $nilai)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Item berhasil ditambahkan'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menambahkan item'
                ]);
            }
        }

        return $this->response->setStatusCode(403);
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
        $promo = $this->promoModel->getPromoWithItems($noTrans);

        if (!$promo) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Promo tidak ditemukan');
        }

        $stats = $this->promoModel->getPromoStats($noTrans);

        $data = [
            'title' => 'Detail Promo',
            'promo' => $promo,
            'stats' => $stats
        ];

        return view('admin/promo/detail', $data);
    }
}
