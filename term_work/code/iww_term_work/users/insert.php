<?php
$errorFeedbacks = array();
$successFeedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //todo more validation rules
    if (empty($_POST["username"])) {
        $feedbackMessage = "username is required";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($_POST["password"])) {
        $feedbackMessage = "password is required";
        array_push($errorFeedbacks, $feedbackMessage);
    }

    if (empty($errorFeedbacks)) {
        //success
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $hashedPassword = crypt($_POST["password"], 'sdfjsdnmvcmv.xcvuesfsdfdsljk');

        $stmt = $conn->prepare("INSERT INTO user (first_name, last_name, password, email, phone_number, role_id)
    VALUES (:first_name, :last_name, :password, :email, :phone_number, :role_id)");
        $stmt->bindParam(':first_name', $_POST["first_name"]);
        $stmt->bindParam(':last_name', $_POST["last_name"]);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $_POST["email"]);
        $stmt->bindParam(':phone_number', $_POST["phone_number"]);
        $stmt->bindParam(':role_id', $_POST["role_id"]);
        $stmt->execute();
        $successFeedback = "Uživatel byl přidán.";
    }
}

?>

<?php
if (!empty($errorFeedbacks)) {
    echo "Form contains following errors:<br>";
    foreach ($errorFeedbacks as $errorFeedback) {
        echo $errorFeedback . "<br>";
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($successFeedback)) {
    echo $successFeedback;
}
?>

<h1>Přidat uživatele</h1>
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
                        <label for="password_repeat"><b>Potvrzení hesla <b class="color-red">*</b></b></label>
                        <input type="password" name="password_repeat">
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
                        <label for="phone_number"><b>Telefon <b class="color-red">*</b></b></label>
                        <input type="tel" name="phone_number"
                               pattern="(([0-9]{3} [0-9]{3} [0-9]{3})|([0-9]{3}[0-9]{3}[0-9]{3}))">
                    </div>
                </div>
                <div id="form-container">
                    <div id="form-line">
                        <label for="last_name"><b>Příjmení <b class="color-red">*</b></b></label>
                        <input type="text" name="last_name">
                    </div>
                    <div id="form-line">
                        <label for="zip_code"><b>PSČ <b class="color-red">*</b></b></label>
                        <input type="text" name="zip_code"
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
            <input type="submit" name="insert_user" value="Přidat uživatele">

        </form>
        <p>Zaregistrovali jste se již dříve? <a href="<?= BASE_URL . "?page=login" ?>">Přihlášení</a>.</p>
        <br><br>
</div>
