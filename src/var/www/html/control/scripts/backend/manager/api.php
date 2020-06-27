<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "base" . DIRECTORY_SEPARATOR . "api.php";
include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "accounts" . DIRECTORY_SEPARATOR . "api.php";

const MANAGER_DATABASE = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR . "manager" . DIRECTORY_SEPARATOR . "database.json";
const MANAGER_USER_CONFIGURABLE_PARAMETER = "ucp";
const MANAGER_API = "manager";

$manager_database_file = MANAGER_DATABASE;
$manager_database = null;

function manager()
{
    manager_load();
    return api(MANAGER_API, function ($action, $parameters) {
        global $manager_database;
        if (accounts() !== null) {
            if ($action === "read") {
                $data = new stdClass();
                $data->parameter = MANAGER_USER_CONFIGURABLE_PARAMETER;
                $data->groups = manager_group_names();
                $data->database = $manager_database;
                return [true, $data];
            } else if ($action === "write") {
                if (isset($parameters->name) && !empty($parameters->name)) {
                    $name = $parameters->name;
                    $name = manager_filter($name);
                    if (isset($parameters->endpoint) && isset($parameters->groups)) {
                        $endpoint = $parameters->endpoint;
                        $groups = $parameters->groups;
                        if (is_array($groups) && filter_var($endpoint, FILTER_VALIDATE_URL)) {

                            for ($g = 0; $g < count($groups); $g++) {
                                $groups[$g] = manager_filter($groups[$g]);
                            }
                            if (!empty($endpoint)) {
                                $entry = new stdClass();
                                $entry->endpoint = $endpoint;
                                $entry->groups = $groups;
                                $manager_database->$name = $entry;
                                manager_save();
                                return [true, null];
                            } else {
                                return [false, "Empty parameters"];
                            }
                        } else {
                            return [false, "Wrong parameters"];
                        }
                    } else {
                        if (isset($manager_database->$name)) {
                            unset($manager_database->$name);
                            manager_save();
                        } else {
                            $entry = new stdClass();
                            $entry->endpoint = "";
                            $entry->groups = [];
                            $manager_database->$name = $entry;
                            manager_save();
                        }
                        return [true, null];
                    }
                } else {
                    return [false, "Missing information"];
                }
            }
        } else {
            return [false, "Authentication failure"];
        }
        return [false, "Unknown action"];
    }, true);
}

function manager_filter($name)
{
    $characters = str_split("AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz0123456789-");
    $return = $name;
    foreach (str_split($name) as $current) {
        $found = false;
        foreach ($characters as $char) if ($char === $current) $found = true;
        if (!$found) $return = str_replace($current, "", $return);
    }
    return $return;
}

function manager_groups()
{
    global $manager_database;
    $groups = new stdClass();
    foreach ($manager_database as $name => $endpoint) {
        $endpoint_groups = $endpoint->groups;
        foreach ($endpoint_groups as $endpoint_group) {
            if (!isset($groups->$endpoint_group)) {
                $groups->$endpoint_group = array();
            }
            array_push($groups->$endpoint_group, $name);
        }
    }
    return $groups;
}

function manager_group_names()
{
    $groups = manager_groups();
    $names = array();
    foreach ($groups as $name => $endpoints) {
        array_push($names, $name);
    }
    return $names;
}

function manager_load()
{
    global $manager_database, $manager_database_file;
    $manager_database = json_decode(file_get_contents($manager_database_file));
}

function manager_save()
{
    global $manager_database, $manager_database_file;
    file_put_contents($manager_database_file, json_encode($manager_database));
}