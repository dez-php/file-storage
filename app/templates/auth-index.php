<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Storage - Manager</title>
    <link rel="stylesheet" href="<?= $url->staticPath('css/main.css') ?>">
    <link rel="stylesheet" href="<?= $url->staticPath('file-storage.css') ?>">
</head>
<body>

<div class="auth-index">

    <h1 class="text-center">File Storage Manager</h1>

    <?= $this->fetch('partials/messages'); ?>

    <?= $this->section('content'); ?>

</div>

</body>
</html>