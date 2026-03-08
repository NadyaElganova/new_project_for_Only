<?php

class User
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function getUserByUsername($user_name)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_name = ?");
        $stmt->execute([$user_name]);
        return $stmt->fetch();
    }

    public function getUserByTelephone($telephone)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE telephone = ?");
        $stmt->execute([$telephone]);
        return $stmt->fetch();
    }

    public function create($user_name, $email, $telephone, $password)
    {
        $sql = "INSERT INTO users (user_name, email, telephone, password) 
                VALUES (:user_name, :email, :telephone, :password)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':user_name' => $user_name,
            ':email' => $email,
            ':telephone' => $telephone,
            ':password' => $password
        ]);
    }

    public function getUserByLogin($login)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? OR telephone = ?");
        $stmt->execute([$login, $login]);
        return $stmt->fetch();
    }

     public function updateUser($id, $user_name, $email, $telephone)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET user_name = ?, email = ?, telephone = ? WHERE id = ?");
        return $stmt->execute([$user_name, $email, $telephone, $id]);
    }

    public function updatePassword($id, $password)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$password, $id]);
    }

    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
