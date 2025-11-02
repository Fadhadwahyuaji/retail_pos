<!DOCTYPE html>
<html>

<head>
    <title>Login Aplikasi CI4</title>
</head>

<body>
    <h2>Form Login</h2>

    <?php if (session()->getFlashdata('msg')): ?>
        <div style="color: red; border: 1px solid red; padding: 10px;">
            <?= session()->getFlashdata('msg') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('auth/login') ?>" method="post">

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>

</html>