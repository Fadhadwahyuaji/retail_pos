<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Promo</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap promo</p>
        </div>
        <nav class="text-sm text-gray-600">
            <a href="<?= base_url('admin/dashboard') ?>" class="hover:text-blue-600">Dashboard</a>
            <span class="mx-2">/</span>
            <a href="<?= base_url('admin/promo') ?>" class="hover:text-blue-600">Promo</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800">Detail</span>
        </nav>
    </div>
</div>

<!-- Main Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left Column: Promo Info -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Header Card -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <h2 class="text-3xl font-bold"><?= esc($promo['Ketentuan']) ?></h2>
                        <?php if ($promo['Status'] == '1'): ?>
                            <span class="px-3 py-1 bg-green-400 text-white text-sm font-semibold rounded-full">
                                <i class="fas fa-check-circle"></i> AKTIF
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 bg-gray-400 text-white text-sm font-semibold rounded-full">
                                NONAKTIF
                            </span>
                        <?php endif; ?>
                    </div>
                    <p class="text-lg opacity-90 font-mono"><?= esc($promo['NoTrans']) ?></p>
                </div>
                <div class="text-right">
                    <a href="<?= base_url('admin/promo/edit/' . $promo['NoTrans']) ?>"
                        class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition inline-flex items-center">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                </div>
            </div>
        </div>

        <!-- Periode & Waktu -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                Periode & Waktu Berlaku
            </h3>

            <div class="space-y-4">
                <!-- Tanggal -->
                <div class="flex items-start">
                    <div class="w-1/3">
                        <p class="text-sm font-semibold text-gray-600">Periode Tanggal</p>
                    </div>
                    <div class="w-2/3">
                        <div class="flex items-center space-x-3">
                            <span class="px-4 py-2 bg-blue-50 text-blue-800 rounded-lg font-semibold">
                                <?= date('d M Y', strtotime($promo['TglAwal'])) ?>
                            </span>
                            <i class="fas fa-arrow-right text-gray-400"></i>
                            <span class="px-4 py-2 bg-blue-50 text-blue-800 rounded-lg font-semibold">
                                <?= date('d M Y', strtotime($promo['TglAkhir'])) ?>
                            </span>
                        </div>
                        <?php
                        $today = date('Y-m-d');
                        $daysLeft = floor((strtotime($promo['TglAkhir']) - strtotime($today)) / (60 * 60 * 24));
                        ?>
                        <?php if ($daysLeft >= 0): ?>
                            <p class="text-sm text-gray-500 mt-2">
                                <i class="fas fa-hourglass-half mr-1"></i>
                                <?= $daysLeft == 0 ? 'Hari terakhir!' : $daysLeft . ' hari lagi' ?>
                            </p>
                        <?php else: ?>
                            <p class="text-sm text-red-500 mt-2">
                                <i class="fas fa-times-circle mr-1"></i>
                                Sudah berakhir
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Jam -->
                <div class="flex items-start">
                    <div class="w-1/3">
                        <p class="text-sm font-semibold text-gray-600">Jam Berlaku</p>
                    </div>
                    <div class="w-2/3">
                        <div class="flex items-center space-x-3">
                            <span class="px-4 py-2 bg-green-50 text-green-800 rounded-lg font-mono font-semibold">
                                <?= substr($promo['jam_mulai'], 0, 5) ?>
                            </span>
                            <i class="fas fa-arrow-right text-gray-400"></i>
                            <span class="px-4 py-2 bg-green-50 text-green-800 rounded-lg font-mono font-semibold">
                                <?= substr($promo['jam_selesai'], 0, 5) ?>
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Promo berlaku selama
                            <?= round((strtotime($promo['jam_selesai']) - strtotime($promo['jam_mulai'])) / 3600, 1) ?>
                            jam per hari
                        </p>
                    </div>
                </div>

                <!-- Hari -->
                <div class="flex items-start">
                    <div class="w-1/3">
                        <p class="text-sm font-semibold text-gray-600">Hari Berlaku</p>
                    </div>
                    <div class="w-2/3">
                        <?php
                        $hariBerlaku = explode(',', $promo['hari_berlaku']);
                        $allDays = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                        ?>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($allDays as $num => $name):
                                $isActive = in_array($num, $hariBerlaku);
                            ?>
                                <span
                                    class="px-3 py-1 rounded-lg text-sm font-semibold <?= $isActive ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-400' ?>">
                                    <?= $name ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            <i class="fas fa-calendar-check mr-1"></i>
                            Berlaku <?= count($hariBerlaku) ?> hari dalam seminggu
                        </p>
                    </div>
                </div>

                <!-- Outlet -->
                <div class="flex items-start">
                    <div class="w-1/3">
                        <p class="text-sm font-semibold text-gray-600">Berlaku di</p>
                    </div>
                    <div class="w-2/3">
                        <?php if ($promo['outlet_id']): ?>
                            <?php
                            $db = \Config\Database::connect();
                            $outlet = $db->table('outlets')->where('id', $promo['outlet_id'])->get()->getRowArray();
                            ?>
                            <span
                                class="inline-flex items-center px-4 py-2 bg-purple-100 text-purple-800 rounded-lg font-semibold">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <?= esc($outlet['KdStore']) ?> - <?= esc($outlet['nama_outlet']) ?>
                            </span>
                        <?php else: ?>
                            <span
                                class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-semibold">
                                <i class="fas fa-globe mr-2"></i>
                                Semua Outlet
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items List -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-boxes text-blue-600 mr-3"></i>
                    Produk dalam Promo (<?= count($promo['items']) ?>)
                </h3>
                <a href="<?= base_url('admin/promo/items/' . $promo['NoTrans']) ?>"
                    class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                    <i class="fas fa-cog mr-1"></i>Kelola Item
                </a>
            </div>

            <div class="p-6">
                <?php if (empty($promo['items'])): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada produk dalam promo</p>
                        <a href="<?= base_url('admin/promo/items/' . $promo['NoTrans']) ?>"
                            class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                            <i class="fas fa-plus mr-2"></i>Tambah Produk
                        </a>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga
                                        Normal</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Diskon
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga Promo
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Hemat</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $no = 1; ?>
                                <?php foreach ($promo['items'] as $item):
                                    // Pastikan data harga tersedia
                                    $hargaNormal = isset($item['Harga1c']) ? $item['Harga1c'] : 0;
                                    $discountAmount = 0;

                                    if ($item['Jenis'] == 'P') {
                                        $discountAmount = ($hargaNormal * $item['Nilai']) / 100;
                                    } else {
                                        $discountAmount = $item['Nilai'];
                                    }

                                    $hargaPromo = $hargaNormal - $discountAmount;
                                    $savePercent = ($hargaNormal > 0) ? round(($discountAmount / $hargaNormal) * 100, 1) : 0;
                                ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900"><?= $no++ ?></td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= esc($item['NamaLengkap'] ?? 'Produk tidak ditemukan') ?>
                                            </div>
                                            <div class="text-xs text-gray-500 font-mono"><?= esc($item['PCode']) ?></div>
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm text-gray-900">
                                            <?php if ($hargaNormal > 0): ?>
                                                Rp <?= number_format($hargaNormal, 0, ',', '.') ?>
                                            <?php else: ?>
                                                <span class="text-red-500">Data harga tidak tersedia</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">
                                                <?php if ($item['Jenis'] == 'P'): ?>
                                                    <?= $item['Nilai'] ?>%
                                                <?php else: ?>
                                                    Rp <?= number_format($item['Nilai'], 0, ',', '.') ?>
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm font-bold text-green-600">
                                            <?php if ($hargaNormal > 0): ?>
                                                Rp <?= number_format($hargaPromo, 0, ',', '.') ?>
                                            <?php else: ?>
                                                <span class="text-red-500">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <?php if ($hargaNormal > 0): ?>
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <?= $savePercent ?>%
                                                </span>
                                            <?php else: ?>
                                                <span class="text-red-500">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Stats & Actions -->
    <div class="space-y-6">

        <!-- Stats Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Promo</h3>

            <div class="space-y-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-3xl font-bold text-blue-600"><?= $stats['item_count'] ?></p>
                    <p class="text-sm text-gray-600 mt-1">Produk</p>
                </div>

                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-3xl font-bold text-green-600"><?= $stats['transaction_count'] ?></p>
                    <p class="text-sm text-gray-600 mt-1">Transaksi</p>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="bg-white rounded-lg shadow-md p-6 space-y-3">
            <a href="<?= base_url('admin/promo/edit/' . $promo['NoTrans']) ?>"
                class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg inline-flex items-center justify-center transition">
                <i class="fas fa-edit mr-2"></i>Edit Promo
            </a>

            <a href="<?= base_url('admin/promo/items/' . $promo['NoTrans']) ?>"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center justify-center transition">
                <i class="fas fa-cog mr-2"></i>Kelola Item
            </a>

            <a href="<?= base_url('admin/promo/toggle-status/' . $promo['NoTrans']) ?>"
                class="w-full <?= $promo['Status'] == '1' ? 'bg-gray-500 hover:bg-gray-600' : 'bg-green-500 hover:bg-green-600' ?> text-white px-4 py-2 rounded-lg inline-flex items-center justify-center transition"
                onclick="return confirm('Yakin ingin mengubah status promo?')">
                <i class="fas fa-power-off mr-2"></i>
                <?= $promo['Status'] == '1' ? 'Nonaktifkan' : 'Aktifkan' ?>
            </a>

            <a href="<?= base_url('admin/promo') ?>"
                class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center justify-center transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Info Card -->
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-yellow-600 text-lg mr-3 mt-0.5"></i>
                <div>
                    <p class="text-sm font-semibold text-yellow-900 mb-2">Cara Kerja Promo</p>
                    <ul class="text-xs text-yellow-800 space-y-1">
                        <li>• Promo otomatis apply saat transaksi</li>
                        <li>• Validasi: tanggal, jam, hari, outlet</li>
                        <li>• Hanya produk terdaftar yang dapat promo</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>