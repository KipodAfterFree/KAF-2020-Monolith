<?php
$accountsdir='/var/www/ftc/private/accounts';
$fileUrl='http://ftc.thepuzik.com/sign/login.php';
$defaultClient='server_verification_login';
if(isset($_POST['login']) && isset($_POST['key'])&&isset($_POST['action'])){
  $accountname=$_POST['login'];
  $accountkey=$_POST['key'];
  $action=$_POST['action'];
  if($action=="verify"){
  $logfile="$accountsdir/$accountname/log.txt";
  $accountsett="$accountsdir/$accountname/settings.json";
  $nmtg='name';
  $kytg='key';
  if(!isset($_POST['client'])){
    $client="unknown";
  }else{
    $client=$_POST['client'];
  }
  $date=date("d/m/Y");
  $time=date("h:ia");
  if(file_exists($accountsett)){
    $fp = fopen($accountsett, 'r');
    $readJson=json_decode(fread($fp,filesize($accountsett)));
    fclose($fp);
    $realkey=$readJson->key;
            if($realkey==$_POST['key']){
              $data="$client $date@$time:(granted)\n";
              file_put_contents($logfile, $data, FILE_APPEND | LOCK_EX);
              $json->login = $_POST['login'];
              $json->key = $_POST['key'];
              $json->access = ($realkey==$_POST['key']);
              $json->real = true;
              $json->success = true;
              $alljson = json_encode($json);
              echo $alljson;
            }else{
              $data="$client $date@$time:(denied)\n";
              file_put_contents($logfile, $data, FILE_APPEND | LOCK_EX);
              $json->login = $_POST['login'];
              $json->key = $_POST['key'];
              $json->access = ($realkey==$_POST['key']);
              $json->real = true;
              $json->success = true;
              $alljson = json_encode($json);
              echo $alljson;
            }
          }else{
            $json->login = $_POST['login'];
            $json->key = $_POST['key'];
            $json->access = false;
            $json->real = false;
            $json->success = true;
            $alljson = json_encode($json);
            echo $alljson;
          }
        }else if($action=="signup"){
          $account_available=is_dir("$accountsdir/$accountname");
          if(!$account_available){
            $sefile="$accountsdir/$accountname/settings.json";
            $madeDir=mkdir("$accountsdir/$accountname",0777);
            $json->login = $accountname;
            $json->key = $accountkey;
            $json->signup = $madeDir;
            if($madeDir){
              $readJson->key=$_POST['key'];
              $fp = fopen($sefile, 'w');
              fwrite($fp, json_encode($readJson));
              fclose($fp);
              mkdir("$accountsdir/$accountname/files",0777);
              $json->access = true;
            }else{
              $json->access = false;
            }
            $json->taken=false;
          }else{
            $json->taken=true;
          }
            $json->success = true;
            $alljson = json_encode($json);
            echo $alljson;
        }else if($action=="read" && isset($_POST['file']) && isset($_POST['tag'])){
          $datas = array('login'=> $accountname,'action'=> "verify",'key'=> $accountkey,'client'=> $defaultClient);
          $ch = curl_init($fileUrl);
          curl_setopt($ch, CURLOPT_POST, count($datas));
          curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datas));
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $result = curl_exec($ch);
          curl_close($ch);
          $alljson=json_decode($result,true);
          $access=($alljson['access']=="true");
          if($access){
            if(file_exists("$accountsdir/$accountname/files/" . $_POST['file'] . ".json")){
              $filename=$_POST['file'];
              $file = "$accountsdir/$accountname/files/" . $_POST['file'] . ".json";
              $fp = fopen($file, 'r');
              $readJson=json_decode(fread($fp,filesize($file)));
              fclose($fp);
              $tag=$_POST['tag'];
              $json->result=$readJson->$tag;
              $json->success=true;
              // $fp = fopen('results.json', 'w');
              // fwrite($fp, json_encode($response));
              // fclose($fp);
          }else{
            $json->success=false;
          }
        }else{
          $json->success=false;
        }
        echo json_encode($json);
}else if($action=="write" && isset($_POST['file']) && isset($_POST['tag']) && isset($_POST['value'])){
  $datas = array('login'=> $accountname,'action'=> "verify",'key'=> $accountkey,'client'=> $defaultClient);
  $ch = curl_init($fileUrl);
  curl_setopt($ch, CURLOPT_POST, count($datas));
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datas));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch);
  curl_close($ch);
  $alljson=json_decode($result,true);
  $access=($alljson['access']=="true");
  if($access){
    if(file_exists("$accountsdir/$accountname/files/" . $_POST['file'] . ".json")){
      $filename=$_POST['file'];
      $file = "$accountsdir/$accountname/files/" . $_POST['file'] . ".json";
      $fp = fopen($file, 'r');
      $readJson=json_decode(fread($fp,filesize($file)));
      fclose($fp);
      $tag=$_POST['tag'];
      $readJson->$tag=$_POST['value'];
      $fp = fopen($file, 'w');
      fwrite($fp, json_encode($readJson));
      $json->wrote=true;
      fclose($fp);
      $json->success=true;
  }else{
    $filename=$_POST['file'];
    $file = "$accountsdir/$accountname/files/" . $_POST['file'] . ".json";
    $tag=$_POST['tag'];
    $readJson->$tag=$_POST['value'];
    $fp = fopen($file, 'w');
    fwrite($fp, json_encode($readJson));
    $json->wrote=true;
    fclose($fp);
    $json->success=true;
}
}else{
  $json->success=false;
}
echo json_encode($json);
}else if($action=="writePublic" && isset($_POST['tag']) && isset($_POST['value'])){
  $datas = array('login'=> $accountname,'action'=> "verify",'key'=> $accountkey,'client'=> $defaultClient);
  $ch = curl_init($fileUrl);
  curl_setopt($ch, CURLOPT_POST, count($datas));
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datas));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch);
  curl_close($ch);
  $alljson=json_decode($result,true);
  $access=($alljson['access']=="true");
  if($access){
    if(file_exists("$accountsdir/$accountname/public.json")){
      $file = "$accountsdir/$accountname/public.json";
      $fp = fopen($file, 'r');
      $readJson=json_decode(fread($fp,filesize($file)));
      fclose($fp);
      $tag=$_POST['tag'];
      $readJson->$tag=$_POST['value'];
      $fp = fopen($file, 'w');
      fwrite($fp, json_encode($readJson));
      $json->wrote=true;
      fclose($fp);
      $json->success=true;
  }else{
    $file = "$accountsdir/$accountname/public.json";
    $tag=$_POST['tag'];
    $readJson->$tag=$_POST['value'];
    $fp = fopen($file, 'w');
    fwrite($fp, json_encode($readJson));
    $json->wrote=true;
    fclose($fp);
    $json->success=true;
}
}else{
  $json->success=false;
}
echo json_encode($json);
}else if($action=="readPublic" && isset($_POST['tag'])){
  $file = "$accountsdir/$accountname/public.json";
    if(file_exists("$accountsdir/$accountname/public.json")){
      $file = "$accountsdir/$accountname/public.json";
      $fp = fopen($file, 'r');
      $readJson=json_decode(fread($fp,filesize($file)));
      fclose($fp);
      $tag=$_POST['tag'];
      $json->result=$readJson->$tag;
      $json->success=true;
  }else{
    $json->success=false;
  }
echo json_encode($json);
}else if($action=="checkFile" && isset($_POST['file'])){
  $datas = array('login'=> $accountname,'action'=> "verify",'key'=> $accountkey,'client'=> $defaultClient);
  $ch = curl_init($fileUrl);
  curl_setopt($ch, CURLOPT_POST, count($datas));
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datas));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch);
  curl_close($ch);
  $alljson=json_decode($result,true);
  $access=($alljson['access']=="true");
  if($access){
    if(file_exists("$accountsdir/$accountname/files/" . $_POST['file'] . ".json")){
      $json->file=$_POST['file'];
      $json->success=true;
  }else{
    $json->file=$_POST['file'];
    $json->success=false;
  }
}else{
  $json->success=false;
}
echo json_encode($json);
}else{
  $json->success=false;
  echo json_encode($json);
}
}else if(isset($_POST['login'])&&isset($_POST['action'])){
  $action = $_POST['action'];
  $loginame=$_POST['login'];
  if($action=="readPublic" && isset($_POST['tag'])){
    $file = "$accountsdir/$loginame/public.json";
      if(file_exists("$accountsdir/$loginame/public.json")){
        $file = "$accountsdir/$loginame/public.json";
        $fp = fopen($file, 'r');
        $readJson=json_decode(fread($fp,filesize($file)));
        fclose($fp);
        $tag=$_POST['tag'];
        $json->result=$readJson->$tag;
        $json->success=true;
    }else{
      $json->success=false;
    }
  echo json_encode($json);
  }
}else{
  $json->success=false;
echo json_encode($json);
}
?>
