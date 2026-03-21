<?php require __DIR__ . '/../partials/header.php'; ?>

<?php
$breadcrumbs = [
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Courts', 'url' => '/courts'],
    ['label' => htmlspecialchars($court->name), 'url' => null],
];
require __DIR__ . '/../partials/breadcrumb.php';
?>

<a class="btn btn-outline-secondary btn-sm mb-3" href="javascript:history.back()">← Back</a>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h1 class="h4 card-title mb-1"><?php echo htmlspecialchars($court->name); ?></h1>
        <p class="card-text text-muted mb-0"><strong>Location:</strong> <?php echo htmlspecialchars($court->location); ?></p>
    </div>
</div>

<?php if (isset($_SESSION['show_my_bookings_button']) && isset($_SESSION['user_id'])): ?>
    <?php unset($_SESSION['show_my_bookings_button']); ?>

    <div class="card mb-4 shadow-sm">
        <div class="card-body text-center py-5">
            <div class="mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="currentColor" class="text-success bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
            </div>
            <h2 class="h5 mb-2">Booking Confirmed!</h2>
            <p class="text-muted mb-4">Your court has been successfully booked.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="/courts/<?php echo (int)$court->id; ?>" class="btn btn-outline-primary">Add Another Booking</a>
                <a href="/my-bookings" class="btn btn-primary">Go to My Bookings</a>
            </div>
        </div>
    </div>

<?php else: ?>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <h2 class="h5 mb-0">Step 1: Choose date</h2>
        </div>
        <div class="card-body">
            <label for="datePicker" class="form-label">Date</label>
            <input type="date" id="datePicker" class="form-control" style="max-width: 250px;" aria-describedby="dateHelp">
            <div id="dateHelp" class="form-text">Pick a date to see available times below.</div>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <h2 class="h5 mb-0">Step 2: Available timeslots</h2>
        </div>
        <div class="card-body">
            <div id="timeslotsBox">
                <div class="alert alert-light border mb-0 text-muted">
                    Select a date above to see availability.
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="csrf-token" value="<?= \App\Framework\Csrf::token() ?>">

    <script>
    const courtId = <?php echo (int)$court->id; ?>;
    const csrfToken = document.getElementById('csrf-token').value;
    const datePicker = document.getElementById('datePicker');
    const box = document.getElementById('timeslotsBox');

    const params = new URLSearchParams(window.location.search);
    const existingDate = params.get('date');

    if (existingDate) {
        datePicker.value = existingDate;
        datePicker.dispatchEvent(new Event('change'));
    }

    datePicker.addEventListener('change', async () => {
        const date = datePicker.value;

        if (!date) {
            box.innerHTML = '<div class="alert alert-light border mb-0 text-muted">Select a date above to see availability.</div>';
            return;
        }

        box.innerHTML = '<div class="alert alert-info mb-0">Loading...</div>';

        try {
            const res = await fetch(`/api/availability?court_id=${courtId}&date=${encodeURIComponent(date)}`);
            const data = await res.json();

            let html = '<ul class="list-group list-group-flush">';

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
                            <input type="hidden" name="_csrf" value="${csrfToken}">
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

<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
