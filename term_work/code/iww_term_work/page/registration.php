<?php
$message = "";
if (isset($_POST['registration'])) {
    if (!empty($_POST['email'])&&!empty($_POST['password'])&&!empty($_POST['password-repeat'])
        &&!empty($_POST['first-name'])&&!empty($_POST['last-name'])&&!empty($_POST['street'])
        &&!empty($_POST['zip-code'])&&!empty($_POST['city'])&&!empty($_POST['phone-number'])) {
        try {
            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // prepare sql and bind parameters
            $stmt = $conn->prepare("INSERT INTO user (email, created) VALUES (:email, NOW())");
            $stmt->bindParam(':email', $_POST["email"]);
            $stmt->execute();

            $message = "Your are subscribed!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            $message = "Unable to save to the database!" . $e->getMessage();
        }
    } else {
        $message = "Pole označena pomocí <b class=\"color-red\">'*'</b> je nutné vyplnit.";
    }
}
?>

<div class="centered-90">
    <br>
    <p class="color-orange">
        <?php
        if(!empty($message)) {
            echo $message;
            $message = "";
            ?><br>
        <?php
        }?>
    </p>
    <h1>Registrace nového uživatele</h1>
    <p>Vyplňte následující údaje pro vytvoření nového účtu.</p>
    <br>
    <form method="post">
        <h2>Registrační údaje</h2>
        <div id="form-contents">
            <div id="form-container">
                <div id="form-line">
                    <label for="email"><b>E-mailová adresa <b class="color-red">*</b></b></label>
                    <input type="email" name="email"
                           pattern="[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$"/>
                </div>
                <div id="form-line">
                    <label for="password"><b>Heslo <b class="color-red">*</b></b></label>
                    <input type="password" name="password">
                </div>

            </div>

            <div id="form-container">
                <div id="form-line">
                    <p id="form-line-blank"><br></p>
                </div>
                <div id="form-line">
                    <label for="password-repeat"><b>Potvrzení hesla <b class="color-red">*</b></b></label>
                    <input type="password" name="password-repeat">
                </div>
            </div>
        </div>
        <br>

        <h2>Fakturační údaje</h2>
        <div id="form-contents">
            <div id="form-container">
                <div id="form-line">
                    <label for="first_name"><b>Jméno <b class="color-red">*</b></b></label>
                    <input type="text" name="first_name">
                </div>
                <div id="form-line">
                    <label for="street"><b>Ulice <b class="color-red">*</b></b></label>
                    <input type="text" name="street"/>
                </div>
                <div id="form-line">
                    <label for="city"><b>Město <b class="color-red">*</b></b></label>
                    <input type="text" name="city"/>
                </div>
                <div id="form-line">
                    <label for="phone-number"><b>Telefon <b class="color-red">*</b></b></label>
                    <input type="tel" name="phone-number"
                           pattern="(([0-9]{3} [0-9]{3} [0-9]{3})|([0-9]{3}[0-9]{3}[0-9]{3}))">
                </div>
            </div>
            <div id="form-container">
                <div id="form-line">
                    <label for="last_name"><b>Příjmení <b class="color-red">*</b></b></label>
                    <input type="text" name="last_name">
                </div>
                <div id="form-line">
                    <label for="zip-code"><b>PSČ <b class="color-red">*</b></b></label>
                    <input type="text" name="zip-code"
                           pattern="(([0-9]{3} [0-9]{2})|([0-9]{3}[0-9]{2}))">
                </div>
                <div id="form-line">
                    <label for="country"><b>Země</b></label>
                    <select name="country">
                        <option value="Česká republika">Česká republika</option>
                        <option value="Slovenská republika">Slovenská republika</option>
                    </select>
                </div>
            </div>
        </div>
        <input type="submit" name="registration" value="Registrovat">

    </form>
    <p>Již jste se dříve zaregistrovali? <a href="<?= BASE_URL . "?page=login" ?>">Přihlášení</a>.</p>
    <br><br>
</div>