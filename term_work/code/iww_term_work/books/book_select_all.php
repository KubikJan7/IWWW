<?php
require_once "./code/functions.php";
require_once "./class/Connection.php";
require_once "./class/BookRepository.php";

$conn = Connection::getPdoInstance();

$bookRepo = new BookRepository(Connection::getPdoInstance());
$data_book = $bookRepo->getAllBooks();

echo '<table>';

echo '  
  <tr>
    <th>ISBN</th>
    <th>Název</th> 
    <th>Autor</th>
    <th>Cena [Kč]</th>
    <th>Datum vydání</th>
    <th>Počet stran</th>
    <th>Vazba</th>
    <th>Jazyk</th>
    <th>Žánr</th>
    <th>Obrázek</th>
    <th>Popis</th>
    <th>Akce</th>
  </tr>';

foreach ($data_book as $row_book) {

    echo '   
   <tr >
    <td >' . $row_book["isbn"] . '</td >
    <td >' . $row_book["name"] . '</td > 
    <td >' . $row_book["author"] . '</td >
    <td >' . $row_book["price"] . '</td >
    <td >' . $row_book["publication_date"] . '</td >
    <td >' . $row_book["page_count"] . '</td >
    <td >' . $row_book["binding"] . '</td >
    <td >' . $row_book["language"] . '</td >
    <td >' . $row_book["genre"] . '</td >
    <td ><img src="./images/books/'.$row_book["image"].'" alt="'.$row_book["name"].'" width="50"></td >
    <td id="book_desc_cell"><textarea id="book_desc_readonly" rows="6" cols="55" readonly>' . $row_book["description"] . '</textarea></td >
    <td>
        <a href="?page=book_management&action=book_update&isbn=' . $row_book["isbn"] . '">Upravit</a>
        <a href="?page=book_management&action=book_delete&isbn=' . $row_book["isbn"] . '">Smazat</a>
    </td>
  </tr >';
    $address = NULL;
}

echo '</table>';
function createAcronym($text)
{
    $words = explode(" ", $text);
    $acronym = "";

    foreach ($words as $w) {
        $acronym .= strtoupper($w[0]);
        // a letter with diacritics is considered as 2 characters: Č -> c + ˇ
        if (mb_detect_encoding($w[0]) == "UTF-8") // ordinary letters are in ASCII
            $acronym .= strtoupper($w[1]); // add one more character
    }
    return $acronym;
}

?>
<p>
    <a id="button_orange_border" href="<?= BASE_URL . "?page=book_management&action=book_insert" ?>">Přidat knihu</a>
    <a id="button_orange_border" href="<?= BASE_URL . "?page=book_management&action=book_import"?>">Import knih</a>
    <a id="button_orange_border"
       href="<?= BASE_URL . "?page=book_management&action=book_delete_all" ?>"
       onclick="return confirm('Opravdu si přejete pokračovat? Tímto vymažete veškerou databázi knih.')">Smazat veškeré
        knihy</a>
</p>