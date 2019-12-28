<?php

$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("DELETE FROM address; ALTER TABLE address AUTO_INCREMENT = 1;");
$stmt->execute();

$stmt = $conn->prepare("DELETE FROM user; ALTER TABLE user AUTO_INCREMENT = 1;");
$stmt->execute();