<?php

include_once "../base/Base.php";

Base::handle(function ($action, $parameters) {
    if ($action === "list") {
        $extension = "png";

        if (isset($parameters->extension))
            $extension = $parameters->extension;

        $directory = "/var/www/html";

        $array = array();

        foreach (scandir($directory) as $project) {
            foreach (scandir($directory . DIRECTORY_SEPARATOR . $project) as $file) {
                if ((strlen($extension) === 0 || substr($file, -strlen($extension)) === $extension)) {

                    $object = new stdClass();

                    $object->icon = $project . DIRECTORY_SEPARATOR . $file;
                    $object->project = $project;
                    $object->description = file_get_contents($project . DIRECTORY_SEPARATOR . ".describe");

                    array_push($array, $object);
                }
            }
        }

        return [true, $array];
    }
    return [false, "Unknown action"];
});