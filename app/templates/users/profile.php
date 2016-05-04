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
            <tr>
                <td>user-agent</td>
                <td><code><?= $ua ?></code></td>
            </tr>
            <tr>
                <td>ip</td>
                <td><code><?= $ip ?> / <?= $real_ip ?></code></td>
            </tr>
        </table>
    </div>
    <div class="grid-5 grid-small-10 grid-smallest-10">
        <h2>Token</h2>
        <form action="<?= $this->url->path('manager/users/generate-token') ?>" method="post">
            <table class="table table-striped table-caption-upper table-hovered">
                <tr>
                    <td>token</td>
                    <td><code><?= ($token->exists() ? $token->getToken() : 'none') ?></code></td>
                </tr>
                <tr>
                    <td>password</td>
                    <td>
                        <input class="input input-border-default input-color-light-green input-rounded" type="password"
                               name="password" placeholder="enter your current password...">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input class="button button-rounded button-light-green" type="submit" value="generate">
                    </td>
                    <td></td>
                </tr>
            </table>
        </form>
    </div>
</div>
