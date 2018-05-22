<?php
$cache_time=30;
$OJ_CACHE_SHARE=false;
//require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once("./include/db_info.inc.php");
if(isset($OJ_LANG)){
	require_once("./lang/$OJ_LANG.php");
}
if(!isset($_SESSION["administrator"]) && !isset($_SESSION["editor"]))
{
    header('Location: error.php');
}
require_once("./template/".$OJ_TEMPLATE."/problem_edit.php");
if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>