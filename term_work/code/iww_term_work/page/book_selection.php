<?php
$imgLink = BASE_URL . "?page=book_detail";
$bookRepo = new BookRepository(Connection::getPdoInstance());

if ($_GET["genre"] == 0) // all genres
{
    $books = $bookRepo->getAllBooks();
    if(isset($_GET["genre"]))
        $genre = "Knihy různých žánrů";

} else if ($_GET["genre"] == 5) //books written in different languages
{
    $books = $bookRepo->getNonCzechBooks();
    $genre = "Cizojazyčná literatura";
} else {
    $books = $bookRepo->getByGenre($_GET["genre"]);
    $genre = $books[0]["genre"];
}

if (isset($books[0]))
    echo '<h1 style="padding: 20px  0  0 175px">' . $genre . '</h1>
<div class="flex-wrap">
';
foreach ($books as $row) {
    echo '
    <div class="card">
  <img id="book-img" src="./images/books/' . $row["image"] . '" alt="' . $row["image"] . '" onclick=location.href="' . BASE_URL . '?page=book_detail&isbn=' . $row["isbn"] . '">
  <a id="book-title" href="' . BASE_URL . '?page=book_detail&isbn=' . $row["isbn"] . '"><h3 class="cut-text" style="margin:10px 0 5px 0">' . $row["name"] . '</h3></a>
  <p>' . $row["author"] . '</p>
  <p class="price"><b class="color-green">' . $row["price"] . ' Kč </b></p>
  <a id="book_to_cart_btn" href="' . BASE_URL . '?page=cart"> <i class="fa fa-shopping-cart"></i> Do košíku</a>
    </div>
    ';
}

echo '</div>';