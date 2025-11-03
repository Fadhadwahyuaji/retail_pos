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
                            <a href="<?= base_url('admin/outlet') ?>"
                                class="text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-white">
                                Outlet
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
                                class="text-gray-500 dark:text-gray-400"><?= isset($outlet) ? 'Edit' : 'Tambah' ?></span>
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
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                </path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                <?= isset($outlet) ? 'Form Edit Outlet' : 'Form Tambah Outlet' ?>
            </h3>
        </div>
    </div>

    <form
        action="<?= isset($outlet) ? base_url('admin/outlet/update/' . $outlet['id']) : base_url('admin/outlet/store') ?>"
        method="POST" id="outletForm">
        <?= csrf_field() ?>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Store -->
                <div>
                    <label for="KdStore" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Kode Store <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-500">(Maks 2 karakter)</span>
                    </label>
                    <input type="text" id="KdStore" name="KdStore"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?= isset($validation) && $validation->hasError('KdStore') ? 'border-red-500' : '' ?>"
                        placeholder="Contoh: 01, 02, CB, BD" value="<?= old('KdStore', $outlet['KdStore'] ?? '') ?>"
                        maxlength="2" style="text-transform: uppercase;" required>
                    <?php if (isset($validation) && $validation->hasError('KdStore')): ?>
                    <p class="mt-1 text-sm text-red-600"><?= $validation->getError('KdStore') ?></p>
                    <?php endif; ?>
                    <p class="mt-1 text-sm text-gray-500">Kode unik untuk identifikasi outlet (akan otomatis uppercase)
                    </p>
                </div>

                <!-- Nama Outlet -->
                <div>
                    <label for="nama_outlet" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nama Outlet <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_outlet" name="nama_outlet"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?= isset($validation) && $validation->hasError('nama_outlet') ? 'border-red-500' : '' ?>"
                        placeholder="Contoh: Outlet Cirebon Pusat"
                        value="<?= old('nama_outlet', $outlet['nama_outlet'] ?? '') ?>" maxlength="100" required>
                    <?php if (isset($validation) && $validation->hasError('nama_outlet')): ?>
                    <p class="mt-1 text-sm text-red-600"><?= $validation->getError('nama_outlet') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label for="alamat"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Alamat lengkap outlet"><?= old('alamat', $outlet['alamat'] ?? '') ?></textarea>
                </div>

                <!-- Telepon -->
                <div>
                    <label for="telepon"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telepon</label>
                    <input type="text" id="telepon" name="telepon"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Contoh: 0231-1234567" value="<?= old('telepon', $outlet['telepon'] ?? '') ?>"
                        maxlength="20">
                </div>

                <!-- Status -->
                <div>
                    <label for="is_active"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select id="is_active" name="is_active"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="1" <?= old('is_active', $outlet['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>
                            Aktif
                        </option>
                        <option value="0" <?= old('is_active', $outlet['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>
                            Nonaktif
                        </option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Outlet nonaktif tidak dapat dipilih saat transaksi</p>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
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
                            <li>Kode Store harus unik dan akan digunakan untuk identifikasi transaksi</li>
                            <li>Field yang bertanda <span class="text-red-500">*</span> wajib diisi</li>
                            <li>Outlet yang sudah memiliki transaksi tidak dapat dihapus</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 rounded-b-lg">
            <div class="flex items-center justify-end space-x-3">
                <a href="<?= base_url('admin/outlet') ?>"
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
                    <?= isset($outlet) ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto uppercase Kode Store
    const kdStoreInput = document.getElementById('KdStore');
    kdStoreInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Form validation
    const outletForm = document.getElementById('outletForm');
    outletForm.addEventListener('submit', function(e) {
        const kdStore = document.getElementById('KdStore').value;
        const namaOutlet = document.getElementById('nama_outlet').value;

        if (kdStore.length === 0 || kdStore.length > 2) {
            e.preventDefault();
            alert('Kode Store harus 1-2 karakter!');
            document.getElementById('KdStore').focus();
            return false;
        }

        if (namaOutlet.trim().length === 0) {
            e.preventDefault();
            alert('Nama Outlet harus diisi!');
            document.getElementById('nama_outlet').focus();
            return false;
        }
    });

    // Format phone number (optional)
    const teleponInput = document.getElementById('telepon');
    teleponInput.addEventListener('blur', function() {
        const phone = this.value.trim();
        // Add basic phone formatting logic if needed
    });
});
</script>
<?= $this->endSection() ?>