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
                            <span
                                class="text-gray-500 dark:text-gray-400"><?= isset($product) ? 'Edit' : 'Tambah' ?></span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Validation Errors -->
<?php if (session()->getFlashdata('errors')): ?>
    <div class="flex items-start p-4 mb-6 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800"
        role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 mt-0.5 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>
        <div>
            <span class="font-medium">Validasi Error!</span>
            <ul class="mt-2 ml-4 list-disc">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <button type="button"
            class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"
            onclick="this.parentElement.style.display='none'">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    </div>
<?php endif; ?>

<!-- Main Form Card -->
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mr-2" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                <?= isset($product) ? 'Form Edit Produk' : 'Form Tambah Produk' ?>
            </h3>
        </div>
    </div>

    <form
        action="<?= isset($product) ? base_url('admin/barang/update/' . $product['PCode']) : base_url('admin/barang/store') ?>"
        method="POST" id="barangForm">
        <?= csrf_field() ?>

        <div class="p-6">
            <!-- Informasi Produk Section -->
            <div class="mb-8">
                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Informasi Produk
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Produk -->
                    <div>
                        <label for="PCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Produk <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-500">(Maks 15 karakter)</span>
                        </label>
                        <input type="text" id="PCode" name="PCode"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?= isset($validation) && $validation->hasError('PCode') ? 'border-red-500' : '' ?>"
                            placeholder="Contoh: BRG001" value="<?= old('PCode', $product['PCode'] ?? '') ?>"
                            maxlength="15" style="text-transform: uppercase;" <?= isset($product) ? 'readonly' : '' ?>
                            required>
                        <?php if (isset($validation) && $validation->hasError('PCode')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('PCode') ?></p>
                        <?php endif; ?>
                        <p class="mt-1 text-sm text-gray-500">Kode unik untuk identifikasi produk (akan otomatis
                            uppercase)</p>
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="NamaLengkap" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="NamaLengkap" name="NamaLengkap"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Nama lengkap produk"
                            value="<?= old('NamaLengkap', $product['NamaLengkap'] ?? '') ?>" maxlength="75" required>
                    </div>

                    <!-- Nama Struk -->
                    <div>
                        <label for="NamaStruk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama di Struk
                        </label>
                        <input type="text" id="NamaStruk" name="NamaStruk"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Nama pendek untuk struk (opsional)"
                            value="<?= old('NamaStruk', $product['NamaStruk'] ?? '') ?>" maxlength="30">
                    </div>

                    <!-- Satuan -->
                    <div>
                        <label for="SatuanSt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Satuan
                        </label>
                        <select id="SatuanSt" name="SatuanSt"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="PCS"
                                <?= old('SatuanSt', $product['SatuanSt'] ?? 'PCS') == 'PCS' ? 'selected' : '' ?>>PCS
                                (Pieces)</option>
                            <option value="BOX"
                                <?= old('SatuanSt', $product['SatuanSt'] ?? '') == 'BOX' ? 'selected' : '' ?>>BOX
                            </option>
                            <option value="KG"
                                <?= old('SatuanSt', $product['SatuanSt'] ?? '') == 'KG' ? 'selected' : '' ?>>KG
                                (Kilogram)</option>
                            <option value="BTL"
                                <?= old('SatuanSt', $product['SatuanSt'] ?? '') == 'BTL' ? 'selected' : '' ?>>BTL
                                (Botol)</option>
                            <option value="PAK"
                                <?= old('SatuanSt', $product['SatuanSt'] ?? '') == 'PAK' ? 'selected' : '' ?>>PAK (Pack)
                            </option>
                            <option value="SAK"
                                <?= old('SatuanSt', $product['SatuanSt'] ?? '') == 'SAK' ? 'selected' : '' ?>>SAK (Sak)
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Barcode Section -->
            <div class="mb-8">
                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                        </path>
                    </svg>
                    Barcode (Opsional)
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="Barcode1" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Barcode 1
                        </label>
                        <input type="text" id="Barcode1" name="Barcode1"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Barcode utama" value="<?= old('Barcode1', $product['Barcode1'] ?? '') ?>"
                            maxlength="20">
                    </div>

                    <div>
                        <label for="Barcode2" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Barcode 2
                        </label>
                        <input type="text" id="Barcode2" name="Barcode2"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Barcode alternatif" value="<?= old('Barcode2', $product['Barcode2'] ?? '') ?>"
                            maxlength="20">
                    </div>

                    <div>
                        <label for="Barcode3" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Barcode 3
                        </label>
                        <input type="text" id="Barcode3" name="Barcode3"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Barcode alternatif" value="<?= old('Barcode3', $product['Barcode3'] ?? '') ?>"
                            maxlength="20">
                    </div>
                </div>
            </div>

            <!-- Harga Section -->
            <div class="mb-8">
                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                        </path>
                    </svg>
                    Harga
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Harga Jual -->
                    <div>
                        <label for="Harga1c" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Harga Jual <span class="text-red-500">*</span>
                        </label>
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">Rp</span>
                            <input type="number" id="Harga1c" name="Harga1c"
                                class="block w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                placeholder="0" value="<?= old('Harga1c', $product['Harga1c'] ?? '') ?>" step="1"
                                min="0" required>
                        </div>
                    </div>

                    <!-- Harga Beli -->
                    <div>
                        <label for="Harga1b" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Harga Beli/HPP
                        </label>
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">Rp</span>
                            <input type="number" id="Harga1b" name="Harga1b"
                                class="block w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                placeholder="0" value="<?= old('Harga1b', $product['Harga1b'] ?? '') ?>" step="1"
                                min="0">
                        </div>
                    </div>
                </div>

                <!-- Profit Calculation -->
                <div id="profitInfo"
                    class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Estimasi Profit:</span>
                        <span id="profitAmount" class="font-semibold text-green-600 dark:text-green-400"></span>
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Margin:</span>
                        <span id="profitMargin" class="font-semibold text-blue-600 dark:text-blue-400"></span>
                    </div>
                </div>
            </div>

            <!-- Status Section -->
            <div class="mb-8">
                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                        </path>
                    </svg>
                    Status & Pengaturan
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status -->
                    <div>
                        <label for="Status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status Produk
                        </label>
                        <select id="Status" name="Status"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="T" <?= old('Status', $product['Status'] ?? 'T') == 'T' ? 'selected' : '' ?>>
                                Aktif</option>
                            <option value="F" <?= old('Status', $product['Status'] ?? 'T') == 'F' ? 'selected' : '' ?>>
                                Nonaktif</option>
                        </select>
                    </div>

                    <!-- Flag Ready -->
                    <div>
                        <label for="FlagReady" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Ketersediaan
                        </label>
                        <select id="FlagReady" name="FlagReady"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="Y"
                                <?= old('FlagReady', $product['FlagReady'] ?? 'Y') == 'Y' ? 'selected' : '' ?>>Ready
                                (Tersedia)</option>
                            <option value="N"
                                <?= old('FlagReady', $product['FlagReady'] ?? 'Y') == 'N' ? 'selected' : '' ?>>Not Ready
                                (Tidak Tersedia)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <div class="flex">
                    <svg class="flex-shrink-0 w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-400">Catatan:</h3>
                        <ul class="mt-2 text-sm text-blue-700 dark:text-blue-300 list-disc list-inside space-y-1">
                            <li>Kode Produk harus unik dan tidak dapat diubah setelah disimpan</li>
                            <li>Nama Struk akan menggunakan Nama Produk jika tidak diisi</li>
                            <li>Barcode dapat diisi nanti, minimal 1 barcode untuk scan di POS</li>
                            <li>Harga Beli digunakan untuk menghitung profit margin</li>
                            <li>Field yang bertanda <span class="text-red-500">*</span> wajib diisi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div
            class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 rounded-b-lg">
            <div class="flex items-center justify-end space-x-3">
                <a href="<?= base_url('admin/barang') ?>"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                    </svg>
                    Kembali
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                        </path>
                    </svg>
                    <?= isset($product) ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto uppercase Kode Produk
        const pcodeInput = document.getElementById('PCode');
        pcodeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Auto-fill NamaStruk if empty
        const namaLengkapInput = document.getElementById('NamaLengkap');
        const namaStrukInput = document.getElementById('NamaStruk');

        namaLengkapInput.addEventListener('blur', function() {
            if (namaStrukInput.value.trim() === '') {
                namaStrukInput.value = this.value;
            }
        });

        // Calculate profit
        function calculateProfit() {
            const hargaJual = parseFloat(document.getElementById('Harga1c').value) || 0;
            const hargaBeli = parseFloat(document.getElementById('Harga1b').value) || 0;

            if (hargaBeli > 0 && hargaJual > 0) {
                const profit = hargaJual - hargaBeli;
                const margin = (profit / hargaBeli) * 100;

                document.getElementById('profitAmount').textContent = 'Rp ' + profit.toLocaleString('id-ID');
                document.getElementById('profitMargin').textContent = margin.toFixed(2) + '%';
                document.getElementById('profitInfo').classList.remove('hidden');
            } else {
                document.getElementById('profitInfo').classList.add('hidden');
            }
        }

        // Trigger profit calculation
        document.getElementById('Harga1c').addEventListener('input', calculateProfit);
        document.getElementById('Harga1b').addEventListener('input', calculateProfit);

        // Initial calculation if editing
        calculateProfit();

        // Form validation
        document.getElementById('barangForm').addEventListener('submit', function(e) {
            const pcode = document.getElementById('PCode').value;
            const namaLengkap = document.getElementById('NamaLengkap').value;
            const hargaJual = parseFloat(document.getElementById('Harga1c').value) || 0;

            if (pcode.trim().length === 0) {
                e.preventDefault();
                alert('Kode Produk harus diisi!');
                document.getElementById('PCode').focus();
                return false;
            }

            if (namaLengkap.trim().length === 0) {
                e.preventDefault();
                alert('Nama Produk harus diisi!');
                document.getElementById('NamaLengkap').focus();
                return false;
            }

            if (hargaJual <= 0) {
                e.preventDefault();
                alert('Harga Jual harus lebih dari 0!');
                document.getElementById('Harga1c').focus();
                return false;
            }
        });
    });
</script>
<?= $this->endSection() ?>