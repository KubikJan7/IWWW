<?php
$message = "";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
if (isset($_GET["action"])) {
    if ($_GET["action"] == "add") {
        if (array_key_exists($_GET["isbn"], $_SESSION['cart'])) {
            $_SESSION['cart'][$_GET['isbn']]['quantity']++;
        } else {
            $_SESSION['cart'][$_GET['isbn']]['quantity'] = 1;
        }
    } else if ($_GET["action"] == "remove") {
        if (array_key_exists($_GET["isbn"], $_SESSION['cart'])) {
            $_SESSION['cart'][$_GET['isbn']]['quantity']--;
            if ($_SESSION['cart'][$_GET['isbn']]['quantity'] == 0) {
                unset($_SESSION['cart'][$_GET['isbn']]);
            }
        }
    } else if ($_GET["action"] == "delete") {
        unset($_SESSION['cart'][$_GET['isbn']]);
    }
}

if (isset($_POST['send-order'])) {
    if (!isset($_POST["payment"]))
        echo "<p style=\"margin-left: 245px\"><b class='color-red'>Vyberte prosím způsob platby!</b></p>";
    else if (empty($_SESSION["user_id"]))
        echo '<p style="margin-left: 245px">Pro dokončení obědnávky je zapotřebí se <a href="'.BASE_URL.'?page=login">přihlásit</a> či <a href="'.BASE_URL.'?page=registration">zaregistrovat</a>.</p>';
}

?>


<?php
if (count($_SESSION['cart']) == 0) {
    ?>
    <!-- cart is empty -->
    <section id=cart-section>
        <div><h1>Košík je prázdný, přidejte do něj zboží.</h1></div>
        <div id="big-cart-img"><i class="fa fa-shopping-cart"></i></div>
        <a id="button_orange_border" href="<?= BASE_URL . "?page=book_selection&genre=0" ?>"><i
                    class="fa fa-shopping-cart"></i> Jít nakupovat</a>
    </section>
    <?php
} else {
    ?>
    <section id="cart-section">
        <div id="cart-items">
            <h1>Váš košík</h1>
            <?php
            $sum = 0;
            foreach ($_SESSION['cart'] as $bookIsbn => $value) {
                $bookRepo = new BookRepository(Connection::getPdoInstance());
                $book = $bookRepo->getByISBN($bookIsbn);
                echo '
                <div id="cart-item-line">
                    <img src="./images/books/' . $book["image"] . '" alt="' . $book["image"] . '">
                    <h3>' . $book["name"] . '</h3>
                    <input type="text" name="book_count" value="' . $value["quantity"] . '" readonly>
                    <a href="' . BASE_URL . '?page=cart&action=add&isbn=' . $bookIsbn . '"><i class="fa fa-plus"></i></a>
                    <a href="' . BASE_URL . '?page=cart&action=remove&isbn=' . $bookIsbn . '"><i class="fa fa-minus"></i></a>
                    <a href="' . BASE_URL . '?page=cart&action=delete&isbn=' . $bookIsbn . '"><i class="fa fa-times"></i></a>
                    <span><b class="color-green">' . $value["quantity"] * $book["price"] . ' Kč</b></span>
                </div>
            ';
                $sum += $value["quantity"] * $book["price"];
            }
            ?>
        </div>
        <div id="price-sum">
            <b>Celkem:</b>
            <b class="color-green">
                <?= $sum ?> Kč
            </b>
        </div>
        <form method="post">
            <h3>Platba</h3>
            <input type="radio" name="payment" value="Převod na účet">Převod na účet<br>
            <input type="radio" name="payment" value="Dobírka">Dobírka<br>
            <input type="radio" name="payment" value="Platba kartou">Platba kartou<br>

            <input type="submit" name="send-order" value="Odeslat objednávku">
        </form>
    </section>
    <?php
}

if (isset($_POST['send-order'])) {
    if (isset($_POST["payment"])&&!empty($_SESSION["user_id"]))
        header("Location:" . BASE_URL . "?page=payment" . "&payment=" . $_POST["payment"]);
}

?>

