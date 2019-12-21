<?php
if ($cartEmpty == true) {
    ?>
    <section id=cart-section>
        <div><h1>Košík je prázdný, přidejte do něj zboží.</h1></div>
        <div id="big-cart-img"><i class="fa fa-shopping-cart"></i></div>
        <a class="button" href="<?= BASE_URL ?>"><i class="fa fa-shopping-cart"></i> Jít nakupovat</a>
    </section>
    <?php
}
?>


<?php
if ($cartEmpty == false) {
    ?>
    <h1>V košíku se nachází jedna či více položek.</h1>
    <?php
}
?>

