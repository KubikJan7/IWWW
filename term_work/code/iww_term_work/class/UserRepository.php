<?php
class UserRepository
{
    private $conn = null;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getUserById($user_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE user.id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getUserByEmail($user_email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE user.email = :email");
        $stmt->bindParam(':email', $user_email);
        $stmt->execute();
        return $stmt->fetch();
    }
}