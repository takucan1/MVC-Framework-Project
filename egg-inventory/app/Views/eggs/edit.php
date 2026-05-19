<h1>Edit Egg</h1>
<form method="POST" action="/eggs/edit?id=<?= $_GET['id'] ?>">
    <label>Type:</label>
    <input type="text" name="type" value="<?= htmlspecialchars($egg['type']) ?>" required>
    <br>
    <label>Quantity:</label>
    <input type="number" name="quantity" value="<?= htmlspecialchars($egg['quantity']) ?>" min="1" required>
    <br>
    <button type="submit">Update</button>
</form>
<a href="/eggs">Back to list</a>
