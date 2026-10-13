<?php
require_once 'db.php';

//КАТЕГОРИИ

function getCategories($pdo) {
    $sql = "SELECT * FROM categories ORDER BY id";
    $result = $pdo->query($sql);
    return $result->fetchAll();
}

function getCategory($pdo, $id) {
    $sql = "SELECT * FROM categories WHERE id = '$id'";
    $result = $pdo->query($sql);
    return $result->fetch(); 
}

function addCategory($pdo, $name) {
    $sql = "INSERT INTO categories (name) VALUES ('$name')";
    $pdo->query($sql);
}

function deleteCategory($pdo, $id) {
    $sql = "SELECT COUNT(*) FROM products WHERE category_id = '$id'";
    $result = $pdo->query($sql);
    $count = $result->fetchColumn();

    if ($count > 0) {
        return "Нельзя удалить: в категории есть товары";
    }

    $sql = "DELETE FROM categories WHERE id = '$id'";
    $pdo->query($sql);
    return "Категория удалена";
}

//ТОВАРЫ
function getProducts($pdo) {
    $sql = "SELECT products.id, products.name, products.price, products.stock, products.category_id, categories.name AS category_name
            FROM products
            LEFT JOIN categories ON categories.id = products.category_id
            ORDER BY products.id";
    $result = $pdo->query($sql);
    return $result->fetchAll();
}
function getProduct($pdo, $id) {
    $sql = "SELECT * FROM products WHERE id = '$id'";
    $result = $pdo->query($sql);
    return $result->fetch();
}

function addProduct($pdo, $name, $price, $stock, $category_id) {
    $sql = "INSERT INTO products (name, price, stock, category_id) 
            VALUES ('$name', '$price', '$stock', '$category_id')";
    $pdo->query($sql);
}

function updateProduct($pdo, $id, $name, $price, $stock, $category_id) {
    $sql = "UPDATE products 
            SET name = '$name', price = '$price', stock = '$stock', category_id = '$category_id' 
            WHERE id = '$id'";
    $pdo->query($sql);
}

function deleteProduct($pdo, $id) {
    $sql = "SELECT COUNT(*) FROM order_items WHERE product_id = '$id'";
    $result = $pdo->query($sql);
    $count = $result->fetchColumn();

    if ($count > 0) {
        return "Нельзя удалить: товар входит в заказы";
    }

    $sql = "DELETE FROM products WHERE id = '$id'";
    $pdo->query($sql);
    return "Товар удалён";
}

//КЛИЕНТЫ

function getCustomers($pdo) {
    $sql = "SELECT * FROM customers ORDER BY id";
    $result = $pdo->query($sql);
    return $result->fetchAll();
}

function getCustomer($pdo, $id) {
    $sql = "SELECT * FROM customers WHERE id = '$id'";
    $result = $pdo->query($sql);
    return $result->fetch();
}

function addCustomer($pdo, $name, $email, $phone) {
    $sql = "INSERT INTO customers (name, email, phone) 
            VALUES ('$name', '$email', '$phone')";
    $pdo->query($sql);
}

function updateCustomer($pdo, $id, $name, $email, $phone) {
    $sql = "UPDATE customers 
            SET name = '$name', email = '$email', phone = '$phone' 
            WHERE id = '$id'";
    $pdo->query($sql);
}

function deleteCustomer($pdo, $id) {
    $sql = "SELECT COUNT(*) FROM orders WHERE customer_id = '$id'";
    $result = $pdo->query($sql);
    $count = $result->fetchColumn();

    if ($count > 0) {
        return "Нельзя удалить: у клиента есть заказы";
    }

    $sql = "DELETE FROM customers WHERE id = '$id'";
    $pdo->query($sql);
    return "Клиент удалён";
}

//ЗАКАЗЫ

function getOrders($pdo) {
    $sql = "SELECT orders.id, orders.customer_id, orders.order_date, orders.total_amount, orders.status, customers.name AS customer_name
            FROM orders
            LEFT JOIN customers ON customers.id = orders.customer_id
            ORDER BY orders.id DESC";
    $result = $pdo->query($sql);
    return $result->fetchAll();
}

