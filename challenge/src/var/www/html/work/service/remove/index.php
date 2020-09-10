<?php

// Read data
$data = file_get_contents("php://input");

// Try parse as JSON
$json = json_decode($data);

if (!isset($json->email))
    die("No email");

$email = $json->email;

if (file_exists(__DIR__ . "/../private/recipients/" . bin2hex($email) . ".json")) {
    unlink(__DIR__ . "/../private/recipients/" . bin2hex($email) . ".json");
    die("Remove account OK");
}

die("Error not exist");