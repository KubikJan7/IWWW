<?php
class PurchaseBookRepository
{
    private $conn = null;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getTotalPriceByPurchaseId($purchase_id)
    {
        $stmt = $this->conn->prepare("SELECT SUM(price) FROM purchase_book WHERE purchase_id = :purchase_id");
        $stmt->bindParam(':purchase_id', $purchase_id);
        $stmt->execute();
        return $stmt->fetch();
    }


}
