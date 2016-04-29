<?php
/**
 * @var $auth \Dez\Authorizer\Adapter\Session
 * @var $token \Dez\Authorizer\Models\Auth\TokenModel
*/

?>
<div class="row">
    <div class="grid-5 grid-small-10 grid-smallest-10">
        <h2>Profile</h2>
        <table class="table table-striped table-caption-upper table-hovered">
            <tr>
                <td>email</td>
                <td><code><?= $auth->credentials()->getEmail() ?></code></td>
            </tr>
            <tr>
                <td>registered</td>
                <td><code><?= $auth->credentials()->getCreatedAt() ?></code></td>
            </tr>
            <tr>
                <td>updated</td>
                <td><code><?= $auth->credentials()->getUpdatedAt() ?></code></td>
            </tr>
            <tr>
                <td>status</td>
                <td><code><?= $auth->credentials()->getStatus() ?></code></td>
            </tr>
            <tr>
                <td>logged at</td>
                <td><code><?= $auth->getModel()->getCreatedAt() ?></code></td>
            </tr>
            <tr>
                <td>last visit</td>
                <td><code><?= $auth->getModel()->getUsedAt() ?></code></td>
            </tr>
            <tr>
                <td>expired</td>
                <td><code><?= $auth->getModel()->getExpiryDate() ?></code></td>
            </tr>
            <tr>
                <td>environment</td>
                <td><code><?= $auth->getModel()->getUniqueHash() ?></code></td>
            </tr>
        </table>
    </div>
    <div class="grid-5 grid-small-10 grid-smallest-10">
        <h2>Token</h2>
        <table class="table table-striped table-caption-upper table-hovered">
            <tr>
                <td><input class="input input-color-light-green input-border-default input-rounded" type="text" style="width: 100%" readonly value="<?= ($token->exists() ? $token->getToken() : 'none') ?>"></td>
                <td>
                    <a class="button button-light-green button-rounded" href="<?= $this->url->path('manager/users/generate-token', ['token' => $token->getToken()]); ?>">generate new</a>
                </td>
            </tr>
        </table>
    </div>
</div>
