<?php
/*
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
*/
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once 'config/Database.php';
require_once 'repositories/UserRepository.php';

$db = (new Database())->connect();
$repo = new UserRepository($db);

$action = $_GET['action'] ?? '';

if ($action === 'list') {
    echo json_encode([
        "success" => true,
        "data" => $repo->getAll()
    ]);
}

elseif ($action === 'add') {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input) {
        echo json_encode(["success" => false, "error" => "Dati non validi"]);
        exit;
    }

    $name = trim($input['name'] ?? '');
    $email = trim($input['email'] ?? '');
    $birthdate = trim($input['birthdate'] ?? '');

    if ($name === '' || $email === '' || $birthdate === '') {
        echo json_encode(["success" => false, "error" => "Campi obbligatori"]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "error" => "Email non valida"]);
        exit;
    }

    try {
        $user = new User($name, $email, $birthdate);
        $repo->add($user);
        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "error" => "Email già esistente"
        ]);
    }
}
