<?php

namespace App;

use PDO;
use PDOException;


class Database
{
    private $pdo;
    private $demoUserEmail = 'demo@demo.pl';
    private $demoUserPassword = 'demo';

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
            
            $this->createUsersTable();
            $this->createNotesTable();
            $this->createDemoUser();

            
        } catch (PDOException $e) {
            die("Błąd połączenia z bazą danych: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    public function createUsersTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";


        $this->pdo->exec($sql);
    }

    
    public function createDemoUser()
    {
        
        $this->createUsersTable();


        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->bindParam(':email', $this->demoUserEmail);
        $stmt->execute();
        $userExists = $stmt->fetchColumn();


        if (!$userExists) {
            $hashedPassword = password_hash($this->demoUserPassword, PASSWORD_DEFAULT);

            $stmt = $this->pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
            $stmt->bindParam(':email', $this->demoUserEmail);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();
        }
    }

    public function createNotesTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS notes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL, 
        title VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        color VARCHAR(50) NOT NULL DEFAULT 'white',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";

        $this->pdo->exec($sql);
    }
}