<?php 

namespace Project\Database;

use Exception;
use PDO;

class Controller {
    
    public $db = null;
    public function __construct() {
        try {
            $this->db = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (\PDOException $e) {
            throw new Exception('Connection error: ' . $e->getMessage(), 1);
        } catch (\Exception $e) {
            throw new Exception('Unknown error: ' . $e->getMessage(), 1);
        }
        return $this->db;
    }
}