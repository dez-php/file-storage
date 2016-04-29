<?php

/**
 * @var
 */

?>
<div class="row">
    <div class="grid-10">
        <h2>Users</h2>
        <div class="row">
            <div class="grid-10">
                <a class="button button-success button-size-small" href="<?= $this->url->path('manager/users/register'); ?>">+ register new user</a>
            </div>
        </div>
        <table class="table table-striped table-caption-upper table-hovered">
            <thead>
            <tr>
                <th class="hidden-smallest">name</th>
                <th>category</th>
                <th>size</th>
                <th class="hidden-smallest">mime-type</th>
                <th class="hidden-small hidden-smallest">hash</th>
                <th>created at</th>
                <th></th>
            </tr>
            </thead>
        </table>
    </div>
</div>