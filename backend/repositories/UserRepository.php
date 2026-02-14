<?php
require_once __DIR__ . '/../models/User.php';

class UserRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT name, email, birthdate FROM users");
        return $stmt->fetchAll();
    }

    public function add(User $user): void {
        $sql = "INSERT INTO users (name, email, birthdate)
                VALUES (:name, :email, :birthdate)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ":name" => $user->getName(),
            ":email" => $user->getEmail(),
            ":birthdate" => $user->getBirthdate()
        ]);
    }
}

