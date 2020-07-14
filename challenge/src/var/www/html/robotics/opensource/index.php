<?php

if (isset($_GET["file"])) {

    $path_to_file = realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . $_GET["file"]);
    $path_to_dir = realpath(__DIR__);

    if (strlen($path_to_file) > strlen($path_to_dir)) {
        http_response_code(200);
        header('Content-Disposition: attachment; filename=robotics-opensource-file.txt');
        header('Content-Type: application/octet-stream');
        echo file_get_contents($path_to_file);
    }else{
        http_response_code(403);
        echo "Access denied";
    }

} else {

    http_response_code(404);
    echo "File not found";

}