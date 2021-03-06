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

    if (isset($_POST['newPasswordChB'])) //check if the checkbox for showing password field is checked
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
            $conn = Connection::getPdoInstance();

            $hashedPassword = crypt($_POST["password"], 'sdfjsdnmvcmv.xcvuesfsdfdsljk');

            $stmt = $conn->prepare("SELECT password FROM user WHERE user.id = :id");
            $stmt->bindParam(':id', $_GET["user_id"]);
            $stmt->execute();
            $fetched_data = $stmt->fetch();

            $stmt = $conn->prepare("UPDATE user SET first_name=:first_name, last_name=:last_name, 
                password= :password, email= :email, phone_number=:phone_number, role = :role WHERE id= :id");
            $stmt->bindParam(':first_name', $_POST["first_name"]);
            $stmt->bindParam(':last_name', $_POST["last_name"]);
            if (isset($_POST['newPasswordChB'])) //check if the checkbox for showing password field is checked
                $stmt->bindParam(':password', $hashedPassword);
            else
                $stmt->bindParam(':password', $fetched_data['password']);
            $stmt->bindParam(':email', $_POST["email"]);
            $stmt->bindParam(':phone_number', $_POST["phone_number"]);
            $stmt->bindParam(':role', $_POST["role"]);
            $stmt->bindParam(':id', $_GET["user_id"]);
            $stmt->execute();

            $stmt = $conn->prepare("UPDATE address SET address=:address, city=:city, 
                zip_code= :zip_code, country= :country WHERE user_id= :user_id AND type = 'primary'");
            $stmt->bindParam(':address', $_POST["street"]);
            $stmt->bindParam(':city', $_POST["city"]);
            $stmt->bindParam(':zip_code', $_POST["zip_code"]);
            $stmt->bindParam(':country', $_POST["country"]);
            $stmt->bindParam(':user_id', $_GET["user_id"]);
            $stmt->execute();

            if (isset($_POST['deliveryChB'])) //check if the checkbox for delivery details is checked
            {
                $stmt = $conn->prepare(
                    "SELECT COUNT(*) FROM address WHERE address.user_id = :id AND type = 'secondary'");
                $stmt->bindParam(':id', $_GET["user_id"]);
                $stmt->execute();
                $sec_addr = $stmt->fetch();
                if ($sec_addr[0] == 0) { //check if secondary address does exist
                    $stmt = $conn->prepare("INSERT INTO address (address, city, zip_code, country, type, user_id)
                VALUES (:address, :city, :zip_code, :country, 'secondary', :user_id)");
                    $stmt->bindParam(':address', $_POST["street_sec"]);
                    $stmt->bindParam(':city', $_POST["city_sec"]);
                    $stmt->bindParam(':zip_code', $_POST["zip_code_sec"]);
                    $stmt->bindParam(':country', $_POST["country_sec"]);
                    $stmt->bindParam(':user_id', $_GET["user_id"]);
                    $stmt->execute();
                } else {
                    $stmt = $conn->prepare("UPDATE address SET address=:address, city=:city, 
                zip_code= :zip_code, country= :country WHERE user_id= :user_id AND type = 'secondary'");
                    $stmt->bindParam(':address', $_POST["street_sec"]);
                    $stmt->bindParam(':city', $_POST["city_sec"]);
                    $stmt->bindParam(':zip_code', $_POST["zip_code_sec"]);
                    $stmt->bindParam(':country', $_POST["country_sec"]);
                    $stmt->bindParam(':user_id', $_GET["user_id"]);
                    $stmt->execute();
                }
            } else {
                $stmt = $conn->prepare(
                    "SELECT COUNT(*) FROM address WHERE address.user_id = :id AND type = 'secondary'");
                $stmt->bindParam(':id', $_GET["user_id"]);
                $stmt->execute();
                $sec_addr = $stmt->fetch();

                if (!$sec_addr[0] == 0) { //check if secondary address does exist
                    {
                        $stmt = $conn->prepare("DELETE FROM address WHERE user_id = :id AND type = 'secondary'");
                        $stmt->bindParam(":id", $_GET["user_id"]);
                        $stmt->execute();
                    }
                }
            }
            $successFeedback = "Účet byl upraven.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) //checks if it's exception other_code of duplicity
            {
                $feedbackMessage = "Uživatel s touto e-mailovou adresou již existuje!";
                array_push($errorFeedbackArray, $feedbackMessage);

                # after unsuccessful INSERT, the id sequence was still incremented
                $stmt = $conn->prepare("ALTER TABLE user AUTO_INCREMENT = 1"); // reset the sequence
                $stmt->execute();

            } else {
                $feedbackMessage = "Při registraci došlo k potížím, zkuste to prosím znovu!";
                array_push($errorFeedbackArray, $feedbackMessage);
            }
        }


    }
}
?>

<?php
if (empty($errorFeedbackArray)) { //load origin data from database
    $conn = Connection::getPdoInstance();

    $stmt = $conn->prepare("SELECT * FROM user, address WHERE user.id = :id AND address.user_id = user.id AND type = 'primary'");
    $stmt->bindParam(':id', $_GET["user_id"]);
    $stmt->execute();
    $user = $stmt->fetch();

    $stmt = $conn->prepare("SELECT address, city, zip_code, country FROM address, user WHERE user.id = :id 
                    AND address.user_id = user.id AND type = 'secondary'");
    $stmt->bindParam(':id', $_GET["user_id"]);
    $stmt->execute();
    $second_addr = $stmt->fetch();

    $first_name = $user["first_name"];
    $last_name = $user["last_name"];
    $email = $user["email"];
    $phone_number = $user["phone_number"];
    $role = $user["role"];

    $address = $user["address"];
    $city = $user["city"];
    $zip_code = $user["zip_code"];
    $country = $user["country"];

    $address_sec = $second_addr["address"];
    $city_sec = $second_addr["city"];
    $zip_code_sec = $second_addr["zip_code"];
    $country_sec = $second_addr["country"];

} else { //in case of any error, load data
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $passwordValue = $_POST["password"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
    $role = $_POST["role"];

    $address = $_POST["street"];
    $city = $_POST["city"];
    $zip_code = $_POST["zip_code"];
    $country = $_POST["country"];

    $address_sec = $_POST["street_sec"];
    $city_sec = $_POST["city_sec"];
    $zip_code_sec = $_POST["zip_code_sec"];
    $country_sec = $_POST["country_sec"];
}

?>
    <form id="custom-form" method="post">
        <h2>Upravit účet</h2>
        <?php
        if (!empty($errorFeedbackArray)) {
            echo "<b class='color-orange'>Při zadávání se vyskytly tyto chyby:</b><br>";
            foreach ($errorFeedbackArray as $errorFeedback) {
                echo "<b class='color-red'>" . $errorFeedback . "</b><br>";
            }
            echo "<br>";
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($successFeedback)) {
            header("Location:" . BASE_URL . "?page=" . $_GET["page"] . "&action=user_update" . "&user_id=" .
                $_GET["user_id"] . "&message=" . "<br><b class='color-green'>$successFeedback</b><br>");
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
            <option value="administrator" <?php if ($role == "administrator") echo "SELECTED"; ?>>Administrátor</option>
            <option value="employee" <?php if ($role == "employee") echo "SELECTED"; ?>>Zaměstnanec</option>
            <option value="customer" <?php if ($role == "customer") echo "SELECTED"; ?>>Zákazník</option>
        </select>
        <br>
        <input id="admin-input" type="text" name="street" placeholder="Ulice" value="<?= $address; ?>">
        <input id="admin-input" type="text" name="city" placeholder="Město" value="<?= $city; ?>">
        <input id="admin-input" type="text" name="zip_code" placeholder="PSČ" pattern="(([0-9]{3} [0-9]{2})|([0-9]{5}))"
               value="<?= $zip_code; ?>">
        <select id="admin-select" name="country">
            <option value="Česká republika" <?php if ($country == "Česká republika") echo "SELECTED"; ?>>Česká
                republika
            </option>
            <option value="Slovenská republika" <?php if ($country == "Slovenská republika") echo "SELECTED"; ?>>
                Slovenská republika
            </option>
        </select>
        <div><input type="checkbox" id="new_passw_check" name="newPasswordChB" onclick="showNewPasswordField()"> Nové
            heslo
        </div>
        <br>
        <div>
            <input type="checkbox" id="sec_addr_check" name="deliveryChB" <?= (isset($address_sec)) ? "checked" : "" ?>
                   onclick="showDeliveryDetails()"> Dodací údaje se
            liší od fakturačních
        </div>
        <br>
        <div id=secondary-address style="display: none">
            <input id="admin-input" type="text" name="street_sec" placeholder="Ulice" value="<?= $address_sec; ?>">
            <input id="admin-input" type="text" name="city_sec" placeholder="Město" value="<?= $city_sec; ?>">
            <input id="admin-input" type="text" name="zip_code_sec" placeholder="PSČ"
                   pattern="(([0-9]{3} [0-9]{2})|([0-9]{5}))" value="<?= $zip_code_sec; ?>">
            <select id="admin-select" name="country_sec">
                <option value="Česká republika" <?php if ($country_sec == "Česká republika") echo "SELECTED"; ?>>Česká
                    republika
                </option>
                <option value="Slovenská republika" <?php if ($country_sec == "Slovenská republika") echo "SELECTED"; ?>>
                    Slovenská republika
                </option>
            </select>
        </div>
        <input id="custom-submit" type="submit" name="add_user" value="Upravit">
    </form>

<?php
if (isset($address_sec))
    echo '<script>showDeliveryDetails();</script>';
