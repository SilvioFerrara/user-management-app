<?php   //Ogni file PHP DEVE iniziare così.
header("Content-Type: application/json");   //“La risposta che ti mando è JSON, non HTML”
//CORS
//React gira su http://localhost:5173
//PHP gira su http://localhost:8000
//header("Access-Control-Allow-Origin: *"); //Sostituito dalla riga successiva
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

//GESTIRE LA RICHIESTA OPTIONS
//Il browser, prima della POST, manda una richiesta speciale chiamata preflight OPTIONS.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

//$_GET: È una variabile speciale di PHP che contiene i parametri nell’URL.
/*
?? '' :
È l’operatore di fallback:
se action esiste → usalo
se non esiste → stringa vuota
Serve a evitare errori.
*/
$action = $_GET['action'] ?? '';

//action=list → chiama getUsers()
//action=add → chiama addUser()
if ($action === 'list') {
    getUsers();
} elseif ($action === 'add') {
    addUser();
} else {
    //Caso errore
    echo json_encode([
        "success" => false,
        "error" => "Azione non valida"
    ]);
}

function getUsers() {
    //Legge TUTTO il file users.json come testo.
    $data = file_get_contents("users.json");    
    
    //Convertire JSON → Array PHP :
    //Array PHP: JSON → array PHP
    //true = array associativo (più semplice)
    $users = json_decode($data, true);  

    //Risposta
    //Trasformiamo l’array PHP → JSON
    //Lo mandiamo al browser
    echo json_encode([
        "success" => true,
        "data" => $users
    ]);
}

function addUser() {
    //Leggere il body della richiesta
    /*
    Qui PHP legge:
    {
    "name": "...",
    "email": "...",
    "birthdate": "..."
    }
    che arriva dal frontend.
    */
    $input = json_decode(file_get_contents("php://input"), true);

    //Se non arriva JSON → errore.
    if (!$input) {
        echo json_encode([
            "success" => false,
            "error" => "Dati non validi"
        ]);
        return;
    }

    //Pulizia dati
    //trim → toglie spazi
    // ?? '' → evita errori se manca il campo
    $name = trim($input['name'] ?? '');
    $email = trim($input['email'] ?? '');
    $birthdate = trim($input['birthdate'] ?? '');

    //Validazione campi: Campi obbligatori
    if ($name === '' || $email === '' || $birthdate === '') {
        echo json_encode([
            "success" => false,
            "error" => "Tutti i campi sono obbligatori"
        ]);
        return;
    }

    //Validazione email: Metodo standard PHP per email valide.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "success" => false,
            "error" => "Email non valida"
        ]);
        return;
    }

    $data = file_get_contents("users.json");
    $users = json_decode($data, true);

    //Aggiungere l’utente alla lista
    $users[] = [
        "name" => $name,
        "email" => $email,
        "birthdate" => $birthdate
    ];

    /*
    Salvare su file
    Sovrascrive il file
    JSON_PRETTY_PRINT = leggibile
    */
    file_put_contents("users.json", json_encode($users, JSON_PRETTY_PRINT));

    //Risposta finale
    echo json_encode([
        "success" => true
    ]);
}
