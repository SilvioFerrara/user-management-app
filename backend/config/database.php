<?php

// Classe responsabile della gestione della connessione al database
class Database {

    // Metodo pubblico che crea e restituisce una connessione PDO
    // Il type hint ": PDO" indica che il metodo restituisce un oggetto PDO
    public function connect(): PDO {

        // Parametri di configurazione del database
        // "mysql" è il nome del servizio Docker (non localhost)
        $host = "mysql";  
        $db   = "user_management"; // Nome del database
        $user = "root";            // Username del database
        $pass = "root";            // Password del database

        /*
         * Creazione del DSN (Data Source Name)
         * Specifica:
         * - tipo di database (mysql)
         * - host
         * - nome del database
         * - charset (utf8mb4 per compatibilità e sicurezza)
         */
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        /*
         * Creazione e ritorno della connessione PDO
         * Parametri:
         * 1. DSN
         * 2. Username
         * 3. Password
         * 4. Array di opzioni di configurazione
         */
        return new PDO($dsn, $user, $pass, [

            // Se si verifica un errore SQL, viene lanciata un'eccezione
            // Utile per gestire gli errori con try/catch
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            // Imposta il formato di default dei risultati come array associativo
            // Es: ["name" => "Mario"] invece di [0 => "Mario", "name" => "Mario"]
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
}


