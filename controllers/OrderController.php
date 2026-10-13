<?php
$page = $_GET['page'];

if ($page == "orders") {
    $message = "";


    if (isset($_GET['status']) && isset($_GET['order_id'])) {
        $message = updateOrderStatus($pdo, (int)$_GET['order_id'], $_GET['status']);
    }

    
    $orders = getOrders($pdo); // по умолчанию все

    if (isset($_GET['fs']) && $_GET['fs'] != "") {
        // по статусу
        $orders = filterOrdersByStatus($pdo, $_GET['fs']);
    } else if (isset($_GET['fc']) && $_GET['fc'] != "") {
        // по клиенту
        $orders = filterOrdersByCustomer($pdo, (int)$_GET['fc']);
    } else if (isset($_GET['from']) && $_GET['from'] != "" && isset($_GET['to']) && $_GET['to'] != "") {
        // по датам
        $orders = filterOrdersByDate($pdo, $_GET['from'], $_GET['to']);
    }

    $customers = getCustomers($pdo);
    require 'views/orders.php';
}


else if ($page == "order_create") {
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $customer_id = $_POST['customer_id'];
        $items = [];

        
        foreach ($_POST['qty'] as $product_id => $quantity) {
            if ($quantity > 0) {
                $items[] = [
                    'product_id' => $product_id,
                    'quantity' => $quantity
                ];
            }
        }

        if (count($items) == 0) {
            $error = "Выбери хотя бы один товар";
        } else {
            $result = createOrder($pdo, $customer_id, $items);

            if (is_numeric($result)) {
                
                header("Location: index.php?page=order_details&id=" . $result);
                exit;
            } else {
                $error = $result;
            }
        }
    }

    $customers = getCustomers($pdo);
    $products = getProducts($pdo);
    require 'views/order_create.php';
}


else if ($page == "order_details") {
    $id = (int)$_GET['id'];
    $order = getOrderDetails($pdo, $id);

    if ($order == null) {
        echo "Заказ не найден";
        exit;
    }

    require 'views/order_details.php';
}