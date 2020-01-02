<?php require_once "./code/functions.php"?>
<p>
    <a id="button_orange_border" onclick="openSetFilepathDialog('import')">Import knih</a>
    <a id="button_orange_border" href="<?= BASE_URL . "?page=book_management&action=book_delete_all&message=<p><b class='color-green'>Všechny knihy byly odstraněny.</b><p>" ?>"
       onclick="return confirm('Opravdu si přejete pokračovat? Tímto vymažete veškerou databázi knih.')" >Smazat veškeré knihy</a>
</p>