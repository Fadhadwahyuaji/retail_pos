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
                            <a href="<?= base_url('admin/user') ?>"
                                class="text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-white">
                                User Management
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
                                class="text-gray-500 dark:text-gray-400"><?= isset($user) ? 'Edit' : 'Tambah' ?></span>
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

<?php if (session()->getFlashdata('error')): ?>
    <div class="flex items-center p-4 mb-6 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800"
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

<!-- Main Form Card -->
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mr-2" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                <?= isset($user) ? 'Form Edit User' : 'Form Tambah User' ?>
            </h3>
        </div>
    </div>

    <form action="<?= isset($user) ? base_url('admin/user/update/' . $user['id']) : base_url('admin/user/store') ?>"
        method="POST" id="userForm">
        <?= csrf_field() ?>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama" name="nama"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?= isset($validation) && $validation->hasError('nama') ? 'border-red-500' : '' ?>"
                        placeholder="Nama lengkap user" value="<?= old('nama', $user['nama'] ?? '') ?>" maxlength="100"
                        required>
                    <?php if (isset($validation) && $validation->hasError('nama')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('nama') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?= isset($validation) && $validation->hasError('email') ? 'border-red-500' : '' ?>"
                        placeholder="email@example.com" value="<?= old('email', $user['email'] ?? '') ?>"
                        maxlength="100" required>
                    <?php if (isset($validation) && $validation->hasError('email')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('email') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Password <?= isset($user) ? '' : '<span class="text-red-500">*</span>' ?>
                    </label>
                    <input type="password" id="password" name="password"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="<?= isset($user) ? 'Kosongkan jika tidak ingin mengubah' : 'Minimal 6 karakter' ?>"
                        minlength="6" <?= isset($user) ? '' : 'required' ?>>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        <?= isset($user) ? 'Kosongkan jika tidak ingin mengubah password' : 'Minimal 6 karakter' ?>
                    </p>
                </div>

                <!-- Role -->
                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select id="role_id" name="role_id"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        required>
                        <option value="">-- Pilih Role --</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" data-kdrole="<?= $role['KdRole'] ?>"
                                <?= old('role_id', $user['role_id'] ?? '') == $role['id'] ? 'selected' : '' ?>>
                                <?= esc($role['nama_role']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Outlet (Conditional) -->
                <div id="outletField">
                    <label for="outlet_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Outlet <span id="outletRequired" class="text-red-500 hidden">*</span>
                    </label>
                    <select id="outlet_id" name="outlet_id"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">-- Semua Outlet / Tidak Terikat --</option>
                        <?php foreach ($outlets as $outlet): ?>
                            <option value="<?= $outlet['id'] ?>"
                                <?= old('outlet_id', $user['outlet_id'] ?? '') == $outlet['id'] ? 'selected' : '' ?>>
                                <?= esc($outlet['KdStore']) ?> - <?= esc($outlet['nama_outlet']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p id="outletHint" class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Pilih outlet untuk user Manajer atau Kasir
                    </p>
                </div>


                <!-- Status -->
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Status
                    </label>
                    <select id="is_active" name="is_active"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="1" <?= old('is_active', $user['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>
                            Aktif
                        </option>
                        <option value="0" <?= old('is_active', $user['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>
                            Nonaktif
                        </option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        User nonaktif tidak dapat login
                    </p>
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
                            <li><strong>Admin Pusat:</strong> Dapat akses semua outlet, tidak perlu pilih outlet</li>
                            <li><strong>Manajer & Kasir:</strong> Wajib dipilih outlet spesifik</li>
                            <li>Email harus unik dan akan digunakan untuk login</li>
                            <li>Password minimal 6 karakter</li>
                            <li>Field yang bertanda <span class="text-red-500">*</span> wajib diisi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 rounded-b-lg">
            <div class="flex items-center justify-end space-x-3">
                <a href="<?= base_url('admin/user') ?>"
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
                    <?= isset($user) ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle role change - show/hide outlet requirement
        const roleSelect = document.getElementById('role_id');
        const outletSelect = document.getElementById('outlet_id');
        const outletRequired = document.getElementById('outletRequired');
        const outletHint = document.getElementById('outletHint');
        const outletField = document.getElementById('outletField');

        function handleRoleChange() {
            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const kdRole = selectedOption.getAttribute('data-kdrole');

            // Roles that require outlet: MG/MNG (Manajer), KS/KSR (Kasir)
            const requiresOutlet = ['MG', 'MNG', 'KS', 'KSR', 'MANAGER', 'KASIR'].includes(kdRole);

            if (requiresOutlet) {
                outletRequired.classList.remove('hidden');
                outletSelect.required = true;
                outletHint.innerHTML =
                    '<strong class="text-red-600 dark:text-red-400">Wajib pilih outlet untuk role ini!</strong>';
                outletField.classList.add('ring-2', 'ring-yellow-300', 'rounded-lg', 'p-2', '-m-2');
            } else {
                outletRequired.classList.add('hidden');
                outletSelect.required = false;
                outletSelect.value = ''; // Set to empty instead of '0'
                outletHint.innerHTML = 'Pilih outlet untuk user Manajer atau Kasir';
                outletField.classList.remove('ring-2', 'ring-yellow-300', 'rounded-lg', 'p-2', '-m-2');
            }
        }

        roleSelect.addEventListener('change', handleRoleChange);

        // Trigger on page load
        handleRoleChange();

        // Form validation
        const userForm = document.getElementById('userForm');
        userForm.addEventListener('submit', function(e) {
            const roleId = roleSelect.value;
            const outletId = outletSelect.value;
            const selectedRole = roleSelect.options[roleSelect.selectedIndex].getAttribute('data-kdrole');

            if (!roleId) {
                e.preventDefault();
                alert('Role harus dipilih!');
                roleSelect.focus();
                return false;
            }

            // Validate outlet for Manajer/Kasir
            const requiresOutlet = ['MG', 'MNG', 'KS', 'KSR', 'MANAGER', 'KASIR'].includes(selectedRole);
            if (requiresOutlet && (!outletId || outletId === '')) {
                e.preventDefault();
                alert('Outlet wajib dipilih untuk role Manajer atau Kasir!');
                outletSelect.focus();
                return false;
            }

            const email = document.getElementById('email').value;
            if (!email || !email.includes('@')) {
                e.preventDefault();
                alert('Email tidak valid!');
                document.getElementById('email').focus();
                return false;
            }

            <?php if (!isset($user)): ?>
                const password = document.getElementById('password').value;
                if (!password || password.length < 6) {
                    e.preventDefault();
                    alert('Password minimal 6 karakter!');
                    document.getElementById('password').focus();
                    return false;
                }
            <?php endif; ?>
        });
    });
</script>
<?= $this->endSection() ?>