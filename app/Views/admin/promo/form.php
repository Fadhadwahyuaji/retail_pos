<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800"><?= $title ?></h1>
            <p class="text-gray-600 mt-1">
                <?= isset($promo) ? 'Ubah data promo' : 'Buat promo dengan validasi jam & hari' ?></p>
        </div>
        <nav class="text-sm text-gray-600">
            <a href="<?= base_url('admin/dashboard') ?>" class="hover:text-blue-600">Dashboard</a>
            <span class="mx-2">/</span>
            <a href="<?= base_url('admin/promo') ?>" class="hover:text-blue-600">Promo</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800"><?= isset($promo) ? 'Edit' : 'Tambah' ?></span>
        </nav>
    </div>
</div>

<!-- Validation Errors -->
<?php if (session()->getFlashdata('errors')): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
        <div class="flex items-start">
            <i class="fas fa-exclamation-circle text-xl mr-3 mt-0.5"></i>
            <div class="flex-1">
                <p class="font-bold mb-2">Validasi Error!</p>
                <ul class="list-disc list-inside space-y-1">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <button onclick="this.parentElement.parentElement.style.display='none'" class="ml-4">
                <i class="fas fa-times text-red-700 hover:text-red-900"></i>
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

<!-- Form Card -->
<div class="bg-white rounded-lg shadow-md">
    <!-- Card Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-tags mr-3 text-blue-600"></i>
            <?= isset($promo) ? 'Form Edit Promo' : 'Form Tambah Promo' ?>
        </h2>
    </div>

    <!-- Form -->
    <form
        action="<?= isset($promo) ? base_url('admin/promo/update/' . $promo['NoTrans']) : base_url('admin/promo/store') ?>"
        method="POST" id="promoForm" class="p-6">
        <?= csrf_field() ?>

        <!-- Basic Info -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Informasi Dasar
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Promo -->
                <div>
                    <label for="NoTrans" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Promo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="NoTrans" name="NoTrans"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase"
                        placeholder="Contoh: PROMO001" value="<?= old('NoTrans', $promo['NoTrans'] ?? '') ?>"
                        maxlength="11" <?= isset($promo) ? 'readonly' : '' ?> required>
                    <p class="mt-1 text-sm text-gray-500">Kode unik untuk identifikasi promo</p>
                </div>

                <!-- Nama Promo -->
                <div>
                    <label for="Ketentuan" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Promo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="Ketentuan" name="Ketentuan"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Contoh: Diskon Weekend Special"
                        value="<?= old('Ketentuan', $promo['Ketentuan'] ?? '') ?>" maxlength="25" required>
                </div>
            </div>
        </div>

        <!-- Periode Berlaku -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                Periode Berlaku
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tanggal Mulai -->
                <div>
                    <label for="TglAwal" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="TglAwal" name="TglAwal"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        value="<?= old('TglAwal', $promo['TglAwal'] ?? date('Y-m-d')) ?>" required>
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <label for="TglAkhir" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="TglAkhir" name="TglAkhir"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        value="<?= old('TglAkhir', $promo['TglAkhir'] ?? '') ?>" required>
                </div>
            </div>
        </div>

        <!-- Jam Berlaku ⭐ CRITICAL -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clock text-blue-600 mr-2"></i>
                Jam Berlaku
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jam Mulai -->
                <div>
                    <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" id="jam_mulai" name="jam_mulai"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        value="<?= old('jam_mulai', isset($promo['jam_mulai']) ? substr($promo['jam_mulai'], 0, 5) : '00:00') ?>"
                        required>
                </div>

                <!-- Jam Selesai -->
                <div>
                    <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" id="jam_selesai" name="jam_selesai"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        value="<?= old('jam_selesai', isset($promo['jam_selesai']) ? substr($promo['jam_selesai'], 0, 5) : '23:59') ?>"
                        required>
                </div>
            </div>

            <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Contoh:</strong> Jam 10:00 - 14:00 berarti promo hanya berlaku dari jam 10 pagi sampai jam 2
                    siang
                </p>
            </div>
        </div>

        <!-- Hari Berlaku ⭐ CRITICAL -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar-week text-blue-600 mr-2"></i>
                Hari Berlaku <span class="text-red-500">*</span>
            </h3>

            <?php
            $hariBerlaku = old('hari_berlaku', isset($promo['hari_berlaku']) ? explode(',', $promo['hari_berlaku']) : ['1', '2', '3', '4', '5', '6', '7']);
            $days = [
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu'
            ];
            ?>

            <div class="grid grid-cols-2 md:grid-cols-7 gap-3">
                <?php foreach ($days as $num => $name): ?>
                    <label class="relative flex items-center justify-center cursor-pointer">
                        <input type="checkbox" name="hari_berlaku[]" value="<?= $num ?>" class="peer sr-only"
                            <?= in_array($num, $hariBerlaku) ? 'checked' : '' ?>>
                        <div
                            class="w-full px-4 py-3 text-center border-2 border-gray-300 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 font-semibold transition">
                            <?= $name ?>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="mt-4 flex space-x-2">
                <button type="button" onclick="selectAllDays()"
                    class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm hover:bg-blue-200 transition">
                    <i class="fas fa-check-double mr-2"></i>Pilih Semua
                </button>
                <button type="button" onclick="selectWeekdays()"
                    class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm hover:bg-green-200 transition">
                    <i class="fas fa-briefcase mr-2"></i>Senin-Jumat
                </button>
                <button type="button" onclick="selectWeekend()"
                    class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg text-sm hover:bg-purple-200 transition">
                    <i class="fas fa-umbrella-beach mr-2"></i>Weekend
                </button>
                <button type="button" onclick="clearAllDays()"
                    class="px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm hover:bg-red-200 transition">
                    <i class="fas fa-times mr-2"></i>Bersihkan
                </button>
            </div>
        </div>

        <!-- Outlet -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-store text-blue-600 mr-2"></i>
                Outlet
            </h3>

            <div>
                <label for="outlet_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Berlaku di Outlet
                </label>
                <select id="outlet_id" name="outlet_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Outlet</option>
                    <?php foreach ($outlets as $outlet): ?>
                        <option value="<?= $outlet['id'] ?>"
                            <?= old('outlet_id', $promo['outlet_id'] ?? '') == $outlet['id'] ? 'selected' : '' ?>>
                            <?= esc($outlet['KdStore']) ?> - <?= esc($outlet['nama_outlet']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="mt-2 text-sm text-gray-500">
                    Kosongkan jika promo berlaku untuk semua outlet
                </p>
            </div>
        </div>

        <!-- Status -->
        <div class="mb-6">
            <label for="Status" class="block text-sm font-medium text-gray-700 mb-2">
                Status
            </label>
            <select id="Status" name="Status"
                class="w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="1" <?= old('Status', $promo['Status'] ?? '1') == '1' ? 'selected' : '' ?>>Aktif</option>
                <option value="0" <?= old('Status', $promo['Status'] ?? '1') == '0' ? 'selected' : '' ?>>Nonaktif
                </option>
            </select>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
            <div class="flex items-start">
                <i class="fas fa-lightbulb text-blue-600 text-xl mr-3 mt-0.5"></i>
                <div>
                    <p class="font-semibold text-blue-900 mb-2">Catatan Penting:</p>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                            <span>Promo hanya berlaku pada <strong>tanggal, jam, dan hari</strong> yang dipilih</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                            <span>Setelah menyimpan, Anda dapat menambahkan <strong>produk yang dapat
                                    promo</strong></span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                            <span>Promo akan otomatis diterapkan saat transaksi di POS jika kondisi terpenuhi</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center space-x-3">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg inline-flex items-center transition">
                <i class="fas fa-save mr-2"></i>
                <?= isset($promo) ? 'Update Promo' : 'Simpan & Lanjut ke Item' ?>
            </button>
            <a href="<?= base_url('admin/promo') ?>"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg inline-flex items-center transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Auto uppercase Kode Promo
        $('#NoTrans').on('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Validate date range
        $('#TglAkhir').on('change', function() {
            const tglAwal = new Date($('#TglAwal').val());
            const tglAkhir = new Date($(this).val());

            if (tglAkhir < tglAwal) {
                alert('Tanggal Selesai harus lebih besar dari Tanggal Mulai!');
                $(this).val('');
            }
        });

        // Form validation
        $('#promoForm').on('submit', function(e) {
            // Check if at least one day is selected
            const checkedDays = $('input[name="hari_berlaku[]"]:checked').length;

            if (checkedDays === 0) {
                e.preventDefault();
                alert('Pilih minimal 1 hari berlaku!');
                return false;
            }
        });
    });

    // Day selector functions
    function selectAllDays() {
        $('input[name="hari_berlaku[]"]').prop('checked', true);
    }

    function selectWeekdays() {
        $('input[name="hari_berlaku[]"]').prop('checked', false);
        $('input[name="hari_berlaku[]"][value="1"]').prop('checked', true);
        $('input[name="hari_berlaku[]"][value="2"]').prop('checked', true);
        $('input[name="hari_berlaku[]"][value="3"]').prop('checked', true);
        $('input[name="hari_berlaku[]"][value="4"]').prop('checked', true);
        $('input[name="hari_berlaku[]"][value="5"]').prop('checked', true);
    }

    function selectWeekend() {
        $('input[name="hari_berlaku[]"]').prop('checked', false);
        $('input[name="hari_berlaku[]"][value="6"]').prop('checked', true);
        $('input[name="hari_berlaku[]"][value="7"]').prop('checked', true);
    }

    function clearAllDays() {
        $('input[name="hari_berlaku[]"]').prop('checked', false);
    }
</script>
<?= $this->endSection() ?>