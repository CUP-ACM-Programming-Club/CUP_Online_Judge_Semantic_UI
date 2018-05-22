
<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
$oj_signal = "BZOJ";
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
require_once('./include/setlang.php');
require_once("./include/simple_html_dom.php");
$ok = true;
$user = $_SESSION['user_id'];
$ok = $ok || isset($_SESSION['administrator']);
$view_src = "";
$sid = "";
$pr_flag=true;
function cchin($txt)
{
    $txt = mb_convert_encoding($txt, "UTF-8", "GBK");
    return $txt;
}

if (!isset($_SESSION['user_id'])) {
    $view_errors = "<a href=newloginpage.php>$MSG_Login</a>";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
//	$_SESSION['user_id']="Guest";
}



$pid = "";
$cpid="";
$row;
$tid = "";
$cnt = "";
$cid="";
if(isset($_GET['cid']))
{
    $cid=intval($_GET['cid']);
    $pid=intval($_GET['pid']);
    if(is_numeric($cid)&&$cid)
    {
        $result=$database->select("contest_problem","problem_id",["contest_id"=>$cid,"num"=>$pid]);
        if(count($result)==0)
        {
            $view_errors="发生错误.";
            print_r($result);
            require("template/" . $OJ_TEMPLATE . "/error.php");
            exit(0);
        }
        $cpid=$pid;
        $pid=$result[0];
    }
    $pr_flag=false;
}
if (isset($_GET['tid'])) {
    $tid = intval($_GET['tid']);
    $cnt = intval($_GET['pid']);
    if (is_numeric($tid) && $tid) {
        $result = $database->select("special_subject_problem", "problem_id", [
            "topic_id" => $tid,
            "num" => $cnt
        ]);
        $pid = $result[0];
    }
} else if (isset($_GET['pid'])&&!isset($_GET['cid'])) {
    $pid = intval($_GET['pid']);
}
else if(isset($_GET['id'])&&!isset($_GET['cid']))
{
    $pid=intval($_GET['id']);
}
if (isset($_GET['sid'])) {
    $sid = intval($_GET['sid']);

    if ($sid > -1) {
        $user_name = $database->select("vjudge_solution", "user_id", [
            "solution_id" => $sid
        ]);
        $user_name = $user_name[0];
        $ok = $ok || ($user_name == $_SESSION['user_id']);
        $result = $database->select("vjudge_source_code", "source", [
            "solution_id" => $sid
        ]);
        $view_src = $result[0];
    }
}
if (!$ok) {
    $view_errors = "Permission Denied.";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
}
$url = "http://acm.hdu.edu.cn/showproblem.php?pid=" . $pid;
if (!$url) $url = $_GET['url'];
if (strpos($url, "http") === false) {
    echo "Please Input like http://acm.split.hdu.edu.cn/showproblem.php?pid=1000";
    exit(1);
}
if (get_magic_quotes_gpc()) {
    $url = stripslashes($url);
}
$result = $database->select("vjudge_problem", "*", [
    "problem_id" => $pid,
    "source" => "BZOJ"
]);
if (!$result) {
   $view_errors = "题目不存在";
            require("template/" . $OJ_TEMPLATE . "/error.php");
            exit(0);
} else {
    $row->problem_id = $result[0]['problem_id'];
    $row->title = $result[0]['title'];
    $row->time_limit = $result[0]['time_limit'];
    $row->memory_limit = $result[0]['memory_limit'];
    $row->description = $result[0]['description'];
    $row->input = $result[0]['input'];
    $row->output = $result[0]['output'];
    $row->sample_input = $result[0]['sample_input'];
    $row->sample_output = $result[0]['sample_output'];
    $row->accepted = $result[0]['accepted'];
    $row->submit = $result[0]['submit'];
    $row->source = $result[0]['source'];
    $view_sample_input = $result[0]['sample_input'];
    $view_sample_output = $result[0]['sample_output'];
}
/////////////////////////Template
require("./template/" . $OJ_TEMPLATE . "/vjproblem.php");
/////////////////////////Common foot
if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>

