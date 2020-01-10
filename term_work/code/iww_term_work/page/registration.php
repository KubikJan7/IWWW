<?php

$message = "";
$success = false;

if (isset($_POST['registration'])) {

    if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_repeat'])
        && !empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['street'])
        && !empty($_POST['zip_code']) && !empty($_POST['city']) && !empty($_POST['phone_number'])) {

        if (isset($_POST['deliveryChB'])) { // check if the checkbox for delivery details is checked
            if (empty($_POST['street_sec']) || empty($_POST['zip_code_sec']) || empty($_POST['city_sec'])) {
                $message = "Je nutné vyplnit dodací údaje.";
                goto end;
            }
        }

        if ($_POST["password"] != $_POST["password_repeat"])
            $message = "Zadaná hesla se neshodují!";
        else
            try {
                $conn = Connection::getPdoInstance();

                $hashedPassword = crypt($_POST["password"], 'sdfjsdnmvcmv.xcvuesfsdfdsljk');  //hash of the user password

                // prepare sql and bind parameters
                $stmt = $conn->prepare("INSERT INTO user (first_name, last_name, password, email, phone_number, role) 
            VALUES (:first_name, :last_name, :password, :email, :phone_number, 'customer')");
                $stmt->bindParam(':first_name', $_POST["first_name"]);
                $stmt->bindParam(':last_name', $_POST["last_name"]);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':email', $_POST["email"]);
                $stmt->bindParam(':phone_number', $_POST["phone_number"]);
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

                $login_url = BASE_URL . "?page=login";
                $message = "Registrace proběhla úspěšně. Nyní se můžete <a id='link' href =$login_url>přihlásit</a>.";
                $success = true;
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) //checks if it's exception code of duplicity
                {
                    $message = "Uživatel s touto e-mailovou adresou již existuje!";

                    # after unsuccessful INSERT, the id sequence was still incremented
                    $stmt = $conn->prepare("ALTER TABLE user AUTO_INCREMENT = 1"); // reset the sequence
                    $stmt->execute();

                } else
                    $message = "Při registraci došlo k potížím, zkuste to prosím znovu!";
            }
    } else
        $message = "Všechna pole označená pomocí '*' je nutné vyplnit.";

    end:
}
?>

<div class="centered-90">
    <br>
    <?php
    if ($success){ ?>
    <b class="color-green">
        <?php
        }else { ?>
        <b class="color-red">
            <?php
            }
            if (!empty($message)) {
                echo $message;
                $message = "";
                ?><br>
                <?php
            } ?>
        </b>
        <h1>Registrace nového uživatele</h1>
        <p>Vyplňte následující údaje pro vytvoření nového účtu.</p>
        <br>
        <form method="post">
            <h2>Registrační údaje</h2>
            <div id="custom-form-contents">
                <div id="custom-form-container">
                    <div id="custom-form-line">
                        <label for="email"><b>E-mailová adresa <b class="color-red">*</b></b></label>
                        <input type="email" name="email"
                               pattern="[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$"/>
                    </div>
                    <div id="custom-form-line">
                        <label for="password"><b>Heslo <b class="color-red">*</b></b></label>
                        <input type="password" name="password">
                    </div>

                </div>

                <div id="custom-form-container">
                    <div id="custom-form-line">
                        <p id="custom-form-line-blank"><br></p>
                    </div>
                    <div id="custom-form-line">
                        <label for="password_repeat"><b>Potvrzení hesla <b class="color-red">*</b></b></label>
                        <input type="password" name="password_repeat">
                    </div>
                </div>
            </div>
            <br>

            <h2>Fakturační údaje</h2>
            <div id="custom-form-contents">
                <div id="custom-form-container">
                    <div id="custom-form-line">
                        <label for="first_name"><b>Jméno <b class="color-red">*</b></b></label>
                        <input type="text" name="first_name">
                    </div>
                    <div id="custom-form-line">
                        <label for="street"><b>Ulice <b class="color-red">*</b></b></label>
                        <input type="text" name="street"/>
                    </div>
                    <div id="custom-form-line">
                        <label for="city"><b>Město <b class="color-red">*</b></b></label>
                        <input type="text" name="city"/>
                    </div>
                    <div id="custom-form-line">
                        <label for="phone_number"><b>Telefon <b class="color-red">*</b></b></label>
                        <input type="tel" name="phone_number"
                               pattern="(([0-9]{3} [0-9]{3} [0-9]{3})|([0-9]{3}[0-9]{3}[0-9]{3}))">
                    </div>
                </div>
                <div id="custom-form-container">
                    <div id="custom-form-line">
                        <label for="last_name"><b>Příjmení <b class="color-red">*</b></b></label>
                        <input type="text" name="last_name">
                    </div>
                    <div id="custom-form-line">
                        <label for="zip_code"><b>PSČ <b class="color-red">*</b></b></label>
                        <input type="text" name="zip_code"
                               pattern="(([0-9]{3} [0-9]{2})|([0-9]{5}))">
                    </div>
                    <div id="custom-form-line">
                        <label for="country"><b>Země</b></label>
                        <select id="registration" name="country">
                            <option value="Česká republika">Česká republika</option>
                            <option value="Slovenská republika">Slovenská republika</option>
                        </select>
                    </div>
                </div>
            </div>

            <br>

            <div>
                <input type="checkbox" id="sec_addr_check" name="sec_addr_check" onclick="showDeliveryDetails()"> Dodací
                údaje se
                liší od
                fakturačních
            </div>

            <div id=secondary-address style="display: none">
                <h2>Dodací údaje</h2>
                <div id="custom-form-contents">
                    <div id="custom-form-container">
                        <div id="custom-form-line">
                            <label for="street"><b>Ulice <b class="color-red">*</b></b></label>
                            <input type="text" name="street_sec"/>
                        </div>
                        <div id="custom-form-line">
                            <label for="city"><b>Město <b class="color-red">*</b></b></label>
                            <input type="text" name="city_sec"/>
                        </div>
                    </div>
                    <div id="custom-form-container">
                        <div id="custom-form-line">
                            <label for="zip_code"><b>PSČ <b class="color-red">*</b></b></label>
                            <input type="text" name="zip_code_sec"
                                   pattern="(([0-9]{3} [0-9]{2})|([0-9]{3}[0-9]{2}))">
                        </div>
                        <div id="custom-form-line">
                            <label for="country"><b>Země</b></label>
                            <select id="registration" name="country_sec">
                                <option value="Česká republika">Česká republika</option>
                                <option value="Slovenská republika">Slovenská republika</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <input type="submit" name="registration" value="Registrovat">

        </form>
        <p>Zaregistrovali jste se již dříve? <a href="<?= BASE_URL . "?page=login" ?>">Přihlášení</a>.</p>
        <br><br>
</div>