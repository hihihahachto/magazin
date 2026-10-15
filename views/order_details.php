<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Заказ</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h1>Заказ №<?php echo $order['id']; ?></h1>
<a href="index.php?page=orders">К списку</a>
<a href="index.php">Назад</a>

<p><b>Покупатель:</b> <?php echo htmlspecialchars($order['customer_name']); ?></p>
<p><b>Оформлен:</b> <?php echo $order['order_date']; ?></p>
<p><b>Состояние:</b> <?php echo htmlspecialchars($order['status']); ?></p>

<p>Состав:</p>
<table>
    <tr><th>Наименование</th><th>Кол-во</th><th>Стоимость</th><th>Всего</th></tr>
    <?php foreach ($order['items'] as $item) { ?>
    <tr>
        <td>
            <?php
            if ($item['product_name'] != null) {
                echo htmlspecialchars($item['product_name']);
            } else {
                echo "—";
            }
            ?>
        </td>
        <td><?php echo $item['quantity']; ?></td>
        <td><?php echo $item['price_at_purchase']; ?></td>
        <td><?php echo $item['quantity'] * $item['price_at_purchase']; ?></td>
    </tr>
    <?php } ?>
</table>

<p><b>К оплате:</b> <?php echo $order['total_amount']; ?> руб.</p>
</body>
</html>