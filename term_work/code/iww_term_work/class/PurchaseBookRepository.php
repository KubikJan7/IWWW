<?php
class PurchaseBookRepository
{
    private $conn = null;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllPurchasedItems()
    {
        return $this->conn->query(
            "SELECT purchase_book.id, price, quantity, book_isbn  FROM purchase_book, purchase
            WHERE purchase_book.purchase_id = purchase.id")->fetchAll();
    }

    public function getPurchasedItemsByPurchaseId($purchase_id)
    {
        $stmt = $this->conn->prepare("SELECT purchase_book.id, price, quantity, book_isbn FROM purchase_book WHERE  purchase_book.purchase_id = :purchase_id");
        $stmt->bindParam(':purchase_id', $purchase_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalPriceByPurchaseId($purchase_id)
    {
        $stmt = $this->conn->prepare("SELECT SUM(price) FROM purchase_book WHERE purchase_id = :purchase_id");
        $stmt->bindParam(':purchase_id', $purchase_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getItemsByPurchaseIdAndISBN($purchase_id, $book_isbn)
    {
        $stmt = $this->conn->prepare("SELECT purchase_book.id, price, quantity, book_isbn FROM purchase_book WHERE purchase_id = :purchase_id AND book_isbn = :isbn");
        $stmt->bindParam(':purchase_id', $purchase_id);
        $stmt->bindParam(':isbn', $book_isbn);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPurchasedItemById($purchase_item_id)
    {
        $stmt = $this->conn->prepare("SELECT purchase_book.id, price, quantity, book_isbn FROM purchase_book WHERE  purchase_book.id = :purchase_item_id");
        $stmt->bindParam(':purchase_item_id', $purchase_item_id);
        $stmt->execute();
        return $stmt->fetch();
    }
}
