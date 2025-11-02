<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Aplikasi CI4 POS' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 dark:bg-gray-900">

    <?= $this->include('layouts/includes/navbar') ?>

    <?= $this->include('layouts/includes/sidebar') ?>

    <div class="p-4 sm:ml-64 mt-14">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
            <?= $this->renderSection('content') ?>
        </div>

        <?= $this->include('layouts/includes/footer') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.js"></script>
</body>

</html>