<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OutletModel;

class OutletController extends BaseController
{
    protected $outletModel;
    protected $validation;

    public function __construct()
    {
        $this->outletModel = new OutletModel();
        $this->validation = \Config\Services::validation();

        // TODO: Add middleware untuk check role Admin only
        // if (session()->get('role') != 'Admin Pusat') {
        //     throw new \CodeIgniter\Exceptions\PageNotFoundException();
        // }
    }

    /**
     * Display list of outlets
     */
    public function index()
    {
        $data = [
            'title'   => 'Manajemen Outlet',
            'outlets' => $this->outletModel->getOutletsWithUserCount()
        ];

        return view('admin/outlet/index', $data);
    }

    /**
     * Show create outlet form
     */
    public function create()
    {
        $data = [
            'title'      => 'Tambah Outlet',
            'validation' => $this->validation
        ];

        return view('admin/outlet/create', $data);
    }

    /**
     * Store new outlet
     */
    public function store()
    {
        // Validation rules for create
        $rules = [
            'KdStore'     => 'required|max_length[2]|is_unique[outlets.KdStore]',
            'nama_outlet' => 'required|max_length[100]',
            'alamat'      => 'permit_empty|max_length[255]',
            'telepon'     => 'permit_empty|max_length[20]',
            'is_active'   => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'KdStore'     => strtoupper(trim($this->request->getPost('KdStore'))),
            'nama_outlet' => trim($this->request->getPost('nama_outlet')),
            'alamat'      => trim($this->request->getPost('alamat')),
            'telepon'     => trim($this->request->getPost('telepon')),
            'is_active'   => (int)$this->request->getPost('is_active')
        ];

        try {
            if ($this->outletModel->insert($data)) {
                return redirect()->to('/admin/outlet')->with('success', 'Outlet berhasil ditambahkan');
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan outlet');
            }
        } catch (\Exception $e) {
            log_message('error', 'Store outlet error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }

    /**
     * Show edit outlet form
     */
    public function edit($id)
    {
        $outlet = $this->outletModel->find($id);

        if (!$outlet) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Outlet tidak ditemukan');
        }

        $data = [
            'title'      => 'Edit Outlet',
            'outlet'     => $outlet,
            'validation' => $this->validation
        ];

        return view('admin/outlet/create', $data);
    }

    /**
     * Update outlet
     */
    public function update($id)
    {
        $outlet = $this->outletModel->find($id);

        if (!$outlet) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Outlet tidak ditemukan');
        }

        $currentKdStore = $outlet['KdStore'];
        $newKdStore = strtoupper(trim($this->request->getPost('KdStore')));

        // Validation rules for update
        $rules = [
            'nama_outlet' => 'required|max_length[100]',
            'alamat'      => 'permit_empty|max_length[255]',
            'telepon'     => 'permit_empty|max_length[20]',
            'is_active'   => 'required|in_list[0,1]'
        ];

        // Only check unique if KdStore is being changed
        if ($currentKdStore !== $newKdStore) {
            $rules['KdStore'] = 'required|max_length[2]|is_unique[outlets.KdStore]';
        } else {
            $rules['KdStore'] = 'required|max_length[2]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'KdStore'     => $newKdStore,
            'nama_outlet' => trim($this->request->getPost('nama_outlet')),
            'alamat'      => trim($this->request->getPost('alamat')),
            'telepon'     => trim($this->request->getPost('telepon')),
            'is_active'   => (int)$this->request->getPost('is_active')
        ];

        try {
            if ($this->outletModel->update($id, $data)) {
                return redirect()->to('/admin/outlet')->with('success', 'Outlet berhasil diupdate');
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal mengupdate outlet');
            }
        } catch (\Exception $e) {
            log_message('error', 'Update outlet error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mengupdate data');
        }
    }

    /**
     * Delete outlet
     */
    public function delete($id)
    {
        $outlet = $this->outletModel->find($id);

        if (!$outlet) {
            return redirect()->to('/admin/outlet')->with('error', 'Outlet tidak ditemukan');
        }

        // Check if outlet has transactions
        if ($this->outletModel->hasTransactions($outlet['KdStore'])) {
            return redirect()->to('/admin/outlet')->with('error', 'Outlet tidak dapat dihapus karena sudah memiliki transaksi');
        }

        // Check if outlet has users
        $userCount = $this->getUserCount($id);
        if ($userCount > 0) {
            return redirect()->to('/admin/outlet')->with('error', "Outlet tidak dapat dihapus karena masih memiliki {$userCount} user");
        }

        try {
            if ($this->outletModel->delete($id)) {
                return redirect()->to('/admin/outlet')->with('success', 'Outlet berhasil dihapus');
            } else {
                return redirect()->to('/admin/outlet')->with('error', 'Gagal menghapus outlet');
            }
        } catch (\Exception $e) {
            log_message('error', 'Delete outlet error: ' . $e->getMessage());
            return redirect()->to('/admin/outlet')->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }

    /**
     * Toggle outlet status (active/inactive)
     */
    public function toggleStatus($id)
    {
        try {
            if ($this->outletModel->toggleStatus($id)) {
                return redirect()->to('/admin/outlet')->with('success', 'Status outlet berhasil diubah');
            } else {
                return redirect()->to('/admin/outlet')->with('error', 'Gagal mengubah status outlet');
            }
        } catch (\Exception $e) {
            log_message('error', 'Toggle status error: ' . $e->getMessage());
            return redirect()->to('/admin/outlet')->with('error', 'Terjadi kesalahan saat mengubah status');
        }
    }

    /**
     * Get outlet data for AJAX (for select2, etc)
     */
    public function getOutletData()
    {
        if ($this->request->isAJAX()) {
            $outlets = $this->outletModel->getActiveOutlets();
            return $this->response->setJSON($outlets);
        }

        return $this->response->setStatusCode(403);
    }

    /**
     * Helper method to get user count for outlet
     */
    private function getUserCount($outletId)
    {
        $db = \Config\Database::connect();
        return $db->table('users')->where('outlet_id', $outletId)->countAllResults();
    }

    /**
     * Helper method to prepare form data
     */
    private function prepareFormData()
    {
        return [
            'KdStore'     => strtoupper(trim($this->request->getPost('KdStore'))),
            'nama_outlet' => trim($this->request->getPost('nama_outlet')),
            'alamat'      => trim($this->request->getPost('alamat')),
            'telepon'     => trim($this->request->getPost('telepon')),
            'is_active'   => (int)$this->request->getPost('is_active')
        ];
    }

    /**
     * Get validation rules for create
     */
    private function getCreateValidationRules()
    {
        return [
            'KdStore'     => 'required|max_length[2]|is_unique[outlets.KdStore]',
            'nama_outlet' => 'required|max_length[100]',
            'alamat'      => 'permit_empty|max_length[255]',
            'telepon'     => 'permit_empty|max_length[20]',
            'is_active'   => 'required|in_list[0,1]'
        ];
    }

    /**
     * Get validation rules for update
     */
    private function getUpdateValidationRules($currentKdStore, $newKdStore)
    {
        $rules = [
            'nama_outlet' => 'required|max_length[100]',
            'alamat'      => 'permit_empty|max_length[255]',
            'telepon'     => 'permit_empty|max_length[20]',
            'is_active'   => 'required|in_list[0,1]'
        ];

        // Only check unique if KdStore is being changed
        if ($currentKdStore !== $newKdStore) {
            $rules['KdStore'] = 'required|max_length[2]|is_unique[outlets.KdStore]';
        } else {
            $rules['KdStore'] = 'required|max_length[2]';
        }

        return $rules;
    }
}
