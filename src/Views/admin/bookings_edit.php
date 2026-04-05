<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-4">
    <?php $backHref = '/admin/bookings'; ?>
    <?php require __DIR__ . '/../partials/back_nav.php'; ?>

    <h1 class="mb-3">Edit booking</h1>

    <div class="card">
        <div class="card-body">
            <p class="text-muted mb-3">
                User: <strong><?php echo htmlspecialchars($booking['user_name']); ?></strong>
                (<?php echo htmlspecialchars($booking['user_email']); ?>)
                <br>
                Court: <strong><?php echo htmlspecialchars($booking['court_name']); ?></strong>
                · Current: <?php echo htmlspecialchars($booking['date']); ?>
                <?php echo htmlspecialchars($booking['start_time']); ?>-<?php echo htmlspecialchars($booking['end_time']); ?>
            </p>

            <form method="post" action="/admin/bookings/update">
                <?= \App\Framework\Csrf::inputField() ?>
                <input type="hidden" name="booking_id" value="<?php echo (int)$booking['booking_id']; ?>">

                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input
                        type="date"
                        name="date"
                        id="date"
                        required
                        class="form-control"
                        value="<?php echo htmlspecialchars($booking['date']); ?>"
                    >
                </div>

                <div class="mb-3">
                    <label for="timeslot_id" class="form-label">Time slot</label>
                    <select name="timeslot_id" id="timeslot_id" required class="form-select">
                        <?php foreach ($timeslots as $t): ?>
                            <option value="<?php echo (int)$t->id; ?>" <?php echo ((int)$t->id === (int)$booking['timeslot_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($t->startTime); ?> - <?php echo htmlspecialchars($t->endTime); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update booking</button>
                <a href="/admin/bookings" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
