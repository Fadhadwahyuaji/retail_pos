<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\OutletModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $roleModel;
    protected $outletModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();

        // Load helper models
        $this->roleModel = model('RoleModel');
        $this->outletModel = model('OutletModel');

        // TODO: Add middleware untuk check role Admin only
    }

    /**
     * Display list of users
     */
    public function index()
    {
        $filters = [
            'search'    => $this->request->getGet('search') ?? '',
            'role_id'   => $this->request->getGet('role_id') ?? '',
            'outlet_id' => $this->request->getGet('outlet_id') ?? '',
            'is_active' => $this->request->getGet('is_active') ?? '',
        ];

        $perPage = 10;
        $result = $this->userModel->getUsersPaginated($perPage, $filters);

        $data = [
            'title'   => 'User Management',
            'users'   => $result['data'],
            'pager'   => $result['pager'],
            'filters' => $filters,
            'roles'   => $this->roleModel->findAll(),
            'outlets' => $this->outletModel->findAll()
        ];

        return view('admin/user/index', $data);
    }

    /**
     * Show create user form
     */
    public function create()
    {
        $data = [
            'title'      => 'Tambah User',
            'validation' => $this->validation,
            'roles'      => $this->roleModel->findAll(),
            'outlets'    => $this->outletModel->getActiveOutlets()
        ];

        return view('admin/user/form', $data);
    }

    /**
     * Store new user
     */
    public function store()
    {
        // Use create validation rules
        $rules = $this->userModel->getCreateValidationRules();

        if (!$this->validate($rules)) {
            log_message('debug', 'Store validation errors: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validate outlet for role
        $roleId = $this->request->getPost('role_id');
        $outletId = $this->request->getPost('outlet_id');

        if (!$this->userModel->validateOutletForRole($roleId, $outletId)) {
            return redirect()->back()->withInput()->with('error', 'Outlet wajib dipilih untuk role Manajer/Kasir');
        }

        // Prepare data - set outlet_id to NULL if empty
        $data = [
            'role_id'   => $roleId,
            'nama'      => $this->request->getPost('nama'),
            'email'     => $this->request->getPost('email'),
            'password'  => $this->request->getPost('password'),
            'outlet_id' => (!empty($outletId) && $outletId !== '' && $outletId > 0) ? $outletId : null,
            'is_active' => $this->request->getPost('is_active') ?? 1
        ];

        log_message('debug', 'Store data: ' . json_encode($data));

        try {
            if ($this->userModel->insert($data)) {
                return redirect()->to('/admin/user')->with('success', 'User berhasil ditambahkan');
            } else {
                $errors = $this->userModel->errors();
                log_message('debug', 'Store model errors: ' . json_encode($errors));
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan user: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception during user store: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show edit user form
     */
    public function edit($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan');
        }

        $data = [
            'title'      => 'Edit User',
            'user'       => $user,
            'validation' => $this->validation,
            'roles'      => $this->roleModel->findAll(),
            'outlets'    => $this->outletModel->getActiveOutlets()
        ];

        return view('admin/user/form', $data);
    }

    /**
     * Update user
     */
    public function update($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan');
        }

        // Debug: Log request data
        log_message('debug', 'Update User ID: ' . $id);
        log_message('debug', 'POST Data: ' . json_encode($this->request->getPost()));

        // Use update validation rules with ID exception
        $rules = $this->userModel->getUpdateValidationRules($id);

        if (!$this->validate($rules)) {
            log_message('debug', 'Update validation errors: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validate outlet for role
        $roleId = $this->request->getPost('role_id');
        $outletId = $this->request->getPost('outlet_id');

        // Debug outlet validation
        log_message('debug', 'Role ID: ' . $roleId . ', Outlet ID: ' . $outletId);

        if (!$this->userModel->validateOutletForRole($roleId, $outletId)) {
            log_message('debug', 'Outlet validation failed');
            return redirect()->back()->withInput()->with('error', 'Outlet wajib dipilih untuk role Manajer/Kasir');
        }

        // Prepare data - handle empty outlet_id
        $data = [
            'role_id'   => $roleId,
            'nama'      => $this->request->getPost('nama'),
            'email'     => $this->request->getPost('email'),
            'outlet_id' => (!empty($outletId) && $outletId !== '' && $outletId > 0) ? $outletId : null,
            'is_active' => $this->request->getPost('is_active') ?? 1
        ];

        // Only update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password) && trim($password) !== '') {
            $data['password'] = $password;
        }

        log_message('debug', 'Data to update: ' . json_encode($data));

        try {
            // Temporarily disable validation for update to use custom rules
            $this->userModel->skipValidation(true);

            if ($this->userModel->update($id, $data)) {
                log_message('debug', 'User updated successfully');
                return redirect()->to('/admin/user')->with('success', 'User berhasil diupdate');
            } else {
                log_message('debug', 'User update failed');
                $errors = $this->userModel->errors();
                log_message('debug', 'Model errors: ' . json_encode($errors));
                return redirect()->back()->withInput()->with('error', 'Gagal mengupdate user: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception during user update: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        } finally {
            // Re-enable validation
            $this->userModel->skipValidation(false);
        }
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/user')->with('error', 'User tidak ditemukan');
        }

        // Check if user can be deleted
        if (!$this->userModel->canDelete($id)) {
            return redirect()->to('/admin/user')->with('error', 'User tidak dapat dihapus karena sudah memiliki transaksi');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/user')->with('success', 'User berhasil dihapus');
        } else {
            return redirect()->to('/admin/user')->with('error', 'Gagal menghapus user');
        }
    }

    /**
     * Toggle user status
     */
    public function toggleStatus($id)
    {
        if ($this->userModel->toggleStatus($id)) {
            return redirect()->to('/admin/user')->with('success', 'Status user berhasil diubah');
        } else {
            return redirect()->to('/admin/user')->with('error', 'Gagal mengubah status user');
        }
    }

    /**
     * Show change password form
     */
    public function changePassword($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan');
        }

        $data = [
            'title' => 'Ganti Password',
            'user'  => $user
        ];

        return view('admin/user/change_password', $data);
    }

    /**
     * Update password
     */
    public function updatePassword($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan');
        }

        // Validation
        $rules = [
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $newPassword = $this->request->getPost('new_password');

        if ($this->userModel->changePassword($id, $newPassword)) {
            return redirect()->to('/admin/user')->with('success', 'Password berhasil diubah');
        } else {
            return redirect()->back()->with('error', 'Gagal mengubah password');
        }
    }

    /**
     * Get users by outlet (AJAX)
     */
    public function getUsersByOutlet()
    {
        if ($this->request->isAJAX()) {
            $outletId = $this->request->getGet('outlet_id');
            $users = $this->userModel->getUsersByOutlet($outletId);

            return $this->response->setJSON($users);
        }

        return $this->response->setStatusCode(403);
    }

    /**
     * User statistics
     */
    public function stats()
    {
        $stats = $this->userModel->getUserStats();

        $data = [
            'title' => 'User Statistics',
            'stats' => $stats
        ];

        return view('admin/user/stats', $data);
    }
}
