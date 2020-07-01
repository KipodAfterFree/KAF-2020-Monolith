<!DOCTYPE html>
<html>
<head>
  <title>Battery Status V1</title>
  <meta name="theme-color" content="#184691" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
</head>
<style>
html {
  overflow-x: hidden;
  width: 100%;
  height: 100%;
  -moz-background-size: cover;
  -webkit-background-size: cover;
  background-size: cover;
  background-position: top center !important;
  background-repeat: no-repeat !important;
  background-attachment: fixed;
}
body {
  position: relative;
}
option{
  background-color: #737a84; /* Blue */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  transition-duration: 0.4s;
  cursor: pointer;
  border-radius: 3px;
  font-size:125%;
  color:#FFFFFF;
  width: 300px;
  height: 55px;
  margin-bottom: 10px;
}
select{
  background-color: #737a84; /* Blue */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  transition-duration: 0.4s;
  cursor: pointer;
  border-radius: 3px;
  font-size:125%;
  color:#FFFFFF;
  width: 300px;
  height: 55px;
  margin-bottom: 10px;
}
.inner {
  position: relative;
  margin: auto;
}
.buttonnew:active {
  background-color: #8d96a3;
}
.buttonnew {
  background-color: #737a84; /* Blue */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  transition-duration: 0.4s;
  cursor: pointer;
  border-radius: 3px;
  font-size:150%;
  color:#FFFFFF;
  width: 300px;
  height: 55px;
  margin-bottom: 10px;
}
.buttonnew:hover {
  background-color: #8d96a3;
}
.input{
  background-color: #333;
  text-align: center;
  border-radius: 5px;
  font-size:175%;
  color:#FFFFFF;
  width: 300px;
  height: 60px;
  display: block;
}
.input2{
  background-color: #555;
  text-align: center;
  border-radius: 5px;
  font-size:150%;
  color:#FFFFFF;
  margin-top:10%;
  width:340px;
  font-family: Arial, Helvetica, sans-serif;
  height: 200px;
  display: block;
}
p{
  color: #FFFFFF;
  font-size:175%;
  width: 360px;
  font-size:200%;
  text-align:center;
  font-family: Arial, Helvetica, sans-serif;
}
::-webkit-input-placeholder {
  color:    #DDDDDD;
}
</style>
<body onload="setBack()">
  <center>
    <div class="inner" id="inner">
      <?php
      if(isset($_POST['key'])){
        $key=$_POST['key'];
        if(isset($_POST['save'])){
          $file = "./settings/key.json";
          $fp = fopen($file, 'r');
          $readJson=json_decode(fread($fp,filesize($file)));
          fclose($fp);
          if($readJson->key==$key){
          $file = "./settings/batteries.json";
          $fp = fopen($file, 'r');
          $readJson=json_decode(fread($fp,filesize($file)));
          fclose($fp);
          $readJson->battery1=$_POST['battery1'];
          $readJson->battery2=$_POST['battery2'];
          $readJson->battery3=$_POST['battery3'];
          $readJson->battery4=$_POST['battery4'];
          $readJson->battery5=$_POST['battery5'];

          $fp = fopen($file, 'w');
          fwrite($fp, json_encode($readJson));
          fclose($fp);
        }
        }
        $file = "./settings/key.json";
        $fp = fopen($file, 'r');
        $readJson=json_decode(fread($fp,filesize($file)));
        fclose($fp);

        $file = "./settings/batteries.json";
        $fp = fopen($file, 'r');
        $readJson1=json_decode(fread($fp,filesize($file)));
        fclose($fp);
        $b1s=$readJson1->battery1;
        $b2s=$readJson1->battery2;
        $b3s=$readJson1->battery3;
        $b4s=$readJson1->battery4;
        $b5s=$readJson1->battery5;
        if($b1s=="charged"){
          $b1a="selected";
          $b1b="";
        }else{
          $b1a="";
          $b1b="selected";
        }
        if($b2s=="charged"){
          $b2a="selected";
          $b2b="";
        }else{
          $b2a="";
          $b2b="selected";
        }
        if($b3s=="charged"){
          $b3a="selected";
          $b3b="";
        }else{
          $b3a="";
          $b3b="selected";
        }
        if($b4s=="charged"){
          $b4a="selected";
          $b4b="";
        }else{
          $b4a="";
          $b4b="selected";
        }
        if($b5s=="charged"){
          $b5a="selected";
          $b5b="";
        }else{
          $b5a="";
          $b5b="selected";
        }
        if($readJson->key==$key){
            echo "
            <p>REV Battery</p>
            <form name=\"edit\" method=\"post\" id=\"battery\">
            <select name=\"battery1\">
            <option value=\"charged\" $b1a>Charged</option>
            <option value=\"discharged\" $b1b>Discharged</option>
            </select>
            <p>Modern Robotics 1</p>
            <select name=\"battery2\">
            <option value=\"charged\" $b2a>Charged</option>
            <option value=\"discharged\" $b2b>Discharged</option>
            </select>
            <p>Modern Robotics 2</p>
            <select name=\"battery3\">
            <option value=\"charged\" $b3a>Charged</option>
            <option value=\"discharged\" $b3b>Discharged</option>
            </select>
            <p>Phone 1 (DS)</p>
            <select name=\"battery4\">
            <option value=\"charged\" $b4a>Charged</option>
            <option value=\"discharged\" $b4b>Discharged</option>
            </select>
            <p>Phone 2 (RC)</p>
            <select name=\"battery5\">
            <option value=\"charged\" $b5a>Charged</option>
            <option value=\"discharged\" $b5b>Discharged</option>
            </select>
            <br/>
            <input class=\"buttonnew\" type=\"submit\" formaction=\"\" value=\"Update Status\" ></input>
            <input type=\"hidden\" name=\"key\" value=\"$key\"></input>
            <input type=\"hidden\" name=\"save\"></input>
            </form>
            ";
            }else{
          redraw();
            }
      }else{
        redraw();
      }
      function redraw(){
        echo "<p id=\"text\" onload=\"textSetup()\">Battery Status V1</p>
        <form name=\"login\" method=\"post\">
        <input class=\"input\" id=\"password\" type=\"password\" name=\"key\" maxlength=\"16\" minlength=\"6\" placeholder=\"Password\" required><br>
        <input class=\"buttonnew\" type=\"submit\" value=\"Login\" formaction=\"\"><br>
          </form>";
        }
        ?>
      </div>
    </center>
  </body>
  <script type="text/javascript">
  function isMobile() {
    var check = false;
    (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
  }
  function textSetup(){
    var text = document.getElementById("text");
    if(isMobile()){
      text.style="font-size:300%;width: 90%;height: 15%;";
    }else{
      text.style="font-size:400%;width: 75%;";
    }
  }
  function setBack() {
    document.body.style.background = "#184691";
    document.body.style.backgroundSize = "cover";
  }
  function forceUpper(strInput){
    strInput.value=strInput.value.toUpperCase();
  }
  </script>
  </html>
