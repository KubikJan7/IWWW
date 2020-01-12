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
        $stmt = $this->conn->prepare("SELECT *, address.id, user.id AS user_id FROM address, user WHERE user.id = :user_id AND address.user_id = user.id AND type = 'primary'");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getSecondaryAddressByUserId($user_id)
    {
        $stmt = $this->conn->prepare("SELECT *, address.id, user.id AS user_id FROM address, user WHERE user.id = :user_id AND address.user_id = user.id AND type = 'secondary'");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAddressById($address_id){
        $stmt = $this->conn->prepare("SELECT * FROM address WHERE address.id = :address_id");
        $stmt->bindParam(':address_id', $address_id);
        $stmt->execute();
        return $stmt->fetch();
    }
}