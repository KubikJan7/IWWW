<h1>Registrace nového uživatele</h1>
<p>Vyplňte následující údaje pro vytvoření nového účtu.</p>
<br>
<form method="post">
    <label for="email"><b>E-mailová adresa *</b></label>
    <input type="email" name="email" pattern="[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$"/>

    <label for="email"><b>Jméno *</b></label>
    <input type="text" name="first_name">
    <label for="email"><b>Příjmení *</b></label>
    <input type="text" name="last_name">
    <label for="email"><b>Heslo *</b></label>
    <input type="password" name="password">
    <label for="email"><b>Heslo pro kontrolu *</b></label>
    <input type="password" name="password">

    <label for="email"><b>Telefon *</b></label>
    <input type="tel" name="phone_number" pattern="(([0-9]{3} [0-9]{3} [0-9]{3})|([0-9]{3}[0-9]{3}[0-9]{3}))">

    <input type="submit" name="isSubmitted" value="Registrovat">

</form>
<p id="sign_in">Již jste se dříve zaregistrovali? <a href="<?= BASE_URL . "?page=login" ?>">Přihlášení</a>.</p>
<br><br>
