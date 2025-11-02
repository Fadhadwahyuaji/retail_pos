<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function index()
    {
        $session = session();

        $data = [
            'title' => 'Dashboard',
            'user' => [
                'nama' => $session->get('nama'),
                'kd_role' => $session->get('kd_role'),
                'nama_role' => $session->get('nama_role'),
                'email' => $session->get('email')
            ]
        ];

        return view('dashboard', $data);
    }
}
