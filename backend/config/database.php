<?php

class Database {
    public function connect(): PDO {
        $host = "mysql";  // NON localhost
        $db   = "user_management";
        $user = "root";
        $pass = "root";

        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
}

/*
class Database {
    private string $host = "localhost";
    private string $db   = "user_management";
    private string $user = "root";
    private string $pass = "";
    private string $charset = "utf8mb4";

    public function connect(): PDO {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        return new PDO($dsn, $this->user, $this->pass, $options);
    }
}
*/
