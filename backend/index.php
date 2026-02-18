<?php

/*
 * =========================
 *  GESTIONE CORS
 * =========================
 */

// Permette richieste da qualsiasi origine (frontend separato, es. React/Vue)
header("Access-Control-Allow-Origin: *");

// Permette l'header Content-Type nelle richieste HTTP
header("Access-Control-Allow-Headers: Content-Type");

// Specifica i metodi HTTP consentiti
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Se la richiesta è di tipo OPTIONS (preflight CORS),
// il server risponde immediatamente senza eseguire altro codice
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}


/*
 * =========================
 *  INCLUSIONE DIPENDENZE
 * =========================
 */

// Include la classe per la connessione al database
require_once 'config/Database.php';

// Include il repository per la gestione utenti
require_once 'repositories/UserRepository.php';


/*
 * =========================
 *  INIZIALIZZAZIONE
 * =========================
 */

// Crea la connessione al database
$db = (new Database())->connect();

// Inietta la connessione nel repository (Dependency Injection)
$repo = new UserRepository($db);

// Recupera l'azione passata nell'URL (?action=list oppure ?action=add)
// Se non esiste, assegna stringa vuota
$action = $_GET['action'] ?? '';


/*
 * =========================
 *  ACTION: LISTA UTENTI (GET)
 * =========================
 */

if ($action === 'list') {

    // Restituisce risposta JSON con:
    // - success: stato dell'operazione
    // - data: elenco utenti recuperati dal database
    echo json_encode([
        "success" => true,
        "data" => $repo->getAll()
    ]);
}


/*
 * =========================
 *  ACTION: AGGIUNGI UTENTE (POST)
 * =========================
 */

elseif ($action === 'add') {

    // Legge il body della richiesta HTTP (JSON)
    // php://input permette di leggere dati raw inviati nel body
    $input = json_decode(file_get_contents("php://input"), true);

    // Se il JSON non è valido o vuoto
    if (!$input) {
        echo json_encode([
            "success" => false,
            "error" => "Dati non validi"
        ]);
        exit;
    }

    /*
     * =========================
     *  PULIZIA DATI
     * =========================
     */

    // trim() rimuove spazi iniziali e finali
    // ?? '' evita errori se la chiave non esiste
    $name = trim($input['name'] ?? '');
    $email = trim($input['email'] ?? '');
    $birthdate = trim($input['birthdate'] ?? '');

    /*
     * =========================
     *  VALIDAZIONE CAMPI
     * =========================
     */

    // Controlla che nessun campo obbligatorio sia vuoto
    if ($name === '' || $email === '' || $birthdate === '') {
        echo json_encode([
            "success" => false,
            "error" => "Campi obbligatori"
        ]);
        exit;
    }

    // Validazione email tramite filtro standard PHP
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "success" => false,
            "error" => "Email non valida"
        ]);
        exit;
    }

    /*
     * =========================
     *  INSERIMENTO NEL DATABASE
     * =========================
     */

    try {
        // Crea un nuovo oggetto User
        $user = new User($name, $email, $birthdate);

        // Salva l'utente nel database tramite repository
        $repo->add($user);

        // Risposta di successo
        echo json_encode([
            "success" => true
        ]);

    } catch (PDOException $e) {

        // Gestione errore (es. email duplicata se UNIQUE)
        echo json_encode([
            "success" => false,
            "error" => "Email già esistente"
        ]);
    }
}

/*
 * =========================
 *  ACTION: ELIMINA UTENTE (DELETE)
 * =========================
 */

elseif ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {

    // Recupera ID dall'URL (?action=delete&id=1)
    $id = $_GET['id'] ?? null;

    // Verifica presenza ID
    if (!$id) {
        echo json_encode([
            "success" => false,
            "error" => "ID mancante"
        ]);
        exit;
    }

    try {
        // Conversione a intero per sicurezza
        $repo->delete((int)$id);

        echo json_encode([
            "success" => true
        ]);

    } catch (PDOException $e) {

        echo json_encode([
            "success" => false,
            "error" => "Errore durante eliminazione"
        ]);
    }
}
/*
 * =========================
 *  ACTION: MODIFICA UTENTE (PUT/POST)
 * =========================
 */
elseif ($action === 'update') {

    // Lettura body JSON
    $input = json_decode(file_get_contents("php://input"), true);

    // Verifica presenza dati e ID
    if (!$input || !isset($input['id'])) {
        echo json_encode(["success" => false, "error" => "Dati non validi"]);
        exit;
    }

    // Conversione ID in intero
    $id = intval($input['id']);

    // Creazione nuovo oggetto User con dati aggiornati
    $user = new User($input['name'], $input['email'], $input['birthdate']);
    
    // Aggiornamento tramite repository
    $repo->update($id, $user);

    echo json_encode(["success" => true]);
}
