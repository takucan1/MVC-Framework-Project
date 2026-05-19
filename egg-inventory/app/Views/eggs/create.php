<h1>Add Egg</h1>
<form method="POST" action="/eggs/create">
    <label>Type:</label>
    <input type="text" name="type" required>
    <br>
    <label>Quantity:</label>
    <input type="number" name="quantity" min="1" required>
    <br>
    <button type="submit">Save</button>
</form>
<a href="/eggs">Back to list</a>
