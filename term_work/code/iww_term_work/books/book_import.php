<?php
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
if (isset($_POST["import-books"])) {
// Get the contents of the JSON file
    $strJsonFileContents = @file_get_contents($_FILES["fileToImport"]["tmp_name"]);
    if ($strJsonFileContents == false) {
        $message = "<p><b class='color-red'>Nebyl vybrán žádný soubor.</b></p>";
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
        $conn = Connection::getPdoInstance();
        $duplicityCount = 0;
        foreach ($array as $row) {
            try {
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
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) //checks if it's duplicate ISBN
                    $duplicityCount++;
                else
                    $message = "<p><b class='color-red'>Při importu nastaly potíže, zkuste to prosím později.</b></p>";
            }
        }
        if ($duplicityCount > 0)
            $message = "<p><b class='color-orange'>Do databáze bylo uloženo <b class='color-green'>" . (count($array) - $duplicityCount) . "</b> knih, <b class='color-red'>" . $duplicityCount . "</b> knih bylo ignorováno z důvodu duplicitního kódu ISBN!</b></p>";
        else
            $message = "<p><b class='color-green'>Do databáze byly uloženy všechny knihy ze zadaného souboru!</b></p>";

    }
    header("Location:" . BASE_URL . "?page=book_management" . "&message=$message");

}
?>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="fileToImport" id="fileToImport" accept="application/json">
    <br>
    <input id="import-btn" type="submit" value="Provést import" name="import-books">
</form>
