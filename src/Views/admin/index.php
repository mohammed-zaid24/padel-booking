<?php require __DIR__ . '/../partials/header.php'; ?>

<a class="btn btn-outline-secondary btn-sm mb-3" href="javascript:history.back()">← Back</a>

<h1 class="mb-2">Admin Dashboard</h1>
<p class="text-muted mb-4">Manage courts, timeslots and bookings.</p>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <div class="col">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h2 class="h5 card-title">Manage Courts</h2>
                <p class="card-text text-muted small mb-3">Add, edit, or remove courts.</p>
                <a class="btn btn-primary btn-sm" href="/admin/courts">Manage Courts</a>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h2 class="h5 card-title">Manage Timeslots</h2>
                <p class="card-text text-muted small mb-3">Define available time slots per court.</p>
                <a class="btn btn-primary btn-sm" href="/admin/timeslots">Manage Timeslots</a>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h2 class="h5 card-title">View Bookings</h2>
                <p class="card-text text-muted small mb-3">See and manage all bookings.</p>
                <a class="btn btn-primary btn-sm" href="/admin/bookings">View Bookings</a>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
