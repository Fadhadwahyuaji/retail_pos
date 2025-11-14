<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\TransaksiModel;
use App\Models\MasterBarangModel;
use App\Models\PromoModel;
use App\Models\OutletModel;

class POSController extends BaseController
{
    protected $transaksiModel;
    protected $barangModel;
    protected $promoModel;
    protected $outletModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->barangModel = new BarangModel();
        $this->promoModel = new PromoModel();
        $this->outletModel = new OutletModel();

        // TODO: Add authentication middleware
        // Check if user logged in and has kasir/admin role
    }

    /**
     * POS Main Interface
     */
    public function index()
    {
        // WAJIB: Pastikan user sudah login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Get user data from session (set saat login)
        $userOutletId = session()->get('outlet_id');
        $userKdStore = session()->get('kdstore');
        $userId = session()->get('user_id');
        $kasir = session()->get('nama');

        // Get outlet info 
        $outlet = $this->outletModel->find($userOutletId);

        if (!$outlet) {
            session()->setFlashdata('error', 'Outlet tidak ditemukan');
            return redirect()->to('/logout');
        }

        // Generate NoKassa dari user_id atau buat mapping
        // Format: 001, 002, 003 (3 digit)
        $noKassa = str_pad($userId, 3, '0', STR_PAD_LEFT);

        // ATAU jika ada field nokassa di table users:
        // $noKassa = session()->get('nokassa');

        $data = [
            'title' => 'POS - Point of Sales',
            'kdStore' => $outlet['KdStore'],
            'outlet_name' => $outlet['nama_outlet'],
            'kasir' => $kasir,
            'noKassa' => $noKassa,
            'user_id' => $userId
        ];

        return view('kasir/pos/index', $data);
    }

    /**
     * Search product by barcode or code (AJAX)
     */
    public function searchProduct()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $keyword = $this->request->getPost('keyword');

        if (empty($keyword)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Keyword tidak boleh kosong'
            ]);
        }

        // Search by PCode, Barcode1, Barcode2, Barcode3
        $product = $this->barangModel->findProductByKeyword($keyword);

        if (!$product) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
        }

        // Cek promo untuk produk ini
        $kdStore = session()->get('kdstore');
        $datetime = date('Y-m-d H:i:s');

        $promoResult = $this->promoModel->calculateDiscount(
            $product['PCode'],
            $product['Harga1c'],
            $datetime,
            $kdStore
        );

        $product['has_promo'] = $promoResult['has_promo'];
        $product['promo_name'] = $promoResult['promo_name'] ?? null;
        $product['promo_code'] = $promoResult['promo_code'] ?? null;
        $product['discount_amount'] = $promoResult['discount_amount'] ?? 0;
        $product['final_price'] = $promoResult['final_price'] ?? $product['Harga1c'];

        return $this->response->setJSON([
            'success' => true,
            'product' => $product
        ]);
    }

    /**
     * Get product list for manual selection (AJAX)
     */
    public function getProductList()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $search = $this->request->getGet('search') ?? '';
        $products = $this->barangModel->searchProducts($search, 50);

        return $this->response->setJSON([
            'success' => true,
            'products' => $products
        ]);
    }

    /**
     * Calculate cart totals (AJAX)
     */
    public function calculateCart()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $items = $this->request->getPost('items'); // Array of cart items

        if (empty($items)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cart kosong'
            ]);
        }

        $totalQty = 0;
        $subtotal = 0;
        $totalDiscount = 0;

        foreach ($items as $item) {
            $qty = $item['qty'];
            $price = $item['price'];
            $discount = $item['discount'] ?? 0;

            $totalQty += $qty;
            $subtotal += ($price * $qty);
            $totalDiscount += ($discount * $qty);
        }

        $grandTotal = $subtotal - $totalDiscount;

        return $this->response->setJSON([
            'success' => true,
            'total_qty' => $totalQty,
            'subtotal' => $subtotal,
            'total_discount' => $totalDiscount,
            'grand_total' => $grandTotal
        ]);
    }

    /**
     * Save transaction
     */
    public function saveTransaction()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        // VALIDASI: Pastikan user login
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Session expired, silakan login kembali'
            ]);
        }

        // AMBIL DARI SESSION (tidak dari POST!)
        $kdStore = session()->get('kdstore');
        $kasir = session()->get('nama');
        $userId = session()->get('user_id');
        $noKassa = str_pad($kdStore, 3, '0', STR_PAD_LEFT);

        // Data dari client
        $items = $this->request->getPost('items');
        $payment = $this->request->getPost('payment');

        // Log untuk debugging
        log_message('info', "=== SAVE TRANSACTION ===");
        log_message('info', "User ID: $userId, NoKassa: $noKassa, KdStore: $kdStore, Kasir: $kasir");

        // Validation
        if (empty($items)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cart kosong, tidak ada item yang dibeli'
            ]);
        }

        if (empty($payment['total_bayar']) || $payment['total_bayar'] < $payment['grand_total']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pembayaran kurang dari total belanja'
            ]);
        }

        // Generate NoStruk
        $noStruk = $this->transaksiModel->generateNoStruk($kdStore);

        log_message('info', "Generated NoStruk: $noStruk for NoKassa: $noKassa");

        // Prepare header data
        $tanggal = date('Y-m-d');
        $waktu = date('H:i:s');

        $headerData = [
            'NoKassa' => $noKassa,  // Dari session user!
            'Gudang' => $kdStore,
            'NoStruk' => $noStruk,
            'Tanggal' => $tanggal,
            'Waktu' => $waktu,
            'Kasir' => $kasir,
            'KdStore' => $kdStore,
            'TotalItem' => count($items),
            'TotalNilaiPem' => $payment['subtotal'],
            'TotalNilai' => $payment['grand_total'],
            'TotalBayar' => $payment['total_bayar'],
            'Kembali' => $payment['kembalian'],
            'Discount' => $payment['total_discount'],
            'Tunai' => $payment['tunai'] ?? 0,
            'KKredit' => $payment['kkredit'] ?? 0,
            'KDebit' => $payment['kdebit'] ?? 0,
            'GoPay' => $payment['gopay'] ?? 0,
            'Voucher' => $payment['voucher'] ?? 0,
            'Status' => 'T'
        ];

        // Prepare details data
        $detailsData = [];
        foreach ($items as $item) {
            $detailsData[] = [
                'NoKassa' => $noKassa,  // Dari session user!
                'Gudang' => $kdStore,
                'NoStruk' => $noStruk,
                'Tanggal' => $tanggal,
                'Waktu' => $waktu,
                'Kasir' => $kasir,
                'KdStore' => $kdStore,
                'PCode' => $item['pcode'],
                'Qty' => $item['qty'],
                'Harga' => $item['price'],
                'Ketentuan1' => $item['promo_code'] ?? null,
                'Disc1' => $item['discount'] ?? 0,
                'Jenis1' => !empty($item['discount']) ? 'R' : null,
                'Netto' => ($item['price'] - ($item['discount'] ?? 0)) * $item['qty'],
                'Hpp' => $item['hpp'] ?? 0,
                'Status' => 'T'
            ];
        }

        // Save transaction
        $success = $this->transaksiModel->saveTransaction($headerData, $detailsData);

        if ($success) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'no_struk' => $noStruk,
                'no_kassa' => $noKassa
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi'
            ]);
        }
    }


    /**
     * Print struk (view)
     */
    public function printStruk($noKassa, $noStruk)
    {
        try {
            // Debug: log parameters
            log_message('debug', "Print Struk - NoKassa: $noKassa, NoStruk: $noStruk");

            $transaction = $this->transaksiModel->getTransactionDetails($noKassa, $noStruk);

            if (!$transaction) {
                log_message('error', "Transaction not found - NoKassa: $noKassa, NoStruk: $noStruk");
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan');
            }

            // Get outlet info
            $outlet = $this->outletModel->getByKdStore($transaction['header']['KdStore']);

            // Debug: log outlet
            log_message('debug', 'Outlet info: ' . json_encode($outlet));

            $data = [
                'transaction' => $transaction,
                'outlet' => $outlet
            ];

            return view('kasir/pos/print_struk', $data);
        } catch (\Exception $e) {
            log_message('error', 'Print Struk Error: ' . $e->getMessage());
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Error: ' . $e->getMessage());
        }
    }

    /**
     * Get transaction history today (AJAX)
     */
    public function getTransactionHistory()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $kdStore = $this->request->getGet('kdStore');
        $date = date('Y-m-d');

        $transactions = $this->transaksiModel->getTransactionsByOutlet($kdStore, $date, $date);

        return $this->response->setJSON([
            'success' => true,
            'transactions' => $transactions
        ]);
    }

    public function togglePromo()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $pcode = $this->request->getPost('pcode');
        $usePromo = $this->request->getPost('use_promo');

        // UBAH: Ambil dari session
        $kdStore = session()->get('kdstore');

        if (empty($pcode)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Product code tidak valid'
            ]);
        }

        // Get product info
        $product = $this->barangModel->findProductByKeyword($pcode);
        if (!$product) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
        }

        $promoResult = ['has_promo' => false, 'discount_amount' => 0];

        if ($usePromo) {
            $datetime = date('Y-m-d H:i:s');
            $promoResult = $this->promoModel->calculateDiscount(
                $product['PCode'],
                $product['Harga1c'],
                $datetime,
                $kdStore
            );
        }

        return $this->response->setJSON([
            'success' => true,
            'has_promo' => $promoResult['has_promo'],
            'promo_name' => $promoResult['promo_name'] ?? null,
            'promo_code' => $promoResult['promo_code'] ?? null,
            'discount_amount' => $promoResult['discount_amount'] ?? 0,
            'original_price' => $product['Harga1c']
        ]);
    }

    /**
     * Void/Cancel transaction
     */
    public function voidTransaction()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $noKassa = $this->request->getPost('noKassa');
        $noStruk = $this->request->getPost('noStruk');

        // TODO: Add authorization check (only manager can void)

        $success = $this->transaksiModel->cancelTransaction($noKassa, $noStruk);

        if ($success) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Transaksi berhasil dibatalkan'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal membatalkan transaksi'
            ]);
        }
    }
}
