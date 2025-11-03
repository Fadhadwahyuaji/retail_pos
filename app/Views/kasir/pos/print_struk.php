<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Struk - <?= $transaction['header']['NoStruk'] ?></title>
    <style>
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 10mm;
            }

            .no-print {
                display: none;
            }
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            max-width: 80mm;
            margin: 0 auto;
            padding: 5mm;
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }

        .header p {
            margin: 2px 0;
            font-size: 11px;
        }

        .info-section {
            margin: 10px 0;
            font-size: 11px;
        }

        .info-section p {
            margin: 3px 0;
        }

        .items-table {
            width: 100%;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            margin: 10px 0;
            padding: 5px 0;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }

        .item-name {
            font-weight: bold;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-left: 10px;
        }

        .totals {
            margin: 10px 0;
            font-size: 12px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }

        .grand-total {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 5px 0;
            margin: 10px 0;
        }

        .payment-section {
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            border-top: 2px dashed #000;
            padding-top: 10px;
            font-size: 11px;
        }

        .promo-badge {
            background: #000;
            color: #fff;
            padding: 2px 5px;
            font-size: 10px;
            display: inline-block;
            margin-left: 5px;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .print-button:hover {
            background: #45a049;
        }
    </style>
</head>

<body>

    <button class="print-button no-print" onclick="window.print()">
        🖨️ Print Struk
    </button>

    <div class="struk-container">
        <!-- Header -->
        <div class="header">
            <h1><?= $outlet ? strtoupper($outlet['nama_outlet']) : 'TOKO SAYA' ?></h1>
            <?php if ($outlet): ?>
                <p><?= esc($outlet['alamat']) ?></p>
                <p>Telp: <?= esc($outlet['telp']) ?></p>
            <?php endif; ?>
        </div>

        <!-- Transaction Info -->
        <div class="info-section">
            <p>No. Struk : <?= $transaction['header']['NoStruk'] ?></p>
            <p>Tanggal :
                <?= date('d/m/Y H:i', strtotime($transaction['header']['Tanggal'] . ' ' . $transaction['header']['Waktu'])) ?>
            </p>
            <p>Kasir : <?= $transaction['header']['Kasir'] ?></p>
            <p>Outlet : <?= $transaction['header']['KdStore'] ?></p>
        </div>

        <!-- Items -->
        <div class="items-table">
            <?php foreach ($transaction['details'] as $detail): ?>
                <div class="item-row">
                    <div style="flex: 1;">
                        <div class="item-name">
                            <?= esc($detail['NamaStruk'] ?? $detail['NamaLengkap']) ?>
                            <?php if ($detail['Ketentuan1']): ?>
                                <span class="promo-badge">PROMO</span>
                            <?php endif; ?>
                        </div>
                        <div class="item-detail">
                            <span><?= number_format($detail['Qty'], 0) ?> x Rp
                                <?= number_format($detail['Harga'], 0) ?></span>
                            <span>Rp <?= number_format($detail['Harga'] * $detail['Qty'], 0) ?></span>
                        </div>
                        <?php if ($detail['Disc1'] > 0): ?>
                            <div class="item-detail" style="margin-top: 2px;">
                                <span>Diskon <?= $detail['Ketentuan1'] ?></span>
                                <span>- Rp <?= number_format($detail['Disc1'] * $detail['Qty'], 0) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rp <?= number_format($transaction['header']['TotalNilaiPem'], 0) ?></span>
            </div>

            <?php if ($transaction['header']['Discount'] > 0): ?>
                <div class="total-row" style="color: #666;">
                    <span>Diskon Promo</span>
                    <span>- Rp <?= number_format($transaction['header']['Discount'], 0) ?></span>
                </div>
            <?php endif; ?>

            <div class="total-row grand-total">
                <span>TOTAL</span>
                <span>Rp <?= number_format($transaction['header']['TotalNilai'], 0) ?></span>
            </div>
        </div>

        <!-- Payment -->
        <div class="payment-section">
            <?php if ($transaction['header']['Tunai'] > 0): ?>
                <div class="total-row">
                    <span>Tunai</span>
                    <span>Rp <?= number_format($transaction['header']['Tunai'], 0) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($transaction['header']['KDebit'] > 0): ?>
                <div class="total-row">
                    <span>Kartu Debit</span>
                    <span>Rp <?= number_format($transaction['header']['KDebit'], 0) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($transaction['header']['KKredit'] > 0): ?>
                <div class="total-row">
                    <span>Kartu Kredit</span>
                    <span>Rp <?= number_format($transaction['header']['KKredit'], 0) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($transaction['header']['GoPay'] > 0): ?>
                <div class="total-row">
                    <span>GoPay</span>
                    <span>Rp <?= number_format($transaction['header']['GoPay'], 0) ?></span>
                </div>
            <?php endif; ?>

            <div class="total-row">
                <span>Total Bayar</span>
                <span>Rp <?= number_format($transaction['header']['TotalBayar'], 0) ?></span>
            </div>

            <div class="total-row" style="font-weight: bold;">
                <span>Kembali</span>
                <span>Rp <?= number_format($transaction['header']['Kembali'], 0) ?></span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>* TERIMA KASIH *</p>
            <p>Barang yang sudah dibeli</p>
            <p>tidak dapat dikembalikan</p>
            <p style="margin-top: 10px;">---------------------------</p>
            <p>www.tokosaya.com</p>
        </div>
    </div>

    <script>
        // Auto print on load
        window.onload = function() {
            // Optional: Auto print
            // setTimeout(() => window.print(), 500);
        };
    </script>

</body>

</html>