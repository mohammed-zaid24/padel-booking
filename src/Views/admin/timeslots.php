<?php require __DIR__ . '/../partials/header.php'; ?>

<h1>Manage Timeslots</h1>

<form method="get" action="/admin/timeslots">
    <label>Select court:</label>
    <select name="court_id" onchange="this.form.submit()">
        <?php foreach ($courts as $c): ?>
            <option value="<?php echo (int)$c->id; ?>" <?php echo ($selectedCourtId === $c->id) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($c->name); ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<h2>Add timeslot</h2>
<form method="post" action="/admin/timeslots/create">
    <?= \App\Framework\Csrf::inputField() ?>
    <input type="hidden" name="court_id" value="<?php echo (int)$selectedCourtId; ?>">

    <div>
        <label>Start time</label><br>
        <input type="time" name="start_time" required>
    </div>

    <div>
        <label>End time</label><br>
        <input type="time" name="end_time" required>
    </div>

    <button type="submit">Add Timeslot</button>
</form>

<h2>Existing timeslots</h2>

<?php if ($selectedCourtId === 0): ?>
    <p>No courts found. Create a court first.</p>
<?php else: ?>
    <table border="1" cellpadding="6">
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
                            <button type="submit" onclick="return confirm('Delete this timeslot?');">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>