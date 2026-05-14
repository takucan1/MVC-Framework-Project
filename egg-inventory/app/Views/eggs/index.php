<?php
/** @var array $eggs */
/** @var array $summary */

$types = ['quail' => 0, 'white' => 0, 'brown' => 0];
$batches = ['quail' => 0, 'white' => 0, 'brown' => 0];
foreach ($summary as $row) {
    $t = $row['egg_type'];
    $types[$t]   = (int)$row['total_quantity'];
    $batches[$t] = (int)$row['batch_count'];
}
$totalAll = array_sum($types);

ob_start();
?>
<div class="page-header">
    <div>
        <h1>Egg Inventory</h1>
        <p><?= count($eggs) ?> batch<?= count($eggs) !== 1 ? 'es' : '' ?> · <?= number_format($totalAll) ?> total eggs</p>
    </div>
    <a href="/eggs/create" class="btn btn-primary">＋ Add Batch</a>
</div>

<!-- Stock summary -->
<div class="summary-grid">
    <div class="summary-chip quail">
        <span class="chip-label">🪺 Quail Eggs</span>
        <span class="chip-qty"><?= number_format($types['quail']) ?></span>
        <span class="chip-sub"><?= $batches['quail'] ?> batch<?= $batches['quail'] !== 1 ? 'es' : '' ?></span>
    </div>
    <div class="summary-chip white">
        <span class="chip-label">🥚 White Eggs</span>
        <span class="chip-qty"><?= number_format($types['white']) ?></span>
        <span class="chip-sub"><?= $batches['white'] ?> batch<?= $batches['white'] !== 1 ? 'es' : '' ?></span>
    </div>
    <div class="summary-chip brown">
        <span class="chip-label">🥚 Brown Eggs</span>
        <span class="chip-qty"><?= number_format($types['brown']) ?></span>
        <span class="chip-sub"><?= $batches['brown'] ?> batch<?= $batches['brown'] !== 1 ? 'es' : '' ?></span>
    </div>
</div>

<!-- Batch table -->
<div class="card">
<?php if (empty($eggs)): ?>
    <div class="empty-state">
        <div class="icon">🥚</div>
        <strong>No egg batches yet.</strong>
        <p><a href="/eggs/create" style="color:var(--gold)">Add your first batch</a> to get started.</p>
    </div>
<?php else: ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Type</th>
                <th>Batch Label</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Value</th>
                <th>Added</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($eggs as $egg): ?>
            <tr>
                <td class="text-muted"><?= $egg['id'] ?></td>
                <td>
                    <span class="badge badge-<?= htmlspecialchars($egg['egg_type']) ?>">
                        <?= ucfirst(htmlspecialchars($egg['egg_type'])) ?>
                    </span>
                </td>
                <td><strong><?= htmlspecialchars($egg['batch_label']) ?></strong></td>
                <td><?= number_format((int)$egg['quantity']) ?></td>
                <td>₱<?= number_format((float)$egg['unit_price'], 2) ?></td>
                <td>₱<?= number_format((float)$egg['unit_price'] * (int)$egg['quantity'], 2) ?></td>
                <td class="text-muted" style="font-size:.82rem"><?= date('M j, Y', strtotime($egg['created_at'])) ?></td>
                <td>
                    <div class="actions">
                        <a href="/eggs/<?= $egg['id'] ?>" class="btn btn-secondary btn-sm">View</a>
                        <a href="/eggs/<?= $egg['id'] ?>/edit" class="btn btn-secondary btn-sm">Edit</a>
                        <form method="POST" action="/eggs/<?= $egg['id'] ?>/delete" style="display:inline"
                              onsubmit="return confirm('Delete this batch?')">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/wrap.php';
echo layout($content, 'Inventory');
