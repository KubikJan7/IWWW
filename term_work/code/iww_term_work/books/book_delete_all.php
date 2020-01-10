<?php
$conn = Connection::getPdoInstance();
$stmt = $conn->prepare("DELETE FROM book;");
$stmt->execute();

header("Location:" . BASE_URL . "?page=book_management" . "&message=<p><b class='color-green'>Všechny knihy byly odstraněny.</b><p>");