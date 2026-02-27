<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-4">
    <a class="btn btn-outline-secondary btn-sm mb-3" href="javascript:history.back()">← Back</a>
    <h1 class="mb-4">Manage Timeslots</h1>

    <form method="get" action="/admin/timeslots" class="mb-3">
        <div class="mb-2">
            <label class="form-label">Select court:</label>
            <select class="form-select" name="court_id" onchange="this.form.submit()">
                <?php foreach ($courts as $c): ?>
                    <option value="<?php echo (int)$c->id; ?>" <?php echo ($selectedCourtId === $c->id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <h2 class="h5 mt-4 mb-3">Add timeslot</h2>
    <form method="post" action="/admin/timeslots/create" class="mb-4">
        <?= \App\Framework\Csrf::inputField() ?>
        <input type="hidden" name="court_id" value="<?php echo (int)$selectedCourtId; ?>">

        <div class="mb-3">
            <label class="form-label">Start time</label>
            <input class="form-control" type="time" name="start_time" required>
        </div>

        <div class="mb-3">
            <label class="form-label">End time</label>
            <input class="form-control" type="time" name="end_time" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Timeslot</button>
    </form>

    <h2 class="h5 mt-4 mb-3">Existing timeslots</h2>

    <?php if ($selectedCourtId === 0): ?>
        <p>No courts found. Create a court first.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($timeslots as $t): ?>
                        <tr>
                            <td><?php echo (int)$t->id; ?></td>
                            <td><?php echo htmlspecialchars($t->startTime); ?></td>
                            <td><?php echo htmlspecialchars($t->endTime); ?></td>
                            <td>
                                <form method="post" action="/admin/timeslots/delete" style="display:inline;">
                                    <?= \App\Framework\Csrf::inputField() ?>
                                    <input type="hidden" name="id" value="<?php echo (int)$t->id; ?>">
                                    <input type="hidden" name="court_id" value="<?php echo (int)$selectedCourtId; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this timeslot?');">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>