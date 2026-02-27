<?php require __DIR__ . '/../partials/header.php'; ?>

<a class="btn btn-outline-secondary btn-sm mb-3" href="javascript:history.back()">← Back</a>

<h1 class="mb-3">Edit booking</h1>

<div class="card">
    <div class="card-body">
        <p class="text-muted mb-3">
            Court: <strong><?php echo htmlspecialchars($booking['court_name']); ?></strong>
            · Current: <?php echo htmlspecialchars($booking['date']); ?>
            <?php echo htmlspecialchars($booking['start_time']); ?>–<?php echo htmlspecialchars($booking['end_time']); ?>
        </p>

        <form method="post" action="/edit-booking">
            <?= \App\Framework\Csrf::inputField() ?>
            <input type="hidden" name="booking_id" value="<?php echo (int)$booking['booking_id']; ?>">

            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" id="date" required class="form-control" value="<?php echo htmlspecialchars($booking['date']); ?>">
            </div>

            <div class="mb-3">
                <label for="timeslot_id" class="form-label">Time slot</label>
                <select name="timeslot_id" id="timeslot_id" required class="form-select">
                    <?php foreach ($timeslots as $t): ?>
                        <option value="<?php echo (int)$t->id; ?>" <?php echo ((int)$t->id === (int)$booking['timeslot_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($t->startTime); ?> – <?php echo htmlspecialchars($t->endTime); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update booking</button>
            <a href="/my-bookings" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
