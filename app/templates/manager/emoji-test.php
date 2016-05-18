<?php
/** @var array $constants */
?>
<div class="row">
    <div class="grid-3 grid-small-10 grid-smallest-10">
        <h2>Emoji</h2>
        <table class="table table-striped table-caption-upper table-hovered">
            <tr>
                <?php $counter = 0; ?>
                <?php foreach ($constants as $constantName => $constantValue): ?>
                <td>
                    <span><?= $constantValue ?></span>
                </td>
                <?php if (++$counter % 6 == 0): ?>
            </tr>
            <tr>
                <?php endif; ?>
                <?php endforeach; ?>
            </tr>
        </table>
    </div>
</div>