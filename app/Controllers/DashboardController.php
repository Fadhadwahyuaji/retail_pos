<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OutletModel;
use App\Models\UserModel;
use App\Models\BarangModel;
use App\Models\PromoModel;
use App\Models\TransaksiModel;

class DashboardController extends BaseController
{
    protected $outletModel;
    protected $userModel;
    protected $barangModel;
    protected $promoModel;
    protected $transaksiModel;

    public function __construct()
    {
        $this->outletModel = new OutletModel();
        $this->userModel = new UserModel();
        $this->barangModel = new BarangModel();
        $this->promoModel = new PromoModel();
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        $session = session();
        $kdRole = $session->get('kd_role');
        $outletId = $session->get('outlet_id');
        $kdStore = $session->get('kd_store');

        $data = [
            'title' => 'Dashboard',
            'user' => [
                'nama' => $session->get('nama'),
                'kd_role' => $kdRole,
                'nama_role' => $session->get('nama_role'),
                'email' => $session->get('email'),
                'outlet_id' => $outletId,
                'nama_outlet' => $session->get('nama_outlet')
            ]
        ];

        // Load data berdasarkan role
        if ($kdRole == 'AD') {
            $data['stats'] = $this->getAdminStats();
        } elseif ($kdRole == 'MG') {
            $data['stats'] = $this->getManagerStats($kdStore);
        } else {
            $data['stats'] = $this->getCashierStats($kdStore);
        }

        return view('dashboard', $data);
    }

    /**
     * Get statistics for Admin
     */
    private function getAdminStats()
    {
        // Total Outlets (aktif)
        $totalOutlets = $this->outletModel->where('is_active', 1)->countAllResults();

        // Total Users (aktif)
        $totalUsers = $this->userModel->where('is_active', 1)->countAllResults();

        // Total Barang (aktif dan ready)
        $totalBarang = $this->barangModel
            ->where('Status', 'T')
            ->where('FlagReady', 'Y')
            ->countAllResults();

        // Active Promos (promo yang sedang berlangsung)
        $today = date('Y-m-d');
        $activePromos = $this->promoModel
            ->where('Status', '1')
            ->where('TglAwal <=', $today)
            ->where('TglAkhir >=', $today)
            ->countAllResults();

        // Today's transactions
        $todayTransactions = $this->transaksiModel
            ->where('DATE(Tanggal)', date('Y-m-d'))
            ->where('Status', 'T')
            ->countAllResults();

        // Today's total sales
        $todaySales = $this->transaksiModel
            ->selectSum('TotalBayar')
            ->where('DATE(Tanggal)', date('Y-m-d'))
            ->where('Status', 'T')
            ->get()
            ->getRow();

        return [
            'total_outlets' => $totalOutlets,
            'total_users' => $totalUsers,
            'total_barang' => $totalBarang,
            'active_promos' => $activePromos,
            'today_transactions' => $todayTransactions,
            'today_sales' => $todaySales->TotalBayar ?? 0
        ];
    }

    /**
     * Get statistics for Manager
     */
    private function getManagerStats($kdStore)
    {
        if (!$kdStore) {
            return [
                'today_transactions' => 0,
                'today_sales' => 0,
                'monthly_transactions' => 0,
                'monthly_sales' => 0
            ];
        }

        // Today's transactions
        $todayTransactions = $this->transaksiModel
            ->where('KdStore', $kdStore)
            ->where('DATE(Tanggal)', date('Y-m-d'))
            ->where('Status', 'T')
            ->countAllResults();

        // Today's sales
        $todaySales = $this->transaksiModel
            ->selectSum('TotalBayar')
            ->where('KdStore', $kdStore)
            ->where('DATE(Tanggal)', date('Y-m-d'))
            ->where('Status', 'T')
            ->get()
            ->getRow();

        // Monthly transactions
        $monthlyTransactions = $this->transaksiModel
            ->where('KdStore', $kdStore)
            ->where('MONTH(Tanggal)', date('m'))
            ->where('YEAR(Tanggal)', date('Y'))
            ->where('Status', 'T')
            ->countAllResults();

        // Monthly sales
        $monthlySales = $this->transaksiModel
            ->selectSum('TotalBayar')
            ->where('KdStore', $kdStore)
            ->where('MONTH(Tanggal)', date('m'))
            ->where('YEAR(Tanggal)', date('Y'))
            ->where('Status', 'T')
            ->get()
            ->getRow();

        return [
            'today_transactions' => $todayTransactions,
            'today_sales' => $todaySales->TotalBayar ?? 0,
            'monthly_transactions' => $monthlyTransactions,
            'monthly_sales' => $monthlySales->TotalBayar ?? 0
        ];
    }

    /**
     * Get statistics for Cashier
     */
    private function getCashierStats($kdStore)
    {
        if (!$kdStore) {
            return [
                'today_transactions' => 0,
                'today_sales' => 0
            ];
        }

        // Today's transactions
        $todayTransactions = $this->transaksiModel
            ->where('KdStore', $kdStore)
            ->where('DATE(Tanggal)', date('Y-m-d'))
            ->where('Status', 'T')
            ->countAllResults();

        // Today's sales
        $todaySales = $this->transaksiModel
            ->selectSum('TotalBayar')
            ->where('KdStore', $kdStore)
            ->where('DATE(Tanggal)', date('Y-m-d'))
            ->where('Status', 'T')
            ->get()
            ->getRow();

        return [
            'today_transactions' => $todayTransactions,
            'today_sales' => $todaySales->TotalBayar ?? 0
        ];
    }
}
