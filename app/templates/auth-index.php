<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Storage - Manager</title>
    <link rel="stylesheet" href="<?= $url->staticPath('css/main.css') ?>">
    <link rel="stylesheet" href="<?= $url->staticPath('file-storage.css') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<div class="auth-index">

    <h1 class="text-center">File Storage Manager</h1>

    <div class="row rounded-inner">
        <?= $this->fetch('partials/messages'); ?>
    </div>

    <?= $this->section('content'); ?>

</div>

</body>
</html>