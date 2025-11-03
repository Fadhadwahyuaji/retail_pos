<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Menampilkan halaman login
     */
    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to($this->getRedirectByRole(session()->get('kd_role')));
        }

        return view('auth/login');
    }

    /**
     * Memproses login
     */
    public function login()
    {
        $session = session();

        // Validation rules
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // PERBAIKAN: Sesuaikan nama kolom dengan struktur tabel
        $user = $this->userModel->select('users.*, roles.KdRole as kd_role, roles.nama_role, outlets.KdStore, outlets.nama_outlet')
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->join('outlets', 'outlets.id = users.outlet_id', 'left')
            ->where('users.email', $email)
            ->first();

        if (!$user) {
            $session->setFlashdata('error', 'Email tidak terdaftar');
            return redirect()->back()->withInput();
        }

        if (!password_verify($password, $user['password'])) {
            $session->setFlashdata('error', 'Password salah');
            return redirect()->back()->withInput();
        }

        if (!$user['is_active']) {
            $session->setFlashdata('error', 'Akun Anda tidak aktif. Hubungi administrator.');
            return redirect()->back();
        }

        // Set session data dengan data lengkap
        $sessionData = [
            'user_id' => $user['id'],
            'nama' => $user['nama'],
            'email' => $user['email'],
            'role_id' => $user['role_id'],
            'kd_role' => $user['kd_role'],
            'nama_role' => $user['nama_role'],
            'outlet_id' => $user['outlet_id'],
            'kdstore' => $user['KdStore'] ?? null,
            'nama_outlet' => $user['nama_outlet'] ?? null,
            'logged_in' => true,
            'user_data' => [
                'id' => $user['id'],
                'nama' => $user['nama'],
                'email' => $user['email'],
                'kd_role' => $user['kd_role'],
                'nama_role' => $user['nama_role'],
                'outlet_id' => $user['outlet_id'],
                'kdstore' => $user['KdStore'] ?? null,
                'nama_outlet' => $user['nama_outlet'] ?? null
            ]
        ];

        $session->set($sessionData);

        // Log activity
        log_message('info', 'User login: ' . $email . ' (Role: ' . $user['nama_role'] . ', Outlet: ' . ($user['nama_outlet'] ?? 'N/A') . ')');

        $session->setFlashdata('success', 'Selamat datang, ' . $user['nama'] . '!');

        return redirect()->to($this->getRedirectByRole($user['kd_role']));
    }

    /**
     * Logout user
     */
    public function logout()
    {
        $email = session()->get('email');
        $nama = session()->get('nama');

        // Log activity
        log_message('info', 'User logout: ' . $email . ' (' . $nama . ')');

        session()->destroy();

        return redirect()->to('/login')
            ->with('success', 'Anda telah berhasil logout');
    }

    /**
     * Get redirect URL by role
     */
    private function getRedirectByRole($kdRole)
    {
        switch ($kdRole) {
            case 'AD': // Admin
                return base_url('dashboard');
            case 'MG': // Manager
                return base_url('dashboard');
            case 'KS': // Kasir
                return base_url('kasir/pos');
            default:
                return base_url('dashboard');
        }
    }
}
