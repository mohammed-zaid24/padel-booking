<?php require __DIR__ . '/../partials/header.php'; ?>

<h1>Login</h1>

<form method="post" action="/login">
    <div>
        <label>Email</label><br>
        <input type="email" name="email" required>
    </div>

    <div>
        <label>Password</label><br>
        <input type="password" name="password" required>
    </div>

    <button type="submit">Login</button>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>
