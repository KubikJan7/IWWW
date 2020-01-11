<?php
require_once "./class/PurchaseRepository.php";
require_once "./class/PurchaseBookRepository.php";
?>

<h1 style="margin: 50px 0 40px 0">Váš účet</h1>
<a id="button_orange_border" href="<?= BASE_URL . "?page=account&action=user_update&user_id=" . $_SESSION["user_id"] ?>">Změna osobních údajů</a>
<a id="button_orange_border" href="<?= BASE_URL . "?page=account&action=purchase_select" ?>">Přehled objednávek</a>

