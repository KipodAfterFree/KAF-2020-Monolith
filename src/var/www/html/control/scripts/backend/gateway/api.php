<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "base" . DIRECTORY_SEPARATOR . "api.php";
include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "manager" . DIRECTORY_SEPARATOR . "api.php";

const GATEWAY_API = "gateway";

function gateway()
{
    global $manager_database;
    manager_load();
    if (isset($_GET["name"])) {
        $name = $_GET["name"];
        $parameter = null;
        if (isset($_GET[MANAGER_USER_CONFIGURABLE_PARAMETER])) {
            $parameter = $_GET[MANAGER_USER_CONFIGURABLE_PARAMETER];
        }
        if (isset($manager_database->$name)) {
            $endpoint = $manager_database->$name->endpoint;
            if ($parameter !== null) {
                $endpoint = str_replace("#(" . MANAGER_USER_CONFIGURABLE_PARAMETER . ")#", $parameter, $endpoint);
            }
            fetch([$endpoint]);
        }
    } else if (isset($_GET["group"])) {
        $group = $_GET["group"];
        $parameter = null;
        if (isset($_GET[MANAGER_USER_CONFIGURABLE_PARAMETER])) {
            $parameter = $_GET[MANAGER_USER_CONFIGURABLE_PARAMETER];
        }
        $groups = manager_groups();
        if (isset($groups->$group)) {
            $endpoints = $groups->$group;
            $urls = array();
            for ($e = 0; $e < count($endpoints); $e++) {
                if (isset($manager_database->{$endpoints[$e]})) {
                    $endpoint = $manager_database->{$endpoints[$e]}->endpoint;
                    if ($parameter !== null) {
                        $endpoint = str_replace("#(" . MANAGER_USER_CONFIGURABLE_PARAMETER . ")#", $parameter, $endpoint);
                    }
                    array_push($urls, $endpoint);
                }
            }
            fetch($urls);
        }
    } else {
        echo "No parameters";
    }
}

function fetch($urls)
{
    $multi = curl_multi_init();
    $reqs = [];
    foreach ($urls as $url) {
        $req = curl_init();
        curl_setopt($req, CURLOPT_URL, $url);
        curl_setopt($req, CURLOPT_HEADER, 0);
        curl_multi_add_handle($multi, $req);
        $reqs[] = $req;
    }
    $active = null;
    do {
        $mrc = curl_multi_exec($multi, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);

    while ($active && $mrc == CURLM_OK) {
        if (curl_multi_select($multi) != -1) {
            do {
                $mrc = curl_multi_exec($multi, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }
    foreach ($reqs as $req) {
        curl_multi_remove_handle($multi, $req);
    }
    curl_multi_close($multi);
}