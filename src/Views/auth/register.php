<?php require __DIR__ . '/../partials/header.php'; ?>

<h1>Register</h1>

<form method="post" action="/register">
    <div>
        <label>Name</label><br>
        <input type="text" name="name" required>
    </div>

    <div>
        <label>Email</label><br>
        <input type="email" name="email" required>
    </div>

    <div>
        <label>Password</label><br>
        <input type="password" name="password" required>
    </div>

    <button type="submit">Create account</button>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>
