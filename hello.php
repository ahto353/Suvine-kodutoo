<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$db   = 'books';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$pdo = new PDO($dsn, $user, $pass, $options);


// 1. Kõikide raamatute nimekiri
$stmt = $pdo->query("SELECT id, title, release_date, price, type FROM books");

echo "<h2>1. Kõik raamatud</h2>";
while ($row = $stmt->fetch()) {
    echo implode(" | ", $row) . "<br>";
}


// 2. Alates 2010. aastast ilmunud uued raamatud
$stmt = $pdo->query("SELECT title, release_date, price, type FROM books WHERE release_date >= 2010 AND type = 'new' ORDER BY title ASC");

echo "<h2>2. Alates 2010. aastast ilmunud uued raamatud</h2>";
while ($row = $stmt->fetch()) {
    echo implode(" | ", $row) . "<br>";
}


// 3. Enne 1970. aastat ilmunud kasutatud raamatud alla 20 euro
$stmt = $pdo->query("SELECT title, release_date, price, type FROM books WHERE release_date < 1970 AND type = 'used' AND price < 20");

echo "<h2>3. Enne 1970. aastat ilmunud kasutatud raamatud alla 20 euro</h2>";
while ($row = $stmt->fetch()) {
    echo implode(" | ", $row) . "<br>";
}


// 4. Täidetud tellimuste arv aasta kaupa
$stmt = $pdo->query("SELECT YEAR(order_date) AS Aasta, COUNT(*) AS `Tellimuste arv` FROM orders WHERE status = 'sent' GROUP BY YEAR(order_date) ORDER BY YEAR(order_date)");

echo "<h2>4. Täidetud tellimuste arv aasta kaupa</h2>";
while ($row = $stmt->fetch()) {
    echo implode(" | ", $row) . "<br>";
}


// 5. Täidetud tellimuste arv ja müügi summa aasta kaupa
$stmt = $pdo->query("SELECT YEAR(o.order_date) AS Aasta, COUNT(o.id) AS `Tellimuste arv`, ROUND(SUM(b.price), 2) AS `Müügi summa` FROM orders o LEFT JOIN books b ON o.book_id = b.id WHERE o.status = 'sent' GROUP BY YEAR(o.order_date) ORDER BY YEAR(o.order_date)");

echo "<h2>5. Tellimuste arv ja müügi summa aasta kaupa</h2>";
while ($row = $stmt->fetch()) {
    echo implode(" | ", $row) . "<br>";
}


// 6. Täidetud tellimuste arv viimase aasta jooksul ja müügi summa
$stmt = $pdo->query("SELECT COUNT(o.id) AS `Tellimuste arv`, ROUND(SUM(b.price), 2) AS `Müügi summa` FROM orders o LEFT JOIN books b ON o.book_id = b.id WHERE o.status = 'sent' AND o.order_date >= DATE_SUB((SELECT MAX(order_date) FROM orders), INTERVAL 1 YEAR) AND o.order_date <= (SELECT MAX(order_date) FROM orders)");

echo "<h2>6. Viimase aasta tellimused ja müügi summa</h2>";
while ($row = $stmt->fetch()) {
    echo implode(" | ", $row) . "<br>";
}


// 7. Kliendid viimase aasta jooksul kulutatud summa järgi
$stmt = $pdo->query("SELECT CONCAT(c.first_name, ' ', c.last_name) AS `Klient`, ROUND(SUM(b.price), 2) AS `Kulutatud summa` FROM clients c JOIN orders o ON c.id = o.client_id JOIN books b ON o.book_id = b.id WHERE o.status = 'sent' AND o.order_date >= DATE_SUB((SELECT MAX(order_date) FROM orders), INTERVAL 1 YEAR) AND o.order_date <= (SELECT MAX(order_date) FROM orders) GROUP BY c.id, c.first_name, c.last_name ORDER BY SUM(b.price) DESC");

echo "<h2>7. Kliendid kulutatud summa järgi</h2>";
while ($row = $stmt->fetch()) {
    echo implode(" | ", $row) . "<br>";
}


// 8. Viimase aasta 10 enim müüdud raamatut
$stmt = $pdo->query("SELECT b.title AS `Raamat`, COUNT(o.id) AS `Müüdud kogus` FROM books b JOIN orders o ON b.id = o.book_id WHERE o.status = 'sent' AND o.order_date >= DATE_SUB((SELECT MAX(order_date) FROM orders), INTERVAL 1 YEAR) AND o.order_date <= (SELECT MAX(order_date) FROM orders) GROUP BY b.id, b.title ORDER BY COUNT(o.id) DESC LIMIT 10");

echo "<h2>8. Viimase aasta 10 enim müüdud raamatut</h2>";
while ($row = $stmt->fetch()) {
    echo implode(" | ", $row) . "<br>";
}


// 9. Raamatud, mille hind on keskmisest kõrgem
$stmt = $pdo->query("SELECT id, title, release_date, price, type FROM books WHERE price > (SELECT AVG(price) FROM books)");

echo "<h2>9. Keskmisest kallimad raamatud</h2>";
while ($row = $stmt->fetch()) {
    echo implode(" | ", $row) . "<br>";
}

?>