<?php
include "./config.php"; //load configurations
$cartEmpty = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Bookworm</title>
</head>
<body>

<header>
    <div id="header-web-title"><a href="<?= BASE_URL ?>"><img id="header-logo" src="./images/logo.png"/>Bookworm</a>
    </div>

    <nav id="nav">
        <a href="<?= BASE_URL . "?page=account" ?>">Účet</a>
        <a href="<?= BASE_URL . "?page=contact" ?>">Kontakty</a>
    </nav>
</header>

<section id="hero">
    <span id="category-btn" onmouseover="openNav()" onmouseleave="closeNav()">&#9776; Kategorie</span>
    <div id="search-container">
        <form action="/action_page.php">
            <input type="text" placeholder="Zadejte název knihy, autora..." name="search">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>
    <a href="<?= BASE_URL . "?page=cart" ?>"><i class="fa fa-shopping-cart"></i> Košík</a>
</section>
<div id="category-menu" onmouseover="openNav()" onmouseleave="closeNav()">
    <a href="<?= BASE_URL ?>">Fantasy</a>
    <a href="<?= BASE_URL ?>">Scifi </a>
    <a href="<?= BASE_URL ?>">Horror</a>
    <a href="<?= BASE_URL ?>">Biografie</a>
    <a href="<?= BASE_URL ?>">Odborné</a>
</div>

<script>
    function openNav() {
        document.getElementById("category-menu").style.height = "100%";
        document.getElementById("category-menu").style.paddingTop = "30px";
    }

    function closeNav() {
        document.getElementById("category-menu").style.height = "0";
        document.getElementById("category-menu").style.paddingTop = "0";
    }
</script>

<main>
    <?php
    if (isset($_GET['page'])) {
        $file = "./page/" . $_GET["page"] . ".php";
        if (file_exists($file)) {
            include $file;
            //header ('Location: ' . $file);
        }
    } else {
        include "./page/default.php";
    }
    ?>
</main>
<footer>
    <div>
        <p>Copyright
            ©<?php echo date("Y"); ?>
            Bookworm
    </div>
</footer>
</body>
</html>


