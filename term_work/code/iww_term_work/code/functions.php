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

    function openNav() {
        document.getElementById("category-menu").style.display = "block";
        document.getElementById("category-menu").style.paddingBottom = "10px";
        document.getElementById("category-menu").style.boxShadow = "0 1px 5px 1px grey";
    }

    function closeNav() {
        document.getElementById("category-menu").style.display = "none";
        document.getElementById("category-menu").style.paddingBottom = "0";
        document.getElementById("category-menu").style.boxShadow = "none";
    }
</script>
<?php
require_once "./class/Connection.php";

class CustomFunctions
{
    public static function getAllBookGenres()
    {
        $conn = Connection::getPdoInstance();
        return $conn->query("SELECT id, name FROM genre")->fetchAll();
    }

    public static function getAllBookLanguages()
    {
        $conn = Connection::getPdoInstance();
        return $conn->query("SELECT id, name FROM language")->fetchAll();
    }

    public static function uploadPicture($file)
    {
        /*
    ***************************************************************************************
    *    Credit: https://www.w3schools.com/php/php_file_upload.asp
    ****************************************************************************************
    */
        $target_dir = "./images/books/";
        $target_file = $target_dir . basename($file["name"]);
        $message = "";
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($file["tmp_name"]);
            if ($check == false) {
                $message = "Vybraný soubor musí být obrázek.";
            }
        }
        // Check file size
        if ($file["size"] > 150000) {
            $message = "Zadaný obrázek je příliš veliký.";
        }
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $message = "Pro nahrání obrázků jsou povoleny pouze formáty typu JPG, JPEG a PNG.";
        }
        // Check if $uploadOk is set to 0 by an error
        if (!empty($message))
            return $message;

        // Check if file already exists
        if (!file_exists($target_file))
            // try to upload file
            if (!move_uploaded_file($file["tmp_name"], $target_file)) {
                $message = "Je nám líto, při nahrávání obrázku došlo k potížím.";
            }
        return $message;
    }

    function createAcronym($text)
    {
        $words = explode(" ", $text);
        $acronym = "";

        foreach ($words as $w) {
            $acronym .= strtoupper($w[0]);
            // a letter with diacritics is considered as 2 characters: Č -> c + ˇ
            if (mb_detect_encoding($w[0]) == "UTF-8") // ordinary letters are in ASCII
                $acronym .= strtoupper($w[1]); // add one more character
        }
        return $acronym;
    }
}

?>