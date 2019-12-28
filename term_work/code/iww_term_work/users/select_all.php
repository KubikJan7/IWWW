<?php
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$data = $conn->query("SELECT *, name FROM user, role WHERE role.id = role_id")->fetchAll();
echo '<table>';

echo '  
  <tr>
    <th>Id</th>
    <th>Jméno</th> 
    <th>Příjmení</th>
    <th>E-mail</th>
    <th>Telefonní číslo</th>
    <th>Role</th>
    <th>Akce</th>
  </tr>';

foreach ($data as $row) {

    echo '   
   <tr >
    <td >' . $row["id"] . '</td >
    <td >' . $row["first_name"] . '</td > 
    <td >' . $row["last_name"] . '</td >
    <td >' . $row["email"] . '</td >
    <td >' . $row["phone_number"] . '</td >
    <td >' . $row["name"] . '</td >
    <td>
        <a href="?page=users&action=update&id=' . $row["id"] . '">U</a>
        <a href="?page=users&action=delete&id=' . $row["id"] . '">D</a>
    </td>
  </tr >';

}

echo '</table>';
?>

<a id="button_orange_border" href="<?= BASE_URL . "?page=users&action=insert" ?>">Přidat uživatele</a>
<a id="button_orange_border" href="<?= BASE_URL . "?page=logout&action=delete_all&message=" .
"<br><b class='color-orange'>Všichni uživatelé byli odstraněni.</b><br>" ?>"
   onclick="return confirm('Opravdu si přejete pokračovat? Tímto smažete i svůj účet.')">Odstranit veškeré uživatele</a>
