<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Storage - Manager</title>
    <link rel="stylesheet" href="<?= $url->staticPath('css/main.css') ?>">
</head>
<body class="">

<?= $this->fetch('partials/messages'); ?>

<div class="grid-10">
    <div class="row">
        <div class="grid-10 box bg-color-dark">
            <?= $this->fetch('partials/nav-bar'); ?>
        </div>
    </div>

    <div class="row">
        <div class="grid-10">
            <?= $this->section('content'); ?>
        </div>
    </div>
</div>

</body>
</html>