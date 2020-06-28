<?php
if (isset($_POST['add'])) {
    $date=$_POST['add'];
    $settingsFile = './datas.json';
    $fileStream = fopen($settingsFile, 'r');
    $fileValue=json_decode(fread($fileStream, filesize($settingsFile)));
    fclose($fileStream);
    if(isset($fileValue->$date)){
      $fileValue->$date+=1;
    }else{
      $fileValue->$date=1;
    }
    $fileStream = fopen($settingsFile, 'w');
    fwrite($fileStream, json_encode($fileValue));
    fclose($fileStream);
} elseif (isset($_POST['get'])) {
    $type=$_POST['get'];
    $settingsFile = './datas.json';
    $fileStream = fopen($settingsFile, 'r');
    $fileValue=json_decode(fread($fileStream, filesize($settingsFile)));
    fclose($fileStream);
    echo json_encode($fileValue);
}
