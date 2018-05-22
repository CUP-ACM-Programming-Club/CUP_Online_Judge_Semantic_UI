<?php
require_once('./include/db_info.inc.php');
$result=$database->select("users","nick",["user_id"=>$_SESSION['user_id']]);
$user_nick=$result[0];
/*
$tsql="SELECT nick FROM users WHERE user_id='".$_SESSION['user_id']."'";
$ttresult=mysqli_query($mysqli,$tsql);
$ttrow=mysqli_fetch_row($ttresult);
$user_nick=$ttrow[0];
*/
?>