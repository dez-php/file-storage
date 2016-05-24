<?php

/**
 * @var $users CredentialModel[]
 */
use Dez\Authorizer\Models\CredentialModel;

?>
<div class="row">
    <div class="grid-10">
        <h2>Users</h2>
        <div class="row">
            <div class="grid-10">
                <a class="button button-success button-size-small" href="<?= $this->url->path('manager/users/profile'); ?>">my profile</a>
                <a class="button button-light-green button-size-small" href="<?= $this->url->path('manager/users/register'); ?>">+ register new user</a>
            </div>
        </div>
        <table class="table table-striped table-caption-upper table-hovered">
            <thead>
            <tr>
                <th>id</th>
                <th>e-mail</th>
                <th class="hidden-smallest">status</th>
                <th class="hidden-small hidden-smallest">created</th>
                <th class="hidden-small hidden-smallest">updated</th>
                <th></th>
            </tr>
            </thead>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user->id() ?></td>
                <td><?= $user->getEmail() ?></td>
                <td><code><?= $user->getStatus() ?></code></td>
                <td><?= $user->getCreatedAt() ?></td>
                <td><?= $user->getUpdatedAt() ?></td>
                <td>
                    <?php if($user->getStatus() == CredentialModel::STATUS_ACTIVE): ?>
                    <a class="button button-warning button-size-small" href="<?= $this->url->path('manager/users/update-status', ['id' => $user->id(), 'status' => CredentialModel::STATUS_DELETED]); ?>">delete</a>
                    <?php else: ?>
                    <a class="button button-light-green button-size-small" href="<?= $this->url->path('manager/users/update-status', ['id' => $user->id(), 'status' => CredentialModel::STATUS_ACTIVE]); ?>">activate</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>