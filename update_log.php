<?php
require_once('./include/db_info.inc.php');
require_once('./include/my_func.inc.php');
require_once('./include/const.inc.php');
require_once('./include/setlang.php');
$result=$database->select("maintain_info","*",["ORDER"=>["mtime"=>"DESC"]]);
$content="";
foreach($result as $row)
{
    $content.="<h3 class='header'>".$row['mtime']."</h3><div class='ui segment'>".$row['msg']."</div>";
}
require("template/$OJ_TEMPLATE/update_log.php");
?>