
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
</script>