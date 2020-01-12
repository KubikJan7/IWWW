# IWWW
## Požadavky na semestrální práci
Požadavky na funkčnost aplikace:
- [ ] Veškeré číselníkové tabulky musí být editovatelné (CRUD)
- [x] Aplikace musí obsahovat správu uživatelů
- [x] Nutná aplikační logika
- [x] Bude vycházet z aktivity diagramu – mělo by se jednat o aktivitu, která je jádrem bussinessu (u eshopu se jedná o provedení objednávky, reklamace, vrácení zboží atp.; u rezervačního systému se jedná o proces rezervace, zrušení atd…) – přihlášení, odhlášení a registrace není vhodná aktivita; vyplnění jednoho formuláře také není aplikační logika.
- [x] Aplikace by měla pokrývat případy užití z use case diagramu
- [x] Podpora zabezpečeného přístupu (přihlášení do aplikace, role, atp.)
- [x] Minimálně 3 uživatelské role (neregistrovaný, registrovaný, administrátor)
- [x] Aplikace musí umožnit export dat do JSONu/XML – exportovaná data se musí nabídnout uživateli ke stažení (opět data, která jsou jádrem bussinessu – u eshopu se může jednat o export zboží)
- [x] Aplikace musí umožnit import JSON/XML souboru (stačí do jedné tabulky)
- [ ] Vybraná stránka musí být optimalizovaná pro tisk (u eshopu se může jednat například o seznam objednávek, nebo fakturu)

## Další požadavky:
- [ ] Validní HTML kód
- [x] Sémantický – využití tagů nav, footer, header, article, section
- [ ] Responzivní – přizbůsobení pro mobilní zařízení a tiskárnu
- [x] CSS formátování – nepoužívat CSS framework
- [x] U veškerého převzatého kódu uveďte zdroj
- [ ] Veškerý použitý kód si musíte obhájit (i ten, který jste nepsali)
- [x] Minimálně jedna relace M:N

## Doporučení:
- [x] Alespoň 7 databázových tabulek (minimálně jedna M:N)
- [ ] Pro kreslení UML diagramů můžete využít online bezplatnou aplikaci draw.io

