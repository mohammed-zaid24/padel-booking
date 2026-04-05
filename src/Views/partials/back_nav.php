<?php
$backHref = $backHref ?? 'javascript:history.back()';
$isAdmin = (($_SESSION['user_role'] ?? '') === 'admin');
$ctaHref = $isAdmin ? '/admin' : '/';
$ctaLabel = $isAdmin ? 'Back to Admin Dashboard' : 'Back to home page';
?>

<div class="d-flex gap-2 mb-3">
    <a class="btn btn-outline-secondary btn-sm" href="<?php echo htmlspecialchars($backHref); ?>">← Back</a>
    <a class="btn btn-outline-primary btn-sm" href="<?php echo htmlspecialchars($ctaHref); ?>"><?php echo htmlspecialchars($ctaLabel); ?></a>
</div>

<?php unset($backHref); ?>
