<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800"><?= $title ?></h1>
            <p class="text-gray-600 mt-1">Detail transaksi per outlet</p>
        </div>
        <nav class="text-sm text-gray-600">
            <a href="<?= base_url('admin/dashboard') ?>" class="hover:text-blue-600">Dashboard</a>
            <span class="mx-2">/</span>
            <a href="<?= base_url('admin/report') ?>" class="hover:text-blue-600">Report</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800">Detail</span>
        </nav>
    </div>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline"><?= esc(session()->getFlashdata('error')) ?></span>
    </div>
<?php endif; ?>

<!-- Filter Card -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form action="<?= base_url('admin/report/sales-detail') ?>" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="<?= $filters['start_date'] ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                <input type="date" name="end_date" value="<?= $filters['end_date'] ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Outlet</label>
                <select name="kdstore"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Outlet</option>
                    <?php foreach ($outlets as $outlet): ?>
                        <option value="<?= $outlet['KdStore'] ?>"
                            <?= $filters['kdStore'] == $outlet['KdStore'] ? 'selected' : '' ?>>
                            <?= esc($outlet['KdStore']) ?> - <?= esc($outlet['nama_outlet']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kasir</label>
                <input type="text" name="kasir" value="<?= $filters['kasir'] ?>" placeholder="Nama kasir..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="<?= base_url('admin/report/export-detail?' . http_build_query($filters)) ?>"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-file-excel"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Transactions Table -->
<div class="bg-white rounded-lg shadow-md">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Detail Transaksi</h2>
            <p class="text-sm text-gray-600 mt-1">
                Periode: <?= date('d M Y', strtotime($filters['start_date'])) ?> -
                <?= date('d M Y', strtotime($filters['end_date'])) ?>
                | Total: <?= count($transactions) ?> transaksi
            </p>
        </div>
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
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Struk</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Outlet</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kasir</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Item</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Diskon</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Bayar</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $no = 1; ?>
                        <?php foreach ($transactions as $trans): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900"><?= $no++ ?></td>
                                <td class="px-4 py-3">
                                    <a href="<?= base_url('admin/report/transaction-detail/' . rawurlencode($trans['NoKassa']) . '/' . rawurlencode($trans['NoStruk'])) ?>"
                                        class="text-sm font-mono font-semibold text-blue-600 hover:underline"
                                        title="Lihat Detail Item">
                                        <?= esc($trans['NoStruk']) ?>
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <?= date('d/m/Y', strtotime($trans['Tanggal'])) ?><br>
                                    <span class="text-xs text-gray-500"><?= date('H:i', strtotime($trans['Waktu'])) ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900"><?= esc($trans['nama_outlet']) ?></div>
                                    <div class="text-xs text-gray-500"><?= esc($trans['KdStore']) ?></div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900"><?= esc($trans['Kasir']) ?></td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900"><?= $trans['TotalItem'] ?></td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">Rp
                                    <?= number_format($trans['TotalNilai'], 0) ?></td>
                                <td class="px-4 py-3 text-right text-sm text-red-600">Rp
                                    <?= number_format($trans['Discount'], 0) ?></td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-green-600">Rp
                                    <?= number_format($trans['TotalBayar'], 0) ?></td>
                                <td class="px-4 py-3 text-center">
                                    <a href="<?= base_url('admin/report/transaction-detail/' . $trans['NoKassa'] . '/' . $trans['NoStruk']) ?>"
                                        class="text-blue-600 hover:text-blue-800" title="Lihat Detail Item">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <!-- Summary -->
                        <?php
                        $totalNilai = array_sum(array_column($transactions, 'TotalNilai'));
                        $totalDiskon = array_sum(array_column($transactions, 'Discount'));
                        $totalBayar = array_sum(array_column($transactions, 'TotalBayar'));
                        ?>
                        <tr class="bg-gray-100 font-bold">
                            <td colspan="6" class="px-4 py-3 text-sm text-gray-900">TOTAL</td>
                            <td class="px-4 py-3 text-right text-sm text-gray-900">Rp <?= number_format($totalNilai, 0) ?>
                            </td>
                            <td class="px-4 py-3 text-right text-sm text-red-600">Rp <?= number_format($totalDiskon, 0) ?>
                            </td>
                            <td class="px-4 py-3 text-right text-sm text-green-600">Rp <?= number_format($totalBayar, 0) ?>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>