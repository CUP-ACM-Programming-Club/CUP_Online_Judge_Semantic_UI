<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
$oj_signal = "UVA";
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
require_once('./include/setlang.php');
require_once("./include/simple_html_dom.php");
$is_vjudge=true;
$ok = true;
$user = $_SESSION['user_id'];
$ok = $ok || isset($_SESSION['administrator']);
$view_src = "";
$sid = "";

if (!isset($_SESSION['user_id'])) {
    $view_errors = "<a href=newloginpage.php>$MSG_Login</a>";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
//	$_SESSION['user_id']="Guest";
}
$pid = "";
$row;
$tid = "";
$cnt = "";
$cid = "";
$lastlang=0;
$pr_flag=true;
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
        $result = $database->select("vjudge_solution", ["user_id","language"], [
            "solution_id" => $sid
        ]);
        $user_name = $result[0]["user_id"];
        $lastlang=$result[0]["language"];
        $lastlang=intval($lastlang);
        $ok = $ok||$user_name == $_SESSION['user_id'];
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
$cnt=$database->count("vjudge_problem",["problem_id"=>$pid,"source"=>"UVA"]);
if($cnt==0)
{
    $view_errors = "No such Problem.";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
}

/////////////////////////Template
require("./template/" . $OJ_TEMPLATE . "/uvasubmitpage.php");
/////////////////////////Common foot
if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>

