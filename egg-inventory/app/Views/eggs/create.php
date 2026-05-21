<h1>Add Egg</h1>

<?php if (!empty($errors)): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="/eggs/create">
    <label>Type:</label>
    <input type="text" name="type">
    <br>
    <label>Quantity:</label>
    <input type="number" name="quantity">
    <br>
    <button type="submit">Save</button>
</form>

<a href="/eggs">Back to list</a>
