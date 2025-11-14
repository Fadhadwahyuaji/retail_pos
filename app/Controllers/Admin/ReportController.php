<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\OutletModel;

class ReportController extends BaseController
{
    protected $transaksiModel;
    protected $outletModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->outletModel = new OutletModel();

        // TODO: Add authentication middleware
    }

    /**
     * Report Dashboard
     */
    public function index()
    {
        $data = [
            'title' => 'Report Penjualan'
        ];

        return view('admin/report/index', $data);
    }

    /**
     * Sales Summary by Outlet
     */
    public function salesSummary()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $summary = $this->transaksiModel->getSalesSummaryByOutlet($startDate, $endDate);

        $data = [
            'title' => 'Laporan Summary Penjualan per Outlet',
            'summary' => $summary,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];

        return view('admin/report/sales_summary', $data);
    }

    /**
     * Sales Detail Transactions
     */
    public function salesDetail()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        $kdStore = $this->request->getGet('kdstore') ?? '';
        $kasir = $this->request->getGet('kasir') ?? '';

        $filters = [
            'kdStore' => $kdStore,
            'kasir' => $kasir
        ];

        $transactions = $this->transaksiModel->getTransactionsByDateRange($startDate, $endDate, $filters);
        $outlets = $this->outletModel->findAll();

        $data = [
            'title' => 'Laporan Detail Transaksi',
            'transactions' => $transactions,
            'outlets' => $outlets,
            'filters' => array_merge($filters, [
                'start_date' => $startDate,
                'end_date' => $endDate
            ])
        ];

        return view('admin/report/sales_detail', $data);
    }

    /**
     * Transaction Detail (Items)
     */
    public function transactionDetail($noKassa, $noStruk)
    {
        try {
            log_message('info', "Accessing transaction detail: NoKassa=$noKassa, NoStruk=$noStruk");

            // Decode URL jika ada encoding
            $noKassa = urldecode($noKassa);
            $noStruk = urldecode($noStruk);

            $transaction = $this->transaksiModel->getTransactionDetails($noKassa, $noStruk);

            log_message('info', "Transaction data: " . json_encode($transaction));

            if (!$transaction || empty($transaction['header'])) {
                log_message('error', "Transaction not found: NoKassa=$noKassa, NoStruk=$noStruk");
                return redirect()->to('admin/report/sales-detail')
                    ->with('error', 'Transaksi tidak ditemukan');
            }

            $data = [
                'title' => 'Detail Transaksi',
                'transaction' => $transaction
            ];

            return view('admin/report/transaction_detail', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error in transactionDetail: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            return redirect()->to('admin/report/sales-detail')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export to Excel - Summary
     */
    public function exportSummary()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $summary = $this->transaksiModel->getSalesSummaryByOutlet($startDate, $endDate);

        // Load PhpSpreadsheet (if installed)
        // For now, export as CSV

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="laporan_summary_' . $startDate . '_' . $endDate . '.csv"');

        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, ['Kode Outlet', 'Nama Outlet', 'Total Transaksi', 'Total Item', 'Total Penjualan', 'Total Diskon', 'Total Bayar']);

        // Data
        foreach ($summary as $row) {
            fputcsv($output, [
                $row['KdStore'],
                $row['nama_outlet'],
                $row['total_transaksi'],
                $row['total_item'],
                $row['total_penjualan'],
                $row['total_discount'],
                $row['total_bayar']
            ]);
        }

        // Grand Total
        $grandTotalTransaksi = array_sum(array_column($summary, 'total_transaksi'));
        $grandTotalItem = array_sum(array_column($summary, 'total_item'));
        $grandTotalPenjualan = array_sum(array_column($summary, 'total_penjualan'));
        $grandTotalDiscount = array_sum(array_column($summary, 'total_discount'));
        $grandTotalBayar = array_sum(array_column($summary, 'total_bayar'));

        fputcsv($output, ['', 'GRAND TOTAL', $grandTotalTransaksi, $grandTotalItem, $grandTotalPenjualan, $grandTotalDiscount, $grandTotalBayar]);

        fclose($output);
        exit;
    }

    /**
     * Export to Excel - Detail
     */
    public function exportDetail()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        $kdStore = $this->request->getGet('kdstore') ?? '';

        $filters = ['kdStore' => $kdStore];
        $transactions = $this->transaksiModel->getTransactionsByDateRange($startDate, $endDate, $filters);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="laporan_detail_' . $startDate . '_' . $endDate . '.csv"');

        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, ['No. Struk', 'Tanggal', 'Waktu', 'Outlet', 'Kasir', 'Total Item', 'Total Nilai', 'Diskon', 'Total Bayar', 'Tunai', 'Kartu Debit', 'Kartu Kredit', 'GoPay']);

        // Data
        foreach ($transactions as $row) {
            fputcsv($output, [
                $row['NoStruk'],
                $row['Tanggal'],
                $row['Waktu'],
                $row['KdStore'] . ' - ' . $row['nama_outlet'],
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
        }

        fclose($output);
        exit;
    }

    /**
     * Dashboard Statistics
     */
    public function dashboard()
    {
        $kdStore = session()->get('kdstore') ?? '01';
        $today = date('Y-m-d');
        $startOfMonth = date('Y-m-01');

        // Daily sales
        $dailySales = $this->transaksiModel->getDailySales($kdStore, $today);

        // Monthly sales
        $monthlySummary = $this->transaksiModel->getSalesSummaryByOutlet($startOfMonth, $today);

        // Top products
        $topProducts = $this->transaksiModel->getTopSellingProducts($kdStore, $startOfMonth, $today, 10);

        // Payment stats
        $paymentStats = $this->transaksiModel->getPaymentMethodStats($kdStore, $startOfMonth, $today);

        $data = [
            'title' => 'Dashboard',
            'daily_sales' => $dailySales,
            'monthly_summary' => $monthlySummary,
            'top_products' => $topProducts,
            'payment_stats' => $paymentStats,
            'kdStore' => $kdStore
        ];

        return view('admin/dashboard/index', $data);
    }
}
