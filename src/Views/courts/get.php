<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="card mb-3">
    <div class="card-body">
        <h1 class="card-title"><?php echo htmlspecialchars($court->name); ?></h1>
        <p class="card-text mb-0">
            <strong>Location:</strong> <?php echo htmlspecialchars($court->location); ?>
        </p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h2 class="h5">Pick a date</h2>

        <label for="datePicker" class="form-label">Date</label>
        <input type="date" id="datePicker" class="form-control" style="max-width: 250px;">

        <div id="timeslotsBox" class="mt-3">
            <div class="alert alert-secondary mb-0">
                Select a date to see availability.
            </div>
        </div>
    </div>
</div>

<script>
const courtId = <?php echo (int)$court->id; ?>;
const datePicker = document.getElementById('datePicker');
const box = document.getElementById('timeslotsBox');

// If URL already has ?date=..., auto-load it
const params = new URLSearchParams(window.location.search);
const existingDate = params.get('date');

if (existingDate) {
    datePicker.value = existingDate;
    datePicker.dispatchEvent(new Event('change'));
}

datePicker.addEventListener('change', async () => {
    const date = datePicker.value;

    if (!date) {
        box.innerHTML = '<div class="alert alert-secondary mb-0">Select a date to see availability.</div>';
        return;
    }

    box.innerHTML = '<div class="alert alert-info mb-0">Loading...</div>';

    try {
        const res = await fetch(`/api/availability?court_id=${courtId}&date=${encodeURIComponent(date)}`);
        const data = await res.json();

        let html = '<h2 class="h5">Timeslots</h2><ul class="list-group">';

        for (const t of data) {
            if (t.is_booked) {
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>${t.start_time} - ${t.end_time}</span>
                    <span class="badge bg-secondary">Booked</span>
                 </li>`;
            } else {
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>${t.start_time} - ${t.end_time}</span>

                    <form method="post" action="/book" class="m-0">
                        <input type="hidden" name="court_id" value="${courtId}">
                        <input type="hidden" name="timeslot_id" value="${t.id}">
                        <input type="hidden" name="date" value="${date}">
                        <button type="submit" class="btn btn-sm btn-success">Book</button>
                    </form>
                 </li>`;
            }
        }

        html += '</ul>';
        box.innerHTML = html;
    } catch (err) {
        box.innerHTML = '<div class="alert alert-danger mb-0">Failed to load availability.</div>';
    }
});
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>