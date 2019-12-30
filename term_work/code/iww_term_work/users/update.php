<?php
include('./code/functions.php');
if (empty($errorFeedbacks)) { //load origin data from database
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $conn->exec("set names utf8");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM user WHERE id= :id");
    $stmt->bindParam(':id', $_GET["user_id"]);
    $stmt->execute();
    $user = $stmt->fetch();

    $first_name = $user["first_name"];
    $last_name = $user["last_name"];
    $email = $user["email"];
    $phone_number = $user["phone_number"];
} else { //in case of any error, load data
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $passwordValue = $_POST["password"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
}

?>
<form id="admin-form" method="post">
    <h2>Upravit uživatele</h2>
    <?php
    if (!empty($errorFeedbacks)) {
        echo "<b class='color-orange'>Při zadávání se vyskytly tyto chyby:</b><br>";
        foreach ($errorFeedbacks as $errorFeedback) {
            echo "<b class='color-red'>" . $errorFeedback . "</b><br>";
        }
        echo "<br>";
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($successFeedback)) {
        header("Location:" . BASE_URL . "?page=user_management"."&action=insert" . "&message=" . "<br><b class='color-green'>$successFeedback.</b><br>");
    }
    ?>
    <input id="admin-input" type="text" name="first_name" placeholder="Jméno" value="<?= $first_name; ?>">
    <input id="admin-input" type="text" name="last_name" placeholder="Příjmení" value="<?= $last_name; ?>">
    <input id="admin-input" style="display: none" type="password" name="password" placeholder="Heslo">
    <input id="admin-input" type="email" name="email" placeholder="E-mailová adresa"
           pattern="[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$" value="<?= $email; ?>">
    <input id="admin-input" type="tel" name="phone_number" placeholder="Telefonní číslo"
           pattern="(([0-9]{3} [0-9]{3} [0-9]{3})|([0-9]{3}[0-9]{3}[0-9]{3}))" value="<?= $phone_number; ?>">
    <select id="admin-select" name="role">
        <option value="Administrátor">Administrátor</option>
        <option value="Zaměstnanec">Zaměstnanec</option>
        <option value="Zákazník">Zákazník</option>
    </select>
    <br>
    <input id="admin-input" type="text" name="street" placeholder="Ulice">
    <input id="admin-input" type="text" name="city" placeholder="Město">
    <input id="admin-input" type="text" name="zip_code" placeholder="PSČ" pattern="(([0-9]{3} [0-9]{2})|([0-9]{5}))">
    <select id="admin-select" name="country">
        <option value="Česká republika">Česká republika</option>
        <option value="Slovenská republika">Slovenská republika</option>
    </select>
    <div><input type="checkbox" id="new_passw_check" name="deliveryChB" onclick="showNewPasswordField()"> Nové heslo
    </div>
    <br>
    <div>
        <input type="checkbox" id="sec_addr_check" name="deliveryChB" onclick="showDeliveryDetails()"> Dodací údaje se
        liší od
        fakturačních
    </div>
    <br>
    <div id=secondary-address style="display: none">
        <input id="admin-input" type="text" name="street_sec" placeholder="Ulice">
        <input id="admin-input" type="text" name="city_sec" placeholder="Město">
        <input id="admin-input" type="text" name="zip_code_sec" placeholder="PSČ" pattern="(([0-9]{3} [0-9]{2})|([0-9]{5}))">
        <select id="admin-select" name="country_sec">
            <option value="Česká republika">Česká republika</option>
            <option value="Slovenská republika">Slovenská republika</option>
        </select>
    </div>
    <input id="admin-submit" type="submit" name="add_user" value="Přidat">
</form>
