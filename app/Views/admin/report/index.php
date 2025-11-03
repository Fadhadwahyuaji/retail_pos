<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Report Penjualan</h1>
            <p class="text-gray-600 mt-1">Pilih jenis laporan yang ingin ditampilkan</p>
        </div>
        <nav class="text-sm text-gray-600">
            <a href="<?= base_url('admin/dashboard') ?>" class="hover:text-blue-600">Dashboard</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800">Report</span>
        </nav>
    </div>
</div>

<!-- Report Menu Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Sales Summary Card -->
    <a href="<?= base_url('admin/report/sales-summary') ?>"
        class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden group">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-2">Summary Penjualan</h3>
                    <p class="text-blue-100">Ringkasan per Outlet</p>
                </div>
                <div class="bg-white bg-opacity-20 p-4 rounded-full">
                    <i class="fas fa-chart-bar text-4xl"></i>
                </div>
            </div>
        </div>
        <div class="p-6">
            <ul class="space-y-3 text-gray-600">
                <li class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <span>Total penjualan per outlet</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <span>Total transaksi dan item</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <span>Total diskon dan pembayaran</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <span>Grafik perbandingan outlet</span>
                </li>
            </ul>
            <div
                class="mt-6 flex items-center text-blue-600 font-semibold group-hover:translate-x-2 transition-transform">
                <span>Lihat Laporan</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </div>
        </div>
    </a>

    <!-- Sales Detail Card -->
    <a href="<?= base_url('admin/report/sales-detail') ?>"
        class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden group">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-2">Detail Transaksi</h3>
                    <p class="text-purple-100">Rincian per Transaksi</p>
                </div>
                <div class="bg-white bg-opacity-20 p-4 rounded-full">
                    <i class="fas fa-file-invoice text-4xl"></i>
                </div>
            </div>
        </div>
        <div class="p-6">
            <ul class="space-y-3 text-gray-600">
                <li class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <span>Detail setiap transaksi</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <span>Filter berdasarkan outlet dan kasir</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <span>Metode pembayaran</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <span>Lihat item per transaksi</span>
                </li>
            </ul>
            <div
                class="mt-6 flex items-center text-purple-600 font-semibold group-hover:translate-x-2 transition-transform">
                <span>Lihat Laporan</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </div>
        </div>
    </a>

</div>

<!-- Quick Access Stats -->
<div class="mt-8 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg shadow-md p-6">
    <h3 class="text-xl font-semibold text-gray-800 mb-4">Akses Cepat</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <a href="<?= base_url('admin/report/sales-summary?start_date=' . date('Y-m-01') . '&end_date=' . date('Y-m-d')) ?>"
            class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Bulan Ini</p>
                    <p class="text-lg font-semibold text-gray-800">Summary</p>
                </div>
            </div>
        </a>

        <a href="<?= base_url('admin/report/sales-detail?start_date=' . date('Y-m-d') . '&end_date=' . date('Y-m-d')) ?>"
            class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-4">
                    <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Hari Ini</p>
                    <p class="text-lg font-semibold text-gray-800">Detail</p>
                </div>
            </div>
        </a>

        <a href="<?= base_url('admin/report/sales-summary?start_date=' . date('Y-m-d', strtotime('-7 days')) . '&end_date=' . date('Y-m-d')) ?>"
            class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class="fas fa-calendar-week text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">7 Hari</p>
                    <p class="text-lg font-semibold text-gray-800">Summary</p>
                </div>
            </div>
        </a>

        <a href="<?= base_url('admin/report/sales-summary?start_date=' . date('Y-m-d', strtotime('-30 days')) . '&end_date=' . date('Y-m-d')) ?>"
            class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-orange-100 p-3 rounded-full mr-4">
                    <i class="fas fa-calendar text-orange-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">30 Hari</p>
                    <p class="text-lg font-semibold text-gray-800">Summary</p>
                </div>
            </div>
        </a>

    </div>
</div>

<!-- Export Options -->
<div class="mt-6 bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Export Data</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="border border-gray-200 rounded-lg p-4 hover:border-green-500 transition-colors">
            <div class="flex items-center mb-3">
                <i class="fas fa-file-excel text-3xl text-green-600 mr-4"></i>
                <div>
                    <h4 class="font-semibold text-gray-800">Export Summary</h4>
                    <p class="text-sm text-gray-600">Data ringkasan penjualan per outlet</p>
                </div>
            </div>
            <p class="text-xs text-gray-500 mb-3">Format: CSV | Gunakan filter tanggal di halaman summary</p>
            <a href="<?= base_url('admin/report/sales-summary') ?>"
                class="inline-flex items-center text-sm text-green-600 hover:text-green-700 font-medium">
                <span>Pilih Periode</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="border border-gray-200 rounded-lg p-4 hover:border-green-500 transition-colors">
            <div class="flex items-center mb-3">
                <i class="fas fa-file-excel text-3xl text-green-600 mr-4"></i>
                <div>
                    <h4 class="font-semibold text-gray-800">Export Detail</h4>
                    <p class="text-sm text-gray-600">Data detail transaksi lengkap</p>
                </div>
            </div>
            <p class="text-xs text-gray-500 mb-3">Format: CSV | Gunakan filter tanggal di halaman detail</p>
            <a href="<?= base_url('admin/report/sales-detail') ?>"
                class="inline-flex items-center text-sm text-green-600 hover:text-green-700 font-medium">
                <span>Pilih Periode</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

    </div>
</div>

<?= $this->endSection() ?>