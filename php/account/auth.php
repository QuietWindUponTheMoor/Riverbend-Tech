<?php
require $_SERVER["DOCUMENT_ROOT"]."/php/DotEnv.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/vendor/autoload.php";

use Google\Client;

header("Content-Type: application/json");

// Get the token from the request body
$input = file_get_contents("php://input");
$data = json_decode($input, true);
$id_token = $data["id_token"] ?? "";

if (!$id_token) {
    echo json_encode(["success" => false, "error" => "No ID token provided"]);
    exit;
}

$client = new Google_Client();
$client->setClientId($_ENV["gClientID"]);

try {
    // Verify the token
    $payload = $client->verifyIdToken($id_token);
    if ($payload) {
        $userid = $payload["sub"]; // Google user ID
        $email = $payload["email"];
        $name = $payload["name"];

        // Perform your database actions here (e.g., check if user exists or register them)

        echo json_encode(["success" => true, "user" => ["id" => $userid, "email" => $email, "name" => $name]]);
    } else {
        echo json_encode(["success" => false, "error" => "Invalid ID token"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}