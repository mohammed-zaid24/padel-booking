<?php require __DIR__ . '/../partials/header.php'; ?>

<h1>All Bookings</h1>

<?php if (count($bookings) === 0): ?>
    <p>No bookings found.</p>
<?php else: ?>
    <table border="1" cellpadding="6">
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
                        <form method="post" action="/admin/bookings/delete" style="display:inline;">
                            <input type="hidden" name="booking_id" value="<?php echo (int)$b['booking_id']; ?>">
                            <button type="submit" onclick="return confirm('Delete this booking?');">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>