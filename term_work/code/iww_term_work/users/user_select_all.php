<?php
require_once "./code/functions.php";
$conn = Connection::getPdoInstance();

$data_user = $conn->query(
    "SELECT *, user.id AS user_id FROM user, address WHERE address.user_id = user.id AND type = 'primary'")->fetchAll();
$data_address = $conn->query(
    "SELECT user.id AS user_id, address, city, zip_code, country FROM address, user
                    WHERE address.user_id = user.id AND type = 'secondary'")->fetchAll();

echo '<table>';

echo '  
  <tr>
    <th>Id</th>
    <th>Jméno</th> 
    <th>Příjmení</th>
    <th>E-mail</th>
    <th>Telefonní číslo</th>
    <th>Role</th>
    <th>Fakturační údaje</th>
    <th>Doručovací údaje</th>
    <th>Akce</th>
  </tr>';

foreach ($data_user as $row_user) {

    // will find delivery address which corresponds with the user
    foreach ($data_address as $row_address) {
        if ($row_user["user_id"] == $row_address["user_id"])
            $address = $row_address;
    }
    // convert the role output to czech
    switch ($row_user["role"]) {
        case "administrator":
            $role = "Administrátor";
            break;
        case "employee":
            $role = "Zaměstnanec";
            break;
        case "customer":
            $role = "Zákazník";
            break;
    }
    echo '   
   <tr >
    <td >' . $row_user["user_id"] . '</td >
    <td >' . $row_user["first_name"] . '</td > 
    <td >' . $row_user["last_name"] . '</td >
    <td >' . $row_user["email"] . '</td >
    <td >' . $row_user["phone_number"] . '</td >
    <td >' . $role . '</td >
    <td >' . $row_user["address"] . "<br> " . $row_user["city"] . " " . $row_user["zip_code"] . ", " . CustomFunctions::createAcronym($row_user["country"]) . '</td >
    <td >' . ((isset($address)) ? ($address["address"] . "<br> " . $address["city"] . " " . $address["zip_code"] . ", " . CustomFunctions::createAcronym($address["country"])) : (" ╶ || ╴ ")) . '</td >
    <td>
        <a href="?page=user_management&action=user_update&user_id=' . $row_user["user_id"] . '">Upravit</a>
        <a href="?page=user_management&action=user_delete&user_id=' . $row_user["user_id"] . '">Smazat</a>
    </td>
  </tr >';
    $address = NULL;
}

echo '</table>';

?>

<a id="button_orange_border" href="<?= BASE_URL . "?page=user_management&action=user_insert" ?>">Přidat uživatele</a>
<a id="button_orange_border" href="<?= BASE_URL . "?page=logout&action=user_delete_all&message=" .
"<br><b class='color-orange'>Všichni uživatelé byli odstraněni.</b><br>" ?>"
   onclick="return confirm('Opravdu si přejete pokračovat? Tímto smažete i svůj účet.')">Odstranit veškeré uživatele</a>
