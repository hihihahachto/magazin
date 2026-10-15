<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Склад</title></head>
<body>
<h1>Склад</h1>
<a href="index.php">Назад</a>

<?php if ($message != "") { ?>
    <p><b><?php echo htmlspecialchars($message); ?></b></p>
<?php } ?>

<p>Отбор:</p>
<form method="get">
    <input type="hidden" name="page" value="products">

    <p>
        Категория:
        <select name="fc">
            <option value="">любая</option>
            <?php foreach ($categories as $c) { ?>
                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
            <?php } ?>
        </select>
    </p>

    <p>
        <label><input type="checkbox" name="fs" value="1"> есть на складе</label>
    </p>

    <p>
        Цена от <input type="number" step="0.01" name="min">
        до <input type="number" step="0.01" name="max">
    </p>

    <p>
        <button type="submit">Отобрать</button>
        <a href="index.php?page=products">Сбросить</a>
    </p>
</form>

<p>
    <?php
    if ($edit != null) {
        echo "Правка позиции";
    } else {
        echo "Новая позиция";
    }
    ?>
</p>

<form method="post">
    <input type="hidden" name="id" value="<?php if ($edit != null) echo $edit['id']; else echo 0; ?>">

    <input type="text" name="name" placeholder="Наименование" required
           value="<?php if ($edit != null) echo htmlspecialchars($edit['name']); ?>">

    <input type="number" step="0.01" name="price" placeholder="Стоимость" required
           value="<?php if ($edit != null) echo $edit['price']; ?>">

    <input type="number" name="stock" placeholder="Кол-во" required
           value="<?php if ($edit != null) echo $edit['stock']; ?>">

    <select name="category_id" required>
        <option value="">категория</option>
        <?php foreach ($categories as $c) { ?>
            <option value="<?php echo $c['id']; ?>"
                <?php if ($edit != null && $edit['category_id'] == $c['id']) echo "selected"; ?>>
                <?php echo htmlspecialchars($c['name']); ?>
            </option>
        <?php } ?>
    </select>

    <button type="submit">Записать</button>
</form>

<table border="1" cellpadding="5">
    <tr>
        <th>№</th><th>Наименование</th><th>Стоимость</th>
        <th>Кол-во</th><th>Категория</th><th></th>
    </tr>
    <?php foreach ($products as $p) { ?>
    <tr>
        <td><?php echo $p['id']; ?></td>
        <td><?php echo htmlspecialchars($p['name']); ?></td>
        <td><?php echo $p['price']; ?></td>
        <td><?php echo $p['stock']; ?></td>
        <td>
            <?php
            if ($p['category_name'] != null) {
                echo htmlspecialchars($p['category_name']);
            } else {
                echo "—";
            }
            ?>
        </td>
        <td>
            <a href="index.php?page=products&edit=<?php echo $p['id']; ?>">Правка</a> |
            <a href="index.php?page=products&delete=<?php echo $p['id']; ?>">Убрать</a>
        </td>
    </tr>
    <?php } ?>
</table>
</body>
</html>