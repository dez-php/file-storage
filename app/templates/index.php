<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Storage - Manager</title>
    <meta name="theme-color" content="#505050">
    <link rel="shortcut icon" href="<?= $url->staticPath('favicon.png') ?>" type="image/png" />
    <link rel="stylesheet" href="<?= $url->staticPath('css/main.css') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="<?= $url->staticPath('js/dom.js') ?>"></script>
</head>
<body class="">

<div class="grid-10">
    <div class="row">
        <div class="grid-10 box bg-color-dark">
            <?= $this->fetch('partials/nav-bar'); ?>
        </div>
    </div>

    <?= $this->fetch('partials/messages'); ?>

    <div class="row">
        <div class="grid-10">
            <?= $this->section('content'); ?>
        </div>
    </div>
</div>

</body>
</html>