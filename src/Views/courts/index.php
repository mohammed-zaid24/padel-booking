<?php require __DIR__ . '/../partials/header.php'; ?>

<h1 class="mb-3">Courts</h1>

<div class="list-group">
    <?php foreach ($courts as $court): ?>
        <a class="list-group-item list-group-item-action" href="/courts/<?php echo (int)$court->id; ?>">
            <div class="d-flex justify-content-between">
                <strong><?php echo htmlspecialchars($court->name); ?></strong>
                <span class="text-muted"><?php echo htmlspecialchars($court->location); ?></span>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>