<?php

namespace App\Controllers\Manajer;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\OutletModel;
use App\Models\UserModel;

class LaporanController extends BaseController
{
    protected $transaksiModel;
    protected $outletModel;
    protected $userModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->outletModel = new OutletModel();
        $this->userModel = new UserModel();
    }

    /**
     * Laporan Dashboard
     */
    public function index()
    {
        // Get user outlet
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user || !$user['outlet_id']) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke outlet');
        }

        $outlet = $this->outletModel->find($user['outlet_id']);
        $kdStore = $outlet['KdStore'];

        // Get date range
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        // Daily sales
        $dailySales = $this->transaksiModel->getDailySales($kdStore, date('Y-m-d'));

        // Period sales summary
        $periodSummary = $this->transaksiModel->getSalesSummaryByOutlet($startDate, $endDate);
        $outletSummary = null;
        foreach ($periodSummary as $summary) {
            if ($summary['KdStore'] == $kdStore) {
                $outletSummary = $summary;
                break;
            }
        }

        // Top products
        $topProducts = $this->transaksiModel->getTopSellingProducts($kdStore, $startDate, $endDate, 10);

        // Payment stats
        $paymentStats = $this->transaksiModel->getPaymentMethodStats($kdStore, $startDate, $endDate);

        // Transaction list
        $transactions = $this->transaksiModel->getTransactionsByOutlet($kdStore, $startDate, $endDate);

        $data = [
            'title' => 'Laporan Penjualan',
            'outlet' => $outlet,
            'kdStore' => $kdStore,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'daily_sales' => $dailySales,
            'outlet_summary' => $outletSummary,
            'top_products' => $topProducts,
            'payment_stats' => $paymentStats,
            'transactions' => $transactions
        ];

        return view('manajer/laporan/index', $data);
    }

    /**
     * Detail Transaksi
     */
    public function detail($noStruk)
    {
        // Get user outlet
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user || !$user['outlet_id']) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses');
        }

        $outlet = $this->outletModel->find($user['outlet_id']);
        $kdStore = $outlet['KdStore'];

        // Get transaction - need NoKassa
        $db = \Config\Database::connect();
        $transaksi = $db->table('transaksi_header')
            ->where('NoStruk', $noStruk)
            ->where('KdStore', $kdStore)
            ->get()
            ->getRowArray();

        if (!$transaksi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan');
        }

        $transaction = $this->transaksiModel->getTransactionDetails($transaksi['NoKassa'], $noStruk);

        // Verify outlet
        if ($transaction['header']['KdStore'] != $kdStore) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Anda tidak memiliki akses ke transaksi ini');
        }

        $data = [
            'title' => 'Detail Transaksi',
            'outlet' => $outlet,
            'transaction' => $transaction
        ];

        return view('manajer/laporan/detail', $data);
    }

    /**
     * Export Laporan
     */
    public function export()
    {
        // Get user outlet
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user || !$user['outlet_id']) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses');
        }

        $outlet = $this->outletModel->find($user['outlet_id']);
        $kdStore = $outlet['KdStore'];

        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $filters = ['kdStore' => $kdStore];
        $transactions = $this->transaksiModel->getTransactionsByDateRange($startDate, $endDate, $filters);

        // Export to CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="laporan_' . $outlet['nama_outlet'] . '_' . $startDate . '_' . $endDate . '.csv"');

        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, ['LAPORAN PENJUALAN']);
        fputcsv($output, ['Outlet: ' . $outlet['KdStore'] . ' - ' . $outlet['nama_outlet']]);
        fputcsv($output, ['Periode: ' . date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate))]);
        fputcsv($output, []);

        fputcsv($output, ['No. Struk', 'Tanggal', 'Waktu', 'Kasir', 'Total Item', 'Subtotal', 'Diskon', 'Total Bayar', 'Tunai', 'Debit', 'Kredit', 'GoPay']);

        // Data
        $totalItem = 0;
        $totalNilai = 0;
        $totalDiskon = 0;
        $totalBayar = 0;
        $totalTunai = 0;
        $totalDebit = 0;
        $totalKredit = 0;
        $totalGopay = 0;

        foreach ($transactions as $row) {
            fputcsv($output, [
                $row['NoStruk'],
                date('d/m/Y', strtotime($row['Tanggal'])),
                date('H:i', strtotime($row['Waktu'])),
                $row['Kasir'],
                $row['TotalItem'],
                $row['TotalNilai'],
                $row['Discount'],
                $row['TotalBayar'],
                $row['Tunai'],
                $row['KDebit'],
                $row['KKredit'],
                $row['GoPay']
            ]);

            $totalItem += $row['TotalItem'];
            $totalNilai += $row['TotalNilai'];
            $totalDiskon += $row['Discount'];
            $totalBayar += $row['TotalBayar'];
            $totalTunai += $row['Tunai'];
            $totalDebit += $row['KDebit'];
            $totalKredit += $row['KKredit'];
            $totalGopay += $row['GoPay'];
        }

        // Total
        fputcsv($output, []);
        fputcsv($output, ['TOTAL', '', '', count($transactions) . ' transaksi', $totalItem, $totalNilai, $totalDiskon, $totalBayar, $totalTunai, $totalDebit, $totalKredit, $totalGopay]);

        fclose($output);
        exit;
    }
}
