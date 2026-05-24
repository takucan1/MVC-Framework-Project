<h1>Edit Egg</h1>

<?php if (empty($egg)): ?>
    <p>Egg not found.</p>
    <a href="/eggs">Back to list</a>
    <?php return; ?>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="/eggs/edit?id=<?= $egg['id'] ?>">
    <input type="hidden" name="id" value="<?= $egg['id'] ?>">

    <label>Type:</label>
    <input type="text" name="type" value="<?= htmlspecialchars($egg['type']) ?>">
    <br>

    <label>Quantity:</label>
    <input type="number" name="quantity" value="<?= htmlspecialchars($egg['quantity']) ?>">
    <br>

    <button type="submit">Update</button>
</form>

<a href="/eggs">Back to list</a>
