<?php
$bookRepo = new BookRepository(Connection::getPdoInstance());
$book = $bookRepo->getByISBN($_GET["isbn"]);
echo '
<img src="./images/books/'. $book["image"] .'" alt="'. $book["image"] .'">
<h1>'. $book["name"] .'</h1>

';
?>
