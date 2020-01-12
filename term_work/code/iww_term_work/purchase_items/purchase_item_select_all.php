<?php
$conn = Connection::getPdoInstance();

$purchaseItemsRepo = new PurchaseBookRepository($conn);
$purchaseItems = $purchaseItemsRepo->getPurchasedItemsByPurchaseId($_GET["purchase_id"]);

echo '<table style="margin-top: 40px">';

echo '  
  <tr>
    <th>Id položky</th>
    <th>ISBN</th>
    <th>Obrázek</th>
    <th>Název</th>
    <th>Množství</th>
    <th>Cena</th> 
    <th>Cena/ks</th> 
    <th>Akce</th>
  </tr>';

foreach ($purchaseItems as $item) {
    $bookRepo = new BookRepository($conn);
    $book = $bookRepo->getByISBN($item["book_isbn"]);


    echo '   
   <tr>
    <td>' . $item["id"] . '</td >
    <td >' . $item["book_isbn"] . '</td > 
    <td ><img src="./images/books/'.$book["image"].'" alt="'.$book["name"].'" width="50"></td >
    <td >' . $book["name"] . '</td >
    <td >' . $item["quantity"] . '</td >
    <td >' . $item["price"] . '</td >
    <td >' . ($item["price"]/$item["quantity"]) . '</td >
    <td>
        <a href="?page=purchase_item_management&action=purchase_item_update&item_id=' . $item["id"] . '&purchase_id='. $_GET["purchase_id"] .'">Upravit</a>
        <a href="?page=purchase_item_management&action=purchase_item_delete&item_id=' . $item["id"] . '&purchase_id='. $_GET["purchase_id"] .'">Smazat</a>
    </td>
  </tr >
  ';
}

echo '</table>';
?>

<a id="button_orange_border" href="<?= BASE_URL . "?page=purchase_item_management&action=purchase_item_insert&purchase_id=". $_GET["purchase_id"] ?>">Přidat položku</a>
