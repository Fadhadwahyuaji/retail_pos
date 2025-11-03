<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<h1 class="text-2xl font-bold mb-4"><?= $title ?></h1>

<?php if (session()->get('errors')): ?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400">
        <ul class="list-disc list-inside">
            <?php foreach (session()->get('errors') as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="bg-white p-6 rounded-xl shadow-lg dark:bg-gray-800">
    <form action="<?= base_url('admin/manajemen-user/save') ?>" method="post">
        <?= csrf_field() ?> <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label for="nama" class="block text-sm mb-2 dark:text-white">Nama Lengkap</label>
                <input type="text" id="nama" name="nama"
                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400"
                    value="<?= old('nama') ?>" required>
            </div>

            <div>
                <label for="email" class="block text-sm mb-2 dark:text-white">Email</label>
                <input type="email" id="email" name="email"
                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400"
                    value="<?= old('email') ?>" required>
            </div>

            <div>
                <label for="password" class="block text-sm mb-2 dark:text-white">Password</label>
                <input type="password" id="password" name="password"
                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400"
                    required>
            </div>

            <div>
                <label for="pass_confirm" class="block text-sm mb-2 dark:text-white">Konfirmasi Password</label>
                <input type="password" id="pass_confirm" name="pass_confirm"
                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400"
                    required>
            </div>

            <div>
                <label for="role_id" class="block text-sm mb-2 dark:text-white">Role</label>
                <select id="role_id" name="role_id"
                    class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400"
                    required>
                    <option value="">Pilih Role</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>" <?= (old('role_id') == $role['id'] ? 'selected' : '') ?>>
                            <?= $role['nama_role'] ?> (<?= $role['kd_role'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="outlet_id" class="block text-sm mb-2 dark:text-white">Outlet ID (Isi 0 jika Admin
                    Pusat)</label>
                <input type="number" id="outlet_id" name="outlet_id"
                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400"
                    value="<?= old('outlet_id', 0) ?>">
            </div>

        </div>

        <div class="mt-6 flex justify-end gap-x-2">
            <button type="submit"
                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                Simpan User
            </button>
            <a href="<?= base_url('admin/manajemen-user') ?>"
                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800">
                Batal
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>