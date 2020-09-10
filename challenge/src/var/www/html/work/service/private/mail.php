<?php

/**
 * This script should be ran by cron, as mailman.
 */

// Scan

$recs = scandir("recipients");

$recs = array_slice($recs, 2);

// Loop

foreach ($recs as $hexfile) {
    $email = hex2bin(str_replace(".json", "", $hexfile));
    $object = json_decode(file_get_contents("recipients/" . $hexfile));
    $subject = escapeshellarg("Hello, " . $object->title . ". " . $object->name);
    $content = escapeshellarg($object->contents);
    if ($object->enable)
        shell_exec("echo $content | mail -s $content $email");
}