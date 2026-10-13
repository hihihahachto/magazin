<?php
require_once 'db.php';
require_once 'models.php';

$page = "";
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

switch ($page) {
    case "categories":
        require 'controllers/CategoryController.php';
        break;

    case "products":
        require 'controllers/ProductController.php';
        break;

    case "customers":
        require 'controllers/CustomerController.php';
        break;

    case "orders":
    case "order_create":
    case "order_details":
        require 'controllers/OrderController.php';
        break;

    case "reports":
        require 'controllers/ReportController.php';
        break;

    default:
        require 'views/home.php';
        break;
}