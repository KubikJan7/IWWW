<?php
if (isset($_GET["filepath"]))
    $filepath = $_GET["filepath"];
$message = "";
// save data to database
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->exec("set names utf8");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("SELECT isbn,book.name,author,price, publication_date, description, page_count, binding, 
            image, language.name AS language , genre.name AS genre FROM book, language, genre WHERE language_id=language.id AND genre_id= genre.id");
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$filepath == "") {
    $file = fopen($filepath, 'w');
    fwrite($file, json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    fclose($file);

    echo "<p><b class='color-green'>Knihy byly uloženy do $filepath</b></p>";
} else
    echo "<p><b class='color-red'>Zadejte prosím validní cestu!</b></p>";

