<?php
/** @var array $egg */
/** @var array $errors */

ob_start();
?>
<div class="page-header">
    <div>
        <h1>Edit Batch</h1>
        <p><?= htmlspecialchars($egg['batch_label']) ?></p>
    </div>
    <a href="/eggs/<?= $egg['id'] ?>" class="btn btn-secondary">← Cancel</a>
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

<div class="card" style="padding:2rem">
    <form method="POST" action="/eggs/<?= $egg['id'] ?>/update">
        <div class="form-grid">

            <div class="form-group">
                <label for="egg_type">Egg Type</label>
                <select name="egg_type" id="egg_type" required>
                    <?php foreach (['quail' => '🪺 Quail Eggs', 'white' => '🥚 White Eggs', 'brown' => '🥚 Brown Eggs'] as $val => $label): ?>
                        <option value="<?= $val ?>" <?= $egg['egg_type'] === $val ? 'selected' : '' ?>>
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
                       value="<?= htmlspecialchars($egg['batch_label']) ?>" required>
                <?php if (!empty($errors['batch_label'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errors['batch_label'][0]) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity (pieces)</label>
                <input type="number" name="quantity" id="quantity" min="1" max="99999"
                       value="<?= htmlspecialchars((string)$egg['quantity']) ?>" required>
                <?php if (!empty($errors['quantity'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errors['quantity'][0]) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="unit_price">Unit Price (₱)</label>
                <input type="number" name="unit_price" id="unit_price" min="0" step="0.01"
                       value="<?= htmlspecialchars((string)$egg['unit_price']) ?>" required>
                <?php if (!empty($errors['unit_price'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errors['unit_price'][0]) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group full">
                <label for="notes">Notes</label>
                <textarea name="notes" id="notes"><?= htmlspecialchars($egg['notes'] ?? '') ?></textarea>
            </div>

        </div>

        <div style="margin-top:1.75rem;display:flex;gap:1rem">
            <button type="submit" class="btn btn-primary">Update Batch</button>
            <a href="/eggs" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/wrap.php';
echo layout($content, 'Edit Batch');
