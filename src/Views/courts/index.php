<?php require __DIR__ . '/../partials/header.php'; ?>

<?php
$breadcrumbs = [
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Courts', 'url' => null],
];
require __DIR__ . '/../partials/breadcrumb.php';
?>

<?php require __DIR__ . '/../partials/back_nav.php'; ?>

<h1 class="mb-4">Courts</h1>

<?php if (empty($courts)): ?>
    <div class="card border-0 bg-light">
        <div class="card-body text-center py-5">
            <h2 class="h5 text-muted mb-2">No courts available yet</h2>
            <p class="text-muted mb-3">Courts will appear here once they are added. Check back soon or contact the club.</p>
            <a class="btn btn-primary" href="/">Back to home</a>
        </div>
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($courts as $court): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h2 class="h5 card-title mb-2"><?php echo htmlspecialchars($court->name); ?></h2>
                        <p class="card-text text-muted small mb-3 flex-grow-1"><?php echo htmlspecialchars($court->location); ?></p>
                        <a class="btn btn-primary btn-sm align-self-start" href="/courts/<?php echo (int)$court->id; ?>">View availability</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
