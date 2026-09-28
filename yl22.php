<?php

$pdo = new PDO(
    "mysql:host=localhost;dbname=books;charset=utf8mb4",
    "root",
    "",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

// 1. Kogu laoväärtus
$stmt = $pdo->query("SELECT ROUND(SUM(price * stock_saldo), 2) AS `Kogu laoväärtus` FROM books");

while ($row = $stmt->fetch()) {
    echo $row['Kogu laoväärtus'];
}
// 2. Kõige odavam, kõige kallim ja keskmine hind
$stmt = $pdo->query("SELECT MIN(price) AS `Odavaim`, MAX(price) AS `Kalleim`, ROUND(AVG(price), 2) AS `Keskmine` FROM books");

while ($row = $stmt->fetch()) {
    echo "<br><br>";
    echo "Odavaim: " . $row['Odavaim'] . " | ";
    echo "Kalleim: " . $row['Kalleim'] . " | ";
    echo "Keskmine: " . $row['Keskmine'];
}
// 3. Kõige kallim kasutatud raamat
$stmt = $pdo->query("SELECT MAX(price) AS `Kõige kallim kasutatud raamat` FROM books WHERE type = 'used'");

while ($row = $stmt->fetch()) {
    echo "<br><br>";
    echo "Kõige kallim kasutatud raamat: " . $row['Kõige kallim kasutatud raamat'];
}
// 4. Tüübi kaupa keskmine hind ja raamatute kogus
$stmt = $pdo->query("SELECT CASE type WHEN 'new' THEN 'Uus' WHEN 'used' THEN 'Kasutatud' WHEN 'ebook' THEN 'E-raamat' END AS `Tüüp`, ROUND(AVG(price), 2) AS `Keskmine hind`, COUNT(*) AS `Hulk` FROM books GROUP BY type ORDER BY type");

while ($row = $stmt->fetch()) {
    echo "<br><br>";
    echo $row['Tüüp'] . " | ";
    echo $row['Keskmine hind'] . " | ";
    echo $row['Hulk'];
}
// 5. Kasutatud raamatud, mis on kallimad kui uute raamatute keskmine hind
$stmt = $pdo->query("SELECT title, price, type FROM books WHERE type = 'used' AND price > (SELECT AVG(price) FROM books WHERE type = 'new') ORDER BY price ASC");

while ($row = $stmt->fetch()) {
    echo "<br>";
    echo $row['title'] . " | ";
    echo $row['price'] . " | ";
    echo $row['type'];
}
// 6. Raamatud, mis on kallimad kui enim müüdud autori raamatute keskmine hind
$stmt = $pdo->query("SELECT title, price, type FROM books WHERE price > (SELECT AVG(b.price) FROM books b JOIN book_authors ba ON b.id = ba.book_id WHERE ba.author_id = (SELECT ba2.author_id FROM book_authors ba2 JOIN orders o ON ba2.book_id = o.book_id WHERE o.status = 'sent' GROUP BY ba2.author_id ORDER BY COUNT(o.id) DESC LIMIT 1)) ORDER BY price ASC");

while ($row = $stmt->fetch()) {
    echo "<br>";
    echo $row['title'] . " | ";
    echo $row['price'] . " | ";
    echo $row['type'];
}
// 7. Paarisarvulise väljaandmisaastaga raamatud
$stmt = $pdo->query("SELECT title, release_date, price, type FROM books WHERE MOD(release_date, 2) = 0");

while ($row = $stmt->fetch()) {
    echo "<br>";
    echo $row['title'] . " | ";
    echo $row['release_date'] . " | ";
    echo $row['price'] . " | ";
    echo $row['type'];
}
// 8. Raamatute hulk keelte kaupa
$stmt = $pdo->query("SELECT language AS Keel, COUNT(*) AS Hulk FROM books GROUP BY language ORDER BY COUNT(*) DESC");

while ($row = $stmt->fetch()) {
    echo "<br>";
    echo $row['Keel'] . " | ";
    echo $row['Hulk'];
}
?>