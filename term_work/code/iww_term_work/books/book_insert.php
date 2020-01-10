<?php
$errorFeedbackArray = array();
$successFeedback = "";
$filename = "default.png";
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
            $filename = $_FILES["image"]["name"];
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

            $stmt = $conn->prepare("INSERT INTO book (isbn, name, author, price, publication_date, description, page_count, binding, image, language_id, genre_id) 
            VALUES (:isbn, :book_name, :author, :price, :publication_date, :description, :page_count, :binding, :image, :language_id, :genre_id)");
            $stmt->bindParam(':isbn', $_POST["isbn"]);
            $stmt->bindParam(':book_name', $_POST["name"]);
            $stmt->bindParam(':author', $_POST["author"]);
            $stmt->bindParam(':price', $_POST["price"]);
            $stmt->bindParam(':publication_date', $_POST["publication_date"]);
            $stmt->bindParam(':description', $_POST["description"]);
            $stmt->bindParam(':page_count', $_POST["page_count"]);
            $stmt->bindParam(':binding', $_POST["binding"]);
            $stmt->bindParam(':image', $filename);
            $stmt->bindParam(':language_id', $language_id["id"]);
            $stmt->bindParam(':genre_id', $genre_id["id"]);
            $stmt->execute();
            $successFeedback = "Kniha byla přidána.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { //checks if it's exception code of duplicity
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

<form id="custom-form" method="post" enctype="multipart/form-data">
    <h2>Přidání knihy</h2>
    <?php
    if (!empty($errorFeedbackArray)) {
        echo "<b class='color-orange'>Při zadávání se vyskytly tyto chyby:</b><br>";
        foreach ($errorFeedbackArray as $errorFeedback) {
            echo "<b class='color-red'>" . $errorFeedback . "</b><br>";
        }
        echo "<br>";
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($successFeedback)) {
        header("Location:" . BASE_URL . "?page=book_management" . "&action=book_insert" . "&message=" . "<br><b class='color-green'>$successFeedback</b><br>");
    }
    ?>
    <input id="admin-input" type="text" name="isbn" placeholder="ISBN" pattern="([0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{1})"/>
    <input id="admin-input" type="text" name="name" placeholder="Název">
    <input id="admin-input" type="text" name="author" placeholder="Autor">
    <input id="admin-input" type="number" name="price" placeholder="Cena [Kč]">
    <input id="admin-input" type="date" name="publication_date">
    <input id="admin-input" type="number" name="page_count" placeholder="Počet stran">
    <input id="admin-input" type="text" name="binding" placeholder="Vazba [Měkká, Pevná, ...]">

    <select id="admin-select" name="genre">
        <?php
        foreach (CustomFunctions::getAllBookGenres() AS $genre) { ?>
            <option value="<?= $genre["name"] ?>"><?= $genre["name"] ?></option>
        <?php } ?>
    </select>
    <select id="admin-select" name="language">
        <?php
        foreach (CustomFunctions::getAllBookLanguages() AS $language) { ?>
            <option value="<?= $language["name"] ?>"><?= $language["name"] ?></option>
        <?php } ?>
    </select>
    <div id="input-file-line">
        <label for="last_name">Obrázek titulu: </label>
        <input id="input-file" type="file" name="image" accept="image/*">
    </div>
    <textarea id=book_desc_writeable name="description" placeholder="Popis knihy" rows="6"></textarea>
    <br>
    <input id="custom-submit" type="submit" name="insert_book" value="Přidat">
</form>
