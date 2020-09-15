<?php

/**
 * This script should be ran by cron, as mailman.
 */

// Scan

$recs = scandir(__DIR__ . "/recipients");

$recs = array_slice($recs, 2); // Remove . and ..

// Loop

foreach ($recs as $hexfile) {
    $email = hex2bin(str_replace(".json", "", $hexfile));

    $object = json_decode(file_get_contents(__DIR__ . "/recipients/" . $hexfile));

    $subject = escapeshellarg("Hello, " . $object->title . ". " . $object->name);
    $content = escapeshellarg($object->contents);

    if ($object->enable) {
        shell_exec("echo $content | mail -s $content $email");
    }
}