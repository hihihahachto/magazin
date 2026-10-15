<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Новый заказ</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h1>Оформление заказа</h1>
<a href="index.php">Назад</a>

<?php if ($error != "") { ?>
    <p><b><?php echo htmlspecialchars($error); ?></b></p>
<?php } ?>

<form method="post">
    <p>
        Покупатель:
        <select name="customer_id" required>
            <option value="">выбери</option>
            <?php foreach ($customers as $c) { ?>
                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
            <?php } ?>
        </select>
    </p>

    <table>
        <tr><th>Наименование</th><th>Стоимость</th><th>Есть</th><th>Берём</th></tr>
        <?php foreach ($products as $p) { ?>
        <tr>
            <td><?php echo htmlspecialchars($p['name']); ?></td>
            <td><?php echo $p['price']; ?></td>
            <td><?php echo $p['stock']; ?></td>
            <td><input type="number" name="qty[<?php echo $p['id']; ?>]" value="0" min="0"></td>
        </tr>
        <?php } ?>
    </table>

    <p><button type="submit">Оформить</button></p>
</form>
</body>
</html>