<?php
$errorFeedbackArray = array();
$successFeedback = "";
$filename = "default.png";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

// <editor-fold default-state="collapsed" desc="input-validation">
    if (empty($_POST["email"])) {
        $feedbackMessage = "Je vyžadována e-mailová adresa!";
        array_push($errorFeedbackArray, $feedbackMessage);
    }
// </editor-fold>

    if (empty($errorFeedbackArray)) {
        // save data to database
        try {
            $conn = Connection::getPdoInstance();

            $userRepo = new UserRepository($conn);
            $user = $userRepo->getUserByEmail($_POST["email"]);

            if (empty($user))
                throw new UnexpectedValueException();

            $addressRepo = new AddressRepository($conn);
            $primaryAddress = $addressRepo->getPrimaryAddressByUserId($user["id"]);
            $secondaryAddress = $addressRepo->getSecondaryAddressByUserId($user["id"]);

            if (!empty($secondaryAddress))
                $address = $secondaryAddress;
            else
                $address = $primaryAddress;

            $stmt = $conn->prepare("INSERT INTO purchase (state, payment, user_id, address_id) 
            VALUES (:state, :payment, :user_id, :address_id)");
            $stmt->bindParam(':state', $_POST["state"]);
            $stmt->bindParam(':payment', $_POST["payment"]);
            $stmt->bindParam(':user_id', $user["id"]);
            $stmt->bindParam(':address_id', $address["id"]);
            $stmt->execute();
            $successFeedback = "Objednávka byla přidána.";

        } catch (UnexpectedValueException $e) {
            $feedbackMessage = "<p><b class='color-red'>Zadaná e-mailová adresa nebyla nalezena v databázi.</b></p>";
            array_push($errorFeedbackArray, $feedbackMessage);
        } catch (Exception $e) {
            $feedbackMessage = "<p><b class='color-red'>Při přidávání nastaly potíže, zkuste to prosím později.</b></p>";
            array_push($errorFeedbackArray, $feedbackMessage);

        }
    }
}

?>

<form id="custom-form" method="post" enctype="multipart/form-data">
    <h2>Přidání objednávky</h2>
    <?php
    if (!empty($errorFeedbackArray)) {
        echo "<b class='color-orange'>Při zadávání se vyskytly tyto chyby:</b><br>";
        foreach ($errorFeedbackArray as $errorFeedback) {
            echo "<b class='color-red'>" . $errorFeedback . "</b><br>";
        }
        echo "<br>";
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($successFeedback)) {
        header("Location:" . BASE_URL . "?page=purchase_management" . "&action=purchase_insert" . "&message=" . "<br><b class='color-green'>$successFeedback</b><br>");
    }
    ?>
    <label for="state" style="margin-right:10px">Stav: </label>
    <select id="admin-select" name="state">
        <option value="Odeslaná">Odeslaná</option>
        <option value="Přijatá">Přijatá</option>
        <option value="Vyexpedovaná">Vyexpedovaná</option>
        <option value="Vyřízená">Vyřízená</option>
    </select>
    <label for="payment" style="margin:0 5px 0 10px">Platba: </label>
    <select id="admin-select" name="payment">
        <option value="Převod na účet">Převod na účet</option>
        <option value="Dobírka">Dobírka</option>
        <option value="Platba kartou">Platba kartou</option>
    </select>
    <label for="payment" style="margin:0 5px 0 10px">E-mail: </label>
    <input id="admin-input" type="email" name="email"
           pattern="[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$">
    <br>
    <input id="custom-submit" type="submit" name="insert_book" value="Přidat">
</form>

