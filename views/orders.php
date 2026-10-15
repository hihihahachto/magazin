<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Заказы</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h1>Список заказов</h1>
<a href="index.php">Назад</a> |
<a href="index.php?page=order_create">Новый заказ</a>

<?php if ($message != "") { ?>
    <p><b><?php echo htmlspecialchars($message); ?></b></p>
<?php } ?>

<p>Отбор:</p>
<form method="get">
    <input type="hidden" name="page" value="orders">
    <select name="fs">
        <option value="">любой статус</option>
        <option value="новый">новый</option>
        <option value="в обработке">в обработке</option>
        <option value="доставлен">доставлен</option>
        <option value="отменён">отменён</option>
    </select>
    <select name="fc">
        <option value="">любой покупатель</option>
        <?php foreach ($customers as $c) { ?>
            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
        <?php } ?>
    </select>
    с <input type="date" name="from"> по <input type="date" name="to">
    <button type="submit">Отобрать</button>
    <a href="index.php?page=orders">Сбросить</a>
</form>

<table>
    <tr>
        <th>№</th><th>Покупатель</th><th>Дата</th>
        <th>Сумма</th><th>Статус</th><th>Действия</th>
    </tr>
    <?php foreach ($orders as $o) { ?>
    <tr>
        <td><?php echo $o['id']; ?></td>
        <td>
            <?php
            if ($o['customer_name'] != null) {
                echo htmlspecialchars($o['customer_name']);
            } else {
                echo "—";
            }
            ?>
        </td>
        <td><?php echo $o['order_date']; ?></td>
        <td><?php echo $o['total_amount']; ?></td>
        <td><?php echo htmlspecialchars($o['status']); ?></td>
        <td>
            <a href="index.php?page=order_details&id=<?php echo $o['id']; ?>">Открыть</a>
            <br>
            <form method="get" style="display:inline">
                <input type="hidden" name="page" value="orders">
                <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                <select name="status">
                    <option value="новый">новый</option>
                    <option value="в обработке">в обработке</option>
                    <option value="доставлен">доставлен</option>
                    <option value="отменён">отменён</option>
                </select>
                <button type="submit">ОК</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>
</body>
</html>