<?php
include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "backend" . DIRECTORY_SEPARATOR . "accounts" . DIRECTORY_SEPARATOR . "api.php";

file_put_contents(ACCOUNTS_DATABASE, json_encode(new stdClass()));

echo "Clear\n";