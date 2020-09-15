<?php

// Read data
$data = file_get_contents("php://input");

// Try parse as JSON
$json = json_decode($data);

$validator = "http://localhost/email/validate/";

if (!isset($json->name))
    die("No name");

if (!isset($json->title))
    die("No title");

if (!isset($json->email))
    die("No email");

$name = $json->name;
$title = $json->title;
$email = $json->email;

if (isset($json->check))
    $validator = $json->check;

if (file_get_contents($validator . "?email=" . urlencode($email)) === "OK") {

    $o = new stdClass();
    $o->enable = false;
    $o->name = $name;
    $o->title = $title;
    $o->contents = "Hello from Automail solutions!";

    $f = __DIR__ . "/../private/recipients/" . bin2hex($email) . ".json";

    if (!file_exists($f)) {

        file_put_contents($f, json_encode($o));

        die("Sign up ok but not enable, please verify");
    } else {
        die("Error already exists");
    }
}

die("Validation issue");