<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BarangModel;

class BarangController extends BaseController
{
    protected $barangModel;
    protected $validation;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->validation = \Config\Services::validation();

        // TODO: Add middleware untuk check role (Admin/Manajer)
    }

    /**
     * Display list of products
     */
    public function index()
    {
        $keyword = $this->request->getGet('search') ?? '';
        $perPage = 10;

        $result = $this->barangModel->getProductsPaginated($perPage, $keyword);

        $data = [
            'title'    => 'Master Barang',
            'products' => $result['data'],
            'pager'    => $result['pager'],
            'keyword'  => $keyword
        ];

        return view('admin/barang/index', $data);
    }

    /**
     * Show create product form
     */
    public function create()
    {
        $data = [
            'title'      => 'Tambah Produk',
            'validation' => $this->validation
        ];

        return view('admin/barang/form', $data);
    }

    /**
     * Store new product
     */
    public function store()
    {
        // Validation menggunakan rules untuk CREATE
        if (!$this->validate($this->barangModel->getCreateValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data
        $data = [
            'PCode'        => strtoupper($this->request->getPost('PCode')),
            'NamaLengkap'  => $this->request->getPost('NamaLengkap'),
            'NamaStruk'    => $this->request->getPost('NamaStruk') ?: $this->request->getPost('NamaLengkap'),
            'NamaInitial'  => $this->request->getPost('NamaInitial') ?: '',
            'SatuanSt'     => $this->request->getPost('SatuanSt') ?: 'PCS',
            'Barcode1'     => $this->request->getPost('Barcode1') ?: '',
            'Barcode2'     => $this->request->getPost('Barcode2') ?: '',
            'Barcode3'     => $this->request->getPost('Barcode3') ?: '',
            'Harga1c'      => $this->request->getPost('Harga1c'), // Harga Jual
            'Harga1b'      => $this->request->getPost('Harga1b') ?: 0, // Harga Beli
            'HargaBeli'    => $this->request->getPost('Harga1b') ?: 0,
            'HPP'          => $this->request->getPost('Harga1b') ?: 0,
            'Status'       => $this->request->getPost('Status') ?? 'T',
            'FlagReady'    => $this->request->getPost('FlagReady') ?? 'Y',
            'Jenis'        => $this->request->getPost('Jenis') ?? '1',
            'JenisBarang'  => $this->request->getPost('JenisBarang') ?? '',
            'AddDate'      => date('Y-m-d H:i:s'),
            'EditDate'     => date('Y-m-d H:i:s'),
        ];

        if ($this->barangModel->insert($data)) {
            return redirect()->to('/admin/barang')->with('success', 'Produk berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan produk');
        }
    }

    /**
     * Show edit product form
     */
    public function edit($pcode)
    {
        $product = $this->barangModel->find($pcode);

        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produk tidak ditemukan');
        }

        $data = [
            'title'      => 'Edit Produk',
            'product'    => $product,
            'validation' => $this->validation
        ];

        return view('admin/barang/form', $data);
    }

    /**
     * Update product
     */
    public function update($pcode)
    {
        $product = $this->barangModel->find($pcode);

        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produk tidak ditemukan');
        }

        // Validation menggunakan rules untuk UPDATE
        if (!$this->validate($this->barangModel->getUpdateValidationRules($pcode))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data - TIDAK termasuk PCode karena primary key tidak bisa diubah
        $data = [
            'NamaLengkap'  => $this->request->getPost('NamaLengkap'),
            'NamaStruk'    => $this->request->getPost('NamaStruk') ?: $this->request->getPost('NamaLengkap'),
            'NamaInitial'  => $this->request->getPost('NamaInitial') ?: '',
            'SatuanSt'     => $this->request->getPost('SatuanSt') ?: 'PCS',
            'Barcode1'     => $this->request->getPost('Barcode1') ?: '',
            'Barcode2'     => $this->request->getPost('Barcode2') ?: '',
            'Barcode3'     => $this->request->getPost('Barcode3') ?: '',
            'Harga1c'      => $this->request->getPost('Harga1c'),
            'Harga1b'      => $this->request->getPost('Harga1b') ?: 0,
            'HargaBeli'    => $this->request->getPost('Harga1b') ?: 0,
            'HPP'          => $this->request->getPost('Harga1b') ?: 0,
            'Status'       => $this->request->getPost('Status') ?? 'T',
            'FlagReady'    => $this->request->getPost('FlagReady') ?? 'Y',
            'Jenis'        => $this->request->getPost('Jenis') ?? '1',
            'JenisBarang'  => $this->request->getPost('JenisBarang') ?? '',
            'EditDate'     => date('Y-m-d H:i:s'),
        ];

        try {
            if ($this->barangModel->update($pcode, $data)) {
                return redirect()->to('/admin/barang')->with('success', 'Produk berhasil diupdate');
            } else {
                // Get validation errors if any
                $errors = $this->barangModel->errors();
                if (!empty($errors)) {
                    return redirect()->back()->withInput()->with('errors', $errors);
                }
                return redirect()->back()->withInput()->with('error', 'Gagal mengupdate produk');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error updating product: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete product
     */
    public function delete($pcode)
    {
        $product = $this->barangModel->find($pcode);

        if (!$product) {
            return redirect()->to('/admin/barang')->with('error', 'Produk tidak ditemukan');
        }

        // Check if product has transactions
        if ($this->barangModel->hasTransactions($pcode)) {
            return redirect()->to('/admin/barang')->with('error', 'Produk tidak dapat dihapus karena sudah memiliki transaksi');
        }

        // Check if product in active promo
        if ($this->barangModel->hasActivePromo($pcode)) {
            return redirect()->to('/admin/barang')->with('error', 'Produk tidak dapat dihapus karena sedang dalam promo aktif');
        }

        if ($this->barangModel->delete($pcode)) {
            return redirect()->to('/admin/barang')->with('success', 'Produk berhasil dihapus');
        } else {
            return redirect()->to('/admin/barang')->with('error', 'Gagal menghapus produk');
        }
    }

    /**
     * Toggle product status
     */
    public function toggleStatus($pcode)
    {
        if ($this->barangModel->toggleStatus($pcode)) {
            return redirect()->to('/admin/barang')->with('success', 'Status produk berhasil diubah');
        } else {
            return redirect()->to('/admin/barang')->with('error', 'Gagal mengubah status produk');
        }
    }

    /**
     * Toggle FlagReady
     */
    public function toggleReady($pcode)
    {
        if ($this->barangModel->toggleReady($pcode)) {
            return redirect()->to('/admin/barang')->with('success', 'Flag ready berhasil diubah');
        } else {
            return redirect()->to('/admin/barang')->with('error', 'Gagal mengubah flag ready');
        }
    }

    /**
     * Search products (AJAX)
     */
    public function search()
    {
        if ($this->request->isAJAX()) {
            $keyword = $this->request->getGet('q') ?? '';
            $products = $this->barangModel->searchProducts($keyword, 10);

            return $this->response->setJSON($products);
        }

        return $this->response->setStatusCode(403);
    }

    /**
     * Get product by barcode (AJAX)
     */
    public function getByBarcode()
    {
        if ($this->request->isAJAX()) {
            $barcode = $this->request->getGet('barcode');
            $product = $this->barangModel->getByBarcode($barcode);

            if ($product) {
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $product
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
        }

        return $this->response->setStatusCode(403);
    }

    /**
     * View product detail
     */
    public function detail($pcode)
    {
        $product = $this->barangModel->find($pcode);

        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produk tidak ditemukan');
        }

        $profit = $this->barangModel->getProfit($pcode);

        $data = [
            'title'   => 'Detail Produk',
            'product' => $product,
            'profit'  => $profit
        ];

        return view('admin/barang/detail', $data);
    }
}
