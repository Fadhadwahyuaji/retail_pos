<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
// Check if transaction data exists
if (!isset($transaction) || empty($transaction['header'])) {
?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">Data transaksi tidak ditemukan.</span>
    </div>
    <a href="<?= base_url('admin/report/sales-detail') ?>" class="bg-blue-600 text-white px-4 py-2 rounded">
        Kembali ke Daftar Transaksi
    </a>
<?php
    return;
}

$header = $transaction['header'];
$details = $transaction['details'] ?? [];
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800"><?= $title ?></h1>
            <p class="text-gray-600 mt-1">Detail item transaksi: <?= esc($header['NoStruk']) ?></p>
        </div>
        <nav class="text-sm text-gray-600">
            <a href="<?= base_url('admin/dashboard') ?>" class="hover:text-blue-600">Dashboard</a>
            <span class="mx-2">/</span>
            <a href="<?= base_url('admin/report/sales-detail') ?>" class="hover:text-blue-600">Transaksi</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800">Detail</span>
        </nav>
    </div>
</div>

<!-- Transaction Header Info -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Column 1 -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Transaksi</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">No. Struk:</span>
                    <span class="font-semibold text-blue-600"><?= esc($header['NoStruk']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">No. Kassa:</span>
                    <span class="font-mono"><?= esc($header['NoKassa']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal:</span>
                    <span><?= date('d/m/Y', strtotime($header['Tanggal'])) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Waktu:</span>
                    <span><?= date('H:i:s', strtotime($header['Waktu'])) ?></span>
                </div>
            </div>
        </div>

        <!-- Column 2 -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Outlet & Kasir</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Outlet:</span>
                    <span class="font-semibold"><?= esc($header['KdStore']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Kasir:</span>
                    <span class="font-semibold"><?= esc($header['Kasir']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span
                        class="px-2 py-1 rounded text-xs font-semibold <?= $header['Status'] == 'T' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        <?= $header['Status'] == 'T' ? 'Selesai' : 'Void' ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Column 3 -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pembayaran</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Item:</span>
                    <span class="font-semibold"><?= $header['TotalItem'] ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Nilai:</span>
                    <span class="font-semibold">Rp <?= number_format($header['TotalNilai'], 0) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Diskon:</span>
                    <span class="font-semibold text-red-600">Rp <?= number_format($header['Discount'], 0) ?></span>
                </div>
                <div class="flex justify-between border-t pt-2">
                    <span class="text-gray-800 font-bold">Total Bayar:</span>
                    <span class="font-bold text-green-600 text-lg">Rp
                        <?= number_format($header['TotalBayar'], 0) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <?php if ($header['Tunai'] > 0 || $header['KDebit'] > 0 || $header['KKredit'] > 0 || $header['GoPay'] > 0): ?>
        <div class="mt-6 pt-6 border-t">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Metode Pembayaran</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php if ($header['Tunai'] > 0): ?>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Tunai</p>
                        <p class="text-lg font-bold text-blue-600">Rp <?= number_format($header['Tunai'], 0) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($header['KDebit'] > 0): ?>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Kartu Debit</p>
                        <p class="text-lg font-bold text-purple-600">Rp <?= number_format($header['KDebit'], 0) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($header['KKredit'] > 0): ?>
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Kartu Kredit</p>
                        <p class="text-lg font-bold text-orange-600">Rp <?= number_format($header['KKredit'], 0) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($header['GoPay'] > 0): ?>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">GoPay</p>
                        <p class="text-lg font-bold text-green-600">Rp <?= number_format($header['GoPay'], 0) ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($header['Kembali'] > 0): ?>
                <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700 font-semibold">Kembalian:</span>
                        <span class="text-xl font-bold text-gray-800">Rp <?= number_format($header['Kembali'], 0) ?></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Transaction Items -->
<div class="bg-white rounded-lg shadow-md">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Detail Item Transaksi</h2>
        <p class="text-sm text-gray-600 mt-1">Total: <?= count($details) ?> item</p>
    </div>

    <div class="p-6">
        <?php if (empty($details)): ?>
            <div class="text-center py-12">
                <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Tidak ada detail item</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Barang</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Bruto</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Diskon</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Netto</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $no = 1; ?>
                        <?php foreach ($details as $item): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900"><?= $no++ ?></td>
                                <td class="px-4 py-3">
                                    <span
                                        class="text-sm font-mono font-semibold text-blue-600"><?= esc($item['PCode']) ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= esc($item['NamaStruk'] ?? $item['NamaLengkap'] ?? 'Produk Tidak Ditemukan') ?>
                                    </div>
                                    <?php if (!empty($item['NamaLengkap']) && $item['NamaLengkap'] != $item['NamaStruk']): ?>
                                        <div class="text-xs text-gray-500"><?= esc($item['NamaLengkap']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">
                                    Rp <?= number_format($item['HrgJual'], 0) ?>
                                </td>
                                <td class="px-4 py-3 text-center text-sm font-semibold text-gray-900">
                                    <?= number_format($item['Qty'], 2) ?>
                                </td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">
                                    Rp <?= number_format($item['Bruto'], 0) ?>
                                </td>
                                <td class="px-4 py-3 text-right text-sm text-red-600">
                                    Rp <?= number_format($item['Disc'], 0) ?>
                                    <?php if ($item['DiscPersen'] > 0): ?>
                                        <span class="text-xs">(<?= number_format($item['DiscPersen'], 1) ?>%)</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-green-600">
                                    Rp <?= number_format($item['Netto'], 0) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <!-- Summary -->
                        <?php
                        $totalBruto = array_sum(array_column($details, 'Bruto'));
                        $totalDisc = array_sum(array_column($details, 'Disc'));
                        $totalNetto = array_sum(array_column($details, 'Netto'));
                        ?>
                        <tr class="bg-gray-100 font-bold">
                            <td colspan="5" class="px-4 py-3 text-sm text-gray-900 text-right">TOTAL</td>
                            <td class="px-4 py-3 text-right text-sm text-gray-900">
                                Rp <?= number_format($totalBruto, 0) ?>
                            </td>
                            <td class="px-4 py-3 text-right text-sm text-red-600">
                                Rp <?= number_format($totalDisc, 0) ?>
                            </td>
                            <td class="px-4 py-3 text-right text-sm text-green-600">
                                Rp <?= number_format($totalNetto, 0) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Action Buttons -->
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
        <a href="<?= base_url('admin/report/sales-detail') ?>"
            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <button onclick="window.print()"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
            <i class="fas fa-print mr-2"></i>Cetak Struk
        </button>
    </div>
</div>

<?= $this->endSection() ?>