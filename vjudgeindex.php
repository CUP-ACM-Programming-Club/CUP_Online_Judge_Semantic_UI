<?php
////////////////////////////Common head
	$cache_time=10;
	$OJ_CACHE_SHARE=false;
	$HOMEPAGE=true;
	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	require_once('./closed.php');
	$view_title= "Welcome To Online Judge";
	
///////////////////////////MAIN	
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/vjudgeindex.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>
