<?php
$conn = Connection::getPdoInstance();

$stmt = $conn->prepare("DELETE FROM address WHERE user_id = :id; 
                                  DELETE FROM user WHERE id = :id;");
$stmt->bindParam(":id", $_GET["user_id"]);
$stmt->execute();

$stmt = $conn->prepare("ALTER TABLE address AUTO_INCREMENT = 1; ALTER TABLE user AUTO_INCREMENT = 1;");
$stmt->execute();
header("Location:" . BASE_URL . "?page=user_management" . "&message=" . "<br><b class='color-orange'>Uživatel byl odstraněn.</b><br>");
