<!--
***************************************************************************************
*    Title: How TO - Display Text when Checkbox is Checked
*    Author: www.w3schools.com
*    Date: 2019
*    Code version: 1.0
*    Availability: https://www.w3schools.com/howto/howto_js_display_checkbox_text.asp
*
****************************************************************************************
-->

<script>
    function showDeliveryDetails() {
        var checkBox = document.getElementById("sec_addr_check");
        var text = document.getElementById("secondary-address");
        if (checkBox.checked == true) {
            text.style.display = "block";
        } else {
            text.style.display = "none";
        }
    }

    function showNewPasswordField() {
        var checkBox = document.getElementById("new_passw_check");
        var text = document.getElementsByName("password")[0];
        if (checkBox.checked == true) {
            text.style.display = "inline-flex";
        } else {
            text.style.display = "none";
        }
    }

    /*
    * ***************************************************************************************
    * End of quoted code
    * ***************************************************************************************
    */

    function openSetFilepathDialog(action) {
        var filepath;
        if (action === 'import')
            filepath = prompt("Zadejte prosím cestu pro načtení JSON souboru:", "./json_files/books.json");
        else
            filepath = prompt("Zadejte prosím cestu pro uložení JSON souboru:", "./json_files/books_export.json");
        if (filepath == null)
            return;
        if (action === "import")
            window.location.href = "<?= BASE_URL . "?page=book_management&action=book_import&filepath="?>" + filepath;
        else
            window.location.href = "<?="?action=book_export&filepath="?>" + filepath;
    }
</script>