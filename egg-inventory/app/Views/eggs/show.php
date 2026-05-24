<h1>Egg Details</h1>
<?php if ($egg): ?>
    <p><strong>Type:</strong> <?= htmlspecialchars($egg['type']) ?></p>
    <p><strong>Quantity:</strong> <?= htmlspecialchars($egg['quantity']) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($egg['date'] ?? 'N/A') ?></p>
<?php else: ?>
    <p>Egg not found.</p>
<?php endif; ?>
<a href="/eggs">Back to list</a>
