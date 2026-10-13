<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Категории</title></head>
<body>
<h1>Категории товаров</h1>
<a href="index.php">Назад</a>

<?php if ($message != "") { ?>
    <p><b><?php echo htmlspecialchars($message); ?></b></p>
<?php } ?>

<form method="post">
    <input type="text" name="name" placeholder="Название категории" required>
    <button type="submit">Записать</button>
</form>

<table border="1" cellpadding="5">
    <tr><th>№</th><th>Категория</th><th></th></tr>
    <?php foreach ($categories as $c) { ?>
    <tr>
        <td><?php echo $c['id']; ?></td>
        <td><?php echo htmlspecialchars($c['name']); ?></td>
        <td><a href="index.php?page=categories&delete=<?php echo $c['id']; ?>">Убрать</a></td>
    </tr>
    <?php } ?>
</table>
</body>
</html>