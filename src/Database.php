<?php

namespace App;

use PDO;
use PDOException;


class Database
{
    private PDO $pdo;

    public function __construct()
    {
        
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $dbname = $_ENV['DB_NAME'] ?? 'myapp';
        $username = $_ENV['DB_USER'] ?? 'root';
        $password = $_ENV['DB_PASS'] ?? 'rootroot';

        try {
            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8",
                $username,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            
        } catch (PDOException $e) {
            die("Błąd połączenia z bazą danych: " . $e->getMessage());
        }
    }

    /**
     * [Returns the PDO connection object.]
     *
     * @return PDO
     * 
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }



    
   
    
}