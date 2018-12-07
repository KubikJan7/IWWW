
<form method="POST" action="<?= CURRENT_URL; ?>">
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

<hr>
<?php
echo CURRENT_URL;
echo "<br>";
echo "<a href=" . BASE_URL . "  >Link</a>";
echo '<br>';
echo BASE_URL;
?>
<hr>