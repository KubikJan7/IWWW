<?php
class AddressRepository
{
    private $conn = null;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getPrimaryAddressByUserId($user_id)
    {
        $stmt = $this->conn->prepare("SELECT address.id FROM address, user WHERE user.id = :user_id AND address.user_id = user.id AND type = 'primary'");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getSecondaryAddressByUserId($user_id)
    {
        $stmt = $this->conn->prepare("SELECT address.id FROM address, user WHERE user.id = :user_id AND address.user_id = user.id AND type = 'secondary'");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch();
    }
}