<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Content Header -->
<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white"><?= $title ?></h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="<?= base_url('admin/dashboard') ?>"
                            class="text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-white">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <a href="<?= base_url('admin/barang') ?>"
                                class="text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-white">
                                Master Barang
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-500 dark:text-gray-400">Detail</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Product Info Card -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Produk</h3>
                    </div>
                    <a href="<?= base_url('admin/barang/edit/' . $product['PCode']) ?>"
                        class="inline-flex items-center px-3 py-1.5 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 text-sm font-medium rounded-md transition-colors duration-150">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="space-y-6">
                    <!-- Kode Produk -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="sm:w-1/3 mb-2 sm:mb-0">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kode Produk</p>
                        </div>
                        <div class="sm:w-2/3">
                            <p class="text-lg font-bold text-blue-600 dark:text-blue-400"><?= esc($product['PCode']) ?>
                            </p>
                        </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="sm:w-1/3 mb-2 sm:mb-0">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nama Produk</p>
                        </div>
                        <div class="sm:w-2/3">
                            <p class="text-base font-semibold text-gray-900 dark:text-white">
                                <?= esc($product['NamaLengkap']) ?></p>
                        </div>
                    </div>

                    <!-- Nama Struk -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="sm:w-1/3 mb-2 sm:mb-0">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nama di Struk</p>
                        </div>
                        <div class="sm:w-2/3">
                            <p class="text-base text-gray-900 dark:text-white"><?= esc($product['NamaStruk']) ?: '-' ?>
                            </p>
                        </div>
                    </div>

                    <!-- Satuan -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="sm:w-1/3 mb-2 sm:mb-0">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Satuan</p>
                        </div>
                        <div class="sm:w-2/3">
                            <span
                                class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md text-sm font-medium">
                                <?= esc($product['SatuanSt']) ?: 'PCS' ?>
                            </span>
                        </div>
                    </div>

                    <!-- Barcode -->
                    <div class="flex flex-col sm:flex-row pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="sm:w-1/3 mb-2 sm:mb-0">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Barcode</p>
                        </div>
                        <div class="sm:w-2/3 space-y-2">
                            <?php if ($product['Barcode1']): ?>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mr-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                        </path>
                                    </svg>
                                    <span
                                        class="font-mono text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded"><?= esc($product['Barcode1']) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($product['Barcode2']): ?>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mr-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                        </path>
                                    </svg>
                                    <span
                                        class="font-mono text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded"><?= esc($product['Barcode2']) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($product['Barcode3']): ?>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mr-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                        </path>
                                    </svg>
                                    <span
                                        class="font-mono text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded"><?= esc($product['Barcode3']) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!$product['Barcode1'] && !$product['Barcode2'] && !$product['Barcode3']): ?>
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">Belum ada barcode</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="flex flex-col sm:flex-row pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="sm:w-1/3 mb-2 sm:mb-0">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</p>
                        </div>
                        <div class="sm:w-2/3 flex flex-wrap gap-2">
                            <?php if ($product['Status'] == 'T'): ?>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Aktif
                                </span>
                            <?php else: ?>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Nonaktif
                                </span>
                            <?php endif; ?>

                            <?php if ($product['FlagReady'] == 'Y'): ?>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Ready
                                </span>
                            <?php else: ?>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Not Ready
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="flex flex-col sm:flex-row">
                        <div class="sm:w-1/3 mb-2 sm:mb-0">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Riwayat</p>
                        </div>
                        <div class="sm:w-2/3">
                            <p class="text-sm text-gray-900 dark:text-white">
                                <span class="font-medium">Dibuat:</span>
                                <?= date('d M Y H:i', strtotime($product['AddDate'])) ?>
                            </p>
                            <?php if ($product['EditDate'] != $product['AddDate']): ?>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <span class="font-medium">Diupdate:</span>
                                    <?= date('d M Y H:i', strtotime($product['EditDate'])) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing & Actions Sidebar -->
    <div class="space-y-6">
        <!-- Harga Card -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mr-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                        </path>
                    </svg>
                    Harga & Profit
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <!-- Harga Jual -->
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Harga Jual</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                        Rp <?= number_format($product['Harga1c'], 0, ',', '.') ?>
                    </p>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Harga Beli/HPP</p>
                    <p class="text-xl font-semibold text-gray-700 dark:text-gray-300">
                        Rp <?= number_format($product['Harga1b'] ?: 0, 0, ',', '.') ?>
                    </p>
                </div>

                <!-- Profit Info -->
                <?php
                $hargaJual = $product['Harga1c'];
                $hargaBeli = $product['Harga1b'] ?: 0;
                $profit = $hargaJual - $hargaBeli;
                $margin = $hargaBeli > 0 ? ($profit / $hargaBeli) * 100 : 0;
                ?>
                <?php if ($profit > 0): ?>
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Profit per Unit:</span>
                            <span class="font-bold text-green-600 dark:text-green-400">
                                Rp <?= number_format($profit, 0, ',', '.') ?>
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Margin:</span>
                            <span class="font-bold text-blue-600 dark:text-blue-400">
                                <?= number_format($margin, 2) ?>%
                            </span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Action Buttons Card -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-3">
            <a href="<?= base_url('admin/barang/edit/' . $product['PCode']) ?>"
                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md inline-flex items-center justify-center transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
                Edit Produk
            </a>

            <a href="<?= base_url('admin/barang/toggle-status/' . $product['PCode']) ?>"
                class="w-full <?= $product['Status'] == 'T' ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-600 hover:bg-green-700' ?> text-white px-4 py-2 rounded-md inline-flex items-center justify-center transition-colors duration-150"
                onclick="return confirm('Yakin ingin mengubah status produk?')">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                    </path>
                </svg>
                <?= $product['Status'] == 'T' ? 'Nonaktifkan' : 'Aktifkan' ?>
            </a>

            <a href="<?= base_url('admin/barang') ?>"
                class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center justify-center transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>