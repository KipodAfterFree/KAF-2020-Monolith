<?php

/**
 * This script should be ran by cron, as mailman.
 */

// Read

$json = json_decode(file_get_contents(__DIR__ . "/recipients.json"));

// Loop

foreach ($json as $email => $object) {
    $subject = escapeshellarg("Hello, " . $object->title . ". " . $object->name);
    $content = escapeshellarg($object->contents);
    if ($object->enable)
        shell_exec("echo $content | mail -s $content $email");
}