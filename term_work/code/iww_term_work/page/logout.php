<?php
session_destroy();
header("Location:".BASE_URL . "?message=" . "<br><b class='color-orange'>Byl jste odhlášen.</b><br>" . $_GET["message"]);
