<?php
$errorFeedbackArray = array();
$successFeedback = "";

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

            $stmt = $conn->prepare("UPDATE purchase SET state = :state, payment = :payment, user_id = :user_id, address_id = :address_id
                                               WHERE purchase.id = :id");
            $stmt->bindParam(':id', $_GET["purchase_id"]);
            $stmt->bindParam(':state', $_POST["state"]);
            $stmt->bindParam(':payment', $_POST["payment"]);
            $stmt->bindParam(':user_id', $user["id"]);
            $stmt->bindParam(':address_id', $address["id"]);
            $stmt->execute();
            $successFeedback = "Objednávka byla upravena.";

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

<?php
if (empty($errorFeedbackArray)) { //load origin data from database
    $conn = Connection::getPdoInstance();
    $purchaseRepo = new PurchaseRepository($conn);
    $purchase = $purchaseRepo->getPurchaseById($_GET["purchase_id"]);

    $userRepo = new UserRepository($conn);
    $user = $userRepo->getUserById($purchase["user_id"]);

    $state = $purchase["state"];
    $payment = $purchase["payment"];
    $email = $user["email"];

} else { //in case of any error, load data
    $state = $_POST["state"];
    $payment = $_POST["payment"];
    $email = $_POST["email"];
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
        header("Location:" . BASE_URL . "?page=purchase_management" . "&action=purchase_update" . "&message=" . "<br><b class='color-green'>$successFeedback</b><br>" . "&purchase_id=" . $_GET["purchase_id"]);
    }
    ?>
    <label for="state" style="margin-right:10px">Stav: </label>
    <select id="admin-select" name="state">
        <option value="Odeslaná" <?php if ($state == "Odeslaná") echo "SELECTED"; ?>>Odeslaná</option>
        <option value="Přijatá" <?php if ($state == "Přijatá") echo "SELECTED"; ?>>Přijatá</option>
        <option value="Vyexpedovaná" <?php if ($state == "Vyexpedovaná") echo "SELECTED"; ?>>Vyexpedovaná</option>
        <option value="Vyřízená" <?php if ($state == "Vyřízená") echo "SELECTED"; ?>>Vyřízená</option>
    </select>
    <label for="payment" style="margin:0 5px 0 10px">Platba: </label>
    <select id="admin-select" name="payment">
        <option value="Převod na účet" <?php if ($payment == "Převod na účet") echo "SELECTED"; ?>>Převod na účet</option>
        <option value="Dobírka" <?php if ($payment == "Dobírka") echo "SELECTED"; ?>>Dobírka</option>
        <option value="Platba kartou" <?php if ($payment == "Platba kartou") echo "SELECTED"; ?>>Platba kartou</option>
    </select>
    <label for="payment" style="margin:0 5px 0 10px">E-mail: </label>
    <input id="admin-input" type="email" name="email"
           pattern="[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$" value="<?= $email; ?>">
    <br>
    <input id="custom-submit" type="submit" name="insert_book" value="Přidat">
</form>

