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
                            <span class="text-gray-500 dark:text-gray-400">Outlet</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Alert Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div id="success-alert"
    class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800"
    role="alert">
    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
        fill="currentColor" viewBox="0 0 20 20">
        <path
            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
    </svg>
    <span class="font-medium"><?= session()->getFlashdata('success') ?></span>
    <button type="button"
        class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"
        onclick="this.parentElement.style.display='none'">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
        </svg>
    </button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div id="error-alert"
    class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800"
    role="alert">
    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
        fill="currentColor" viewBox="0 0 20 20">
        <path
            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
    </svg>
    <span class="font-medium"><?= session()->getFlashdata('error') ?></span>
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

<!-- Main Card -->
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Daftar Outlet</h3>
        <a href="<?= base_url('admin/outlet/create') ?>"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            Tambah Outlet
        </a>
    </div>

    <div class="p-6">
        <?php if (!empty($outlets)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">
                            No</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-20">
                            Kode</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Nama Outlet</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Alamat</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">
                            Telepon</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-20">
                            User</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-24">
                            Status</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <?php $no = 1; ?>
                    <?php foreach ($outlets as $outlet): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300"><?= $no++ ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="text-sm font-medium text-gray-900 dark:text-white"><?= esc($outlet['KdStore']) ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                            <?= esc($outlet['nama_outlet']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                            <?= esc($outlet['alamat'] ?? '-') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                            <?= esc($outlet['telepon'] ?? '-') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300"><?= $outlet['user_count'] ?>
                                User</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <?php if ($outlet['is_active'] == 1): ?>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Aktif</span>
                            <?php else: ?>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="<?= base_url('admin/outlet/edit/' . $outlet['id']) ?>"
                                    class="inline-flex items-center px-3 py-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 text-xs font-medium rounded-md transition-colors duration-150"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>

                                <a href="<?= base_url('admin/outlet/toggle-status/' . $outlet['id']) ?>"
                                    class="inline-flex items-center px-3 py-1 <?= $outlet['is_active'] == 1 ? 'bg-gray-100 hover:bg-gray-200 text-gray-800' : 'bg-green-100 hover:bg-green-200 text-green-800' ?> text-xs font-medium rounded-md transition-colors duration-150"
                                    title="<?= $outlet['is_active'] == 1 ? 'Nonaktifkan' : 'Aktifkan' ?>"
                                    onclick="return confirm('Yakin ingin mengubah status outlet ini?')">
                                    <?php if ($outlet['is_active'] == 1): ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21">
                                        </path>
                                    </svg>
                                    <?php else: ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <?php endif; ?>
                                </a>

                                <?php if ($outlet['user_count'] == 0): ?>
                                <button type="button"
                                    onclick="confirmDelete(<?= $outlet['id'] ?>, '<?= addslashes(esc($outlet['nama_outlet'])) ?>')"
                                    class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-800 text-xs font-medium rounded-md transition-colors duration-150"
                                    title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                                <?php else: ?>
                                <button type="button"
                                    class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-400 text-xs font-medium rounded-md cursor-not-allowed"
                                    title="Tidak dapat dihapus (masih ada user)" disabled>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                </path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-300">Belum ada outlet</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan outlet pertama Anda.</p>
            <div class="mt-6">
                <a href="<?= base_url('admin/outlet/create') ?>"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Outlet
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Function to confirm and delete outlet
function confirmDelete(id, nama) {
    Swal.fire({
        title: '<span style="font-size:22px; font-weight:600;">Konfirmasi Penghapusan</span>',
        html: `
            <div style="text-align:left; font-size:15px; color:#444;">
                <p>Apakah Anda yakin ingin menghapus outlet <strong>${nama}</strong>?</p>
                <div style="background:#fff8e1; border-left:4px solid #f6c343; padding:10px 15px; border-radius:6px; margin-top:10px;">
                    <p style="margin:0; font-size:14px;">
                        ⚠️ <strong>Peringatan:</strong><br>
                        - Data yang dihapus <b>tidak dapat dikembalikan</b>.<br>
                        - Pastikan outlet ini <b>tidak memiliki transaksi</b> atau data penting lainnya.
                    </p>
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '🗑️ Hapus Sekarang',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#e74c3c', // merah elegan
        cancelButtonColor: '#6c757d', // abu profesional
        background: '#fefefe',
        reverseButtons: true,
        focusCancel: true,
        width: 480,
        padding: '1.5em',
        customClass: {
            popup: 'rounded-xl shadow-lg border border-gray-200',
            confirmButton: 'px-4 py-2 text-sm font-medium rounded-md',
            cancelButton: 'px-4 py-2 text-sm font-medium rounded-md'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Menghapus Outlet...',
                html: `<p style="font-size:14px; color:#555;">Outlet <b>${nama}</b> sedang dihapus.</p>`,
                icon: 'info',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                    setTimeout(() => {
                        window.location.href =
                            `<?= base_url('admin/outlet/delete/') ?>${id}`;
                    }, 1200);
                }
            });
        }
    });
}


// Legacy function for backward compatibility
function deleteOutlet(id, nama) {
    confirmDelete(id, nama);
}

// Auto hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    // Hide success alerts
    const successAlert = document.getElementById('success-alert');
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.remove(), 300);
        }, 5000);
    }

    // Hide error alerts
    const errorAlert = document.getElementById('error-alert');
    if (errorAlert) {
        setTimeout(function() {
            errorAlert.style.opacity = '0';
            setTimeout(() => errorAlert.remove(), 300);
        }, 10000); // Error alerts stay longer
    }
});

// Add visual feedback for button interactions
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('[onclick*="confirmDelete"]');

    deleteButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });

        button.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});

// Debug function to test if JavaScript is working
function testJS() {
    alert('JavaScript is working!');
}

// Log to console for debugging
console.log('Outlet index page loaded successfully');
console.log('Available outlets:', <?= json_encode($outlets ?? []) ?>);
</script>
<?= $this->endSection() ?>