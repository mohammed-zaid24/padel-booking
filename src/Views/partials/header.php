<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Padel Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Padel Booking</a>

            <div>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex flex-row gap-3">

                    <li class="nav-item">
                        <a class="nav-link text-white" href="/">Home</a>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): ?>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="/my-bookings">My bookings</a>
                        </li>

                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="/admin">Admin</a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item text-white">
                            <span class="nav-link text-white">
                                Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </span>
                        </li>

                        <li class="nav-item">
                            <form method="post" action="/logout">
                                <?= \App\Framework\Csrf::inputField() ?>
                                <button class="btn btn-sm btn-outline-light">Logout</button>
                            </form>
                        </li>

                    <?php else: ?>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="/register">Register</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="/login">Login</a>
                        </li>

                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>

    <hr>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($_SESSION['flash_success']); ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($_SESSION['flash_error']); ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>
</header>
