<?php
$conn = Connection::getPdoInstance();

$stmt = $conn->prepare("DELETE FROM purchase WHERE id = :id");
$stmt->bindParam(":id", $_GET["purchase_id"]);
$stmt->execute();

$stmt = $conn->prepare("ALTER TABLE purchase AUTO_INCREMENT = 1");
$stmt->execute();

header("Location:" . BASE_URL . "?page=purchase_management" . "&message=" . "<br><b class='color-orange'>Objednávka byla odstraněna.</b><br>");