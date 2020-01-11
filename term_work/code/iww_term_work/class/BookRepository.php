<?php
class BookRepository
{
    private $conn = null;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllBooks()
    {
        return $this->conn->query(
            "SELECT isbn,book.name,author,price, publication_date, description, page_count, binding, 
            image, language.name AS language , genre.name AS genre FROM book, language, genre 
            WHERE language_id=language.id AND genre_id= genre.id")->fetchAll();
    }

    public function getNonCzechBooks()
    {
        return $this->conn->query(
            "SELECT isbn,book.name,author,price, publication_date, description, page_count, binding, 
            image, language.name AS language , genre.name AS genre FROM book, language, genre 
            WHERE language_id=language.id AND genre_id= genre.id AND language .name != 'Čeština'")->fetchAll();
    }

    public function getImageByISBN($isbn)
    {
        $stmt = $this->conn->prepare("SELECT image FROM book WHERE isbn=:isbn");
        $stmt->bindParam(':isbn', $isbn);
        $stmt->execute();
        $book = $stmt->fetch();
        return $book["image"];
    }

    public function getByISBN($isbn)
    {
        $stmt = $this->conn->prepare("SELECT isbn,book.name,author,price, publication_date, description, page_count, binding, 
            image, language.name AS language , genre.name AS genre FROM book, language, genre WHERE language_id=language.id AND genre_id= genre.id AND isbn=:isbn");
        $stmt->bindParam(':isbn', $isbn);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getByGenre($genre)
    {
        $stmt = $this->conn->prepare("SELECT isbn,book.name,author,price, publication_date, description, page_count, binding, 
            image, language.name AS language , genre.name AS genre FROM book, language, genre WHERE language_id=language.id AND genre_id= genre.id AND genre.id=:genre");
        $stmt->bindParam(':genre', $genre);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}