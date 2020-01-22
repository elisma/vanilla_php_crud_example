<?php

namespace Utils;

use PDO;
use PDOException;

class Database
{
// Connection Creds
    private $host = "localhost";
    private $user = "root";
    private $password = "";    // PASSWORD
    private $database = "test";   //DATABASE NAME
    public $connection;

    public function get_connection() {
        // restart connection
        $this->connection = NULL;
        try {
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->user, $this->password);
            $this->connection->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Error: " . $exception->getMessage();
        }
        return $this->connection;
    }
}