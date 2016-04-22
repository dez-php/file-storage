<?php
/** @var \Dez\Authorizer\Adapter\Session $authorizerSession */
$userModel = $authorizerSession->credentials();
?>
<?php if($authorizerSession->isGuest()):?>
    <div class="bg-color-dark input-rounded" style="width: 300px; margin: 10% auto; padding: 30px 10px">
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
<?php else: ?>
    <h3>currect session</h3>
    <ul>
        <li>
            <i><?= $userModel->getEmail(); ?></i>
        </li>
        <li>
            <a href="<?= $url->path("manager/close-session"); ?>">logout</a>
        </li>
    </ul>
<?php endif; ?>