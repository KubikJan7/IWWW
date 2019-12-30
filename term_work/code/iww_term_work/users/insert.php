<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

// <editor-fold default-state="collapsed" desc="input-validation">
    if (empty($_POST["first_name"])) {
        $feedbackMessage = "Je vyžadováno jméno!";
        array_push($errorFeedbacks, $feedbackMessage);
    }
    if (empty($_POST["last_name"])) {
        $feedbackMessage = "Je vyžadováno příjmení!";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["password"])) {
        $feedbackMessage = "Je vyžadováno heslo!";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["email"])) {
        $feedbackMessage = "Je vyžadován e-mail!";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["phone-number"])) {
        $feedbackMessage = "Je vyžadováno telefonní číslo!";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["role"])) {
        $feedbackMessage = "Je vyžadována uživatelova role!";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["street"])) {
        $feedbackMessage = "Je vyžadována ulice s číslem popisným!";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["city"])) {
        $feedbackMessage = "Je vyžadováno město!";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["zip-code"])) {
        $feedbackMessage = "Je vyžadováno poštovní směrovací číslo!";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (isset($_POST['deliveryChB'])) { // check if the checkbox for delivery details is checked
        if (empty($_POST["street_sec"])) {
            $feedbackMessage = "V sekundární adrese je vyžadována ulice s číslem popisným!";
            array_push($errorFeedbacks, $feedbackMessage);
        }

        if (empty($_POST["city_sec"])) {
            $feedbackMessage = "V sekundární adrese je vyžadováno město!";
            array_push($errorFeedbacks, $feedbackMessage);
        }

        if (empty($_POST["zip-code_sec"])) {
            $feedbackMessage = "V sekundární adrese je vyžadováno poštovní směrovací číslo!";
            array_push($errorFeedbacks, $feedbackMessage);
        }
    }
// </editor-fold>

    if (empty($errorFeedbacks)) {
        //success
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $hashedPassword = crypt($_POST["password"], 'sdfjsdnmvcmv.xcvuesfsdfdsljk');

        $stmt = $conn->prepare("INSERT INTO user (first_name, last_name, password, email, phone_number, role_id) 
            VALUES (:first_name, :last_name, :password, :email, :phone_number, :role)");
        $stmt->bindParam(':first_name', $_POST["first_name"]);
        $stmt->bindParam(':last_name', $_POST["last_name"]);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $_POST["email"]);
        $stmt->bindParam(':phone_number', $_POST["phone_number"]);
        $stmt->bindParam(':role', $_POST["role"]);
        $stmt->execute();
        $user_id = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO address (address, city, zip_code, country, type, user_id) 
            VALUES (:address, :city, :zip_code, :country, 'primary', :user_id)");
        $stmt->bindParam(':address', $_POST["street"]);
        $stmt->bindParam(':city', $_POST["city"]);
        $stmt->bindParam(':zip_code', $_POST["zip_code"]);
        $stmt->bindParam(':country', $_POST["country"]);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        if (isset($_POST['deliveryChB'])) //check if the checkbox for delivery details is checked
        {
            $stmt = $conn->prepare("INSERT INTO address (address, city, zip_code, country, type, user_id) 
                VALUES (:address, :city, :zip_code, :country, 'secondary', :user_id)");
            $stmt->bindParam(':address', $_POST["street_sec"]);
            $stmt->bindParam(':city', $_POST["city_sec"]);
            $stmt->bindParam(':zip_code', $_POST["zip_code_sec"]);
            $stmt->bindParam(':country', $_POST["country_sec"]);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
        }
        $successFeedback = "Uživatel byl přidán.";
    }
}

?>

<form id="admin-form" method="post">
    <h2>Přidat uživatele</h2>
    <?php
    if (!empty($errorFeedbacks)) {
        echo "<b class='color-orange'>Při zadávání se vyskytly tyto chyby:</b><br>";
        foreach ($errorFeedbacks as $errorFeedback) {
            echo "<b class='color-red'>" . $errorFeedback . "</b><br>";
        }
        echo "<br>";
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($successFeedback)) {
        echo "<b class='color-green'>" . $successFeedback . "</b><br>";
    }
    ?>
    <input id="admin-input" type="text" name="first_name" placeholder="Jméno"/>
    <input id="admin-input" type="text" name="last_name" placeholder="Příjmení">
    <input id="admin-input" type="password" name="password" placeholder="Heslo">
    <input id="admin-input" type="email" name="email" placeholder="E-mailová adresa">
    <input id="admin-input" type="tel" name="phone-number" placeholder="Telefonní číslo">
    <select id="admin-select" name="role">
        <option value="administrator">Administrátor</option>
        <option value="employee">Zaměstnanec</option>
        <option value="customer">Zákazník</option>
    </select>
    <br>
    <input id="admin-input" type="text" name="street" placeholder="Ulice">
    <input id="admin-input" type="text" name="city" placeholder="Město">
    <input id="admin-input" type="text" name="zip-code" placeholder="PSČ">
    <select id="admin-select" name="country">
        <option value="Česká republika">Česká republika</option>
        <option value="Slovenská republika">Slovenská republika</option>
    </select>
    <div><input type="checkbox" id="myCheck" name="deliveryChB" onclick="showDeliveryDetails()"> Dodací údaje se
        liší od
        fakturačních
    </div>
    <br>
    <div id=secondary-address style="display: none">
        <input id="admin-input" type="text" name="street-sec" placeholder="Ulice">
        <input id="admin-input" type="text" name="city-sec" placeholder="Město">
        <input id="admin-input" type="text" name="zip-code-sec" placeholder="PSČ">
        <select id="admin-select" name="country_sec">
            <option value="Česká republika">Česká republika</option>
            <option value="Slovenská republika">Slovenská republika</option>
        </select>
    </div>
    <input id="admin-submit" type="submit" name="add_user" value="Přidat">
</form>

<!--
***************************************************************************************
*    Title: How TO - Display Text when Checkbox is Checked
*    Author: www.w3schools.com
*    Date: 2019
*    Code version: 1.0
*    Availability: https://www.w3schools.com/howto/howto_js_display_checkbox_text.asp
*
****************************************************************************************
-->

<script>
    function showDeliveryDetails() {
        var checkBox = document.getElementById("myCheck");
        var text = document.getElementById("secondary-address");
        if (checkBox.checked == true) {
            text.style.display = "block";
        } else {
            text.style.display = "none";
        }
    }
</script>