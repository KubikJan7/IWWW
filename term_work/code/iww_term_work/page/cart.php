<?php
if ($cartEmpty == true) {
    ?>
    <section id=cart-section>
        <div><h1>Košík je prázdný, přidejte do něj zboží.</h1></div>
        <div id="big-cart-img"><i class="fa fa-shopping-cart"></i></div>
        <a id="button_orange_border" href="<?= BASE_URL ?>"><i class="fa fa-shopping-cart"></i> Jít nakupovat</a>
        <input id="admin-input" type="number" name="book_count" value="1" min="1" max="10" style="width: 60px">
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

