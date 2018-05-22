<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

////////////////////////////Common head
$cache_time = 2;
$OJ_CACHE_SHARE = false;
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once("./include/const.inc.php");
$sid = "";
if (isset($_GET['sid'])) {
    $sid = intval($_GET['sid']);
}
else if(isset($_GET['solution_id']))
{
    $sid=intval($_GET['solution_id']);
}
else {
    echo "error";
    exit(0);
}
$solution_id = 0;
// check the top arg
$result = $database->select("vjudge_solution", "*", [
    "solution_id" => $sid
]);
echo $result[0]['result'] . "," . $result[0]['memory'] . "," . $result[0]['time'].",".$result[0]['runner_id'];
?>

