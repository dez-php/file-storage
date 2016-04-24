<?php
/** @var \Dez\Authorizer\Adapter\Session $authorizerSession */
$userModel = $authorizerSession->credentials();
?>
<?php if($authorizerSession->isGuest()):?>
    <div class="row">

        <div class="bg-color-dark input-rounded grid-10 hidden-middle">
            <form class="form-default" action="<?= $url->path('manager/index'); ?>" method="post">
                <div class="form-row">
                    <label>e-mail</label>
                    <input type="text" name="email" class="input input-color-success input-rounded">
                </div>
                <div class="form-row">
                    <label>password</label>
                    <input type="password" name="password" class="input input-color-success input-rounded">
                </div>
                <div class="form-row text-center">
                    <input type="submit" value="sign in" class="button button-success button-rounded">
                </div>
            </form>
        </div>

    </div>
<?php else: ?>
    <div class="bg-color-dark input-rounded grid-10 text-center">
        <h4>Welcome <?= $authorizerSession->credentials()->getEmail() ?></h4>
        <a class="button button-light-green button-rounded" href="<?= $url->path('manager/dashboard'); ?>">Go to dashboard</a>
        <br>
        <a class="button button-orange button-rounded" href="<?= $url->path('manager/close-session'); ?>">Logout</a>
    </div>
<?php endif; ?>