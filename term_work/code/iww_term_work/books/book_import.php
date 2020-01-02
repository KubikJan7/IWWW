<?php
if (isset($_GET["filepath"]))
    $filepath = $_GET["filepath"];
$message = "";
?>
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
$strJsonFileContents = @file_get_contents($filepath);
if ($strJsonFileContents == false) {
    $message = "<p><b class='color-red'>Zadejte prosím validní cestu!</b></p>";
} else {
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
    try {
        foreach ($array as $row) {
            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            $conn->exec("set names utf8");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT genre.id FROM genre WHERE genre.name = :genre_name;");
            $stmt->bindParam(':genre_name', $row["genre"]);
            $stmt->execute();
            $genre_id = $stmt->fetch();

            $stmt = $conn->prepare("SELECT language.id FROM language WHERE language.name=:language_name");
            $stmt->bindParam(':language_name', $row["language"]);
            $stmt->execute();
            $language_id = $stmt->fetch();

            $stmt = $conn->prepare("INSERT INTO book (isbn, name, author, price, publication_date, description, page_count, binding, image, language_id, genre_id) 
            VALUES (:isbn, :book_name, :author, :price, :publication_date, :description, :page_count, :binding, :image, :language_id, :genre_id)");
            $stmt->bindParam(':isbn', $row["isbn"]);
            $stmt->bindParam(':book_name', $row["name"]);
            $stmt->bindParam(':author', $row["author"]);
            $stmt->bindParam(':price', $row["price"]);
            $stmt->bindParam(':publication_date', $row["publication_date"]);
            $stmt->bindParam(':description', $row["description"]);
            $stmt->bindParam(':page_count', $row["page_count"]);
            $stmt->bindParam(':binding', $row["binding"]);
            $stmt->bindParam(':image', $row["image"]);
            $stmt->bindParam(':language_id', $language_id["id"]);
            $stmt->bindParam(':genre_id', $genre_id["id"]);
            $stmt->execute();
            $message = "<p><b class='color-green'>Do databáze byly uloženy knihy ze zadaného souboru!</b></p>";
        }

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) //checks if it's exception code of duplicity
            $message = "<p><b class='color-red'>Databáze již obsahuje knihu se stejným ISBN!</b></p>";
        else
            $message = "<p><b class='color-red'>Při importu nastaly potíže, zkuste to prosím později.</b></p>";
    }
}
header("Location:" . BASE_URL . "?page=book_management" . "&message=$message");
