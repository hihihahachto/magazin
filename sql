CREATE DATABASE ShopInt CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ShopInt;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    category_id INT NOT NULL
);

CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'новый'
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_purchase DECIMAL(10,2) NOT NULL
);
USE ShopInt;

-- ============ КАТЕГОРИИ ============
INSERT INTO categories (name) VALUES
('Электроника'),
('Одежда'),
('Книги'),
('Продукты');

-- ============ ТОВАРЫ ============
INSERT INTO products (name, price, stock, category_id) VALUES
('Смартфон Samsung', 25000, 10, 1),
('Ноутбук Lenovo', 55000, 5, 1),
('Наушники Sony', 8000, 15, 1),
('Футболка белая', 1200, 50, 2),
('Джинсы синие', 3500, 20, 2),
('Куртка зимняя', 7000, 8, 2),
('Война и мир', 900, 30, 3),
('Преступление и наказание', 750, 25, 3),
('Мастер и Маргарита', 650, 40, 3),
('Молоко 1л', 80, 100, 4),
('Хлеб белый', 45, 60, 4),
('Сыр российский', 450, 15, 4);

-- ============ КЛИЕНТЫ ============
INSERT INTO customers (name, email, phone) VALUES
('Иван Иванов', 'ivan@mail.ru', '+79001112233'),
('Мария Петрова', 'maria@mail.ru', '+79004445566'),
('Сергей Сидоров', 'sergey@mail.ru', '+79007778899'),
('Анна Кузнецова', 'anna@mail.ru', '+79002223344');

-- ============ ЗАКАЗЫ ============

-- Заказ 1: Иван, доставлен
INSERT INTO orders (customer_id, status) VALUES (1, 'доставлен');
INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES
(1, 1, 1, 25000),
(1, 3, 2, 8000);
UPDATE orders SET total_amount = 25000 + 2*8000 WHERE id = 1;

-- Заказ 2: Мария, доставлен
INSERT INTO orders (customer_id, status) VALUES (2, 'доставлен');
INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES
(2, 4, 3, 1200),
(2, 5, 1, 3500),
(2, 7, 2, 900);
UPDATE orders SET total_amount = 3*1200 + 3500 + 2*900 WHERE id = 2;

-- Заказ 3: Сергей, в обработке
INSERT INTO orders (customer_id, status) VALUES (3, 'в обработке');
INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES
(3, 2, 1, 55000);
UPDATE orders SET total_amount = 55000 WHERE id = 3;

-- Заказ 4: Анна, новый
INSERT INTO orders (customer_id, status) VALUES (4, 'новый');
INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES
(4, 9, 3, 650),
(4, 10, 5, 80);
UPDATE orders SET total_amount = 3*650 + 5*80 WHERE id = 4;

-- Заказ 5: Иван, отменён
INSERT INTO orders (customer_id, status) VALUES (1, 'отменён');
INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES
(5, 6, 1, 7000);
UPDATE orders SET total_amount = 7000 WHERE id = 5;

-- Заказ 6: Мария, доставлен
INSERT INTO orders (customer_id, status) VALUES (2, 'доставлен');
INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES
(6, 1, 1, 25000),
(6, 11, 10, 45);
UPDATE orders SET total_amount = 25000 + 10*45 WHERE id = 6;

-- Заказ 7: Сергей, доставлен
INSERT INTO orders (customer_id, status) VALUES (3, 'доставлен');
INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES
(7, 3, 1, 8000),
(7, 8, 2, 750);
UPDATE orders SET total_amount = 8000 + 2*750 WHERE id = 7;