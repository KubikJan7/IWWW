<?php
include "./config.php"; //load configurations
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    <link rel="stylesheet" type="text/css" href="css/responsive.css">
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
        <a href="<?= BASE_URL . "?page=cart" ?>"><i class="fa fa-shopping-cart"></i> Košík</a>
    </nav>
</header>

<section id="hero">
    <div id="search-container">
        <form action="/action_page.php">
            <input type="text" placeholder="Zadejte název knihy, autora..." name="search">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>


</section>
<section id="genres">
    <div id="categories-menu">
        <a href="<?= BASE_URL ?>">Romány</a>
        <a href="<?= BASE_URL ?>">Scifi a Fantasy</a>
        <a href="<?= BASE_URL ?>">Naučné</a>
        <a href="<?= BASE_URL ?>">Odborné</a>
    </div>
</section>

<main>

    <div class="center-wrapper">
        <div>
            <div class="flex-wrap">
                <div class="card">
                    <img src="./images/books/atlas_shrugged.jpg"/>
                    <h2>Atlas Shrugged</h2>
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam sit amet magna in magna gravida
                        vehicula. Maecenas aliquet accumsan leo.</p>
                </div>

                <div class="card">
                    <img src="./images/books/lotr_red_edition.jpg"/>
                    <h2>The Lord Of The Rings</h2>
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam sit amet magna in magna gravida
                        vehicula. Maecenas aliquet accumsan leo.</p>
                </div>


                <div class="card">
                    <img src="./images/books/steve_jobs.jpg"/>
                    <h2>Steve Jobs</h2>
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam sit amet magna in magna gravida
                        vehicula. Maecenas aliquet accumsan leo.</p>
                </div>
            </div>
        </div>
    </div>
</main>
<footer>
    <div class="full-width-wrapper">

        <div class="flex-wrap">
            <section>
                <h4>Contact</h4>
                <address>
                    Honzovo, s. r. o.<br/>
                    2354 Pacific Coast Highway<br/>
                    USA<br/>
                    +420 123 456<br/>
                    Email: <a href="mailto:honza@iwww.cz">honza@iwww.cz</a><br/>
                </address>
            </section>
        </div>
        <section>
            <p>Copyright
                ©<?php echo date("Y"); ?>
                Bookworm
        </section>
    </div>
</footer>
<main>

    <?php
    if (isset($_GET['page'])) {
        $file = "./page/" . $_GET["page"] . ".php";
        if (file_exists($file)) {
            include $file;
        }
    }
    ?>

</main>
</body>
</html>

