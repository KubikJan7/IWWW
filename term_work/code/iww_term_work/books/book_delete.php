<?php
$conn = Connection::getPdoInstance();

$stmt = $conn->prepare("DELETE FROM book WHERE isbn = :isbn");
$stmt->bindParam(":isbn", $_GET["isbn"]);
$stmt->execute();

header("Location:" . BASE_URL . "?page=book_management" . "&message=" . "<br><b class='color-orange'>Kniha byla odstraněna.</b><br>");
