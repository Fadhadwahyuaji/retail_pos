<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- Top Bar -->
    <div class="bg-white shadow-lg border-b border-gray-200">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-3 rounded-xl">
                        <i class="fas fa-cash-register text-2xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Point of Sales</h1>
                        <p class="text-sm text-gray-600">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                                Outlet: <?= $kdStore ?> - <?= $outlet_name ?>
                            </span>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Kasir: <?= $kasir ?>
                            </span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    <div
                        class="text-center bg-gradient-to-r from-orange-50 to-red-50 px-4 py-2 rounded-lg border border-orange-200">
                        <p class="text-xs text-gray-600 font-medium">No. Struk Berikutnya</p>
                        <p id="nextStrukNo" class="text-lg font-bold text-orange-600 font-mono">Loading...</p>
                    </div>
                    <div
                        class="text-center bg-gradient-to-r from-blue-50 to-purple-50 px-4 py-2 rounded-lg border border-blue-200">
                        <p class="text-xs text-gray-600 font-medium" id="currentDate">Loading...</p>
                        <p class="text-lg font-bold text-blue-600" id="currentTime">Loading...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex gap-6 p-6 min-h-[calc(100vh-120px)]">

        <!-- Left Panel: Product Scanner & Cart -->
        <div class="flex-1 flex flex-col space-y-6">

            <!-- Scanner Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-barcode text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Scanner Produk</h3>
                </div>

                <div class="flex gap-3 mb-4">
                    <div class="flex-1">
                        <input type="text" id="scanInput"
                            class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono transition-all duration-200"
                            placeholder="Scan barcode atau ketik kode produk..." autofocus>
                    </div>
                    <button onclick="searchProduct()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg flex items-center gap-2"
                        title="Cari Produk">
                        <i class="fas fa-search text-lg"></i>
                        <span class="font-medium">Cari</span>
                    </button>
                    <button onclick="showProductList()"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg flex items-center gap-2"
                        title="Tampilkan Daftar Produk">
                        <i class="fas fa-list text-lg"></i>
                        <span class="font-medium">Katalog</span>
                    </button>
                </div>

                <!-- Promo Control -->
                <div
                    class="flex items-center justify-between bg-gradient-to-r from-yellow-50 to-orange-50 p-4 rounded-xl border border-yellow-200">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" id="autoPromo" checked
                            class="w-5 h-5 text-yellow-600 bg-white border-2 border-gray-300 rounded focus:ring-yellow-500 focus:ring-2">
                        <span class="text-sm font-medium text-gray-700">
                            <i class="fas fa-tag mr-2 text-yellow-600"></i>Auto Apply Promo
                        </span>
                    </label>
                    <button onclick="toggleAllPromos()" id="btnTogglePromo"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm">
                        <i class="fas fa-sync-alt mr-1"></i>Toggle All
                    </button>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-4 gap-4">
                <button onclick="clearCart()"
                    class="bg-red-500 hover:bg-red-600 text-white py-3 rounded-xl transition-all duration-200 font-semibold shadow-md hover:shadow-lg hover:scale-105">
                    <i class="fas fa-trash-alt block mb-1 text-lg"></i>
                    <span class="text-sm">Clear</span>
                </button>
                <!-- <button onclick="showHistory()"
                    class="bg-purple-500 hover:bg-purple-600 text-white py-3 rounded-xl transition-all duration-200 font-semibold shadow-md hover:shadow-lg hover:scale-105">
                    <i class="fas fa-history block mb-1 text-lg"></i>
                    <span class="text-sm">History</span>
                </button>
                <button onclick="holdTransaction()"
                    class="bg-amber-500 hover:bg-amber-600 text-white py-3 rounded-xl transition-all duration-200 font-semibold shadow-md hover:shadow-lg hover:scale-105">
                    <i class="fas fa-pause-circle block mb-1 text-lg"></i>
                    <span class="text-sm">Hold</span>
                </button>
                <button onclick="loadSettings()"
                    class="bg-slate-500 hover:bg-slate-600 text-white py-3 rounded-xl transition-all duration-200 font-semibold shadow-md hover:shadow-lg hover:scale-105">
                    <i class="fas fa-cog block mb-1 text-lg"></i>
                    <span class="text-sm">Setting</span> -->
                <!-- </button> -->
            </div>

            <!-- Cart Items -->
            <div class="flex-1 bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-shopping-cart mr-3 text-blue-600"></i>
                            Keranjang Belanja
                        </h3>
                        <span id="cartCount"
                            class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-bold">0</span>
                    </div>
                </div>

                <div id="cartItems" class="flex-1 overflow-y-auto p-6 max-h-96">
                    <div id="emptyCart" class="flex flex-col items-center justify-center h-64 text-gray-400">
                        <div class="bg-gray-100 p-6 rounded-full mb-4">
                            <i class="fas fa-shopping-cart text-4xl"></i>
                        </div>
                        <p class="text-xl font-medium mb-2">Keranjang Kosong</p>
                        <p class="text-sm text-center">Scan produk untuk memulai transaksi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Summary & Payment -->
        <div class="w-96 flex flex-col space-y-6">

            <!-- Summary Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center mb-6">
                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-calculator text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Ringkasan Belanja</h3>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Total Item:</span>
                        <span id="summaryQty" class="font-bold text-gray-900 text-lg">0</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Subtotal:</span>
                        <span id="summarySubtotal" class="font-bold text-gray-900 text-lg">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 text-red-600">
                        <span class="flex items-center font-medium">
                            <i class="fas fa-tag mr-2"></i>Diskon Promo:
                        </span>
                        <span id="summaryDiscount" class="font-bold text-lg">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center py-4 border-t-2 border-gray-300">
                        <span class="font-bold text-gray-800 text-xl">TOTAL:</span>
                        <span id="summaryGrandTotal" class="font-bold text-green-600 text-2xl">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- Payment Card -->
            <div class="flex-1 bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center mb-6">
                    <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-credit-card text-indigo-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Pembayaran</h3>
                </div>

                <!-- Payment Method Tabs -->
                <div class="grid grid-cols-2 gap-2 mb-6">
                    <button onclick="selectPaymentMethod('tunai')" id="btnTunai"
                        class="py-3 px-4 rounded-xl font-semibold transition-all duration-200 bg-blue-600 text-white shadow-md">
                        <i class="fas fa-money-bill-wave mr-2"></i>Tunai
                    </button>
                    <button onclick="selectPaymentMethod('debit')" id="btnDebit"
                        class="py-3 px-4 rounded-xl font-semibold transition-all duration-200 bg-gray-100 text-gray-700 hover:bg-gray-200">
                        <i class="fas fa-credit-card mr-2"></i>Debit
                    </button>
                    <button onclick="selectPaymentMethod('kredit')" id="btnKredit"
                        class="py-3 px-4 rounded-xl font-semibold transition-all duration-200 bg-gray-100 text-gray-700 hover:bg-gray-200">
                        <i class="fas fa-credit-card mr-2"></i>Kredit
                    </button>
                    <button onclick="selectPaymentMethod('gopay')" id="btnGopay"
                        class="py-3 px-4 rounded-xl font-semibold transition-all duration-200 bg-gray-100 text-gray-700 hover:bg-gray-200">
                        <i class="fab fa-google-pay mr-2"></i>GoPay
                    </button>
                </div>

                <!-- Payment Input -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Jumlah Bayar:</label>
                    <input type="text" id="paymentAmount"
                        class="w-full px-4 py-4 text-2xl font-bold border-2 border-green-500 rounded-xl focus:ring-2 focus:ring-green-500 text-right bg-green-50"
                        placeholder="0" oninput="calculateChange()">
                </div>

                <!-- Quick Amount Buttons -->
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <button onclick="quickAmount(10000)"
                        class="bg-gray-50 hover:bg-gray-100 border border-gray-200 py-3 rounded-lg font-semibold text-sm transition-all duration-200">
                        10rb
                    </button>
                    <button onclick="quickAmount(20000)"
                        class="bg-gray-50 hover:bg-gray-100 border border-gray-200 py-3 rounded-lg font-semibold text-sm transition-all duration-200">
                        20rb
                    </button>
                    <button onclick="quickAmount(50000)"
                        class="bg-gray-50 hover:bg-gray-100 border border-gray-200 py-3 rounded-lg font-semibold text-sm transition-all duration-200">
                        50rb
                    </button>
                    <button onclick="quickAmount(100000)"
                        class="bg-gray-50 hover:bg-gray-100 border border-gray-200 py-3 rounded-lg font-semibold text-sm transition-all duration-200">
                        100rb
                    </button>
                    <button onclick="quickAmountExact()"
                        class="bg-blue-50 hover:bg-blue-100 border border-blue-200 py-3 rounded-lg font-semibold text-sm transition-all duration-200 text-blue-700">
                        Uang Pas
                    </button>
                    <button onclick="clearPayment()"
                        class="bg-red-50 hover:bg-red-100 border border-red-200 py-3 rounded-lg font-semibold text-sm transition-all duration-200 text-red-700">
                        Clear
                    </button>
                </div>

                <!-- Change Display -->
                <div id="changeDisplay"
                    class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300 rounded-xl p-4 mb-6 hidden">
                    <p class="text-sm text-gray-600 mb-1 font-medium">Kembalian:</p>
                    <p id="changeAmount" class="text-3xl font-bold text-green-600">Rp 0</p>
                </div>

                <!-- Process Button -->
                <button onclick="processPayment()" id="btnProcess"
                    class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-4 rounded-xl text-xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                    disabled>
                    <i class="fas fa-check-circle mr-2"></i>PROSES PEMBAYARAN
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Product List Modal -->
<div id="productListModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[80vh] flex flex-col">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-boxes mr-3 text-blue-600"></i>Pilih Produk
            </h3>
            <button onclick="closeProductList()"
                class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100 transition-all duration-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4 border-b border-gray-100">
            <input type="text" id="productSearch"
                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Cari produk..." oninput="searchProductList()">
        </div>
        <div id="productListContent" class="flex-1 overflow-y-auto px-6 pb-6">
            <!-- Product list will be loaded here -->
        </div>
    </div>
