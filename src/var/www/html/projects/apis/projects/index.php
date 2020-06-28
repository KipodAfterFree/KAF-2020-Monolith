<?php

include_once "../base/Base.php";

Base::handle(function ($action, $parameters) {
    if ($action === "list") {
        $extension1 = "png";
        $extension2 = "info";

        if (isset($parameters->extension1))
            $extension1 = $parameters->extension1;
        if (isset($parameters->extension2))
            $extension2 = $parameters->extension2;

        $directory = "/var/www/html";

        $array = array();

        foreach (scandir($directory) as $project) {
            if (is_dir($directory . DIRECTORY_SEPARATOR . $project)) {
                $object = new stdClass();
                $object->project = $project;
                foreach (scandir($directory . DIRECTORY_SEPARATOR . $project) as $file) {
                    if ((strlen($extension1) !== 0 && substr($file, -strlen($extension1)) === $extension1)) {
                        $object->icon = $project . DIRECTORY_SEPARATOR . $file;
                    }
                    if ((strlen($extension2) !== 0 && substr($file, -strlen($extension2)) === $extension2)) {
                        $object->description = file_get_contents($directory . DIRECTORY_SEPARATOR . $project . DIRECTORY_SEPARATOR . $file);
                    }
                }
                if (isset($object->icon) && isset($object->description)) {
                    array_push($array, $object);
                }
            }
        }

        return [true, $array];
    }
    return [false, "Unknown action"];
});