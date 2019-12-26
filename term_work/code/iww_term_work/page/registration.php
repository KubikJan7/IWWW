<div class="centered-90">
    <br>
    <h1>Registrace nového uživatele</h1>
    <p>Vyplňte následující údaje pro vytvoření nového účtu.</p>
    <br>
    <form method="post">
        <h2>Registrační údaje</h2>
        <div id="form-contents">
            <div id="form-container">
                <div id="form-line">
                    <label for="email"><b>E-mailová adresa *</b></label>
                    <input type="email" name="email"
                           pattern="[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$"/>
                </div>
                <div id="form-line">
                    <label for="password"><b>Heslo *</b></label>
                    <input type="password" name="password">
                </div>

            </div>

            <div id="form-container">
                <div id="form-line">
                    <p id="form-line-blank"><br></p>
                </div>
                <div id="form-line">
                    <label for="password_repeat"><b>Potvrzení hesla</b></label>
                    <input type="password" name="password_repeat">
                </div>
            </div>
        </div>
        <br>

        <h2>Fakturační údaje</h2>
        <div id="form-contents">
            <div id="form-container">
                <div id="form-line">
                    <label for="first_name"><b>Jméno *</b></label>
                    <input type="text" name="first_name">
                </div>
                <div id="form-line">
                    <label for="street"><b>Ulice *</b></label>
                    <input type="text" name="street"/>
                </div>
                <div id="form-line">
                    <label for="city"><b>Město *</b></label>
                    <input type="text" name="city"/>
                </div>
                <div id="form-line">
                    <label for="phone-number"><b>Telefon *</b></label>
                    <input type="tel" name="phone-number"
                           pattern="(([0-9]{3} [0-9]{3} [0-9]{3})|([0-9]{3}[0-9]{3}[0-9]{3}))">
                </div>
            </div>
            <div id="form-container">
                <div id="form-line">
                    <label for="last_name"><b>Příjmení *</b></label>
                    <input type="text" name="last_name">
                </div>
                <div id="form-line">
                    <label for="zip-code"><b>PSČ *</b></label>
                    <input type="text" name="zip-code"
                           pattern="(([0-9]{3} [0-9]{2})|([0-9]{3}[0-9]{2}))">
                </div>
                <div id="form-line">
                    <label for="country"><b>Země *</b></label>
                    <select name="country">
                        <option value="Česká republika">Česká republika</option>
                        <option value="Slovenská republika">Slovenská republika</option>
                    </select>
                </div>
            </div>
        </div>
        <input type="submit" name="isSubmitted" value="Registrovat">

    </form>
    <p id="sign_in">Již jste se dříve zaregistrovali? <a href="<?= BASE_URL . "?page=login" ?>">Přihlášení</a>.</p>
    <br><br>
</div>