<?php require __DIR__ . '/../partials/header.php'; ?>

<?php require __DIR__ . '/../partials/back_nav.php'; ?>

<?php $prefillEmail = $_SESSION['login_prefill_email'] ?? ''; ?>
<?php if (isset($_SESSION['login_prefill_email'])) { unset($_SESSION['login_prefill_email']); } ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h3 mb-4 text-center">Login</h1>

                    <?php if ($prefillEmail !== ''): ?>
                        <div class="alert alert-info" role="alert">
                            Your account is ready. Just enter your password to continue.
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/login">
                        <?= \App\Framework\Csrf::inputField() ?>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($prefillEmail); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input class="form-control" type="password" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
