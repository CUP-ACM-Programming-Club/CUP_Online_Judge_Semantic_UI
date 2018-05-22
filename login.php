<?php
require_once("./include/db_info.inc.php");
$vcode = "";
$json_msg = $_POST['msg'];
//echo $json_msg;
$json_msg = base64_decode($json_msg);
$json_msg = base64_decode($json_msg);
//echo $json_msg;
$decode_json = json_decode($json_msg, true);
if ($OJ_VCODE) $vcode = $decode_json["vcode"];
if ($OJ_VCODE && ($vcode != $_SESSION["vcode"] || $vcode == "" || $vcode == null)) {
    echo $_SESSION["vcode"];
    echo "vcode false";
    exit(0);
}
require_once("./include/login-" . $OJ_LOGIN_MOD . ".php");

//$user_id=$_POST['user_id'];
$user_id = $decode_json['user_id'];
$password = $decode_json['password'];
//$password=$_POST['password'];
if (get_magic_quotes_gpc()) {
    $user_id = stripslashes($user_id);
    $password = stripslashes($password);
}
//if (checknosql($user_id)) {
 //   $sql = "SELECT `rightstr` FROM `privilege` WHERE `user_id`='" . mysqli_real_escape_string($mysqli, $user_id) . "'";
//}
$login = check_login($user_id, $password);
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
while($redis->lSize($user_id)>0)
{
    $redis->rPop($user_id);
}
if ($login) {
    $_SESSION['user_id'] = $login;
  //  $result=[];
    $nick=$database->select("users","nick",["user_id"=>$login]);
    $nick=$nick[0];
    $_SESSION['nick']=$nick;
    $result=$database->select("privilege","rightstr",["user_id"=>$user_id]);
    //$result = mysqli_query($mysqli, $sql);
    // echo var_dump($result);
  //  $mem = new Memcache;
  //  $mem->connect($OJ_MEMSERVER,$OJ_MEMPORT);
    foreach($result as $row)
    {
        $_SESSION[$row]=true;
        $redis->lPush($user_id,$row);
        //$result[]=$row;
       // $mem->set($user_id.$row,1,0,)
    }
    //$redis->set($user_id,$result);
   // while ($result && $row = mysqli_fetch_assoc($result))
     //   $_SESSION[$row['rightstr']] = true;
    // echo var_dump($_SESSION);
  //   $redis = new Redis();
//$redis->connect('127.0.0.1', 6379);
//echo '通过php用redis获取>>>>>>>'.$redis->get('PHPREDIS_SESSION:' . session_id());
    if (isset($_POST['msg'])) {
        echo "true";
    } else {
        echo "<script language='javascript'>\n";
        echo "history.go(-2);\n";
        echo "</script>";
    }
} else {
    if (isset($_POST['msg'])) {
        echo "false";
    } else {
        echo "<script language='javascript'>\n";
        echo "alert('UserName or Password Wrong!');\n";
        echo "alert('UserName:" . $user_id . ",Password:" . $password . "');\n";
        echo "history.go(-1);\n";
        echo "</script>";
    }
}
?>
