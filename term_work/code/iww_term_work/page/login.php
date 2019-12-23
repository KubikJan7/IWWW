<?php

if (!empty($_POST) && !empty($_POST["loginMail"]) && !empty($_POST["loginPassword"])) {

    //connect to database
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $hashedPassword = crypt($_POST["loginPassword"], 'sdfjsdnmvcmv.xcvuesfsdfdsljk');
    //get user by email and password
    $stmt = $conn->prepare("SELECT id, username, email FROM user 
                                      WHERE email= :email and password = :password");
    $stmt->bindParam(':email', $_POST["loginMail"]);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();
    $user = $stmt->fetch();
    if (!$user) {
        echo "user not found";
    } else {
        echo "You are logged in. Your ID is: " . $user["id"];
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["email"] = $user["email"];
        echo "<script>setTimeout(function(){window.top.location=\"index.php\"} ,1000);</script>";
    }

} else if (!empty($_POST)) {
    echo "Username and password are required";
}

?>


<form method="post">

    <input type="email" name="loginMail" placeholder="Insert your email">
    <input type="password" name="loginPassword" placeholder="Password">
    <input type="submit" value="Log in">

</form>
