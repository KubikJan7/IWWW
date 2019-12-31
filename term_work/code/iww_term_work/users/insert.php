<?php
$errorFeedbackArray = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

// <editor-fold default-state="collapsed" desc="input-validation">
    if (empty($_POST["first_name"])) {
        $feedbackMessage = "Je vyžadováno jméno!";
        array_push($errorFeedbackArray, $feedbackMessage);
    }
    if (empty($_POST["last_name"])) {
        $feedbackMessage = "Je vyžadováno příjmení!";
        array_push($errorFeedbackArray, $feedbackMessage);
    }

    if (empty($_POST["password"])) {
        $feedbackMessage = "Je vyžadováno heslo!";
        array_push($errorFeedbackArray, $feedbackMessage);
    }

    if (empty($_POST["email"])) {
        $feedbackMessage = "Je vyžadován e-mail!";
        array_push($errorFeedbackArray, $feedbackMessage);
    }

    if (empty($_POST["phone_number"])) {
        $feedbackMessage = "Je vyžadováno telefonní číslo!";
        array_push($errorFeedbackArray, $feedbackMessage);
    }

    if (empty($_POST["street"])) {
        $feedbackMessage = "Je vyžadována ulice s číslem popisným!";
        array_push($errorFeedbackArray, $feedbackMessage);
    }

    if (empty($_POST["city"])) {
        $feedbackMessage = "Je vyžadováno město!";
        array_push($errorFeedbackArray, $feedbackMessage);
    }

    if (empty($_POST["zip_code"])) {
        $feedbackMessage = "Je vyžadováno poštovní směrovací číslo!";
        array_push($errorFeedbackArray, $feedbackMessage);
    }

    if (isset($_POST['deliveryChB'])) { // check if the checkbox for delivery details is checked
        if (empty($_POST["street_sec"])) {
            $feedbackMessage = "V sekundární adrese je vyžadována ulice s číslem popisným!";
            array_push($errorFeedbackArray, $feedbackMessage);
        }

        if (empty($_POST["city_sec"])) {
            $feedbackMessage = "V sekundární adrese je vyžadováno město!";
            array_push($errorFeedbackArray, $feedbackMessage);
        }

        if (empty($_POST["zip_code_sec"])) {
            $feedbackMessage = "V sekundární adrese je vyžadováno poštovní směrovací číslo!";
            array_push($errorFeedbackArray, $feedbackMessage);
        }
    }
// </editor-fold>

    if (empty($errorFeedbackArray)) {
        //success
        try {
            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            $conn->exec("set names utf8");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $hashedPassword = crypt($_POST["password"], 'sdfjsdnmvcmv.xcvuesfsdfdsljk');

            $stmt = $conn->prepare("SELECT id FROM role WHERE name = :role LIMIT 1");
            $stmt->bindParam(':role', $_POST["role"]);
            $stmt->execute();
            $role_id = $stmt->fetch();

            $stmt = $conn->prepare("INSERT INTO user (first_name, last_name, password, email, phone_number, role_id) 
            VALUES (:first_name, :last_name, :password, :email, :phone_number, :role_id)");
            $stmt->bindParam(':first_name', $_POST["first_name"]);
            $stmt->bindParam(':last_name', $_POST["last_name"]);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', $_POST["email"]);
            $stmt->bindParam(':phone_number', $_POST["phone_number"]);
            $stmt->bindParam(':role_id', $role_id[0]);
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
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) //checks if it's exception other_code of duplicity
            {
                $feedbackMessage = "Uživatel s touto e-mailovou adresou již existuje!";
                array_push($errorFeedbackArray, $feedbackMessage);

                # after unsuccessful INSERT, the id sequence was still incremented
                $stmt = $conn->prepare("ALTER TABLE user AUTO_INCREMENT = 1"); // reset the sequence
                $stmt->execute();

            } else
                $message = "Při registraci došlo k potížím, zkuste to prosím znovu!";
        }
        $successFeedback = "Uživatel byl přidán.";
    }
}

?>

<form id="admin-form" method="post">
    <h2>Přidat uživatele</h2>
    <?php
    if (!empty($errorFeedbackArray)) {
        echo "<b class='color-orange'>Při zadávání se vyskytly tyto chyby:</b><br>";
        foreach ($errorFeedbackArray as $errorFeedback) {
            echo "<b class='color-red'>" . $errorFeedback . "</b><br>";
        }
        echo "<br>";
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($successFeedback)) {
        header("Location:" . BASE_URL . "?page=user_management" . "&action=insert" . "&message=" . "<br><b class='color-green'>$successFeedback.</b><br>");
    }
    ?>
    <input id="admin-input" type="text" name="first_name" placeholder="Jméno"/>
    <input id="admin-input" type="text" name="last_name" placeholder="Příjmení">
    <input id="admin-input" type="password" name="password" placeholder="Heslo">
    <input id="admin-input" type="email" name="email" placeholder="E-mailová adresa"
           pattern="[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$">
    <input id="admin-input" type="tel" name="phone_number" placeholder="Telefonní číslo"
           pattern="(([0-9]{3} [0-9]{3} [0-9]{3})|([0-9]{3}[0-9]{3}[0-9]{3}))">
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
    <div>
        <?php require_once('./code/functions.php'); ?>
        <input type="checkbox" id="sec_addr_check" name="deliveryChB" onclick="showDeliveryDetails()"> Dodací údaje se
        liší od
        fakturačních
    </div>
    <br>
    <div id=secondary-address style="display: none">
        <input id="admin-input" type="text" name="street_sec" placeholder="Ulice">
        <input id="admin-input" type="text" name="city_sec" placeholder="Město">
        <input id="admin-input" type="text" name="zip_code_sec" placeholder="PSČ"
               pattern="(([0-9]{3} [0-9]{2})|([0-9]{5}))">
        <select id="admin-select" name="country_sec">
            <option value="Česká republika">Česká republika</option>
            <option value="Slovenská republika">Slovenská republika</option>
        </select>
    </div>
    <input id="admin-submit" type="submit" name="add_user" value="Přidat">
</form>