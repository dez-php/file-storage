<?php

?>
<div class="row">
    <div class="grid-3 grid-small-10 grid-smallest-10">
        <h2>Register new user</h2>
        <form class="form-default bg-color-dark" action="<?= $this->url->path('manager/users/register'); ?>" method="post">
            <div class="form-row">
                <label>e-mail</label>
                <input type="text" name="email" class="input input-color-notice input-rounded input-border-default">
            </div>
            <div class="form-row">
                <label>password</label>
                <input type="text" name="password" class="input input-color-notice input-rounded input-border-default">
            </div>
            <div class="form-row">
                <label></label>
                <input type="submit" value="register user" class="button button-notice button-rounded">
            </div>
        </form>
    </div>
</div>
