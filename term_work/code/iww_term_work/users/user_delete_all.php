<?php
$conn = CustomFunctions::createConnectionToDatabase();

$stmt = $conn->prepare("DELETE FROM address; ALTER TABLE address AUTO_INCREMENT = 1;");
$stmt->execute();
$stmt = $conn->prepare("DELETE FROM user; ALTER TABLE user AUTO_INCREMENT = 1;");
$stmt->execute();