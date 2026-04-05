<?php require __DIR__ . '/../partials/header.php'; ?>

<?php
$breadcrumbs = [
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'My bookings', 'url' => null],
];
require __DIR__ . '/../partials/breadcrumb.php';
?>

<?php require __DIR__ . '/../partials/back_nav.php'; ?>

<h1 class="mb-4">My bookings</h1>

<?php if (count($bookings) === 0): ?>
    <div class="card border-0 bg-light">
        <div class="card-body text-center py-5">
            <h2 class="h5 text-muted mb-2">You have no bookings yet</h2>
            <p class="text-muted mb-3">Find a court and book your first slot.</p>
            <a class="btn btn-primary" href="/courts">Find a court</a>
        </div>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Court</th>
                    <th>Location</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($b['date']); ?></td>
                        <td>
                            <?php echo htmlspecialchars($b['start_time']); ?>
                            –
                            <?php echo htmlspecialchars($b['end_time']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($b['court_name']); ?></td>
                        <td><?php echo htmlspecialchars($b['court_location']); ?></td>
                        <td>
                            <a href="/edit-booking?id=<?php echo (int)$b['booking_id']; ?>" class="btn btn-secondary btn-sm me-1">Edit</a>
                            <form method="post" action="/cancel-booking" style="display:inline;">
                                <?= \App\Framework\Csrf::inputField() ?>
                                <input type="hidden" name="booking_id" value="<?php echo (int)$b['booking_id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
