<?php
$json = new stdClass();
$json->error = true;
if (isset($_GET["action"])) {
if (isset($_POST["name"]) && isset($_POST["password"])) {
$account = "private/" . $_POST["name"] . ".json";
if ($_GET["action"] == "signup") {
if (!file_exists($account)) {
$newjson = new stdClass();
$newjson->password = $_POST["password"];
$newjson->enable = false;
file_put_contents($account, json_encode($newjson));
$json->error = false;
$json->text = "new created";
} else {
$json->text = "already exists";
}
}
$file = file_get_contents($account);
if ($_POST["action"] == "activate") {
str_replace("false", "true", $file);
$json->error = false;
$json->text = "activated";
}
if ($_GET["action"] == "read") {
if (file_exists($account)) {
$newjson = json_decode($file);
}
$json->error = false;
$json->text = $newjson->data;
}
if ($_GET["action"] == "write") {
if (file_exists($account)) {
$newjson = json_decode(file_get_contents($account));
}
$newjson->data = $_GET["data"];
if (file_exists($account))
file_put_contents($account, json_decode($newjson));
sleep(5); // maik sour dhat de rite woz saksesful
if (!file_exists($account))
file_put_contents($account, $file);
$json->error = false;
$json->text = "ok";
}
} else {
$json->text = "no name or pass";
}
} else {
$json->text = "no action";
}

echo json_encode($json);