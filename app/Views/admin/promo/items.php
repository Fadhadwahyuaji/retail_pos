<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Kelola Item Promo</h1>
            <p class="text-gray-600 mt-1">Pilih produk dan atur diskon untuk promo: <?= esc($promo['Ketentuan']) ?></p>
        </div>
        <nav class="text-sm text-gray-600">
            <!-- Breadcrumb -->
        </nav>
    </div>
</div>

<!-- Promo Info Card -->
<div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg shadow-lg p-6 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold mb-2"><?= esc($promo['Ketentuan']) ?></h2>
            <div class="flex items-center space-x-4 text-sm">
                <span>Kode: <?= esc($promo['NoTrans']) ?></span>
                <span>Periode: <?= date('d M Y', strtotime($promo['TglAwal'])) ?> -
                    <?= date('d M Y', strtotime($promo['TglAkhir'])) ?></span>
            </div>
        </div>
        <div class="text-right">
            <p class="text-3xl font-bold"><?= count($items) ?></p>
            <p class="text-sm opacity-90">Produk</p>
        </div>
    </div>
</div>

<!-- Alert Messages -->
<div id="alertContainer"></div>

<!-- Main Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Left: Add Product -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-plus text-green-600 mr-2"></i>
                Tambah Produk ke Promo
            </h3>
        </div>

        <div class="p-6">
            <form id="addItemForm">
                <!-- Hidden field untuk NoTrans -->
                <input type="hidden" name="NoTrans" value="<?= esc($promo['NoTrans']) ?>">

                <!-- Product Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Produk <span class="text-red-500">*</span>
                    </label>
                    <select id="productSelect" name="PCode"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach ($availableProducts as $product): ?>
                            <option value="<?= esc($product['PCode']) ?>" data-nama="<?= esc($product['NamaLengkap']) ?>"
                                data-harga="<?= $product['Harga1c'] ?>">
                                <?= esc($product['PCode']) ?> - <?= esc($product['NamaLengkap']) ?>
                                (Rp <?= number_format($product['Harga1c'], 0, ',', '.') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Product Info Preview -->
                <div id="productInfo" class="mb-4 p-4 bg-gray-50 rounded-lg hidden">
                    <h4 class="font-semibold text-gray-800">Informasi Produk</h4>
                    <p class="text-sm text-gray-600">Kode: <span id="productCode"></span></p>
                    <p class="text-sm text-gray-600">Harga: <span id="productPrice"></span></p>
                </div>

                <!-- Discount Type -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Diskon <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <label
                            class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50">
                            <input type="radio" name="Jenis" value="P" class="mr-3" required>
                            <div>
                                <div class="font-semibold text-gray-800">Persen (%)</div>
                                <div class="text-sm text-gray-600">Diskon dalam persen</div>
                            </div>
                        </label>
                        <label
                            class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50">
                            <input type="radio" name="Jenis" value="R" class="mr-3" required>
                            <div>
                                <div class="font-semibold text-gray-800">Rupiah (Rp)</div>
                                <div class="text-sm text-gray-600">Diskon dalam rupiah</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Discount Value -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nilai Diskon <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="discountValue" name="Nilai"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Masukkan nilai diskon" min="0" step="0.01" required>
                    <p class="mt-1 text-xs text-gray-500" id="discountHint">
                        Untuk persen: maksimal 100. Untuk rupiah: maksimal harga produk
                    </p>
                </div>

                <!-- Discount Preview -->
                <div id="discountPreview" class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg hidden">
                    <h4 class="font-semibold text-blue-800 mb-2">Preview Diskon</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Harga Normal:</span>
                            <span id="previewOriginal" class="font-semibold"></span>
                        </div>
                        <div class="flex justify-between text-red-600">
                            <span>Diskon:</span>
                            <span id="previewDiscount" class="font-semibold"></span>
                        </div>
                        <div class="flex justify-between text-green-600 border-t border-blue-200 pt-2">
                            <span class="font-semibold">Harga Akhir:</span>
                            <span id="previewFinal" class="font-bold text-lg"></span>
                        </div>
                        <div class="text-center">
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                Hemat <span id="previewSave"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-plus mr-2"></i>Tambah ke Promo
                </button>
            </form>
        </div>
    </div>

    <!-- Right: Product List -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-list text-blue-600 mr-2"></i>
                Produk dalam Promo (<?= count($items) ?>)
            </h3>
        </div>

        <div class="p-6" style="max-height: 600px; overflow-y: auto;">
            <?php if (empty($items)): ?>
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Belum ada produk dalam promo</p>
                    <p class="text-sm text-gray-400 mt-2">Tambahkan produk menggunakan form di sebelah kiri</p>
                </div>
            <?php else: ?>
                <div id="itemList" class="space-y-3">
                    <?php foreach ($items as $item):
                        $discountAmount = 0;
                        if ($item['Jenis'] == 'P') {
                            $discountAmount = ($item['Harga1c'] * $item['Nilai']) / 100;
                        } else {
                            $discountAmount = $item['Nilai'];
                        }
                        $finalPrice = $item['Harga1c'] - $discountAmount;
                    ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition"
                            data-pcode="<?= esc($item['PCode']) ?>">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800"><?= esc($item['NamaLengkap']) ?></h4>
                                    <p class="text-sm text-gray-600 font-mono"><?= esc($item['PCode']) ?></p>
                                    <div class="mt-2 space-y-1">
                                        <div class="flex justify-between text-sm">
                                            <span>Harga Normal:</span>
                                            <span class="line-through text-gray-500">Rp
                                                <?= number_format($item['Harga1c'], 0, ',', '.') ?></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span>Diskon:</span>
                                            <span class="text-red-600">
                                                <?php if ($item['Jenis'] == 'P'): ?>
                                                    <?= $item['Nilai'] ?>% (Rp <?= number_format($discountAmount, 0, ',', '.') ?>)
                                                <?php else: ?>
                                                    Rp <?= number_format($item['Nilai'], 0, ',', '.') ?>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="flex justify-between font-semibold text-green-600">
                                            <span>Harga Promo:</span>
                                            <span>Rp <?= number_format($finalPrice, 0, ',', '.') ?></span>
                                        </div>
                                    </div>
                                </div>
                                <button
                                    onclick="removeItem('<?= esc($promo['NoTrans']) ?>', '<?= esc($item['PCode']) ?>', '<?= esc($item['NamaLengkap']) ?>')"
                                    class="ml-4 text-red-600 hover:text-red-800 p-2">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="mt-6 flex justify-between items-center">
    <a href="<?= base_url('admin/promo') ?>"
        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg inline-flex items-center transition">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Daftar Promo
    </a>

    <?php if (count($items) > 0): ?>
        <a href="<?= base_url('admin/promo/detail/' . $promo['NoTrans']) ?>"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg inline-flex items-center transition">
            <i class="fas fa-eye mr-2"></i>
            Lihat Detail Promo
        </a>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Tunggu sampai DOM dan jQuery ready
    $(document).ready(function() {
        console.log('jQuery loaded:', typeof $ !== 'undefined');
        console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));

        // Product selection change
        $('#productSelect').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const pcode = selectedOption.val();
            const nama = selectedOption.data('nama');
            const harga = selectedOption.data('harga');

            console.log('Product selected:', {
                pcode,
                nama,
                harga
            });

            if (pcode) {
                $('#productInfo').removeClass('hidden');
                $('#productCode').text(pcode);
                $('#productPrice').text('Rp ' + parseInt(harga).toLocaleString('id-ID'));
                calculatePreview();
            } else {
                $('#productInfo').addClass('hidden');
                $('#discountPreview').addClass('hidden');
            }
        });

        // Discount type change
        $('input[name="Jenis"]').on('change', function() {
            const jenis = $(this).val();
            if (jenis == 'P') {
                $('#discountHint').text('Untuk persen: maksimal 100');
            } else {
                $('#discountHint').text('Untuk rupiah: maksimal harga produk');
            }
            calculatePreview();
        });

        // Discount value change
        $('#discountValue').on('input', calculatePreview);

        // Calculate preview
        function calculatePreview() {
            const pcode = $('#productSelect').val();
            const harga = parseFloat($('#productSelect').find('option:selected').data('harga')) || 0;
            const jenis = $('input[name="Jenis"]:checked').val();
            const nilai = parseFloat($('#discountValue').val()) || 0;

            console.log('Calculate preview:', {
                pcode,
                harga,
                jenis,
                nilai
            });

            if (!pcode || nilai <= 0) {
                $('#discountPreview').addClass('hidden');
                return;
            }

            let discountAmount = 0;
            if (jenis == 'P') {
                if (nilai > 100) {
                    alert('Diskon persen maksimal 100%');
                    $('#discountValue').val(100);
                    return;
                }
                discountAmount = (harga * nilai) / 100;
            } else {
                if (nilai > harga) {
                    alert('Diskon rupiah tidak boleh lebih dari harga produk');
                    $('#discountValue').val(harga);
                    return;
                }
                discountAmount = nilai;
            }

            const finalPrice = harga - discountAmount;
            const savePercent = ((discountAmount / harga) * 100).toFixed(1);

            $('#previewOriginal').text('Rp ' + parseInt(harga).toLocaleString('id-ID'));
            $('#previewDiscount').text('- Rp ' + parseInt(discountAmount).toLocaleString('id-ID'));
            $('#previewFinal').text('Rp ' + parseInt(finalPrice).toLocaleString('id-ID'));
            $('#previewSave').text(savePercent + '%');

            $('#discountPreview').removeClass('hidden');
        }

        // Submit form
        $('#addItemForm').on('submit', function(e) {
            e.preventDefault();
            console.log('Form submitted');

            // Validasi form
            const pcode = $('#productSelect').val();
            const jenis = $('input[name="Jenis"]:checked').val();
            const nilai = $('#discountValue').val();

            console.log('Form data:', {
                pcode,
                jenis,
                nilai
            });

            if (!pcode) {
                showAlert('error', 'Pilih produk terlebih dahulu');
                return;
            }

            if (!jenis) {
                showAlert('error', 'Pilih jenis diskon');
                return;
            }

            if (!nilai || nilai <= 0) {
                showAlert('error', 'Masukkan nilai diskon yang valid');
                return;
            }

            // Show loading
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Menambahkan...').prop('disabled',
                true);

            const formData = {
                NoTrans: $('input[name="NoTrans"]').val(),
                PCode: pcode,
                Jenis: jenis,
                Nilai: nilai,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            };

            console.log('Sending AJAX data:', formData);

            $.ajax({
                url: '<?= base_url('admin/promo/add-item') ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    console.log('AJAX Success Response:', response);

                    if (response && response.success) {
                        showAlert('success', response.message);
                        // Reset form
                        $('#addItemForm')[0].reset();
                        $('#productInfo').addClass('hidden');
                        $('#discountPreview').addClass('hidden');
                        // Reload page after 1.5 seconds
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert('error', response.message || 'Gagal menambahkan item');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {
                        xhr,
                        status,
                        error
                    });
                    showAlert('error', 'Terjadi kesalahan pada server');
                },
                complete: function() {
                    // Reset button
                    submitBtn.html(originalText).prop('disabled', false);
                }
            });
        });
    });

    // Remove item
    function removeItem(noTrans, pcode, nama) {
        if (!confirm(`Yakin ingin menghapus "${nama}" dari promo?`)) {
            return;
        }

        $.ajax({
            url: '<?= base_url('admin/promo/remove-item') ?>',
            type: 'POST',
            data: {
                NoTrans: noTrans,
                PCode: pcode,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $(`[data-pcode="${pcode}"]`).fadeOut(300, function() {
                        $(this).remove();
                        if ($('#itemList .border').length === 0) {
                            location.reload();
                        }
                    });
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                showAlert('error', 'Terjadi kesalahan saat menghapus item');
            }
        });
    }

    // Show alert
    function showAlert(type, message) {
        const bgColor = type === 'success' ? 'bg-green-100 border-green-500 text-green-700' :
            'bg-red-100 border-red-500 text-red-700';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

        const alert = `
        <div class="${bgColor} border-l-4 p-4 mb-6 rounded-lg" role="alert" id="alertMessage">
            <div class="flex items-center">
                <i class="fas ${icon} text-xl mr-3"></i>
                <p class="font-medium">${message}</p>
                <button onclick="$('#alertMessage').fadeOut()" class="ml-auto text-xl hover:opacity-75">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;

        $('#alertContainer').html(alert);

        // Auto hide after 5 seconds
        setTimeout(() => {
            $('#alertMessage').fadeOut();
        }, 5000);
    }
</script>
<?= $this->endSection() ?>