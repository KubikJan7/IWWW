# Jan_Kubik
Požadavky na semestrální práci
Požadavky na funkčnost aplikace:
[] Veškeré číselníkové tabulky musí být editovatelné (CRUD)
• Aplikace musí obsahovat správu uživatelů
• Nutná aplikační logika
◦ Bude vycházet z aktivity diagramu – mělo by se jednat o aktivitu, která je jádrem
bussinessu (u eshopu se jedná o provedení objednávky, reklamace, vrácení zboží
atp.; u rezervačního systému se jedná o proces rezervace, zrušení atd...) –
přihlášení, odhlášení a registrace není vhodná aktivita; vyplnění jednoho formuláře
také není aplikační logika.
• Aplikace by měla pokrývat případy užití z use case diagramu
• Podpora zabezpečeného přístupu (přihlášení do aplikace, role, atp.)
• Minimálně 3 uživatelské role (neregistrovaný, registrovaný, administrátor)
• Aplikace musí umožnit export dat do JSONu/XML – exportovaná data se musí
nabídnout uživateli ke stažení (opět data, která jsou jádrem bussinessu – u eshopu se
může jednat o export zboží)
• Aplikace musí umožnit import JSON/XML souboru (stačí do jedné tabulky)
• Vybraná stránka musí být optimalizovaná pro tisk (u eshopu se může jednat například o
seznam objednávek, nebo fakturu)
Další požadavky:
• Validní HTML kód
• Sémantický – využití tagů nav, footer, header, article, section
• Responzivní – přizbůsobení pro mobilní zařízení a tiskárnu
• CSS formátování – nepoužívat CSS framework
• U veškerého převzatého kódu uveďte zdroj
• Veškerý použitý kód si musíte obhájit (i ten, který jste nepsali)
• Minimálně jedna relace M:N
Doporučení:
• Alespoň 7 databázových tabulek (minimálně jedna M:N)
• Pro kreslení UML diagramů můžete využít online bezplatnou aplikaci draw.io
