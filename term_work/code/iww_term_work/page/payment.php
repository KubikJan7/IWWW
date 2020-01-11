<?php
$state = "Odeslaná";

$conn = Connection::getPdoInstance();
$addressRepo = new AddressRepository($conn);

// There will be used the secondary address if user has it
$primaryAddress = $addressRepo->getPrimaryAddressByUserId($_SESSION["user_id"]);
$secondaryAddress = $addressRepo->getSecondaryAddressByUserId($_SESSION["user_id"]);

if (empty($secondaryAddress))
    $address_id = $primaryAddress["id"];
else
    $address_id = $secondaryAddress["id"];

$stmt = $conn->prepare("INSERT INTO purchase (state, payment, user_id,address_id) 
            VALUES (:state, :payment, :user_id, :address_id)");
$stmt->bindParam(':state', $state);
$stmt->bindParam(':payment', $_GET["payment"]);
$stmt->bindParam(':user_id', $_SESSION["user_id"]);
$stmt->bindParam(':address_id', $address_id);
$stmt->execute();

$purchase_id = $conn->query("SELECT MAX(id) FROM purchase")->fetch()[0];

foreach ($_SESSION['cart'] as $bookIsbn => $value) {
    $bookRepo = new BookRepository($conn);
    $book_price = $bookRepo->getByISBN($bookIsbn)["price"];
    $price_sum = $book_price * $value["quantity"];

    $stmt = $conn->prepare("INSERT INTO purchase_book (price,quantity, purchase_id, book_isbn) 
            VALUES (:price, :quantity, :purchase_id, :book_isbn)");
    $stmt->bindParam(':price', $price_sum);
    $stmt->bindParam(':quantity', $value["quantity"]);
    $stmt->bindParam(':purchase_id', $purchase_id);
    $stmt->bindParam(':book_isbn', $bookIsbn);
    $stmt->execute();
}

// Erase items in the shopping cart
unset($_SESSION["cart"]);
?>

<div id="order-finished">
    <h1 class="color-green">Děkujeme za Váš nákup.</h1> <br>
    <h2 style="color: #F49268">Vaše objednávka byla odeslána.</h2>
</div>
