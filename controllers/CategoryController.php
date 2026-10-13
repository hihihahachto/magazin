<?php
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    addCategory($pdo, $_POST['name']);
    $message = "Категория добавлена";
}

if (isset($_GET['delete'])) {
    $message = deleteCategory($pdo, $_GET['delete']);
}

$categories = getCategories($pdo);

require 'views/categories.php';