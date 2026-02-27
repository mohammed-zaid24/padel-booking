<?php require __DIR__ . '/../partials/header.php'; ?>

<section class="bg-white rounded shadow-sm p-5 mb-5 text-center">
    <h1 class="display-5 fw-bold mb-3">Book Your Padel Court</h1>
    <p class="lead text-muted mb-4">Reserve your slot in seconds. Play more, wait less.</p>
    <div class="d-flex flex-wrap gap-2 justify-content-center">
        <a class="btn btn-primary btn-lg" href="/courts">Find a court</a>
        <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'user'): ?>
            <a class="btn btn-outline-primary btn-lg" href="/my-bookings">My bookings</a>
        <?php endif; ?>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a class="btn btn-outline-secondary btn-lg" href="/login">Login</a>
            <a class="btn btn-outline-dark btn-lg" href="/register">Register</a>
        <?php endif; ?>
    </div>
</section>

<h2 class="h4 mb-3">Why book with us</h2>
<div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
    <div class="col">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h3 class="h5 card-title">Real-time availability</h3>
                <p class="card-text text-muted mb-0">See free slots instantly. No phone calls, no guesswork.</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h3 class="h5 card-title">Secure booking</h3>
                <p class="card-text text-muted mb-0">Your data is safe. Secure login and reliable reservations.</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h3 class="h5 card-title">Manage bookings</h3>
                <p class="card-text text-muted mb-0">Edit or cancel anytime. You’re in control.</p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
