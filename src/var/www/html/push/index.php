<?php
// Push v3.2
// Host for writing new pushes, or reading relevant pushes.
// Local Variables
$namer = "group";
$pushesFile = 'pushes.json';
$filterKey = 'filter';
$passwordKey = 'key';
$password = 'nadavsmessages';

// Setup JSON
$json = json_decode(file_get_contents($pushesFile));

function createPush($title, $message, $sender, $receiver)
{
    // Setup globals
    global $json,$namer;
    // Generate an identifier, so that we can call this push from multiple indexes and register it in the app.
    $pushId=0;
    while ($pushId==0) {
        $currentRand=rand(100000, 999999);
        if (!isset($json->pushes->$currentRand)) {
            $pushId=$currentRand;
        }
    }
    // Write push to general database.
    $json->pushes->$pushId->title = $title;
    $json->pushes->$pushId->message = $message;
    $json->pushes->$pushId->sender = $sender;
    $json->pushes->$pushId->timeStamp->year=date("Y");
    $json->pushes->$pushId->timeStamp->month=date("m");
    $json->pushes->$pushId->timeStamp->day=date("d");
    // Write id in $receiver's index.
    for ($i = 0; $i < sizeof($receiver); $i++) {
        $arrayName=$namer . $receiver[$i];
        if (!isset($json->indexes->$arrayName)) {
            $json->indexes->$arrayName=array();
        }
        array_push($json->indexes->$arrayName, $pushId);
    }
}

function save()
{
    global $json, $pushesFile;
    file_put_contents($pushesFile, json_encode($json));
}

if (isset($_POST[$passwordKey])) {
    // Admin Mode - Write Pushes.
    $response->mode="admin";
    // Verify Password
    $enteredPassword = $_POST[$passwordKey];
    if ($enteredPassword == $password) {
        // Admin Mode approved.
        if (isset($_POST['title'])&&isset($_POST['message'])&&isset($_POST['sender'])&&isset($_POST['receivers'])) {
            createPush($_POST['title'], $_POST['message'], $_POST['sender'], json_decode($_POST['receivers'], true));
            save();
        }
    }
    $response->approved=($enteredPassword == $password);
} else {
    // Client Mode - Read Pushes.
    $response->mode="client";
    $response->approved=true;
    // Check For Info
    $dataFilter=[0];
    if (isset($_POST[$filterKey])) {
        $dataFilter=json_decode($_POST[$filterKey], true);
    }
    $responseList=array();
    for ($i = 0; $i < sizeof($dataFilter); $i++) {
        $arrayName=$namer . $dataFilter[$i];
        if (isset($json->indexes->$arrayName)) {
            for ($b = 0; $b < sizeof($json->indexes->$arrayName); $b++) {
                $pushId=$json->indexes->$arrayName[$b];
                $push = $json->pushes->$pushId;
                $push->id = $pushId;
                // Check if the push should be given at this time
                $pushDay=$push->timeStamp->day;
                $pushMonth=$push->timeStamp->month;
                $pushYear=$push->timeStamp->year;
                $pushDate=($pushYear*365)+($pushMonth-1)*31+$pushDay;
                $currentDate=(date("Y")*365)+(date("m")-1)*31+date("d");
                if ($pushDate+1>=$currentDate) {
                    array_push($responseList, $push);
                }
            }
        }
    }
    $response->pushes=$responseList;
}
echo json_encode($response);