function getOrderDetails($pdo, $id) {
    $sql = "SELECT orders.id, orders.customer_id, orders.order_date, orders.total_amount, orders.status, customers.name AS customer_name
            FROM orders
            LEFT JOIN customers ON customers.id = orders.customer_id
            WHERE orders.id = '$id'";
    $result = $pdo->query($sql);
    $order = $result->fetch();

    if (!$order) {
        return null;
    }

    $sql = "SELECT order_items.id, order_items.order_id, order_items.product_id, order_items.quantity, order_items.price_at_purchase, products.name AS product_name
            FROM order_items
            LEFT JOIN products ON products.id = order_items.product_id
            WHERE order_items.order_id = '$id'";
    $result = $pdo->query($sql);
    $order['items'] = $result->fetchAll();

    return $order;
}

function createOrder($pdo, $customer_id, $items) {
    try {
        $pdo->beginTransaction();

        // проверяем, что товаров хватает
        foreach ($items as $item) {
            $product_id = $item['product_id'];
            $sql = "SELECT name, stock FROM products WHERE id = '$product_id'";
            $result = $pdo->query($sql);
            $product = $result->fetch();

            if (!$product) {
                throw new Exception("Товар не найден");
            }
            if ($product['stock'] < $item['quantity']) {
                throw new Exception("Не хватает товара: " . $product['name']);
            }
        }

        // создаём заказ
        $sql = "INSERT INTO orders (customer_id) VALUES ('$customer_id')";
        $pdo->query($sql);
        $order_id = $pdo->lastInsertId();

        // позиции + списание + сумма
        $total = 0;
        foreach ($items as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];

            $sql = "SELECT price FROM products WHERE id = '$product_id'";
            $result = $pdo->query($sql);
            $price = $result->fetchColumn();

            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) 
                    VALUES ('$order_id', '$product_id', '$quantity', '$price')";
            $pdo->query($sql);

            $sql = "UPDATE products SET stock = stock - '$quantity' WHERE id = '$product_id'";
            $pdo->query($sql);

            $total = $total + $price * $quantity;
        }

        $sql = "UPDATE orders SET total_amount = '$total' WHERE id = '$order_id'";
        $pdo->query($sql);

        $pdo->commit();
        return $order_id;
    } catch (Exception $e) {
        $pdo->rollBack();
        return $e->getMessage();
    }
}

function updateOrderStatus($pdo, $id, $new_status) {
    $allowed = ['новый', 'в обработке', 'доставлен', 'отменён'];

    if (!in_array($new_status, $allowed)) {
        return "Недопустимый статус";
    }

    try {
        $pdo->beginTransaction();

        $sql = "SELECT status FROM orders WHERE id = '$id'";
        $result = $pdo->query($sql);
        $old_status = $result->fetchColumn();

        if ($old_status == false) {
            throw new Exception("Заказ не найден");
        }

        // при отмене возвращаем остатки
        if ($new_status == 'отменён' && $old_status != 'отменён') {
            $sql = "SELECT product_id, quantity FROM order_items WHERE order_id = '$id'";
            $result = $pdo->query($sql);
            $items = $result->fetchAll();

            foreach ($items as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $sql = "UPDATE products SET stock = stock + '$quantity' WHERE id = '$product_id'";
                $pdo->query($sql);
            }
        }

        $sql = "UPDATE orders SET status = '$new_status' WHERE id = '$id'";
        $pdo->query($sql);

        $pdo->commit();
        return "Статус изменён на «" . $new_status . "»";
    } catch (Exception $e) {
        $pdo->rollBack();
        return "Ошибка: " . $e->getMessage();
    }
}

//ФИЛЬТРЫ
function filterProducts($pdo, $category_id, $in_stock, $min, $max) {
    $sql = "SELECT products.id, products.name, products.price, products.stock, products.category_id, categories.name AS category_name
            FROM products
            LEFT JOIN categories ON categories.id = products.category_id
            WHERE 1=1";

    if ($category_id != "") {
        $sql = $sql . " AND products.category_id = '$category_id'";
    }
    if ($in_stock == "1") {
        $sql = $sql . " AND products.stock > 0";
    }
    if ($min != "" && $max != "") {
        $sql = $sql . " AND products.price BETWEEN '$min' AND '$max'";
    }

    $sql = $sql . " ORDER BY products.id";
    $result = $pdo->query($sql);
    return $result->fetchAll();
}
function filterProductsByCategory($pdo, $category_id) {
    $sql = "SELECT products.id, products.name, products.price, products.stock, products.category_id, categories.name AS category_name
            FROM products
            LEFT JOIN categories ON categories.id = products.category_id
            WHERE products.category_id = '$category_id'
            ORDER BY products.id";
    $result = $pdo->query($sql);
    return $result->fetchAll();
}

