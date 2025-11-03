<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<h1 class="text-2xl font-bold mb-4">Manajemen User</h1>

<div class="flex justify-end mb-4">
    <a href="<?= base_url('admin/manajemen-user/create') ?>"
        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
        + Tambah User
    </a>
</div>

<div class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="overflow-hidden border rounded-lg dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-gray-400">
                                Nama</th>
                            <th scope="col"
                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-gray-400">
                                Email</th>
                            <th scope="col"
                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-gray-400">
                                Role</th>
                            <th scope="col"
                                class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-gray-400">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        <?= esc($user['nama']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        <?= esc($user['email']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        <span
                                            class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-<?= ($user['kd_role'] == 'AD' ? 'red' : ($user['kd_role'] == 'MG' ? 'yellow' : 'green')) ?>-100 text-<?= ($user['kd_role'] == 'AD' ? 'red' : ($user['kd_role'] == 'MG' ? 'yellow' : 'green')) ?>-800">
                                            <?= esc($user['nama_role']) ?> (<?= esc($user['kd_role']) ?>)
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                        <a class="text-blue-500 hover:text-blue-700 me-3"
                                            href="<?= base_url('admin/manajemen-user/edit/' . $user['id']) ?>">Edit</a>
                                        <a class="text-red-500 hover:text-red-700" href="#"
                                            onclick="confirmDelete(<?= $user['id'] ?>)">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada data user
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>