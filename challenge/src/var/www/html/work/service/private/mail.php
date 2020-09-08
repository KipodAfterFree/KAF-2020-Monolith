<?php

/**
 * This script should be ran by cron, as mailman.
 */

// Read

$json = json_decode(file_get_contents(__DIR__ . "/recipients.json"));

// Loop

foreach ($json as $email => $object) {
    $title = $object->title;
    $name = $object->name;
    $content = $object->contents;
    shell_exec("echo $content | mail -s \"Hello, $title, $name\" $email");
}