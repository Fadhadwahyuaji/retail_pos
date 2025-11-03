<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class UserManagementController extends BaseController
{
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        // Debug: cek apakah data ada
        $users = $this->userModel->getAllUsersWithRole();

        // Uncomment untuk debug (hapus setelah selesai)
        dd($users);

        $data['users'] = $users;
        $data['title'] = 'Manajemen User';

        return view('admin/management_user/index', $data);
    }

    public function create()
    {
        // Ambil daftar semua roles untuk dropdown
        $data['roles'] = $this->roleModel->findAll();
        $data['title'] = 'Tambah User Baru';

        return view('admin/management_user/create', $data);
    }

    // Fungsi untuk menyimpan data dari Form (CREATE 2)
    public function save()
    {
        // 1. Validasi Input
        $rules = [
            'nama'      => 'required|min_length[3]|max_length[100]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
            'pass_confirm' => 'required_with[password]|matches[password]',
            'role_id'   => 'required',
            // 'outlet_id' => 'required', // Optional, tergantung role
        ];

        if (!$this->validate($rules)) {
            // Jika validasi gagal, kembali ke form dengan error
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Ambil Data dari Form
        $data = [
            'nama'      => $this->request->getPost('nama'),
            'email'     => $this->request->getPost('email'),
            // Password akan di-hash otomatis oleh UserModel (fitur beforeInsert)
            'password'  => $this->request->getPost('password'),
            'role_id'   => $this->request->getPost('role_id'),
            'outlet_id' => $this->request->getPost('outlet_id') ?? 0, // Default 0
            'is_active' => 1, // Aktifkan secara default
        ];

        // 3. Simpan Data
        $this->userModel->save($data);

        // 4. Redirect dengan pesan sukses
        session()->setFlashdata('success', 'User baru berhasil ditambahkan.');
        return redirect()->to(base_url('admin/management_user/index'));
    }
}
