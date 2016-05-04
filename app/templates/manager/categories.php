<?php

use Dez\ORM\Collection\ModelCollection;
use FileStorage\Models\Categories;

/**
 * @var ModelCollection $categories
 * @var Categories $category
*/

?>
<div class="row">
    <div class="grid-7 grid-small-10 grid-smallest-10">
        <h2>Categories</h2>
        <table class="table table-striped table-caption-upper table-hovered">
            <thead>
            <tr>
                <th>id</th>
                <th class="hidden-smallest">name</th>
                <th>slug</th>
                <th class="hidden-smallest">created</th>
                <th style="min-width: 135px"></th>
            </tr>
            </thead>
            <?php if($categories->count() > 0): ?>
                <?php foreach ($categories as $category): ?>
                    <tr <?= ($category->getStatus() == Categories::STATUS_DELETED ? ' class="opacity-6"' : null) ?>>
                        <td><?= $category->id(); ?></td>
                        <td class="hidden-smallest"><?= $category->getName(); ?></td>
                        <td><code><?= $category->getSlug(); ?></code></td>
                        <td class="hidden-smallest"><?= date('d F, Y H:i:s', $category->getCreatedAt()); ?></td>
                        <td class="text-center">
                            <?php if($category->getStatus() == Categories::STATUS_DELETED):?>
                                <a class="button button-yellow button-size-small" href="<?= $url->path("manager/activate-category/{$category->id()}") ?>">activate</a>
                            <?php else: ?>
                                <a class="button button-notice button-size-small" href="<?= $url->path("manager/files/category", ['slug' => $category->getSlug()]) ?>">look files</a>
                                <a class="button button-light-green button-size-small" href="<?= $url->path("manager/edit-category/{$category->id()}") ?>">edit</a>
                                <a class="button button-orange button-size-small" href="<?= $url->path("manager/delete-category/{$category->id()}") ?>">delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">
                        <h4><i class="text-color-gray">No records found...</i></h4>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
    <div class="grid-3 grid-small-10 grid-smallest-10">
        <h2>Category manager</h2>
        <form class="form-default bg-color-dark" action="<?= $url->path('manager/create-category'); ?>" method="post">
            <div class="form-row">
                <label>name</label>
                <input type="text" name="name" class="input input-color-notice input-rounded input-border-default">
            </div>
            <div class="form-row">
                <label></label>
                <input type="submit" value="create new category" class="button button-notice button-rounded">
            </div>
        </form>
    </div>
</div>