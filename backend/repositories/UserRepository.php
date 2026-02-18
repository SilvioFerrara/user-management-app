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
            //"SELECT name, email, birthdate FROM users" 
            "SELECT id, name, email, birthdate FROM users" //id serve per delete e update
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
    /*
    * Elimina un utente tramite ID
    */
    public function delete(int $id): void {

        // Prepara la query SQL con parametro nominato
        // L'uso dei parametri previene SQL Injection
        $stmt = $this->db->prepare(
            "DELETE FROM users WHERE id = :id"
        );

        // Esegue la query sostituendo :id con il valore reale
        $stmt->execute([
            ":id" => $id
        ]);
        // Nota:
        // Se l'ID non esiste, la query non genera errore
        // ma semplicemente non elimina nessuna riga.
    }

    /*
    * Aggiorna un utente esistente
    */
    public function update(int $id, User $user): void {
        /*
         * Query SQL parametrizzata:
         * - Aggiorna i campi name, email, birthdate
         * - Solo per il record con l'ID specificato
         */
        $sql = "UPDATE users
                SET name = :name,
                    email = :email,
                    birthdate = :birthdate
                WHERE id = :id";

        // Prepara la query
        $stmt = $this->db->prepare($sql);
        
        // Esegue la query associando:
        // - i valori presi dall'oggetto User
        // - l'ID del record da aggiornare
        $stmt->execute([
            ":name" => $user->getName(),
            ":email" => $user->getEmail(),
            ":birthdate" => $user->getBirthdate(),
            ":id" => $id
        ]);
        // Nota:
        // Se l'ID non esiste, nessuna riga verrà aggiornata.
        // Per verificarlo si potrebbe controllare:
        // $stmt->rowCount();
    }



}
