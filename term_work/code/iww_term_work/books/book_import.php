<!--
***************************************************************************************
*    Title: How To Read A JSON File Using PHP With Examples
*    Author:  Dan Englishby
*    Date: 2019
*    Code version: 1.0
*    Availability: https://www.codewall.co.uk/how-to-read-json-file-using-php-examples/
*
****************************************************************************************
-->
<?php
// Get the contents of the JSON file
$strJsonFileContents = file_get_contents("./json_files/books.json");
// Convert to array
$array = json_decode($strJsonFileContents, true);
?>
<!--
***************************************************************************************
*    End of quoted code
****************************************************************************************
-->

<?php
// save data to database
foreach ($array as $row) {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $conn->exec("set names utf8");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $hashedPassword = crypt($_POST["password"], 'sdfjsdnmvcmv.xcvuesfsdfdsljk');

    $stmt = $conn->prepare("SELECT id FROM role WHERE name = :role LIMIT 1");
    $stmt->bindParam(':role', $_POST["role"]);
    $stmt->execute();
    $role_id = $stmt->fetch();
}
