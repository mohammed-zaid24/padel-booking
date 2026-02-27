<?php require __DIR__ . '/../partials/header.php'; ?>

<a class="btn btn-outline-secondary btn-sm mb-3" href="javascript:history.back()">← Back</a>

<h1>Manage Courts</h1>

<h2>Add new court</h2>
<form method="post" action="/admin/courts/create">
    <?= \App\Framework\Csrf::inputField() ?>
    <div class="mb-2">
        <label class="form-label">Name</label>
        <input type="text" name="name" required class="form-control">
    </div>

    <div class="mb-2">
        <label class="form-label">Location</label>
        <input type="text" name="location" required class="form-control">
    </div>

    <button type="submit" class="btn btn-primary mt-2">Add Court</button>
</form>

<h2>Existing courts</h2>

<table class="table table-striped table-bordered align-middle">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Location</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($courts as $c): ?>
            <tr>
                <td><?php echo (int)$c->id; ?></td>
                <td><?php echo htmlspecialchars($c->name); ?></td>
                <td><?php echo htmlspecialchars($c->location); ?></td>
                <td>
                    <a href="/admin/courts/edit?id=<?php echo (int)$c->id; ?>" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                    <form method="post" action="/admin/courts/delete" style="display:inline;">
                        <?= \App\Framework\Csrf::inputField() ?>
                        <input type="hidden" name="id" value="<?php echo (int)$c->id; ?>">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this court?');">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../partials/footer.php'; ?>