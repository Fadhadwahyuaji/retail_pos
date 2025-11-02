<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Retail POS System</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f7fa;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .login-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
        width: 100%;
        max-width: 420px;
        padding: 50px 40px;
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .logo {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 28px;
        color: white;
        font-weight: bold;
    }

    .login-header h2 {
        color: #1a1a1a;
        font-size: 26px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .login-header p {
        color: #6b7280;
        font-size: 14px;
    }

    .alert {
        background-color: #fef2f2;
        border-left: 4px solid #ef4444;
        color: #991b1b;
        padding: 14px 16px;
        border-radius: 6px;
        margin-bottom: 25px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert::before {
        content: "⚠";
        font-size: 18px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    label {
        display: block;
        color: #374151;
        font-weight: 500;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .input-wrapper {
        position: relative;
    }

    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 13px 16px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s;
        outline: none;
        background-color: #fafafa;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
        border-color: #667eea;
        background-color: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    input[type="email"]::placeholder,
    input[type="password"]::placeholder {
        color: #9ca3af;
    }

    .btn-login {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 8px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
    }

    .btn-login:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.35);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .divider {
        text-align: center;
        margin: 30px 0;
        position: relative;
    }

    .divider::before {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        width: 100%;
        height: 1px;
        background: #e5e7eb;
    }

    .divider span {
        background: white;
        padding: 0 15px;
        color: #9ca3af;
        font-size: 13px;
        position: relative;
    }

    .footer-text {
        text-align: center;
        margin-top: 30px;
        color: #9ca3af;
        font-size: 13px;
    }

    .footer-text a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
    }

    .footer-text a:hover {
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .login-container {
            padding: 40px 30px;
        }

        .login-header h2 {
            font-size: 24px;
        }

        body {
            padding: 15px;
        }
    }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <!-- <div class="logo">RP</div> -->
            <h2>Selamat Datang</h2>
            <p>Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <?php if (session()->getFlashdata('msg')): ?>
        <div class="alert">
            <?= session()->getFlashdata('msg') ?>
        </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/login') ?>" method="post">
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" placeholder="nama@perusahaan.com" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
                </div>
            </div>

            <button type="submit" class="btn-login">Masuk</button>
        </form>

        <div class="divider">
            <span>Retail POS System</span>
        </div>

        <div class="footer-text">
            © 2025 PT Natuna Pesona Mandiri. All rights reserved.
        </div>
    </div>
</body>

</html>