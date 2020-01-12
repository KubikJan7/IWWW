<?php
require_once "./class/PurchaseBookRepository.php";

$conn = Connection::getPdoInstance();
$purchaseBookRepo = new PurchaseBookRepository($conn);
$purchasedItems = $purchaseBookRepo->getPurchasedItemsByPurchaseId($_GET["purchase_id"]);

$addressRepo = new AddressRepository($conn);
$primaryAddress = $addressRepo->getPrimaryAddressByUserId($_SESSION["user_id"]);
$secondaryAddress = $addressRepo->getSecondaryAddressByUserId($_SESSION["user_id"]);

if(!empty($secondaryAddress))
    $address = $secondaryAddress;
else
    $address = $primaryAddress;

?>
<section id="cart-section">
    <div id="cart-items">
        <?php
        echo'<h1>Objednávka č. '. $_GET["purchase_id"] .'</h1>

        <div style="display: flex">
            <div id="address_column">
                <h3 style="margin-bottom: 5px">Fakturační adresa</h3>
                <span>'. $primaryAddress["address"] .'</span>
                <span>'. $primaryAddress["city"] .'</span>
                <span>'. $primaryAddress["zip_code"] .'</span>
                <span>'. $primaryAddress["country"] .'</span>
            </div>
        
            <div id="address_column">
                <h3 style="margin-bottom: 5px">Doručovací adresa</h3>
                <span>'. $address["address"] .'</span>
                <span>'. $address["city"] .'</span>
                <span>'. $address["zip_code"] .'</span>
                <span>'. $address["country"] .'</span>
            </div>
        </div>
        <h3 style="margin: 0 0 15px 50px; text-align: left">Vaše objednávka</h3>
        ';
        $sum=0;
        foreach ($purchasedItems as $item) {
            $bookRepo = new BookRepository($conn);
            $book = $bookRepo->getByISBN($item["book_isbn"]);
            echo '
                <div id="cart-item-line">
                    <img src="./images/books/' . $book["image"] . '" alt="' . $book["image"] . '">
                    <h3>' . $book["name"] . '</h3>
                    <input type="text" name="book_count" value="' . $item["quantity"] . '" readonly>
                    <span><b>' . ($item["price"]/$item["quantity"]) . ' Kč/ks</b></span>
                    <span><b>' . $item["price"] . ' Kč</b></span>
                </div>
            ';
            $sum += $item["price"];
        }
        ?>
    </div>
    <div id="price-sum">
        <b>Celkem:</b>
        <b class="color-green">
            <?= $sum ?> Kč
        </b>
    </div>
</section>
