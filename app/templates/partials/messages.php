<?php foreach ($flash->getMessages() as $type => $messages): ?>
    <div class="flash-messages flash-messages-<?= $type; ?>">
        <?php foreach($messages as $message): ?>
            <div><?= $message; ?></div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>