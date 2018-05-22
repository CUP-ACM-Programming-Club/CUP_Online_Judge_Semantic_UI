<?php
//$cache_time=1;
//require_once('./include/cache_start.php');
    require_once("./include/db_info.inc.php");
	require_once("./include/setlang.php");
	$view_title= "LOGIN";

	if (isset($_SESSION['user_id'])){
	echo "<script>location.href=\"http://".$_SERVER['HTTP_HOST']."\"</script>";
	echo "<a href=logout.php>Please logout First!</a>";
	exit(1);
}

/////////////////////////Template
if(!file_exists("template/" . $OJ_TEMPLATE . "/newloginpage.php")) {
    if (file_exists("template/" . $OJ_TEMPLATE . "/loginpage.php"))
        require("template/" . $OJ_TEMPLATE . "/loginpage.php");
}
else {
    require("template/" . $OJ_TEMPLATE . "/newloginpage.php");
}
/////////////////////////Common foot
//if(file_exists('./include/cache_end.php'))
//	require_once('./include/cache_end.php');
?>

