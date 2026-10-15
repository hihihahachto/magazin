<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Покупатели</title></head>
<body>
<h1>Покупатели</h1>
<a href="index.php">Назад</a>

<?php if ($message != "") { ?>
    <p><b><?php echo htmlspecialchars($message); ?></b></p>
<?php } ?>

<p>
    <?php
    if ($edit != null) {
        echo "Правка данных покупателя";
    } else {
        echo "Новый покупатель";
    }
    ?>
</p>
<form method="post">
    <input type="hidden" name="id" value="<?php if ($edit != null) echo $edit['id']; else echo 0; ?>">

    <input type="text" name="name" placeholder="Имя" required
        value="<?php if ($edit != null) echo htmlspecialchars($edit['name']); ?>">

    <input type="email" name="email" placeholder="Почта" required
        value="<?php if ($edit != null) echo htmlspecialchars($edit['email']); ?>">

    <input type="text" name="phone" placeholder="Телефон"
        value="<?php if ($edit != null) echo htmlspecialchars($edit['phone']); ?>">

    <button type="submit">Записать</button>
</form>

<table border="1" cellpadding="5">
    <tr><th>№</th><th>ФИО</th><th>Почта</th><th>Телефон</th><th></th></tr>
    <?php foreach ($customers as $c) { ?>
    <tr>
        <td><?php echo $c['id']; ?></td>
        <td><?php echo htmlspecialchars($c['name']); ?></td>
        <td><?php echo htmlspecialchars($c['email']); ?></td>
        <td><?php echo htmlspecialchars($c['phone']); ?></td>
        <td>
            <a href="index.php?page=customers&edit=<?php echo $c['id']; ?>">Изменить</a> |
            <a href="index.php?page=customers&delete=<?php echo $c['id']; ?>">Убрать</a>
        </td>
    </tr>
    <?php } ?>
</table>
</body>
</html>