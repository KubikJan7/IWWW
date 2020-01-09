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

class CustomFunctions
{

    public static function createConnectionToDatabase()
    {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $conn->exec("set names utf8");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    public static function getAllBookGenres()
    {
        $conn = self::createConnectionToDatabase();
        return $conn->query("SELECT name FROM genre")->fetchAll();
    }
    public static function getAllBookLanguages(){
        $conn = self::createConnectionToDatabase();
        return $conn->query("SELECT name FROM language")->fetchAll();
    }

}

?>