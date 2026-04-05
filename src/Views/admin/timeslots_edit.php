<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-4">
    <?php $backHref = '/admin/timeslots?court_id=' . (int)$timeslot->courtId . '&date=' . urlencode($timeslot->slotDate); ?>
    <?php require __DIR__ . '/../partials/back_nav.php'; ?>

    <h1 class="mb-3">Edit timeslot</h1>

    <div class="card">
        <div class="card-body">
            <form method="post" action="/admin/timeslots/update">
                <?= \App\Framework\Csrf::inputField() ?>
                <input type="hidden" name="id" value="<?php echo (int)$timeslot->id; ?>">
                <input type="hidden" name="court_id" value="<?php echo (int)$timeslot->courtId; ?>">

                <div class="mb-3">
                    <label for="slot_date" class="form-label">Date</label>
                    <input type="date" name="slot_date" id="slot_date" required class="form-control" value="<?php echo htmlspecialchars($timeslot->slotDate); ?>">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_time" class="form-label">Start time</label>
                        <input type="time" name="start_time" id="start_time" required class="form-control" value="<?php echo htmlspecialchars(substr($timeslot->startTime, 0, 5)); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_time" class="form-label">End time</label>
                        <input type="time" name="end_time" id="end_time" required class="form-control" value="<?php echo htmlspecialchars(substr($timeslot->endTime, 0, 5)); ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update timeslot</button>
                <a href="/admin/timeslots?court_id=<?php echo (int)$timeslot->courtId; ?>&date=<?php echo urlencode($timeslot->slotDate); ?>" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
