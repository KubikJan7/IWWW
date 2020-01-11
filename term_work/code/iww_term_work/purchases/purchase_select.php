<?php
$purchaseRepo = new PurchaseRepository(Connection::getPdoInstance());
$purchases = $purchaseRepo->getPurchaseByUserId($_SESSION["user_id"]);

$purchaseBookRepo = new PurchaseBookRepository(Connection::getPdoInstance());

echo '<table style="margin-top: 40px">';

echo '  
  <tr>
    <th>Id</th>
    <th>Stav</th> 
    <th>Platba</th>
    <th>Cena</th>
  </tr>';

foreach ($purchases as $purchase) {
    $totalPrice = $purchaseBookRepo->getTotalPriceByPurchaseId($purchase["purchase_id"]);
    echo '   
   <tr id="purchase_row" onclick="location.href=\''. BASE_URL .'?page=invoice&purchase_id='. $purchase["purchase_id"].'\'">
    <td>' . $purchase["purchase_id"] . '</td >
    <td >' . $purchase["state"] . '</td > 
    <td >' . $purchase["payment"] . '</td >
    <td >' . $totalPrice[0] . ' Kč</td >
  </tr >
  ';
}

echo '</table>';
?>

