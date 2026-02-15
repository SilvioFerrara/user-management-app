<?php

// Include il modello User per poter utilizzare la classe User
require_once __DIR__ . '/../models/User.php';

/*
 * Classe Repository responsabile della comunicazione
 * tra l'applicazione e la tabella "users" nel database.
 * Segue il Repository Pattern.
 */
class UserRepository {

    // Proprietà privata che contiene la connessione PDO
    private PDO $db;

    /*
     * Costruttore della classe.
     * Riceve una connessione PDO tramite Dependency Injection.
     */
    public function __construct(PDO $db) {
        $this->db = $db; // Salva la connessione nel repository
    }

    /*
     * Metodo che recupera tutti gli utenti dal database.
     * Restituisce un array di risultati.
     */
    public function getAll(): array {

        // Esegue una query SQL diretta (senza parametri)
        // Seleziona solo i campi necessari dalla tabella users
        $stmt = $this->db->query(
            "SELECT name, email, birthdate FROM users"
        );

        // fetchAll() recupera tutti i record come array associativi
        // (grazie a PDO::FETCH_ASSOC impostato nella connessione)
        return $stmt->fetchAll();
    }

    /*
     * Metodo che inserisce un nuovo utente nel database.
     * Riceve un oggetto User come parametro.
     */
    public function add(User $user): void {

        /*
         * Query SQL con parametri nominati.
         * L'uso dei parametri previene SQL Injection.
         */
        $sql = "INSERT INTO users (name, email, birthdate)
                VALUES (:name, :email, :birthdate)";

        // Prepara la query (non viene ancora eseguita)
        $stmt = $this->db->prepare($sql);

        /*
         * Esegue la query associando i parametri
         * ai valori ottenuti dall'oggetto User
         */
        $stmt->execute([
            ":name" => $user->getName(),           // Recupera il nome dall'oggetto
            ":email" => $user->getEmail(),         // Recupera l'email
            ":birthdate" => $user->getBirthdate()  // Recupera la data di nascita
        ]);
    }
}
