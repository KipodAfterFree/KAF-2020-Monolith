<?php

// Read data
$data = file_get_contents("php://input");

// Try parse as JSON
$json = json_decode($data);

if (!isset($json->email))
    die("No email");

$email = $json->email;

$f = __DIR__ . "/../private/recipients/" . bin2hex($email) . ".json";

if (file_exists($f)) {

    unlink($f);

    die("Remove account OK");
}

die("Error not exist");