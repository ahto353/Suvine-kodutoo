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

/*
ÜLESANNE 23

1. Uue kasutaja lisamine
INSERT INTO clients (username, first_name, last_name, email, password, address)
VALUES ('minukasutaja', 'Ahto', 'Klais', 'ahto@example.com', 'parool', 'Eesti');

2. Esimese raamatu keele muutmine
UPDATE books
SET language = 'Eesti'
WHERE id = 1;

3. Tellimuse id 2300 kustutamine
DELETE FROM orders
WHERE id = 2300;

4. Viie uue kasutaja lisamine ühe päringuga
INSERT INTO clients (username, first_name, last_name, email, password, address)
VALUES
('kasutaja1', 'Mari', 'Mets', 'mari1@example.com', 'parool1', 'Eesti'),
('kasutaja2', 'Jaan', 'Kask', 'jaan2@example.com', 'parool2', 'Eesti'),
('kasutaja3', 'Kati', 'Tamm', 'kati3@example.com', 'parool3', 'Eesti'),
('kasutaja4', 'Mati', 'Saar', 'mati4@example.com', 'parool4', 'Eesti'),
('kasutaja5', 'Liis', 'Kivi', 'liis5@example.com', 'parool5', 'Eesti');

5. Vendetta tellimus alampäringutega
INSERT INTO orders (delivery_address, order_date, status, client_id, book_id)
VALUES (
    'Eesti',
    NOW(),
    'ordered',
    (SELECT id FROM clients WHERE username = 'mcage1o'),
    (SELECT id FROM books WHERE title = 'Vendetta' LIMIT 1)
);

6. Kõigi raamatute hinna ja lehekülgede muutmine
UPDATE books
SET price = price * 1.05,
    pages = pages - 5;

7. Autorite kustutamine, kellel pole ühtegi raamatut
DELETE FROM authors
WHERE id NOT IN (
    SELECT author_id
    FROM book_authors
);
*/

echo "<h2>Ülesanne 23</h2>";
echo "Kõik 7 SQL-päringut on ülesande täitmiseks kasutatud ja salvestatud siia faili.";

/*
Päringud on kommentaarides, sest need on juba andmebaasis
käivitatud ja nende uuesti käivitamine muudaks andmeid teist korda.
*/

?>