function filterProductsInStock($pdo) {
    $sql = "SELECT products.id, products.name, products.price, products.stock, products.category_id, categories.name AS category_name
            FROM products
            LEFT JOIN categories ON categories.id = products.category_id
            WHERE products.stock > 0
            ORDER BY products.id";
    $result = $pdo->query($sql);
    return $result->fetchAll();
}

function filterProductsByPrice($pdo, $min, $max) {
    $sql = "SELECT products.id, products.name, products.price, products.stock, products.category_id, categories.name AS category_name
            FROM products
            LEFT JOIN categories ON categories.id = products.category_id
            WHERE products.price BETWEEN '$min' AND '$max'
            ORDER BY products.id";
    $result = $pdo->query($sql);
    return $result->fetchAll();
}

function filterOrdersByStatus($pdo, $status) {
    $sql = "SELECT orders.id, orders.customer_id, orders.order_date, orders.total_amount, orders.status, customers.name AS customer_name
            FROM orders
            LEFT JOIN customers ON customers.id = orders.customer_id
            WHERE orders.status = '$status'
            ORDER BY orders.id DESC";
    $result = $pdo->query($sql);
    return $result->fetchAll();
}

function filterOrdersByCustomer($pdo, $customer_id) {
    $sql = "SELECT orders.id, orders.customer_id, orders.order_date, orders.total_amount, orders.status, customers.name AS customer_name
            FROM orders
            LEFT JOIN customers ON customers.id = orders.customer_id
            WHERE orders.customer_id = '$customer_id'
            ORDER BY orders.id DESC";
    $result = $pdo->query($sql);
    return $result->fetchAll();
}

function filterOrdersByDate($pdo, $from, $to) {
    $sql = "SELECT orders.id, orders.customer_id, orders.order_date, orders.total_amount, orders.status, customers.name AS customer_name
            FROM orders
            LEFT JOIN customers ON customers.id = orders.customer_id
            WHERE DATE(orders.order_date) BETWEEN '$from' AND '$to'
            ORDER BY orders.id DESC";
    $result = $pdo->query($sql);
    return $result->fetchAll();
}
//ОТЧЁТЫ

function reportTotalRevenue($pdo) {
    $sql = "SELECT SUM(total_amount) FROM orders WHERE status = 'доставлен'";
    $result = $pdo->query($sql);
    $sum = $result->fetchColumn();
    if ($sum == null) {
        return 0;
    }
    return $sum;
}

function reportRevenueByCategory($pdo) {
    $sql = "SELECT categories.name, SUM(order_items.quantity * order_items.price_at_purchase) AS itog
            FROM orders
            JOIN order_items ON order_items.order_id = orders.id
            JOIN products ON products.id = order_items.product_id
            JOIN categories ON categories.id = products.category_id
            WHERE orders.status = 'доставлен'
            GROUP BY categories.name
            ORDER BY itog DESC";
    $result = $pdo->query($sql);
    return $result->fetchAll();
}

function reportTop5Products($pdo) {
    $sql = "SELECT products.name, SUM(order_items.quantity) AS itog
            FROM order_items
            JOIN products ON products.id = order_items.product_id
            JOIN orders ON orders.id = order_items.order_id
            WHERE orders.status = 'доставлен'
            GROUP BY products.name
            ORDER BY itog DESC
            LIMIT 5";
    $result = $pdo->query($sql);
    return $result->fetchAll();
}

function reportTop5Customers($pdo) {
    $sql = "SELECT customers.name, SUM(orders.total_amount) AS itog
            FROM orders
            JOIN customers ON customers.id = orders.customer_id
            WHERE orders.status = 'доставлен'
            GROUP BY customers.name
            ORDER BY itog DESC
            LIMIT 5";
    $result = $pdo->query($sql);
    return $result->fetchAll();
}

function reportStatusStats($pdo) {
    $sql = "SELECT status, COUNT(*) AS itog FROM orders GROUP BY status";
    $result = $pdo->query($sql);
    return $result->fetchAll();
}

function reportAverageCheck($pdo) {
    $sql = "SELECT AVG(total_amount) FROM orders WHERE status = 'доставлен'";
    $result = $pdo->query($sql);
    $avg = $result->fetchColumn();
    if ($avg == null) {
        return 0;
    }
    return $avg;
}


