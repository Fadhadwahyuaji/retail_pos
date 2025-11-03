<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800"><?= $title ?></h1>
            <p class="text-gray-600 mt-1">Ringkasan penjualan per outlet</p>
        </div>
        <nav class="text-sm text-gray-600">
            <a href="<?= base_url('admin/dashboard') ?>" class="hover:text-blue-600">Dashboard</a>
            <span class="mx-2">/</span>
            <a href="<?= base_url('admin/report') ?>" class="hover:text-blue-600">Report</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800">Summary</span>
        </nav>
    </div>
</div>

<!-- Filter Card -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form action="<?= base_url('admin/report/sales-summary') ?>" method="GET" class="flex items-end space-x-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
            <input type="date" name="start_date" value="<?= $start_date ?>"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
            <input type="date" name="end_date" value="<?= $end_date ?>"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
            <i class="fas fa-search mr-2"></i>Tampilkan
        </button>

        <a href="<?= base_url('admin/report/export-summary?start_date=' . $start_date . '&end_date=' . $end_date) ?>"
            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition">
            <i class="fas fa-file-excel mr-2"></i>Export Excel
        </a>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <?php
    $totalTransaksi = array_sum(array_column($summary, 'total_transaksi'));
    $totalItem = array_sum(array_column($summary, 'total_item'));
    $totalPenjualan = array_sum(array_column($summary, 'total_penjualan'));
    $totalDiskon = array_sum(array_column($summary, 'total_discount'));
    $totalBayar = array_sum(array_column($summary, 'total_bayar'));
    ?>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-2">
            <p class="text-sm opacity-90">Total Transaksi</p>
            <i class="fas fa-receipt text-2xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold"><?= number_format($totalTransaksi, 0) ?></p>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-2">
            <p class="text-sm opacity-90">Total Item</p>
            <i class="fas fa-boxes text-2xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold"><?= number_format($totalItem, 0) ?></p>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-2">
            <p class="text-sm opacity-90">Total Penjualan</p>
            <i class="fas fa-money-bill-wave text-2xl opacity-75"></i>
        </div>
        <p class="text-2xl font-bold">Rp <?= number_format($totalPenjualan, 0) ?></p>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-2">
            <p class="text-sm opacity-90">Total Diskon</p>
            <i class="fas fa-tag text-2xl opacity-75"></i>
        </div>
        <p class="text-2xl font-bold">Rp <?= number_format($totalDiskon, 0) ?></p>
    </div>
</div>

<!-- Summary Table -->
<div class="bg-white rounded-lg shadow-md">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Ringkasan per Outlet</h2>
        <p class="text-sm text-gray-600 mt-1">Periode: <?= date('d M Y', strtotime($start_date)) ?> -
            <?= date('d M Y', strtotime($end_date)) ?></p>
    </div>

    <div class="p-6">
        <?php if (empty($summary)): ?>
        <div class="text-center py-12">
            <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Tidak ada data penjualan pada periode ini</p>
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Outlet</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Transaksi
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Item</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Penjualan
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Diskon</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Bayar</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $no = 1; ?>
                    <?php foreach ($summary as $row): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900"><?= $no++ ?></td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?= esc($row['nama_outlet']) ?></div>
                            <div class="text-xs text-gray-500"><?= esc($row['KdStore']) ?></div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-gray-900">
                            <?= number_format($row['total_transaksi'], 0) ?></td>
                        <td class="px-6 py-4 text-right text-sm text-gray-900">
                            <?= number_format($row['total_item'], 0) ?></td>
                        <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Rp
                            <?= number_format($row['total_penjualan'], 0) ?></td>
                        <td class="px-6 py-4 text-right text-sm text-red-600">Rp
                            <?= number_format($row['total_discount'], 0) ?></td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-green-600">Rp
                            <?= number_format($row['total_bayar'], 0) ?></td>
                    </tr>
                    <?php endforeach; ?>

                    <!-- Grand Total -->
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="2" class="px-6 py-4 text-sm text-gray-900">GRAND TOTAL</td>
                        <td class="px-6 py-4 text-right text-sm text-gray-900"><?= number_format($totalTransaksi, 0) ?>
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-gray-900"><?= number_format($totalItem, 0) ?></td>
                        <td class="px-6 py-4 text-right text-sm text-gray-900">Rp
                            <?= number_format($totalPenjualan, 0) ?></td>
                        <td class="px-6 py-4 text-right text-sm text-red-600">Rp <?= number_format($totalDiskon, 0) ?>
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-green-600">Rp <?= number_format($totalBayar, 0) ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Chart Preview (Optional) -->
        <div class="mt-8 p-6 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Penjualan per Outlet</h3>
            <div class="space-y-3">
                <?php foreach ($summary as $row):
                        $percentage = ($totalPenjualan > 0) ? ($row['total_penjualan'] / $totalPenjualan) * 100 : 0;
                    ?>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700"><?= esc($row['nama_outlet']) ?></span>
                        <span class="text-sm text-gray-600"><?= number_format($percentage, 1) ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-blue-600 h-4 rounded-full transition-all duration-500"
                            style="width: <?= $percentage ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>