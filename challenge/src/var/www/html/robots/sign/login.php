<?php
$json = new stdClass();
$json->error = true;
if (isset($_GET["action"])) {
if (isset($_GET["name"]) && isset($_GET["password"])) {
$account = "../private/" . $_GET["name"] . ".json";
$file = file_get_contents($account);
if ($_POST["action"] == "activate") {
str_replace("false", "true", $file);
$json->error = false;
$json->text = "activated";
sleep(5); // ensur deley so no brute fors
}
if ($_GET["action"] == "signup") {
$newjson = new stdClass();
$newjson->password = $_GET["password"];
$newjson->enable = false;
file_put_contents($account, json_encode($newjson));
$json->error = false;
$json->text = "new created";
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
if ($_GET["password"]!=$newjson->password) {
$json->text = "password wrong";
}
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