<?php
        $OJ_CACHE_SHARE=true;
        $cache_time=1;
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once('./closed.php');
    require_once('./include/cache_start.php');
        $view_title= $MSG_RANKLIST;
 if (!isset($_SESSION['user_id'])){

	$view_errors= "<a href=newloginpage.php>$MSG_Login</a>";
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
//	$_SESSION['user_id']="Guest";
}
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/vjudgeranklist.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
        require_once('./include/cache_end.php');
?>
