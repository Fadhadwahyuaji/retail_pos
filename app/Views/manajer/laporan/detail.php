<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800"><?= $title ?></h1>
            <p class="text-gray-600 mt-1">Detail item transaksi</p>
        </div>
        <nav class="text-sm text-gray-600">
            <a href="<?= base_url('dashboard') ?>" class="hover:text-blue-600">Dashboard</a>
            <span class="mx-2">/</span>
            <a href="<?= base_url('manajer/laporan-outlet') ?>" class="hover:text-blue-600">Laporan</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800">Detail</span>
        </nav>
    </div>
</div>

<!-- Transaction Header Info -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Transaksi</h3>
            <dl class="space-y-2">
                <div class="flex justify-between">
                    <dt class="text-gray-600">No. Struk:</dt>
                    <dd class="font-mono font-semibold text-blue-600"><?= esc($transaction['header']['NoStruk']) ?></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Tanggal:</dt>
                    <dd class="font-medium"><?= date('d/m/Y', strtotime($transaction['header']['Tanggal'])) ?></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Waktu:</dt>
                    <dd class="font-medium"><?= date('H:i:s', strtotime($transaction['header']['Waktu'])) ?></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Kasir:</dt>
                    <dd class="font-medium"><?= esc($transaction['header']['Kasir']) ?></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Outlet:</dt>
                    <dd class="font-medium"><?= esc($outlet['KdStore']) ?> - <?= esc($outlet['nama_outlet']) ?></dd>
                </div>
            </dl>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembayaran</h3>
            <dl class="space-y-2">
                <div class="flex justify-between">
                    <dt class="text-gray-600">Total Item:</dt>
                    <dd class="font-medium"><?= $transaction['header']['TotalItem'] ?> item</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Subtotal:</dt>
                    <dd class="font-medium">Rp <?= number_format($transaction['header']['TotalNilai'], 0) ?></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Diskon:</dt>
                    <dd class="font-medium text-red-600">Rp <?= number_format($transaction['header']['Discount'], 0) ?>
                    </dd>
                </div>
                <div class="flex justify-between border-t pt-2">
                    <dt class="text-gray-800 font-semibold">Total Bayar:</dt>
                    <dd class="font-bold text-green-600 text-lg">Rp
                        <?= number_format($transaction['header']['TotalBayar'], 0) ?></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Uang Tunai:</dt>
                    <dd class="font-medium">Rp <?= number_format($transaction['header']['Tunai'], 0) ?></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Kembalian:</dt>
                    <dd class="font-medium">Rp <?= number_format($transaction['header']['Kembali'], 0) ?></dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="mt-6 flex justify-end space-x-2">
        <a href="<?= base_url('manajer/laporan-outlet') ?>"
            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
</div>

<!-- Transaction Items -->
<div class="bg-white rounded-lg shadow-md">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Detail Item</h2>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Produk</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Diskon</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $no = 1; ?>
                    <?php foreach ($transaction['details'] as $item): ?>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900"><?= $no++ ?></td>
                            <td class="px-4 py-3">
                                <span class="text-sm font-mono font-semibold text-gray-900">
                                    <?= esc($item['PCode']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= esc($item['NamaLengkap'] ?? $item['NamaStruk']) ?>
                                </div>
                                <?php if (!empty($item['Ketentuan1'])): ?>
                                    <div class="text-xs text-blue-600">
                                        <i class="fas fa-tag mr-1"></i>Promo: <?= esc($item['Ketentuan1']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-900"><?= $item['Qty'] ?></td>
                            <td class="px-4 py-3 text-right text-sm text-gray-900">
                                Rp <?= number_format($item['Harga'], 0) ?>
                            </td>
                            <td class="px-4 py-3 text-right text-sm text-red-600">
                                Rp <?= number_format($item['Disc1'] ?? 0, 0) ?>
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">
                                Rp <?= number_format($item['Netto'], 0) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- Total -->
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="6" class="px-4 py-3 text-sm text-gray-900">TOTAL</td>
                        <td class="px-4 py-3 text-right text-sm text-green-600">
                            Rp <?= number_format($transaction['header']['TotalBayar'], 0) ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>