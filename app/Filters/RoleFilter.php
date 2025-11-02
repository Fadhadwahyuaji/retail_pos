<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Pastikan user sudah login dulu
        if (!session()->get('logged_in')) {
            session()->setFlashdata('error', 'Anda harus login terlebih dahulu.');
            return redirect()->to(base_url('login'));
        }

        // Dapatkan role user dari session
        $userRole = session()->get('kd_role');

        // Jika tidak ada arguments (role yang diizinkan), izinkan akses
        if (empty($arguments)) {
            return;
        }

        // Periksa apakah role user ada dalam daftar role yang diizinkan
        if (!in_array($userRole, $arguments)) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
            return redirect()->to(base_url('dashboard'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
