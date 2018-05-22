<?php
$cache_time=10;
	$OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	$view_title= "Welcome To Online Judge";
require_once("./include/login-".$OJ_LOGIN_MOD.".php");
	require_once("./include/my_func.inc.php");
$err_str="";
$err_cnt=0;
$len;
$user_id=$_POST['user_id'];
$answer=$_POST['confirmanswer'];
//echo $user_id;
//$sql="SELECT `user_id`,`password`,`confirmquestion`,`confirmanswer` FROM `users` WHERE `user_id`='".$user_id."'";
//$result=mysqli_query($mysqli,$sql);
$result=$database->select("users","*",["user_id"=>$user_id]);
$result=$result[0];
$vcode=$_POST['vcode'];
//echo var_dump($result);
$row=$result;
//$row=mysqli_fetch_array($result);
if(checknosql($user_id))
{
if(strlen($answer)){
if($row&&pwCheck($answer,$row['confirmanswer'])&&$vcode==$_SESSION['vcode'])
{
mysqli_free_result($result);
$password=pwGen("12345678");
if(isset($_POST['newpass']))
{
    $password=pwGen($_POST['newpass']);
}
$sql="UPDATE `users` SET"
."`password`='".($password)."' WHERE `user_id`='".mysqli_real_escape_string($mysqli,$user_id)."'";
//echo $sql;
//exit(0);
mysqli_query($mysqli,$sql);// or die("Insert Error!\n");
//header("Location: ./");
echo "success!";
}
else
{
    echo "false";
}
}
else{
    if(strlen($row['confirmquestion'])){
    echo $row['confirmquestion'];
    }
    else
    {
        //echo var_dump($row);
        echo "false";
    }
}
}
else
{
    echo "illegal";
}
?>