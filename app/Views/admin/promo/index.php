<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800"><?= $title ?></h1>
            <p class="text-gray-600 mt-1">Kelola promo dengan validasi jam & hari berlaku</p>
        </div>
        <nav class="text-sm text-gray-600">
            <a href="<?= base_url('admin/dashboard') ?>" class="hover:text-blue-600">Dashboard</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800">Promo</span>
        </nav>
    </div>
</div>

<!-- Alert Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-xl mr-3"></i>
            <p class="font-medium"><?= session()->getFlashdata('success') ?></p>
            <button onclick="this.parentElement.parentElement.style.display='none'" class="ml-auto">
                <i class="fas fa-times text-green-700 hover:text-green-900"></i>
            </button>
        </div>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-xl mr-3"></i>
            <p class="font-medium"><?= session()->getFlashdata('error') ?></p>
            <button onclick="this.parentElement.parentElement.style.display='none'" class="ml-auto">
                <i class="fas fa-times text-red-700 hover:text-red-900"></i>
            </button>
        </div>
    </div>
<?php endif; ?>

<!-- Add Button -->
<div class="mb-6 flex justify-end">
    <a href="<?= base_url('admin/promo/create') ?>"
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center transition shadow-lg">
        <i class="fas fa-plus mr-2"></i>
        Tambah Promo Baru
    </a>
</div>

<!-- Promo Cards Grid -->
<?php if (empty($promos)): ?>
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <i class="fas fa-tags text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Promo</h3>
        <p class="text-gray-500 mb-6">Mulai buat promo untuk menarik pelanggan</p>
        <a href="<?= base_url('admin/promo/create') ?>"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg inline-flex items-center transition">
            <i class="fas fa-plus mr-2"></i>
            Buat Promo Pertama
        </a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <?php foreach ($promos as $promo):
            $hariBerlaku = explode(',', $promo['hari_berlaku']);
            $isActive = $promo['Status'] == '1';
            $today = date('Y-m-d');
            $isValid = ($today >= $promo['TglAwal'] && $today <= $promo['TglAkhir']);
        ?>
            <div
                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition <?= $isActive && $isValid ? 'border-l-4 border-green-500' : 'border-l-4 border-gray-300' ?>">

                <!-- Card Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="text-lg font-bold text-gray-800"><?= esc($promo['Ketentuan']) ?></h3>
                                <?php if ($isActive && $isValid): ?>
                                    <span
                                        class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full animate-pulse">
                                        <i class="fas fa-bolt"></i> AKTIF
                                    </span>
                                <?php elseif (!$isActive): ?>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">
                                        NONAKTIF
                                    </span>
                                <?php elseif ($today < $promo['TglAwal']): ?>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                        AKAN DATANG
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                        EXPIRED
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm font-mono text-gray-600"><?= esc($promo['NoTrans']) ?></p>
                        </div>
                        <div class="flex space-x-1">
                            <a href="<?= base_url('admin/promo/detail/' . $promo['NoTrans']) ?>"
                                class="text-blue-600 hover:text-blue-800 p-2" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?= base_url('admin/promo/edit/' . $promo['NoTrans']) ?>"
                                class="text-yellow-600 hover:text-yellow-800 p-2" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="px-6 py-4 space-y-4">

                    <!-- Periode Berlaku -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">
                            <i class="fas fa-calendar-alt mr-1"></i> Periode Berlaku
                        </p>
                        <div class="flex items-center space-x-2 text-sm">
                            <span class="font-semibold text-gray-700"><?= date('d M Y', strtotime($promo['TglAwal'])) ?></span>
                            <i class="fas fa-arrow-right text-gray-400"></i>
                            <span class="font-semibold text-gray-700"><?= date('d M Y', strtotime($promo['TglAkhir'])) ?></span>
                        </div>
                    </div>

                    <!-- Jam Berlaku -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">
                            <i class="fas fa-clock mr-1"></i> Jam Berlaku
                        </p>
                        <div class="flex items-center space-x-2 text-sm">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded font-mono">
                                <?= substr($promo['jam_mulai'], 0, 5) ?>
                            </span>
                            <i class="fas fa-arrow-right text-gray-400"></i>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded font-mono">
                                <?= substr($promo['jam_selesai'], 0, 5) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Hari Berlaku -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">
                            <i class="fas fa-calendar-week mr-1"></i> Hari Berlaku
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <?php
                            $allDays = [1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab', 7 => 'Min'];
                            foreach ($allDays as $dayNum => $dayName):
                                $isActive = in_array($dayNum, $hariBerlaku);
                            ?>
                                <span
                                    class="px-2 py-1 rounded text-xs font-semibold <?= $isActive ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-400' ?>">
                                    <?= $dayName ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Outlet -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">
                            <i class="fas fa-store mr-1"></i> Berlaku di
                        </p>
                        <?php if ($promo['outlet_id']): ?>
                            <span
                                class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 rounded text-sm font-medium">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <?= esc($promo['KdStore']) ?> - <?= esc($promo['nama_outlet']) ?>
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded text-sm font-medium">
                                <i class="fas fa-globe mr-2"></i>
                                Semua Outlet
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600"><?= $promo['item_count'] ?></p>
                            <p class="text-xs text-gray-500">Produk</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600"><?= $promo['transaction_count'] ?></p>
                            <p class="text-xs text-gray-500">Transaksi</p>
                        </div>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <a href="<?= base_url('admin/promo/items/' . $promo['NoTrans']) ?>"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center">
                            <i class="fas fa-cog mr-2"></i>
                            Kelola Item (<?= $promo['item_count'] ?>)
                        </a>

                        <div class="flex space-x-2">
                            <a href="<?= base_url('admin/promo/toggle-status/' . $promo['NoTrans']) ?>"
                                class="<?= $isActive ? 'bg-gray-500 hover:bg-gray-600' : 'bg-green-500 hover:bg-green-600' ?> text-white px-3 py-1 rounded text-xs transition"
                                onclick="return confirm('Yakin ingin mengubah status promo?')">
                                <i class="fas fa-power-off"></i>
                            </a>

                            <?php if ($promo['transaction_count'] == 0): ?>
                                <button onclick="deletePromo('<?= $promo['NoTrans'] ?>', '<?= esc($promo['Ketentuan']) ?>')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            <?php else: ?>
                                <button class="bg-gray-300 text-gray-500 px-3 py-1 rounded text-xs cursor-not-allowed"
                                    title="Tidak dapat dihapus (sudah ada transaksi)" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Delete confirmation
    function deletePromo(noTrans, ketentuan) {
        if (confirm(
                `Yakin ingin menghapus promo "${ketentuan}"?\n\nSemua item dalam promo ini juga akan dihapus!\n\nData yang terhapus tidak dapat dikembalikan!`
            )) {
            window.location.href = `<?= base_url('admin/promo/delete/') ?>${noTrans}`;
        }
    }

    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
<?= $this->endSection() ?>