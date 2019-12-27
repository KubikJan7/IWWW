<?php

if (!empty($_POST) && !empty($_POST["loginMail"]) && !empty($_POST["loginPassword"])) {

    //connect to database
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $hashedPassword = crypt($_POST["loginPassword"], 'sdfjsdnmvcmv.xcvuesfsdfdsljk'); // hash the password from login

    //get user by email and password
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, role_id FROM user 
                                      WHERE email= :email and password = :password");
    $stmt->bindParam(':email', $_POST["loginMail"]);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();
    $user = $stmt->fetch();
    if (!$user) {
        $message = "Uživatel s danou e-mailovou adresou nebyl nalezen!";
    } else {
        echo "You are logged in. Your ID is: " . $user["id"];
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["first_name"] = $user["first_name"];
        $_SESSION["last_name"] = $user["last_name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role_id"] = $user["role_id"];
        header('Location: ' . BASE_URL);
        echo "<script>setTimeout(function(){window.top.location=\"index.php\"} ,1000);</script>";
    }

} else if (!empty($_POST)) {
    $message = "Prosím zadejte validní přihlašovací údaje.";
}

?>

<div class="centered-50">
    <br>
    <b class="color-red">
        <?php
        if (!empty($message)) {
            echo $message;
            $message = "";
            ?><br>
            <?php
        } ?>
    </b>
    <h1>Přihlášení</h1>
    <br>
    <form method="post">
        <div id="form-line">
            <label for="email"><b>E-mailová adresa</b></label>
            <input type="email" name="loginMail">
        </div>
        <div id="form-line">
            <label for="email"><b>Heslo</b></label>
            <input type="password" name="loginPassword">
        </div>
        <input type="submit" value="Přihlásit">
    </form>
    <p>Ještě nemáte vytvořený účet? <a href="<?= BASE_URL . "?page=registration" ?>">Registrace</a>.</p>
</div>