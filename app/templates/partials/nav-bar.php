<?php
/** @var \Dez\Authorizer\Adapter\Session $authorizerSession */
?>
<div class="row">
    <div class="grid-10">
        <h4 class="inline text-color-gray mr-10">file storage manager</h4>
        <a class="button button-warning mr-10" href="<?= $url->path('manager/upload-file'); ?>">+ upload file</a>
        <a class="button button-notice mr-10" href="<?= $url->path('manager/categories'); ?>">categories</a>
        <a class="button button-success mr-10" href="<?= $url->path('manager/files/latest'); ?>">files</a>
        <a class="button button-danger pull-right ml-10" href="<?= $url->path('manager/close-session'); ?>">logout</a>
        <a class="button button-gray pull-right" href="<?= $url->path('manager/users/profile'); ?>"><?= explode('@', $authorizerSession->credentials()->getEmail())[0]; ?></a>
    </div>
</div>