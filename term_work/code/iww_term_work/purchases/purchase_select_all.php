<?php
$conn = Connection::getPdoInstance();
$purchaseRepo = new PurchaseRepository($conn);
$purchases = $purchaseRepo->getAllPurchases();

echo '<table style="margin-top: 40px">';

echo '  
  <tr>
    <th>Id</th>
    <th>Stav</th> 
    <th>Platba</th>
    <th>Jméno</th>
    <th>Příjmení</th>
    <th>E-mailová adresa</th>
    <th>Fakturační adresa</th>
    <th>Doručovací adresa</th>
    <th>Akce</th>
  </tr>';

foreach ($purchases as $purchase) {

    $userRepo = new UserRepository($conn);
    $user = $userRepo->getUserById($purchase["user_id"]);

    $addressRepo = new AddressRepository($conn);
    $primaryAddress = $addressRepo->getPrimaryAddressByUserId($purchase["user_id"]);
    $secondaryAddress = $addressRepo->getSecondaryAddressByUserId($purchase["user_id"]);

    if(!empty($secondaryAddress))
        $address = $secondaryAddress;
    else
        $address = $primaryAddress;

    echo '   
   <tr id="purchase_row" onclick="location.href=\''. BASE_URL .'?page=invoice&purchase_id='. $purchase["purchase_id"].'\'">
    <td>' . $purchase["purchase_id"] . '</td >
    <td >' . $purchase["state"] . '</td > 
    <td >' . $purchase["payment"] . '</td >
    <td >' . $user["first_name"] . '</td >
    <td >' . $user["last_name"] . '</td >
    <td >' . $user["email"] . '</td >
    <td >' . $primaryAddress["address"] . "<br> " . $primaryAddress["city"] . " " . $primaryAddress["zip_code"] . ", " . CustomFunctions::createAcronym($primaryAddress["country"]) . '</td >
    <td >' . $address["address"] . "<br> " . $address["city"] . " " . $address["zip_code"] . ", " . CustomFunctions::createAcronym($address["country"]) . '</td >
    <td>
        <a href="?page=purchase_management&action=purchase_update&purchase_id=' . $purchase["purchase_id"] . '">Upravit</a>
        <a href="?page=purchase_management&action=purchase_delete&purchase_id=' . $purchase["purchase_id"] . '">Smazat</a>
    </td>
  </tr >
  ';
}

echo '</table>';
?>

<a id="button_orange_border" href="<?= BASE_URL . "?page=purchase_management&action=purchase_insert" ?>">Přidat objednávku</a>

