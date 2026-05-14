<?php
/** @var array $errors */
/** @var array $old */

ob_start();
?>
<div class="page-header">
    <div>
        <h1>Add New Batch</h1>
        <p>Record a new egg batch into the inventory.</p>
    </div>
    <a href="/eggs" class="btn btn-secondary">← Back</a>
</div>

<?php if (!empty($errors)): ?>
<div class="error-box">
    <?php foreach ($errors as $fieldErrors): ?>
        <?php foreach ($fieldErrors as $msg): ?>
            <div>• <?= htmlspecialchars($msg) ?></div>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="card" style="padding: 2rem">
    <form method="POST" action="/eggs">
        <div class="form-grid">

            <div class="form-group">
                <label for="egg_type">Egg Type</label>
                <select name="egg_type" id="egg_type" required>
                    <option value="">— Select type —</option>
                    <?php foreach (['quail' => '🪺 Quail Eggs', 'white' => '🥚 White Eggs', 'brown' => '🥚 Brown Eggs'] as $val => $label): ?>
                        <option value="<?= $val ?>" <?= ($old['egg_type'] ?? '') === $val ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['egg_type'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errors['egg_type'][0]) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="batch_label">Batch Label</label>
                <input type="text" name="batch_label" id="batch_label"
                       placeholder="e.g. Batch #001 — Farm A"
                       value="<?= htmlspecialchars($old['batch_label'] ?? '') ?>" required>
                <?php if (!empty($errors['batch_label'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errors['batch_label'][0]) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity (pieces)</label>
                <input type="number" name="quantity" id="quantity" min="1" max="99999"
                       placeholder="e.g. 500"
                       value="<?= htmlspecialchars($old['quantity'] ?? '') ?>" required>
                <?php if (!empty($errors['quantity'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errors['quantity'][0]) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="unit_price">Unit Price (₱ per egg)</label>
                <input type="number" name="unit_price" id="unit_price" min="0" step="0.01"
                       placeholder="e.g. 3.50"
                       value="<?= htmlspecialchars($old['unit_price'] ?? '') ?>" required>
                <?php if (!empty($errors['unit_price'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errors['unit_price'][0]) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group full">
                <label for="notes">Notes (optional)</label>
                <textarea name="notes" id="notes" placeholder="Supplier, farm origin, expiry date…"><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
            </div>

        </div>

        <div style="margin-top:1.75rem; display:flex; gap:1rem">
            <button type="submit" class="btn btn-primary">Save Batch</button>
            <a href="/eggs" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/wrap.php';
echo layout($content, 'Add Batch');
