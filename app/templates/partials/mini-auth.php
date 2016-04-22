<?php
/** @var \Dez\Authorizer\Adapter\Session $authorizerSession */
$userModel = $authorizerSession->credentials();
?>
<?php if($authorizerSession->isGuest()):?>
    <h3>auth</h3>
    <form action="<?= $url->path('manager/index'); ?>" method="post">
        <p><label for="login">email:</label>
            <input name="email" id="login" value="" type="text" /></p>
        <p><label for="password">password:</label>
            <input name="password" id="password" value="" type="password" /></p>
        <p><input value="login" type="submit" /></p>
    </form>
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