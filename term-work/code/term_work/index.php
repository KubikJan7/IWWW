<?php
include "./config.php"; //load configurations

#process of a message
$message = "";
if (isset($_POST['newsletter'])) {
    if (!empty($_POST['email'])) {
        if ((preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $_POST['email']))) {
            try {
                $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // prepare sql and bind parameters
                $stmt = $conn->prepare("INSERT INTO newsletter (email, created) VALUES (:email, NOW())");
                $stmt->bindParam(':email', $_POST["email"]);
                $stmt->execute();

                $message = "Your are subscribed!";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                $message = "Unable to save to the database!";
            }
        } else {
            $message = "Bad formatted email address!";
        }
    } else {
        $message = "Email address is needed!";
    }
}

//create table newsletter
//(
//	id int auto_increment primary key,
//	email varchar(255) not null,
//	created datetime not null
//);

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
        <a href="<?= BASE_URL . "?page=blog" ?>">Účet</a>
        <a href="<?= BASE_URL . "?page=contact-me" ?>"><i class="fa fa-shopping-cart"></i> Košík</a>
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
        <a href="<?= BASE_URL . "?page=blog" ?>">Romány</a>
        <a href="<?= BASE_URL . "?page=blog" ?>">Scifi a Fantasy</a>
        <a href="<?= BASE_URL . "?page=blog" ?>">Naučné</a>
        <a href="<?= BASE_URL . "?page=blog" ?>">Odborné</a>
    </div>
</section>

<main>

    <div class="center-wrapper">
        <div>
            <h2>Who am I?</h2>
            <p>
                Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Duis ante orci, molestie vitae vehicula
                venenatis,
                tincidunt ac pede. Fusce wisi. Praesent vitae arcu tempor neque lacinia pretium. Phasellus faucibus
                molestie
                nisl. Etiam quis quam. Duis sapien <strong>nunc</strong>, <i>commodo</i> et, <u>interdum</u> suscipit,
                sollicitudin et, dolor. Nam libero
                tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat
                facere
                possimus, omnis voluptas assumenda est, omnis dolor repellendus. Quisque tincidunt scelerisque libero.
                Aenean
                vel massa quis mauris vehicula lacinia. Mauris tincidunt sem sed arcu. Maecenas ipsum velit,
                consectetuer eu
                lobortis ut, dictum at dui. Vivamus ac leo pretium faucibus. Sed vel lectus. Donec odio tempus molestie,
                porttitor ut, iaculis quis, sem. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
                dolore eu
                fugiat nulla pariatur.
            </p>

            <hr/>

            <div class="flex-wrap">
                <div class="card">
                    <img src="./images/books/atlas_shrugged.jpg"/>
                    <h2>Atlas Shrugged</h2>
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam sit amet magna in magna gravida
                        vehicula. Maecenas aliquet accumsan leo.</p>
                    <a href="#">
                        <div>More...</div>
                    </a>
                </div>

                <div class="card">
                    <img src="./images/books/lotr_red_edition.jpg"/>
                    <h2>The Lord Of The Rings</h2>
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam sit amet magna in magna gravida
                        vehicula. Maecenas aliquet accumsan leo.</p>
                    <a href="#">
                        <div>More...</div>
                    </a>
                </div>


                <div class="card">
                    <img src="./images/books/steve_jobs.jpg"/>
                    <h2>Steve Jobs</h2>
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam sit amet magna in magna gravida
                        vehicula. Maecenas aliquet accumsan leo.</p>
                    <a href="#">
                        More...
                    </a>
                </div>
            </div>

            <hr/>

            <div class="flex-wrap">
                <strong>Next customers:</strong>
                <div style="width:50%; text-align:center;padding:20px; margin-left: 20px;border: 1px solid #4a4a4a; border-radius: 5px; background-color: white">
                    <img height="50px" src="./images/price.png"/>
                    <img height="50px" src="./images/price.png"/>
                    <img height="50px" src="./images/price.png"/>
                    <img height="50px" src="./images/price.png"/>
                    <img height="50px" src="./images/price.png"/>
                    <img height="50px" src="./images/price.png"/>
                </div>
            </div>

        </div>
    </div>
</main>
<footer>
    <div class="full-width-wrapper">

        <div class="flex-wrap">
            <section>
                <h4>About me</h4>
                <ul>
                    <li><a href="#">Work with me</a></li>
                    <li><a href="#">References</a></li>
                    <li><a href="#">Contact me</a></li>
                    <li><a href="#">Authors</a></li>
                    <li><a href="#">Login</a></li>
                </ul>
            </section>

            <section>
                <h4>Blog news</h4>
                <ul>
                    <li><a href="#">New article #1</a></li>
                    <li><a href="#">New article #2</a></li>
                    <li><a href="#">New article #3</a></li>
                    <li><a href="#">New article #4</a></li>
                </ul>
            </section>
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

            <section id="footer-newsletter">
                <h4>Newsletter</h4>
                <form method="POST" action="#">
                    <div>
                        <label>Enter your email address: </label>
                    </div>
                    <div>
                        <input type="text" name="email"/>
                    </div>
                    <div>
                        <input type="submit" name="newsletter" value="Subscribe"/>
                    </div>
                </form>
            </section>
        </div>
        <section>
            <p>Copyright
                ©<?php echo date("Y"); ?>
                Bookworm
        </section>
    </div>
</footer>

<script>
    function showHamburgerMenu() {
        var element = document.getElementById("nav");
        if (element.className === "") {
            element.className = "nav-responsive";
        } else {
            element.className = "";
        }
    }
</script>

<?php
#feedback message
if (!empty($message)) {
    echo $message;
    $message = "";
}
?>
<main>

    <?php

    $file = "./page/" . $_GET["page"] . ".php";
    if (file_exists($file)) {
        include $file;
    } else {
        include "./page/default.php";
    }
    ?>

</main>

</body>
</html>

