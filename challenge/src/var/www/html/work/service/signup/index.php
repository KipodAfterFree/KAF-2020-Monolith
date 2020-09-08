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

if (file_get_contents($validator . "?email=$email") === "OK") {
    $o = json_decode(file_get_contents("../private/recipients.json"));
    $o->$email = new stdClass();
    $o->$email->enable = false;
    $o->$email->name = $name;
    $o->$email->title = $title;
    $o->$email->contents = "Hello from Automail solutions!";
    file_put_contents("../private/recipients.json", json_encode($o));
    die("Sign up ok but not enable, please verify");
}

die("Validation issue");