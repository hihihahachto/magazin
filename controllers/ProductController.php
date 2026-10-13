<?php
$message = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['id'] == 0) {
        addProduct($pdo, $_POST['name'], $_POST['price'], $_POST['stock'], $_POST['category_id']);
        $message = "Товар добавлен";
    } else {
        updateProduct($pdo, $_POST['id'], $_POST['name'], $_POST['price'], $_POST['stock'], $_POST['category_id']);
        $message = "Товар обновлён";
    }
}


if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $message = deleteProduct($pdo, $id);
}

// фильтры 
$fc = "";
$fs = "";
$min = "";
$max = "";

if (isset($_GET['fc'])) { $fc = $_GET['fc']; }
if (isset($_GET['fs'])) { $fs = $_GET['fs']; }
if (isset($_GET['min'])) { $min = $_GET['min']; }
if (isset($_GET['max'])) { $max = $_GET['max']; }

$products = filterProducts($pdo, $fc, $fs, $min, $max);

$edit = null;
if (isset($_GET['edit'])) {
    $edit = getProduct($pdo, (int)$_GET['edit']);
}

$categories = getCategories($pdo);

require 'views/products.php';