<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    // Menampilkan halaman login
    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('auth/login');
    }

    // Memproses login
    public function login()
    {
        $session = session();
        $model = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->select('users.*, roles.KdRole as kd_role, roles.nama_role')
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->where('users.email', $email)
            ->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {

                // Set session data
                $sessionData = [
                    'id' => $user['id'],
                    'nama' => $user['nama'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id'],
                    'kd_role' => $user['kd_role'],
                    'nama_role' => $user['nama_role'],
                    'outlet_id'  => $user['outlet_id'],
                    'logged_in' => true,
                ];
                $session->set($sessionData);
                return redirect()->to(base_url('dashboard'));
            } else {
                $session->setFlashdata('error', 'Password salah.');
                return redirect()->back();
            }
        } else {
            $session->setFlashdata('error', 'Email tidak ditemukan.');
            return redirect()->back();
        }
    }

    // Logout user
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
