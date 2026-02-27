<?php if (!empty($breadcrumbs) && is_array($breadcrumbs)): ?>
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <?php foreach ($breadcrumbs as $i => $item): ?>
            <?php $last = ($i === count($breadcrumbs) - 1); ?>
            <li class="breadcrumb-item<?php echo $last ? ' active' : ''; ?>">
                <?php if (!$last && !empty($item['url'])): ?>
                    <a href="<?php echo htmlspecialchars($item['url']); ?>"><?php echo htmlspecialchars($item['label']); ?></a>
                <?php else: ?>
                    <?php echo htmlspecialchars($item['label']); ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
<?php endif; ?>
