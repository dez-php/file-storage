<div class="row">
    <div class="grid-10">
        <a class="button button-success" href="<?= $url->path('manager/upload-file'); ?>">+ upload file</a>
        <a class="button button-notice" href="<?= $url->path('manager/categories'); ?>">categories</a>
        <a class="button button-warning" href="<?= $url->path('manager/files/latest'); ?>">latest</a>
        <a class="button button-danger pull-right" href="<?= $url->path('manager/close-session'); ?>">logout</a>
        <a class="button button-pink pull-right" href="<?= $url->path('manager/users/profile'); ?>">profile</a>
    </div>
</div>