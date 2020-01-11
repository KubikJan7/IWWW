<?php
$bookRepo = new BookRepository(Connection::getPdoInstance());
$book = $bookRepo->getByISBN($_GET["isbn"]);
echo '
    <!--Left side of book detail -> book image -->
    <section id="book_detail_section">
        <div id="book_detail_left">
            <img src="./images/books/' . $book["image"] . '" alt="' . $book["image"] . '">
        </div>
        <!-- right side of book detail -->
        <div id="book_detail_right">
            <!-- Book description -->
            <div id="book_detail_desc">
                <h1>' . $book["name"] . '</h1>
                <h3 class="color-red">' . $book["author"] . '</h3>
                    <!-- Book pricing -->
                <div id="book_detail_price">
                    <span><b>'. $book["price"] .' Kč</b></span>
                    <a id="button_orange_border" href="'. BASE_URL .'?page=cart&action=add&isbn='. $book["isbn"] .'" <i class="fa fa-shopping-cart"></i>Do košíku</a>
                </div>
                <p>'. $book["description"] .'</p>
                
            </div>
            <div class="additional_info">
                <div>
                <div><b>ISBN: </b>'. $book["isbn"] .'</div>
                <div><b>Datum vydání: </b>'. $book["publication_date"] .'</div>
                <div><b>Počet stran: </b>'. $book["page_count"] .'</div>
                </div>
                
                <div>
                <div><b>Jazyk: </b>'. $book["language"] .'</div>
                <div><b>Žánr: </b>'. $book["genre"] .'</div>
                <div><b>Vazba: </b>'. $book["binding"] .'</div>
                </div>
            </div>
            
        </div>
    </section>
';
?>
