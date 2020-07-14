<?php
include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "backend" . DIRECTORY_SEPARATOR . "accounts" . DIRECTORY_SEPARATOR . "api.php";

$user = $argv[1];
$password = $argv[2];

accounts_load();
accounts_register($user, $password);

echo "Registered $user with password " . obfuscate($password) . ".\n";

function obfuscate($text)
{
    $return = $text[0];
    for ($i = 1; $i < strlen($text); $i++) $return .= "*";
    return $return;
}