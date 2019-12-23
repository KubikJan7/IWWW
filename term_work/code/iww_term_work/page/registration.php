<form method="post">
    <input type="text" name="username" placeholder="Vaše jméno a příjmení">
    <input type="email" name="email" placeholder="Vaše emailová adresa" pattern="[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$"/>
    <input type="password" name="password" placeholder="Heslo">
    <input type="password" name="password" placeholder="Heslo pro kontrolu">
    <input type="tel" name="phone_number" placeholder="Vaše telefonní číslo" pattern="(([0-9]{3} [0-9]{3} [0-9]{3})|([0-9]{3}[0-9]{3}[0-9]{3}))" >
    <input type="submit" name="isSubmitted" value="Registrovat">
</form>
