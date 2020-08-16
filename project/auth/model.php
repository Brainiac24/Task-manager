<?php 

namespace Project\Auth;

use PDO;
use Project\Database;

class Model {

    private $db = null;
    public function __construct() {
    
        $this->db = (new Database\Controller())->db;

    }

    public function Login($login, $password)
    {
        // Выборка пользователя по логину и паролю 
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE `login` = :login AND `password` = PASSWORD(:password)");
            $stmt->execute([
                ':login' => $login,
                ':password' => $password,
                ]);
            return $stmt;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function Get_By_Id($id)
    {
        // Выборка пользователя по id
        try {
            $user = null;
            $stmt = $this->db->prepare("SELECT * FROM users WHERE `id` = :id LIMIT 1");
            $stmt->execute([
                ':id' => $id
                ]);
            for ($i = 0; $i < $stmt->rowCount(); $i++) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            return $user;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}