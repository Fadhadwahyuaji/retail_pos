<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    // Fungsi yang dijalankan SEBELUM Controller diakses
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            session()->setFlashdata('msg', 'Anda harus login terlebih dahulu.');
            return redirect()->to(base_url('login'));
        }
    }

    // Fungsi yang dijalankan SETELAH Controller diakses (biasanya dikosongkan)
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
