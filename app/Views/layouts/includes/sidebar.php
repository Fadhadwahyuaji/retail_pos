<?php
$kd_role = session()->get('kd_role');
$nama_user = session()->get('nama');
$nama_role = session()->get('nama_role');
?>

<aside id="application-sidebar"
    class="hs-overlay [--auto-close:lg] hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform fixed top-0 start-0 bottom-0 z-[60] w-64 bg-white border-e border-gray-200 overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-800 dark:border-neutral-700 sm:translate-x-0"
    role="dialog" tabindex="-1" aria-label="Sidebar">

    <!-- Sidebar Header dengan Logo/Brand -->
    <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200 dark:border-neutral-700">
        <a href="<?= base_url('dashboard') ?>" class="flex items-center space-x-2">
            <div
                class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                </svg>
            </div>
            <span class="text-lg font-bold text-gray-800 dark:text-white">RetailPOS</span>
        </a>

        <!-- Close button untuk mobile -->
        <button type="button"
            class="sm:hidden inline-flex items-center justify-center size-7 text-sm font-semibold rounded-lg border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
            data-hs-overlay="#application-sidebar">
            <span class="sr-only">Close</span>
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"></path>
                <path d="m6 6 12 12"></path>
            </svg>
        </button>
    </div>

    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-neutral-800">
        <!-- User Info -->
        <div
            class="my-4 p-3 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-neutral-700 dark:to-neutral-700 rounded-lg border border-blue-100 dark:border-neutral-600">
            <div class="flex items-center space-x-3">
                <div
                    class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-lg font-bold">
                        <?= strtoupper(substr($nama_user, 0, 1)) ?>
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Selamat datang</p>
                    <p class="font-semibold text-gray-900 dark:text-white truncate"><?= esc($nama_user) ?></p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-medium"><?= esc($nama_role) ?></p>
                </div>
            </div>
        </div>

        <ul class="space-y-2 font-medium">
            <li>
                <a href="<?= base_url('dashboard') ?>"
                    class="flex items-center p-2.5 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors <?= uri_string() == 'dashboard' ? 'bg-blue-50 text-blue-600 dark:bg-neutral-700 dark:text-blue-400' : '' ?>">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
                        </path>
                    </svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>

            <?php if ($kd_role == 'AD'): ?>
            <!-- Menu khusus Admin Pusat -->
            <li>
                <button type="button"
                    class="flex items-center w-full p-2.5 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700"
                    aria-controls="dropdown-admin" data-collapse-toggle="dropdown-admin">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="flex-1 ms-3 text-left whitespace-nowrap">Manajemen</span>
                    <svg class="w-3 h-3 transition-transform duration-200" fill="none" viewBox="0 0 10 6"
                        id="arrow-admin">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="dropdown-admin" class="hidden py-2 space-y-2">
                    <li>
                        <a href="<?= base_url('admin/manajemen-user') ?>"
                            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z">
                                </path>
                            </svg>
                            Manajemen User
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/outlet') ?>"
                            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Manajemen Outlet
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/barang') ?>"
                            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
                                </path>
                            </svg>
                            Manajemen Barang
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($kd_role == 'AD' || $kd_role == 'MG'): ?>
            <li>
                <a href="<?= base_url('manajer/laporan-outlet') ?>"
                    class="flex items-center p-2.5 text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd"
                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="ms-3">Laporan Penjualan</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if ($kd_role == 'AD' || $kd_role == 'MG' || $kd_role == 'KS'): ?>
            <li>
                <a href="<?= base_url('kasir/transaksi-pos') ?>"
                    class="flex items-center p-2.5 text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                        <path fill-rule="evenodd"
                            d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="ms-3">Transaksi POS</span>
                </a>
            </li>
            <?php endif; ?>

            <li class="pt-4 mt-4 border-t border-gray-200 dark:border-neutral-700">
                <a href="<?= base_url('auth/logout') ?>"
                    class="flex items-center p-2.5 text-red-600 rounded-lg hover:bg-red-50 dark:text-red-400 dark:hover:bg-neutral-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span class="ms-3 font-medium">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle dropdown functionality
    const toggleButtons = document.querySelectorAll('[data-collapse-toggle]');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-collapse-toggle');
            const target = document.getElementById(targetId);
            const arrow = this.querySelector('svg:last-child');

            target.classList.toggle('hidden');
            if (arrow) {
                arrow.classList.toggle('rotate-180');
            }
        });
    });
});
</script>