</div>

<!-- Hidden Data -->
<input type="hidden" id="hdnKdStore" value="<?= $kdStore ?>">
<input type="hidden" id="hdnNoKassa" value="<?= $noKassa ?>">
<input type="hidden" id="hdnKasir" value="<?= $kasir ?>">

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Global variables
    let cart = [];
    let currentPaymentMethod = 'tunai';
    let grandTotal = 0;

    // Initialize
    $(document).ready(function() {
        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Auto-focus scan input
        $('#scanInput').focus();

        // Enter key on scan input
        $('#scanInput').on('keypress', function(e) {
            if (e.which === 13) {
                searchProduct();
            }
        });

        // Format payment amount input
        $('#paymentAmount').on('input', function() {
            let value = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(formatNumber(value));
        });
    });

    // Update date & time
    function updateDateTime() {
        const now = new Date();
        const dateOptions = {
            weekday: 'short',
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        };
        const timeOptions = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };

        $('#currentDate').text(now.toLocaleDateString('id-ID', dateOptions));
        $('#currentTime').text(now.toLocaleTimeString('id-ID', timeOptions));
    }

    // Search product
    function searchProduct() {
        const keyword = $('#scanInput').val().trim();

        if (!keyword) {
            showAlert('error', 'Masukkan kode produk atau barcode');
            return;
        }

        const kdStore = $('#hdnKdStore').val();
        const usePromo = $('#autoPromo').is(':checked');

        $.ajax({
            url: '<?= base_url('kasir/pos/search-product') ?>',
            type: 'POST',
            data: {
                keyword: keyword,
                kdStore: kdStore,
                use_promo: usePromo
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    addToCart(response.product);
                    $('#scanInput').val('').focus();

                    if (response.product.promo_available && !response.product.has_promo) {
                        showAlert('info',
                            `Produk ${response.product.NamaLengkap} memiliki promo yang tersedia`);
                    }
                } else {
                    showAlert('error', response.message);
                    $('#scanInput').select();
                }
            },
            error: function() {
                showAlert('error', 'Gagal mencari produk');
            }
        });
    }

    // Add to cart
    function addToCart(product) {
        // cek apakah produk sudah ada di keranjang
        const existingIndex = cart.findIndex(item => item.pcode === product.PCode);

        // jika sudah ada, tambahkan qty
        if (existingIndex !== -1) {
            cart[existingIndex].qty += 1;
        } else {
            // jika belum ada, tambahkan item baru
            cart.push({
                pcode: product.PCode,
                nama: product.NamaStruk || product.NamaLengkap,
                price: parseFloat(product.Harga1c),
                qty: 1,
                discount: parseFloat(product.discount_amount || 0),
                has_promo: product.has_promo || false,
                promo_name: product.promo_name || null,
                promo_code: product.promo_code || null,
                hpp: parseFloat(product.HargaBeli || 0)
            });
        }

        renderCart(); // Render ulang keranjang
        calculateTotals(); // Hitung ulang total

        if (product.has_promo) {
            showAlert('success', `PROMO AKTIF: ${product.promo_name}! Hemat Rp ${formatNumber(product.discount_amount)}`);
        }
    }

    // Render cart
    function renderCart() {
        const cartContainer = $('#cartItems');
        const emptyCart = $('#emptyCart');

        if (cart.length === 0) {
            emptyCart.show();
            cartContainer.find('.cart-item').remove();
            $('#cartCount').text('0');
            return;
        }

        emptyCart.hide();
        cartContainer.find('.cart-item').remove();

        cart.forEach((item, index) => {
            const subtotal = (item.price - item.discount) * item.qty;
            const hasPromoAvailable = item.promo_available || item.has_promo;

            const cartItem = `
            <div class="cart-item bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-4 mb-3 border border-gray-200 hover:shadow-md transition-all duration-200 hover:scale-102">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 text-lg">${item.nama}</h4>
                        <p class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full inline-block mt-1">${item.pcode}</p>
                        ${item.has_promo ? `<p class="text-xs text-green-600 font-semibold mt-2 bg-green-100 px-2 py-1 rounded-full inline-block"><i class="fas fa-tag mr-1"></i>${item.promo_name}</p>` : ''}
                        ${hasPromoAvailable && !item.has_promo ? `<p class="text-xs text-orange-600 mt-2 bg-orange-100 px-2 py-1 rounded-full inline-block"><i class="fas fa-info-circle mr-1"></i>Promo tersedia</p>` : ''}
                    </div>
                    <div class="flex items-center space-x-2">
                        ${hasPromoAvailable ? `
                        <button onclick="toggleItemPromo(${index})" 
                                class="text-xs ${item.has_promo ? 'bg-green-500 hover:bg-green-600' : 'bg-orange-500 hover:bg-orange-600'} text-white px-3 py-1 rounded-lg transition-all duration-200">
                            <i class="fas fa-tag mr-1"></i>${item.has_promo ? 'Hapus' : 'Pakai'}
                        </button>
                        ` : ''}
                        <button onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all duration-200">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <button onclick="decreaseQty(${index})" class="bg-red-500 hover:bg-red-600 text-white w-8 h-8 rounded-lg font-bold transition-all duration-200">-</button>
                        <input type="number" 
                               value="${item.qty}" 
                               onchange="updateQty(${index}, this.value)"
                               class="w-16 text-center border-2 border-gray-300 rounded-lg py-2 font-semibold focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               min="1">
                        <button onclick="increaseQty(${index})" class="bg-green-500 hover:bg-green-600 text-white w-8 h-8 rounded-lg font-bold transition-all duration-200">+</button>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">@ Rp ${formatNumber(item.price)}</p>
                        ${item.discount > 0 ? `<p class="text-xs text-red-600 font-medium">Disc: -Rp ${formatNumber(item.discount)}</p>` : ''}
                        <p class="text-xl font-bold text-green-600">Rp ${formatNumber(subtotal)}</p>
                    </div>
                </div>
            </div>
        `;

            cartContainer.append(cartItem);
        });

        $('#cartCount').text(cart.length);
    }

    // Toggle promo for specific item
    function toggleItemPromo(index) {
        const item = cart[index];
        const usePromo = !item.has_promo;
        const kdStore = $('#hdnKdStore').val();

        $.ajax({
            url: '<?= base_url('kasir/pos/toggle-promo') ?>',
            type: 'POST',
            data: {
                pcode: item.pcode,
                use_promo: usePromo,
                kdStore: kdStore
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    cart[index].has_promo = response.has_promo;
                    cart[index].promo_name = response.promo_name || null;
                    cart[index].promo_code = response.promo_code || null;
                    cart[index].discount = response.discount_amount || 0;
                    cart[index].price = response.original_price;

                    renderCart();
                    calculateTotals();

                    const action = response.has_promo ? 'diterapkan' : 'dihapus';
                    showAlert('success', `Promo ${action} untuk ${item.nama}`);
                } else {
                    showAlert('error', response.message);
                }
            }
        });
    }

    // Toggle all promos in cart
    function toggleAllPromos() {
        if (cart.length === 0) {
            showAlert('error', 'Keranjang kosong');
            return;
        }

        const hasAnyPromo = cart.some(item => item.has_promo);
        const usePromo = !hasAnyPromo;
        const kdStore = $('#hdnKdStore').val();

        let processed = 0;
        const total = cart.filter(item => item.promo_available || item.has_promo).length;

        if (total === 0) {
            showAlert('info', 'Tidak ada produk dengan promo');
            return;
        }

        cart.forEach((item, index) => {
            if (item.promo_available || item.has_promo) {
                $.ajax({
                    url: '<?= base_url('kasir/pos/toggle-promo') ?>',
                    type: 'POST',
                    data: {
                        pcode: item.pcode,
                        use_promo: usePromo,
                        kdStore: kdStore
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            cart[index].has_promo = response.has_promo;
                            cart[index].promo_name = response.promo_name || null;
                            cart[index].promo_code = response.promo_code || null;
                            cart[index].discount = response.discount_amount || 0;
                        }

                        processed++;
                        if (processed === total) {
                            renderCart();
                            calculateTotals();
                            const action = usePromo ? 'diterapkan' : 'dihapus';
                            showAlert('success', `Semua promo ${action}`);
                        }
                    }
                });
            }
        });
    }

    // Cart operations
    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
        calculateTotals();
    }

    function increaseQty(index) {
        cart[index].qty += 1;
        renderCart();
        calculateTotals();
    }

    function decreaseQty(index) {
        if (cart[index].qty > 1) {
            cart[index].qty -= 1;
            renderCart();
            calculateTotals();
        }
    }

    function updateQty(index, value) {
        const qty = parseInt(value);
        if (qty > 0) {
            cart[index].qty = qty;
            renderCart();
            calculateTotals();
        }
    }

    function clearCart() {
        if (cart.length === 0) return;

        if (confirm('Yakin ingin mengosongkan keranjang?')) {
            cart = [];
            renderCart();
            calculateTotals();
        }
    }

    // Calculate totals
    function calculateTotals() {
        let totalQty = 0;
        let subtotal = 0;
        let totalDiscount = 0;

        cart.forEach(item => {
            totalQty += item.qty;
            subtotal += (item.price * item.qty);
            totalDiscount += (item.discount * item.qty);
        });

        grandTotal = subtotal - totalDiscount; // Hitung grand total

        // Update summary display
        $('#summaryQty').text(totalQty);
        $('#summarySubtotal').text('Rp ' + formatNumber(subtotal));
        $('#summaryDiscount').text('Rp ' + formatNumber(totalDiscount));
        $('#summaryGrandTotal').text('Rp ' + formatNumber(grandTotal));

        $('#btnProcess').prop('disabled', cart.length === 0); // Enable/disable process button based on cart
        calculateChange(); // menghitung kembalian
    }

    // Payment methods
    function selectPaymentMethod(method) {
        currentPaymentMethod = method;

        $('.bg-blue-600').removeClass('bg-blue-600 text-white shadow-md').addClass('bg-gray-100 text-gray-700');
        $(`#btn${method.charAt(0).toUpperCase() + method.slice(1)}`).removeClass('bg-gray-100 text-gray-700').addClass(
            'bg-blue-600 text-white shadow-md');
    }

    function quickAmount(amount) {
        $('#paymentAmount').val(formatNumber(amount));
        calculateChange();
    }

    function quickAmountExact() {
        $('#paymentAmount').val(formatNumber(grandTotal));
        calculateChange();
    }

    function clearPayment() {
        $('#paymentAmount').val('');
        $('#changeDisplay').addClass('hidden');
    }

    // kalkulasi kembalian
    function calculateChange() {
        const paymentInput = $('#paymentAmount').val().replace(/[^0-9]/g, '');
        const payment = parseInt(paymentInput) || 0;

        if (payment >= grandTotal && grandTotal > 0) {
            const change = payment - grandTotal;
            $('#changeAmount').text('Rp ' + formatNumber(change));
            $('#changeDisplay').removeClass('hidden');
            $('#btnProcess').prop('disabled', false);
        } else {
            $('#changeDisplay').addClass('hidden');
            $('#btnProcess').prop('disabled', true);
        }
    }

    // Process payment
    function processPayment() {
        if (cart.length === 0) {
            showAlert('error', 'Keranjang kosong');
            return;
        }

        const paymentInput = $('#paymentAmount').val().replace(/[^0-9]/g, '');
        const payment = parseInt(paymentInput) || 0;

        if (payment < grandTotal) {
            showAlert('error', 'Pembayaran kurang dari total belanja');
            return;
        }

        const paymentData = {
            // kdStore: $('#hdnKdStore').val(),
            // noKassa: $('#hdnNoKassa').val(),
            // kasir: $('#hdnKasir').val(),
            items: cart,
            payment: {
                subtotal: cart.reduce((sum, item) => sum + (item.price * item.qty), 0),
                total_discount: cart.reduce((sum, item) => sum + (item.discount * item.qty), 0),
                grand_total: grandTotal,
                total_bayar: payment,
                kembalian: payment - grandTotal,
                tunai: currentPaymentMethod === 'tunai' ? payment : 0,
                kdebit: currentPaymentMethod === 'debit' ? payment : 0,
                kkredit: currentPaymentMethod === 'kredit' ? payment : 0,
                gopay: currentPaymentMethod === 'gopay' ? payment : 0
            }
        };

        $('#btnProcess').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');

        $.ajax({
            url: '<?= base_url('kasir/pos/save-transaction') ?>',
            type: 'POST',
            data: paymentData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Tampilkan notifikasi sukses 
                    showAlert('success',
                        `🎉 Transaksi berhasil disimpan!\nNo. Struk: ${response.no_struk}\nKembalian: Rp ${formatNumber(paymentData.payment.kembalian)}`
                    );

                    setTimeout(() => {
                        // Manual reset semua
                        cart = [];
                        currentPaymentMethod = 'tunai';
                        grandTotal = 0;

                        // Reset UI
                        renderCart();
                        calculateTotals();
                        clearPayment();

                        // Reset payment method buttons
                        $('.bg-blue-600').removeClass('bg-blue-600 text-white shadow-md').addClass(
                            'bg-gray-100 text-gray-700');
                        $('#btnTunai').removeClass('bg-gray-100 text-gray-700').addClass(
                            'bg-blue-600 text-white shadow-md');

                        // Reset button dan focus
                        $('#btnProcess').prop('disabled', true).html(
                            '<i class="fas fa-check-circle mr-2"></i>PROSES PEMBAYARAN');
                        $('#scanInput').focus();

                    }, 1500);

                } else {
                    showAlert('error', response.message);
                    $('#btnProcess').prop('disabled', false).html(
                        '<i class="fas fa-check-circle mr-2"></i>PROSES PEMBAYARAN');
                }
            },
            error: function() {
                showAlert('error', 'Gagal menyimpan transaksi');
                $('#btnProcess').prop('disabled', false).html(
                    '<i class="fas fa-check-circle mr-2"></i>PROSES PEMBAYARAN');
            }
        });
    }

    // Show product list modal
    function showProductList() {
        $('#productListModal').removeClass('hidden');
        loadProductList();
    }

    function closeProductList() {
        $('#productListModal').addClass('hidden');
    }

    function loadProductList(search = '') {
        $.ajax({
            url: '<?= base_url('kasir/pos/get-product-list') ?> ',
            type: 'GET',
            data: {
                search: search
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    renderProductList(response.products);
                }
            }
        });
    }

    function renderProductList(products) {
        const container = $('#productListContent');
        container.empty();

        if (products.length === 0) {
            container.html('<p class="text-center text-gray-500 py-8">Produk tidak ditemukan</p>');
            return;
        }

        products.forEach(product => {
            const item = `
            <div class="border border-gray-200 rounded-xl p-4 mb-3 hover:bg-blue-50 cursor-pointer transition-all duration-200 hover:shadow-md hover:border-blue-300"
                 onclick='selectProductFromList(${JSON.stringify(product)})'>
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="font-semibold text-gray-900 text-lg">${product.NamaLengkap}</h4>
                        <p class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full inline-block mt-1">${product.PCode}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-blue-600">Rp ${formatNumber(product.Harga1c)}</p>
                    </div>
                </div>
            </div>
        `;
            container.append(item);
        });
    }

    function searchProductList() {
        const search = $('#productSearch').val();
        loadProductList(search);
    }

    function selectProductFromList(product) {
        closeProductList();
        $('#scanInput').val(product.PCode);
        searchProduct();
    }

    // Utilities
    function formatNumber(num) {
        return parseInt(num).toLocaleString('id-ID');
    }

    // Update fungsi showAlert:
    function showAlert(type, message) {
        const bgColor = type === 'success' ? 'bg-green-500' :
            type === 'info' ? 'bg-blue-500' : 'bg-red-500';
        const icon = type === 'success' ? 'fas fa-check-circle' :
            type === 'info' ? 'fas fa-info-circle' : 'fas fa-exclamation-circle';

        // Handle multi-line messages
        const formattedMessage = message.replace(/\n/g, '<br>');

        const toast = `
    <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl z-50 animate-slide-in-right max-w-sm">
        <div class="flex items-start">
            <i class="${icon} mr-3 text-lg mt-1 flex-shrink-0"></i>
            <div class="font-semibold leading-relaxed">${formattedMessage}</div>
        </div>
    </div>
    `;

        $('body').append(toast);

        // Durasi lebih lama untuk success message
        const duration = type === 'success' ? 5000 : 3000;

        setTimeout(() => {
            $('.animate-slide-in-right').fadeOut(300, function() {
                $(this).remove();
            });
        }, duration);
    }

    // Placeholder functions
    function showHistory() {
        showAlert('info', 'Fitur History dalam pengembangan');
    }

    function holdTransaction() {
        showAlert('info', 'Fitur Hold dalam pengembangan');
    }

    function loadSettings() {
        showAlert('info', 'Fitur Settings dalam pengembangan');
    }
</script>

<style>
    @keyframes slide-in-right {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .animate-slide-in-right {
        animation: slide-in-right 0.3s ease-out;
    }

    .hover\:scale-102:hover {
        transform: scale(1.02);
    }

    .hover\:scale-105:hover {
        transform: scale(1.05);
    }
</style>

<?= $this->endSection() ?>