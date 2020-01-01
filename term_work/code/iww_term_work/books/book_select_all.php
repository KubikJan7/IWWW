<?php $filepath = ";" ?>
<p>
    <a id="button_orange_border" onclick="openSetFilepathDialog()">Import knih</a>
    <a id="button_orange_border" href="<?= BASE_URL . "?page=book_management&action=book_delete_all&message=<p><b class='color-green'>Všechny knihy byly odstraněny.</b><p>" ?>"
       onclick="return confirm('Opravdu si přejete pokračovat? Tímto vymažete veškerou databázi knih.')" >Smazat veškeré knihy</a>
</p>
<script>
    function openSetFilepathDialog() {
        var filepath = prompt("Zadejte prosím cestu k JSON souboru:", "./json_files/books.json");
        window.location.href = "<?= BASE_URL . "?page=book_management&action=book_import&filepath="?>" + filepath;
    }
</script>