<?php require __DIR__ . '/../partials/header.php'; ?>

<a class="btn btn-outline-secondary btn-sm mb-3" href="javascript:history.back()">← Back</a>

<h1>Edit court</h1>

<div class="card mb-3">
    <div class="card-body">
        <form method="post" action="/admin/courts/update">
            <?= \App\Framework\Csrf::inputField() ?>
            <input type="hidden" name="id" value="<?php echo (int)$court->id; ?>">

            <div class="mb-2">
                <label class="form-label" for="name">Name</label>
                <input type="text" name="name" id="name" required class="form-control" value="<?php echo htmlspecialchars($court->name); ?>">
            </div>

            <div class="mb-2">
                <label class="form-label" for="location">Location</label>
                <input type="text" name="location" id="location" required class="form-control" value="<?php echo htmlspecialchars($court->location); ?>">
            </div>

            <button type="submit" class="btn btn-primary mt-2">Update court</button>
            <a href="/admin/courts" class="btn btn-outline-secondary mt-2 ms-2">Cancel</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
