<?php	$cache_time=10;
	$OJ_CACHE_SHARE=false;
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	require_once('./closed.php');
	require_once('./include/cache_start.php');
	$view_title= "Welcome To Online Judge";
	if (!isset($_SESSION['user_id'])){
		$view_errors= "<a href=./loginpage.php>$MSG_Login</a>";
		
		require("template/".$OJ_TEMPLATE."/error.php");
		exit(0);
	}


$result = $database->select("users","*",["user_id"=>$_SESSION["user_id"]]);
$row = $result[0];

$result=$database->select("users_account","*",["user_id"=>$_SESSION['user_id']]);
$hdu="";
$poj="";
$uva="";
$vj="";
if(count($result)!=0)
{
    $hdu=$result[0]["hdu"];
    $poj=$result[0]["poj"];
    $uva=$result[0]["uva"];
    $vj=$result[0]["vjudge"];
}
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/modifypage.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>

