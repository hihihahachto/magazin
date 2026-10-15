<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Сводка</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h1>Сводка по магазину</h1>
<a href="index.php">Назад</a>

<p>1. Продано на сумму (доставленные):</p>
<p><b><?php echo number_format($totalRevenue, 2, '.', ' '); ?> руб.</b></p>

<p>2. Продажи по категориям:</p>
<table>
    <tr><th>Категория</th><th>Сумма</th></tr>
    <?php foreach ($revenueByCategory as $r) { ?>
    <tr>
        <td><?php echo htmlspecialchars($r['name']); ?></td>
        <td><?php echo number_format($r['itog'], 2, '.', ' '); ?></td>
    </tr>
    <?php } ?>
</table>

<p>3. Хиты продаж (топ-5):</p>
<table>
    <tr><th>Наименование</th><th>Кол-во</th></tr>
    <?php foreach ($top5Products as $r) { ?>
    <tr>
        <td><?php echo htmlspecialchars($r['name']); ?></td>
        <td><?php echo $r['itog']; ?></td>
    </tr>
    <?php } ?>
</table>

<p>4. Лучшие покупатели (топ-5):</p>
<table>
    <tr><th>ФИО</th><th>Сумма</th></tr>
    <?php foreach ($top5Customers as $r) { ?>
    <tr>
        <td><?php echo htmlspecialchars($r['name']); ?></td>
        <td><?php echo number_format($r['itog'], 2, '.', ' '); ?></td>
    </tr>
    <?php } ?>
</table>

<p>5. Сколько заказов в каждом состоянии:</p>
<table>
    <tr><th>Состояние</th><th>Штук</th></tr>
    <?php foreach ($statusStats as $r) { ?>
    <tr>
        <td><?php echo htmlspecialchars($r['status']); ?></td>
        <td><?php echo $r['itog']; ?></td>
    </tr>
    <?php } ?>
</table>

<p>6. Средний чек (доставленные):</p>
<p><b><?php echo number_format($averageCheck, 2, '.', ' '); ?> руб.</b></p>

</body>
</html>