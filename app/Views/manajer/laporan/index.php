<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Penjualan</h1>
            <p class="text-gray-600 mt-1">
                <i class="fas fa-store mr-2"></i>
                <?= esc($outlet['KdStore']) ?> - <?= esc($outlet['nama_outlet']) ?>
            </p>
        </div>
        <nav class="text-sm text-gray-600">
            <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600">Dashboard</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800">Laporan</span>
        </nav>
    </div>
</div>

<!-- Filter Card -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form action="<?= base_url('manajer/laporan-outlet') ?>" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="<?= $start_date ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                <input type="date" name="end_date" value="<?= $end_date ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="<?= base_url('manajer/laporan-outlet/export?' . http_build_query(['start_date' => $start_date, 'end_date' => $end_date])) ?>"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-file-excel"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <!-- Penjualan Hari Ini -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-blue-100 text-sm">Penjualan Hari Ini</p>
                <h3 class="text-3xl font-bold mt-1">
                    Rp <?= number_format($daily_sales['total_bayar'] ?? 0, 0) ?>
                </h3>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                <i class="fas fa-cash-register text-2xl"></i>
            </div>
        </div>
        <div class="text-sm text-blue-100">
            <i class="fas fa-shopping-cart mr-1"></i>
            <?= $daily_sales['jumlah_transaksi'] ?? 0 ?> transaksi
        </div>
    </div>

    <!-- Total Periode -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-green-100 text-sm">Total Periode</p>
                <h3 class="text-3xl font-bold mt-1">
                    Rp <?= number_format($outlet_summary['total_bayar'] ?? 0, 0) ?>
                </h3>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
        </div>
        <div class="text-sm text-green-100">
            <i class="fas fa-box mr-1"></i>
            <?= number_format($outlet_summary['total_item'] ?? 0, 0) ?> item terjual
        </div>
    </div>

    <!-- Total Diskon -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-orange-100 text-sm">Total Diskon</p>
                <h3 class="text-3xl font-bold mt-1">
                    Rp <?= number_format($outlet_summary['total_discount'] ?? 0, 0) ?>
                </h3>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                <i class="fas fa-tags text-2xl"></i>
            </div>
        </div>
        <div class="text-sm text-orange-100">
            <?php
            $penjualan = $outlet_summary['total_penjualan'] ?? 0;
            $diskon = $outlet_summary['total_discount'] ?? 0;
            $persen = $penjualan > 0 ? ($diskon / $penjualan * 100) : 0;
            ?>
            <i class="fas fa-percent mr-1"></i>
            <?= number_format($persen, 1) ?>% dari penjualan
        </div>
    </div>

    <!-- Transaksi -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-purple-100 text-sm">Total Transaksi</p>
                <h3 class="text-3xl font-bold mt-1">
                    <?= number_format($outlet_summary['total_transaksi'] ?? 0, 0) ?>
                </h3>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                <i class="fas fa-receipt text-2xl"></i>
            </div>
        </div>
        <div class="text-sm text-purple-100">
            <?php
            $transaksi = $outlet_summary['total_transaksi'] ?? 0;
            $rata2 = $transaksi > 0 ? ($outlet_summary['total_bayar'] ?? 0) / $transaksi : 0;
            ?>
            <i class="fas fa-calculator mr-1"></i>
            Rata-rata: Rp <?= number_format($rata2, 0) ?>
        </div>
    </div>
</div>

<!-- Payment Methods Stats -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Metode Pembayaran</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600">Tunai</span>
                <i class="fas fa-money-bill-wave text-green-500"></i>
            </div>
            <p class="text-xl font-bold text-gray-800">
                Rp <?= number_format($payment_stats['total_tunai'] ?? 0, 0) ?>
            </p>
            <p class="text-xs text-gray-500 mt-1">
                <?= $payment_stats['count_tunai'] ?? 0 ?> transaksi
            </p>
        </div>

        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600">Debit</span>
                <i class="fas fa-credit-card text-blue-500"></i>
            </div>
            <p class="text-xl font-bold text-gray-800">
                Rp <?= number_format($payment_stats['total_kdebit'] ?? 0, 0) ?>
            </p>
            <p class="text-xs text-gray-500 mt-1">
                <?= $payment_stats['count_kdebit'] ?? 0 ?> transaksi
            </p>
        </div>

        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600">Kredit</span>
                <i class="fas fa-credit-card text-purple-500"></i>
            </div>
            <p class="text-xl font-bold text-gray-800">
                Rp <?= number_format($payment_stats['total_kkredit'] ?? 0, 0) ?>
            </p>
            <p class="text-xs text-gray-500 mt-1">
                <?= $payment_stats['count_kkredit'] ?? 0 ?> transaksi
            </p>
        </div>

        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600">GoPay</span>
                <i class="fas fa-mobile-alt text-cyan-500"></i>
            </div>
            <p class="text-xl font-bold text-gray-800">
                Rp <?= number_format($payment_stats['total_gopay'] ?? 0, 0) ?>
            </p>
            <p class="text-xs text-gray-500 mt-1">
                <?= $payment_stats['count_gopay'] ?? 0 ?> transaksi
            </p>
        </div>
    </div>
</div>

<!-- Top Products -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Produk Terlaris</h3>
    <?php if (empty($top_products)): ?>
        <p class="text-center text-gray-500 py-8">Tidak ada data produk</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Penjualan</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($top_products as $product): ?>
                        <tr>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= esc($product['NamaLengkap'] ?? $product['NamaStruk']) ?>
                                </div>
                                <div class="text-xs text-gray-500"><?= esc($product['PCode']) ?></div>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-900">
                                <?= number_format($product['total_qty'], 0) ?>
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">
                                Rp <?= number_format($product['total_netto'], 0) ?>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-900">
                                <?= $product['jumlah_transaksi'] ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Recent Transactions -->
<div class="bg-white rounded-lg shadow-md">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Riwayat Transaksi</h2>
        <p class="text-sm text-gray-600 mt-1">
            Periode: <?= date('d M Y', strtotime($start_date)) ?> - <?= date('d M Y', strtotime($end_date)) ?>
        </p>
    </div>

    <div class="p-6">
        <?php if (empty($transactions)): ?>
            <div class="text-center py-12">
                <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Tidak ada transaksi pada periode ini</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Struk</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal/Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kasir</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Item</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Diskon</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Bayar</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach (array_slice($transactions, 0, 20) as $trans): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <span class="text-sm font-mono font-semibold text-blue-600">
                                        <?= esc($trans['NoStruk']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <?= date('d/m/Y', strtotime($trans['Tanggal'])) ?><br>
                                    <span class="text-xs text-gray-500"><?= date('H:i', strtotime($trans['Waktu'])) ?></span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900"><?= esc($trans['Kasir']) ?></td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900"><?= $trans['TotalItem'] ?></td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">
                                    Rp <?= number_format($trans['TotalNilai'], 0) ?>
                                </td>
                                <td class="px-4 py-3 text-right text-sm text-red-600">
                                    Rp <?= number_format($trans['Discount'], 0) ?>
                                </td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-green-600">
                                    Rp <?= number_format($trans['TotalBayar'], 0) ?>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="<?= base_url('manajer/laporan-outlet/detail/' . $trans['NoStruk']) ?>"
                                        class="text-blue-600 hover:text-blue-800" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (count($transactions) > 20): ?>
                <p class="text-center text-gray-500 text-sm mt-4">
                    Menampilkan 20 dari <?= count($transactions) ?> transaksi. Export untuk melihat semua.
                </p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>