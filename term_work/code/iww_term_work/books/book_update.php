<?php
$errorFeedbackArray = array();
$successFeedback = "";
$bookRepo = new BookRepository(Connection::getPdoInstance());
$image = $bookRepo->getImageByISBN($_GET["isbn"]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
// <editor-fold default-state="collapsed" desc="input-validation">
    if (empty($_POST["isbn"])) {
        $feedbackMessage = "Je vyžadován kód ISBN!";
        array_push($errorFeedbackArray, $feedbackMessage);
    }

    if (empty($_POST["name"])) {
        $feedbackMessage = "Je vyžadován název knihy!";
        array_push($errorFeedbackArray, $feedbackMessage);
    }

    if (empty($_POST["price"])) {
        $feedbackMessage = "Je vyžadována cena knihy!";
        array_push($errorFeedbackArray, $feedbackMessage);
    }

    //image validation
    if (!empty($_FILES["image"]["name"])) {
        $feedbackMessage = CustomFunctions::uploadPicture($_FILES["image"]);
        if ($feedbackMessage != "")
            array_push($errorFeedbackArray, $feedbackMessage);
        else
            $image = $_FILES["image"]["name"];
    }
// </editor-fold>

    if (empty($errorFeedbackArray)) {
        // save data to database
        try {
            $conn = Connection::getPdoInstance();

            $stmt = $conn->prepare("SELECT genre.id FROM genre WHERE genre.name = :genre_name;");
            $stmt->bindParam(':genre_name', $_POST["genre"]);
            $stmt->execute();
            $genre_id = $stmt->fetch();

            $stmt = $conn->prepare("SELECT language.id FROM language WHERE language.name=:language_name");
            $stmt->bindParam(':language_name', $_POST["language"]);
            $stmt->execute();
            $language_id = $stmt->fetch();

            // Found duplicate values
            $stmt = $conn->prepare("SELECT COUNT(*) FROM book WHERE isbn = :isbn");
            $stmt->bindParam(':isbn', $_POST["isbn"]);
            $stmt->execute();
            $same_isbn_count = $stmt->fetch();

            //checks if count of duplicity is bigger than 0 and if the duplicity is not the original value
            if ($same_isbn_count != 0 && $_GET['isbn'] != $_POST['isbn'])
                throw new PDOException("Duplicate ISBN");

            $stmt = $conn->prepare("UPDATE book SET  isbn = :isbn, name = :name, author = :author, 
                price = :price, publication_date = :publication_date, description = :description, page_count = :page_count, 
                binding = :binding, image = :image, language_id = :language_id, genre_id = :genre_id WHERE isbn = :isbn");
            $stmt->bindParam(':isbn', $_GET["isbn"]);
            $stmt->bindParam(':name', $_POST["name"]);
            $stmt->bindParam(':author', $_POST["author"]);
            $stmt->bindParam(':price', $_POST["price"]);
            $stmt->bindParam(':publication_date', $_POST["publication_date"]);
            $stmt->bindParam(':description', $_POST["description"]);
            $stmt->bindParam(':page_count', $_POST["page_count"]);
            $stmt->bindParam(':binding', $_POST["binding"]);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':language_id', $language_id["id"]);
            $stmt->bindParam(':genre_id', $genre_id["id"]);
            $stmt->execute();
            $successFeedback = "Kniha byla upravena.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000 || $e->getMessage() == "Duplicate ISBN") { //checks if it's exception code of duplicity
                $feedbackMessage = "<p><b class='color-red'>Databáze již obsahuje knihu se stejným ISBN!</b></p>";
                array_push($errorFeedbackArray, $feedbackMessage);
            } else {
                $feedbackMessage = "<p><b class='color-red'>Při importu nastaly potíže, zkuste to prosím později.</b></p>";
                array_push($errorFeedbackArray, $feedbackMessage);
            }
        }
    }
}

?>

<?php
if (empty($errorFeedbackArray)) { //load origin data from database
    $conn = Connection::getPdoInstance();

    $book = $bookRepo->getByISBN($_GET["isbn"]);

    $isbn = $book["isbn"];
    $name = $book["name"];
    $author = $book["author"];
    $price = $book["price"];
    $publication_date = $book["publication_date"];

    $description = $book["description"];
    $page_count = $book["page_count"];
    $binding = $book["binding"];
    $language = $book["language"];
    $genre = $book["genre"];

} else { //in case of any error, load data
    $isbn = $_POST["isbn"];
    $name = $_POST["name"];
    $author = $_POST["author"];
    $price = $_POST["price"];
    $publication_date = $_POST["publication_date"];

    $description = $_POST["description"];
    $page_count = $_POST["page_count"];
    $binding = $_POST["binding"];
    $language = $_POST["language"];
    $genre = $_POST["genre"];
}
?>

<form id="custom-form" method="post" enctype="multipart/form-data">
    <h2>Upravit knihu</h2>
    <?php
    if (!empty($errorFeedbackArray)) {
        echo "<b class='color-orange'>Při zadávání se vyskytly tyto chyby:</b><br>";
        foreach ($errorFeedbackArray as $errorFeedback) {
            echo "<b class='color-red'>" . $errorFeedback . "</b><br>";
        }
        echo "<br>";
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($successFeedback)) {
        header("Location:" . BASE_URL . "?page=book_management" . "&action=book_update" . "&message=" . "<br><b class='color-green'>$successFeedback</b><br>" . "&isbn=" . $_GET["isbn"]);
    }
    ?>
    <input id="admin-input" type="text" name="isbn" placeholder="ISBN" pattern="([0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{1})"
           value="<?= $isbn; ?>">
    <input id="admin-input" type="text" name="name" placeholder="Název" value="<?= $name; ?>">
    <input id="admin-input" type="text" name="author" placeholder="Autor" value="<?= $author; ?>">
    <input id="admin-input" type="number" name="price" placeholder="Cena [Kč]" value="<?= $price; ?>">
    <input id="admin-input" type="date" name="publication_date" placeholder="Datum vydání"
           value="<?= $publication_date; ?>">
    <input id="admin-input" type="number" name="page_count" placeholder="Počet stran" value="<?= $page_count; ?>">
    <input id="admin-input" type="text" name="binding" placeholder="Vazba" value="<?= $binding; ?>">

    <select id="admin-select" name="genre">
        <?php
        foreach (CustomFunctions::getAllBookGenres() AS $genre_row) { ?>
            <option value="<?= $genre_row["name"] ?>"<?php if ($genre_row["name"] == $genre) echo "SELECTED"; ?>><?= $genre_row["name"] ?></option>
        <?php } ?>
    </select>
    <select id="admin-select" name="language">
        <?php
        foreach (CustomFunctions::getAllBookLanguages() AS $language_row) { ?>
            <option value="<?= $language_row["name"] ?>"<?php if ($language_row["name"] == $language) echo "SELECTED"; ?>><?= $language_row["name"] ?></option>
        <?php } ?>
    </select>
    <div id="input-file-line">
        <label for="book_img">Obrázek titulu: </label>
        <input id="input-file" type="file" name="image" accept="image/*">
        <label for="book_img_old">Současný: </label>
        <img id="update_img" src="./images/books/<?= $image ?>" alt="<?= $image ?>" width="40">
    </div>
    <textarea id=book_desc_writeable name="description" rows="6" spellcheck="false"><?= $description;?></textarea>
    <br>
    <input id="custom-submit" type="submit" name="insert_book" value="Upravit">
</form>
