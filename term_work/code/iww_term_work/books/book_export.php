<?php

require_once "./code/functions.php";
$message = "";

// save data to database
$conn = Connection::getPdoInstance();

$stmt = $conn->prepare("SELECT isbn,book.name,author,price, publication_date, description, page_count, binding, 
            image, language.name AS language , genre.name AS genre FROM book, language, genre WHERE language_id=language.id AND genre_id= genre.id");
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

$filename="book_export.json";
$json = json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

header("Content-Disposition: attachment; filename=".basename($filename).";");
header('Content-type: application/json');
ob_clean();
echo $json;
exit();
