<?php
require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
if(isset($_SESSION['administrator'])){
	$view_title= "Welcome To Online Judge";
require("template/".$OJ_TEMPLATE."/announce.php");
}
else
{
    $view_errors="Permission denied.";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
}
?>