<?php
$this->extend('layouts/main');

$this->section('content');
?>
<div class="min-h-screen">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400">Selamat datang kembali, <?= esc($user['nama']) ?>!</p>
        <p class="text-sm text-gray-500 dark:text-gray-400">Role: <?= esc($user['nama_role']) ?></p>
    </div>

    <!-- Role Specific Content -->
    <?php if ($user['kd_role'] == 'AD'): ?>
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Admin Tools</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="<?= base_url('admin/manajemen-user') ?>"
                    class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700">
                    <h4 class="font-medium text-gray-900 dark:text-white">Manajemen User</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola user dan akses sistem</p>
                </a>
                <a href="<?= base_url('admin/manajemen-outlet') ?>"
                    class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700">
                    <h4 class="font-medium text-gray-900 dark:text-white">Manajemen Outlet</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola outlet dan konfigurasi</p>
                </a>
            </div>
        </div>
    <?php elseif ($user['kd_role'] == 'MG'): ?>
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Manager Tools</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="<?= base_url('manajer/laporan-outlet') ?>"
                    class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700">
                    <h4 class="font-medium text-gray-900 dark:text-white">Laporan Penjualan</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Lihat laporan dan analisis penjualan</p>
                </a>
                <a href="<?= base_url('kasir/transaksi-pos') ?>"
                    class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700">
                    <h4 class="font-medium text-gray-900 dark:text-white">POS System</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Akses sistem kasir</p>
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Cashier Tools</h3>
            <div class="grid grid-cols-1 gap-4">
                <a href="<?= base_url('kasir/transaksi-pos') ?>"
                    class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700">
                    <h4 class="font-medium text-gray-900 dark:text-white">Transaksi POS</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Mulai transaksi penjualan</p>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Stats atau informasi lainnya -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Akun</h3>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white"><?= esc($user['nama']) ?></dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white"><?= esc($user['email']) ?></dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white"><?= esc($user['nama_role']) ?></dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Role</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white"><?= esc($user['kd_role']) ?></dd>
            </div>
        </dl>
    </div>
</div>
<?= $this->endSection() ?>