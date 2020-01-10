<?php
$imgLink = BASE_URL . "?page=book_detail";
$bookRepo = new BookRepository(Connection::getPdoInstance());

$books = ((isset($_GET["genre"])) ? $bookRepo->getByGenre($_GET["genre"]) : $bookRepo->getAllBooks());

if (isset($books[0]))
    echo '<h1 style="padding: 20px  0  0 175px">' . ((isset($_GET["genre"])) ? $books[0]["genre"] : "Knihy různých žánrů") . '</h1>
<div class="flex-wrap">
';
foreach ($books as $row) {
    echo '
    <div class="card">
  <img id="book-img" src="./images/books/' . $row["image"] . '" alt="' . $row["image"] . '" onclick=location.href="' . BASE_URL . '?page=book_detail&isbn=' . $row["isbn"] .'">
  <a id="book-title" href="' . BASE_URL . '?page=book_detail&isbn=' . $row["isbn"] . '"><h3 class="cut-text" style="margin:10px 0 5px 0">' . $row["name"] . '</h3></a>
  <p>' . $row["author"] . '</p>
  <p class="price"><b class="color-green">' . $row["price"] . ' Kč </b></p>
  <a id="book_to_cart_btn" href="' . BASE_URL . '?page=cart"> <i class="fa fa-shopping-cart"></i> Do košíku</a>
    </div>
    ';
}

echo '</div>';