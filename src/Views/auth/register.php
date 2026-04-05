<?php require __DIR__ . '/../partials/header.php'; ?>

<?php require __DIR__ . '/../partials/back_nav.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h3 mb-4 text-center">Register</h1>

                    <form method="post" action="/register">
                        <?= \App\Framework\Csrf::inputField() ?>

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input class="form-control" type="text" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input class="form-control" type="password" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Create account</button>
                    </form>
                    <p class="mt-3 text-center">
                        Already have an account? <a href="/login" class="text-decoration-none">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
