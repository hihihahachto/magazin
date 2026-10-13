<?php
// собираем все отчёты сразу
$totalRevenue = reportTotalRevenue($pdo);
$revenueByCategory = reportRevenueByCategory($pdo);
$top5Products = reportTop5Products($pdo);
$top5Customers = reportTop5Customers($pdo);
$statusStats = reportStatusStats($pdo);
$averageCheck = reportAverageCheck($pdo);

require 'views/reports.php';