<?php

class PurchaseRepository
{
    private $conn = null;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllPurchases()
    {
        return $this->conn->query(
            "SELECT *, purchase.id AS purchase_id, user.id AS user_id, address.id AS address_id  FROM purchase, user, address
            WHERE purchase.user_id = user.id AND purchase.address_id = address.id")->fetchAll();
    }

    public function getPurchaseByUserId($user_id)
    {
        $stmt = $this->conn->prepare("SELECT *, purchase.id AS purchase_id, user.id AS user_id, address.id AS address_id 
                                        FROM purchase, user, address WHERE purchase.user_id = :user_id AND purchase.user_id = user.id AND address.id = purchase.address_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPurchaseById($purchase_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM purchase WHERE purchase.id = :purchase_id");
        $stmt->bindParam(':purchase_id', $purchase_id);
        $stmt->execute();
        return $stmt->fetch();
    }

}
