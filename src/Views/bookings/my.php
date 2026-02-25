<?php require __DIR__ . '/../partials/header.php'; ?>

<h1 class="mb-3">My bookings</h1>

<?php if (count($bookings) === 0): ?>
    <div class="alert alert-secondary">You have no bookings yet.</div>
<?php else: ?>
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
                        -
                        <?php echo htmlspecialchars($b['end_time']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($b['court_name']); ?></td>
                    <td><?php echo htmlspecialchars($b['court_location']); ?></td>
                    <td>
                        <form method="post" action="/cancel-booking" style="display:inline;">
                            <input type="hidden" name="booking_id" value="<?php echo (int)$b['booking_id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>