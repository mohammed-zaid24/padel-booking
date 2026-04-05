<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-4">
    <?php require __DIR__ . '/../partials/back_nav.php'; ?>
    <h1 class="mb-4">All Bookings</h1>

    <?php if (count($bookings) === 0): ?>
        <div class="alert alert-secondary">No bookings found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Court</th>
                        <th>Location</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($b['date']); ?></td>
                            <td>
                                <?php echo htmlspecialchars($b['start_time']); ?> -
                                <?php echo htmlspecialchars($b['end_time']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($b['court_name']); ?></td>
                            <td><?php echo htmlspecialchars($b['court_location']); ?></td>
                            <td><?php echo htmlspecialchars($b['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($b['user_email']); ?></td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="/admin/bookings/edit?id=<?php echo (int)$b['booking_id']; ?>">
                                    Edit
                                </a>
                                <form method="post" action="/admin/bookings/delete" style="display:inline;">
                                    <?= \App\Framework\Csrf::inputField() ?>
                                    <input type="hidden" name="booking_id" value="<?php echo (int)$b['booking_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this booking?');">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>