<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");
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

// Set permission level groups
$externalWhitelist = [];
$managers = [];
$admins = [];
$Query = new Query(
    $conn,
    "sa",
    "SELECT * FROM managers;",
    "",
) or die("There was an issue selecting managers list, please try again or contact an administrator.");
$result = $Query->result;
if ($result->num_rows > 0) {
    while ($r = mysqli_fetch_assoc($result)) {
        array_push($managers, $r["email"]);
    }
}
$Query = new Query(
    $conn,
    "sa",
    "SELECT * FROM admins;",
    "",
) or die("There was an issue selecting admins list, please try again or contact an administrator.");
$result = $Query->result;
if ($result->num_rows > 0) {
    while ($r = mysqli_fetch_assoc($result)) {
        array_push($admins, $r["email"]);
    }
}
$Query = new Query(
    $conn,
    "sa",
    "SELECT * FROM external_whitelist;",
    "",
) or die("There was an issue selecting external_whitelist, please try again or contact an administrator.");
$result = $Query->result;
if ($result->num_rows > 0) {
    while ($r = mysqli_fetch_assoc($result)) {
        array_push($externalWhitelist, $r["email"]);
    }
}

try {
    // Verify the token
    $payload = $client->verifyIdToken($id_token);
    if ($payload) {
        $userid = $payload["sub"]; // Google user ID
        $email = $payload["email"];
        $name = $payload["name"];
        $picture = $payload["picture"];
        $nameParts = explode(" ", $name);
        $first = $nameParts[0];
        $last = $nameParts[1];

        // Check if is external and check whitelist
        if (!emailIsInternal($email)) {
            if (!in_array($email, $externalWhitelist)) {
                echo json_encode(["success" => false, "error" => "This user is from an external domain and is not on the allow list."]);
                return;
            }
        }

        // Check permission level
        $perm = 0;
        if (in_array($email, $managers)) {
            $perm = 1;
        }
        if (in_array($email, $admins)) {
            $perm = 2;
        }

        // Create user if necessary
        $insert = new Query(
            $conn,
            "i",
            "INSERT INTO users (`uid`, permission_level, email, `image`, `first`, `last`, full_name)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                `first`=VALUES(`first`),
                `last`=VALUES(`last`),
                full_name=VALUES(full_name),
                permission_level=VALUES(permission_level),
                `image`=VALUES(`image`);",
            "sssssss",
            $userid,
            $perm,
            $email,
            $picture,
            $first,
            $last,
            $name
        ) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

        // Perform your database actions here (e.g., check if user exists or register them)
        echo json_encode(["success" => true,
            "user" => [
                "id" => $userid,
                "email" => $email,
                "first" => $first,
                "last" => $last,
                "name" => $name,
                "picture" => $picture,
                "perm_level" => $perm,
            ],
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "Invalid ID token"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}

function emailIsInternal($email) {
    $domain = substr(strrchr($email, "@"), 1);
    $internalDomain = "riverbendschools.net";
    if ($domain === $internalDomain) {
        // Is internal email
        return true;
    } else {
        return false;
    }
}