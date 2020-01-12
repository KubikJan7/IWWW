<?php
$errorFeedbackArray = array();
$successFeedback = "";
$filename = "default.png";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

// <editor-fold default-state="collapsed" desc="input-validation">
    if (empty($_POST["isbn"])) {
        $feedbackMessage = "Je vyžadován ISBN kód knihy!";
        array_push($errorFeedbackArray, $feedbackMessage);
    }
    if (empty($_POST["quantity"])) {
        $feedbackMessage = "Je vyžadováno množství položek!";
        array_push($errorFeedbackArray, $feedbackMessage);
    }
// </editor-fold>

    if (empty($errorFeedbackArray)) {
        // save data to database
        try {
            $conn = Connection::getPdoInstance();

            $bookRepo = new BookRepository($conn);
            $book = $bookRepo->getByISBN($_POST["isbn"]);

            $purchaseItemsRepo = new PurchaseBookRepository($conn);
            $items = $purchaseItemsRepo->getItemsByPurchaseIdAndISBN($_GET["purchase_id"], $_POST["isbn"]);
            if(count($items)>0)
                throw new LogicException();

            if (empty($book))
                throw new UnexpectedValueException();

            $price = $book["price"] * $_POST["quantity"];

            $stmt = $conn->prepare("INSERT INTO purchase_book (price, quantity, purchase_id, book_isbn) 
            VALUES (:price, :quantity, :purchase_id, :book_isbn)");
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':quantity', $_POST["quantity"]);
            $stmt->bindParam(':purchase_id', $_GET["purchase_id"]);
            $stmt->bindParam(':book_isbn', $book["isbn"]);
            $stmt->execute();
            $successFeedback = "Položka byla přidána do objednávky.";

        } catch (UnexpectedValueException $e) {
            $feedbackMessage = "<p><b class='color-red'>Zadaný kód ISBN se nenachází v databázi.</b></p>";
            array_push($errorFeedbackArray, $feedbackMessage);
        } catch (LogicException $e) {
            $feedbackMessage = "<p><b class='color-red'>V databázi se již nachází kniha se zadaným kódem ISBN.</b></p>";
            array_push($errorFeedbackArray, $feedbackMessage);
        } catch (Exception $e) {
            $feedbackMessage = "<p><b class='color-red'>Při přidávání nastaly potíže, zkuste to prosím později.</b></p>";
            array_push($errorFeedbackArray, $feedbackMessage);

        }
    }
}

?>

<form id="custom-form" method="post" enctype="multipart/form-data">
    <h2>Přidání položky</h2>
    <?php
    if (!empty($errorFeedbackArray)) {
        echo "<b class='color-orange'>Při zadávání se vyskytly tyto chyby:</b><br>";
        foreach ($errorFeedbackArray as $errorFeedback) {
            echo "<b class='color-red'>" . $errorFeedback . "</b><br>";
        }
        echo "<br>";
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($successFeedback)) {
        header("Location:" . BASE_URL . "?page=purchase_item_management" . "&action=purchase_item_insert&purchase_id=" . $_GET["purchase_id"] . "&message=" . "<br><b class='color-green'>$successFeedback</b><br>");
    }
    ?>
    <input id="admin-input" type="text" name="isbn" placeholder="ISBN" pattern="([0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{1})"/>
    <input id="admin-input" type="number" name="quantity" placeholder="Množství" min="1" max="50">
    <br>
    <input id="custom-submit" type="submit" name="insert_book" value="Přidat">
</form>


