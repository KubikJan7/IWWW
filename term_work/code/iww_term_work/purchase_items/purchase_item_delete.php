<?php
$conn = Connection::getPdoInstance();

$stmt = $conn->prepare("DELETE FROM purchase_book WHERE id = :id");
$stmt->bindParam(":id", $_GET["item_id"]);
$stmt->execute();

$stmt = $conn->prepare("ALTER TABLE purchase_book AUTO_INCREMENT = 1");
$stmt->execute();

header("Location:" . BASE_URL . "?page=purchase_item_management&purchase_id=" . $_GET["purchase_id"] . "&item_id=" . $_GET["item_id"] . "&message=" . "<br><b class='color-orange'>Položka byla odstraněna.</b><br>");