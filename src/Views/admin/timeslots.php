<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-4">
    <a class="btn btn-outline-secondary btn-sm mb-3" href="javascript:history.back()">← Back</a>
    <h1 class="mb-4">Manage Timeslots</h1>

    <form method="get" action="/admin/timeslots" class="mb-3">
        <div class="mb-2">
            <label class="form-label">Select court:</label>
            <select class="form-select" name="court_id" onchange="this.form.submit()">
                <option value="" <?php echo ($selectedCourtId === 0) ? 'selected' : ''; ?> disabled>Choose a court...</option>
                <?php foreach ($courts as $c): ?>
                    <option value="<?php echo (int)$c->id; ?>" <?php echo ($selectedCourtId === $c->id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if (empty($courts)): ?>
        <p>No courts found. Create a court first.</p>
    <?php elseif ($selectedCourtId === 0): ?>
        <div class="alert alert-info">Please choose a court to manage its timeslots.</div>
    <?php else: ?>

        <?php if (isset($_SESSION['show_timeslot_added_success'])): ?>
            <?php unset($_SESSION['show_timeslot_added_success']); ?>

            <div class="card mb-4 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="currentColor" class="text-success bi bi-check-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                    </div>
                    <h2 class="h5 mb-2">Timeslot Added Successfully!</h2>
                    <p class="text-muted mb-4">Your new timeslot has been added.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-primary" onclick="document.getElementById('addTimeslotForm').scrollIntoView({behavior:'smooth'});">Add Another Timeslot</button>
                        <a href="/admin" class="btn btn-outline-primary">Back to Admin</a>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <h2 class="h5 mt-4 mb-3">Add timeslot</h2>
        <form method="post" action="/admin/timeslots/create" class="mb-4" id="addTimeslotForm">
            <?= \App\Framework\Csrf::inputField() ?>
            <input type="hidden" name="court_id" value="<?php echo (int)$selectedCourtId; ?>">

            <div class="mb-3">
                <label class="form-label" for="slot_day">Pick day</label>
                <input class="form-control" type="date" id="slot_day" required>
                <div class="form-text">Choose a day first, then select start and end time.</div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="start_time">Start time</label>
                    <select class="form-select" id="start_time" name="start_time" required disabled>
                        <option value="" selected disabled>Select start time...</option>
                        <?php for ($h = 6; $h <= 23; $h++): ?>
                            <?php foreach (['00', '30'] as $m): ?>
                                <option value="<?php echo sprintf('%02d:%s:00', $h, $m); ?>">
                                    <?php echo sprintf('%02d:%s', $h, $m); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="end_time">End time</label>
                    <select class="form-select" id="end_time" name="end_time" required disabled>
                        <option value="" selected disabled>Select end time...</option>
                        <?php for ($h = 6; $h <= 23; $h++): ?>
                            <?php foreach (['00', '30'] as $m): ?>
                                <option value="<?php echo sprintf('%02d:%s:00', $h, $m); ?>">
                                    <?php echo sprintf('%02d:%s', $h, $m); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" id="addTimeslotBtn" disabled>Add Timeslot</button>
        </form>

        <h2 class="h5 mt-4 mb-3">Existing timeslots</h2>

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

        <script>
        const slotDay = document.getElementById('slot_day');
        const startTime = document.getElementById('start_time');
        const endTime = document.getElementById('end_time');
        const addTimeslotBtn = document.getElementById('addTimeslotBtn');

        function toggleTimeslotFields() {
            const hasDay = !!slotDay.value;
            startTime.disabled = !hasDay;
            endTime.disabled = !hasDay;
            addTimeslotBtn.disabled = !hasDay;
        }

        slotDay.addEventListener('change', toggleTimeslotFields);
        toggleTimeslotFields();
        </script>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>