<?php
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['id'] == 0) {
        addCustomer($pdo, $_POST['name'], $_POST['email'], $_POST['phone']);
        $message = "Клиент добавлен";
    } else {
        updateCustomer($pdo, $_POST['id'], $_POST['name'], $_POST['email'], $_POST['phone']);
        $message = "Клиент обновлён";
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $message = deleteCustomer($pdo, $id);
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = getCustomer($pdo, (int)$_GET['edit']);
}

$customers = getCustomers($pdo);

require 'views/customers.php';