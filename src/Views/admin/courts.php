<?php require __DIR__ . '/../partials/header.php'; ?>

<?php require __DIR__ . '/../partials/back_nav.php'; ?>

<h1>Manage Courts</h1>

<?php if (isset($_SESSION['show_court_added_success'])): ?>
    <?php $newCourtId = (int)($_SESSION['new_court_id'] ?? 0); ?>
    <?php unset($_SESSION['show_court_added_success']); ?>
    <?php unset($_SESSION['new_court_id']); ?>

    <div class="card mb-4 shadow-sm">
        <div class="card-body text-center py-5">
            <div class="mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="currentColor" class="text-success bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
            </div>
            <h2 class="h5 mb-2">Court Added Successfully!</h2>
            <p class="text-muted mb-4">Your new court has been added to the system.</p>
            <div class="d-flex justify-content-center gap-3">
                <?php if ($newCourtId > 0): ?>
                    <a href="/admin/timeslots?court_id=<?php echo $newCourtId; ?>" class="btn btn-success">Add Timeslot To This Court</a>
                <?php endif; ?>
                <button class="btn btn-primary" onclick="document.getElementById('addCourtForm').scrollIntoView({behavior:'smooth'});">Add Another Court</button>
                <a href="/admin" class="btn btn-outline-primary">Back to Admin</a>
            </div>
        </div>
    </div>

<?php endif; ?>

<h2>Add new court</h2>
<form method="post" action="/admin/courts/create" id="addCourtForm">
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