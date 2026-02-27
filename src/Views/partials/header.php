<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Padel Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    <style>
        :root { --bs-body-bg: #f8f9fa; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background: #f8f9fa; }
        .navbar { box-shadow: 0 2px 8px rgba(0,0,0,.15); padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .navbar .nav-link, .navbar .btn { transition: opacity 0.2s ease, color 0.2s ease; }
        .navbar .nav-link:hover, .navbar .btn:hover { opacity: 0.9; }
        .card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.1); }
        .btn { transition: transform 0.15s ease, box-shadow 0.15s ease; }
        .btn:hover { transform: translateY(-1px); }
        a:not(.btn):not(.nav-link) { transition: color 0.2s ease; }
        main { min-height: 60vh; }
        footer { margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #dee2e6; }
    </style>
</head>
<body class="bg-light">
<div class="navbar-wrapper">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded mx-3 mt-3">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="/">Padel Booking</a>

                <div>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex flex-row gap-3 align-items-center">

                        <?php if (($_SESSION['user_role'] ?? '') !== 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="/">Home</a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['user_id'])): ?>

                            <?php if (($_SESSION['user_role'] ?? '') === 'user'): ?>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="/courts">Courts</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-outline-light btn-sm" href="/my-bookings">My bookings</a>
                                </li>
                            <?php endif; ?>

                            <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
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
                                    <button class="btn btn-sm btn-outline-light" type="submit">Logout</button>
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

        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0" role="alert">
                <?php echo htmlspecialchars($_SESSION['flash_success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3 mb-0" role="alert">
                <?php echo htmlspecialchars($_SESSION['flash_error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>
    </header>
</div>

<main class="container py-4">
