<?php
/** @var array $egg */

ob_start();
?>
<div class="page-header">
    <div>
        <h1><?= htmlspecialchars($egg['batch_label']) ?></h1>
        <p>Batch detail</p>
    </div>
    <div style="display:flex;gap:.75rem">
        <a href="/eggs/<?= $egg['id'] ?>/edit" class="btn btn-primary">Edit Batch</a>
        <a href="/eggs" class="btn btn-secondary">← Back</a>
    </div>
</div>

<div class="card" style="padding:2rem">
    <div class="detail-grid">
        <div class="detail-item">
            <span class="dl">Egg Type</span>
            <span class="dd">
                <span class="badge badge-<?= htmlspecialchars($egg['egg_type']) ?>">
                    <?= ucfirst(htmlspecialchars($egg['egg_type'])) ?>
                </span>
            </span>
        </div>
        <div class="detail-item">
            <span class="dl">Batch Label</span>
            <span class="dd"><?= htmlspecialchars($egg['batch_label']) ?></span>
        </div>
        <div class="detail-item">
            <span class="dl">Quantity</span>
            <span class="dd" style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--brown)">
                <?= number_format((int)$egg['quantity']) ?> <span style="font-size:.9rem;color:var(--muted)">eggs</span>
            </span>
        </div>
        <div class="detail-item">
            <span class="dl">Unit Price</span>
            <span class="dd">₱<?= number_format((float)$egg['unit_price'], 2) ?> per egg</span>
        </div>
        <div class="detail-item">
            <span class="dl">Total Value</span>
            <span class="dd" style="font-weight:600;color:var(--green)">
                ₱<?= number_format((float)$egg['unit_price'] * (int)$egg['quantity'], 2) ?>
            </span>
        </div>
        <div class="detail-item">
            <span class="dl">Date Added</span>
            <span class="dd"><?= date('F j, Y g:i A', strtotime($egg['created_at'])) ?></span>
        </div>
        <?php if (!empty($egg['notes'])): ?>
        <div class="detail-item full" style="grid-column:1/-1">
            <span class="dl">Notes</span>
            <span class="dd"><?= htmlspecialchars($egg['notes']) ?></span>
        </div>
        <?php endif; ?>
    </div>

    <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--warm);display:flex;gap:1rem">
        <a href="/eggs/<?= $egg['id'] ?>/edit" class="btn btn-primary">Edit</a>
        <form method="POST" action="/eggs/<?= $egg['id'] ?>/delete"
              onsubmit="return confirm('Permanently delete this batch?')">
            <button type="submit" class="btn btn-danger">Delete Batch</button>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/wrap.php';
echo layout($content, htmlspecialchars($egg['batch_label']));
