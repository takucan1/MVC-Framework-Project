<h1>Egg Inventory</h1>

<a href="/eggs/create">Add Egg</a>

<ul>
<?php foreach ($eggs as $egg): ?>
    <?php $id = (int) $egg['id']; ?>
    <li>
        <strong><?= htmlspecialchars($egg['type']) ?></strong> 
        — Quantity: <?= htmlspecialchars($egg['quantity']) ?>
        — Date: <?= htmlspecialchars($egg['date'] ?? 'N/A') ?>
        |
        <a href="/eggs/show?id=<?= $id ?>">View</a>
        <a href="/eggs/edit?id=<?= $id ?>">Edit</a>
        <a href="/eggs/delete?id=<?= $id ?>">Delete</a>
    </li>
<?php endforeach; ?>
</ul>
