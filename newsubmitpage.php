<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
require_once('./include/setlang.php');
$now = strftime("%Y-%m-%d %H:%M", time());
$nodejsmode=isset($_GET['js'])&&$OJ_TEMPLATE=="semantic-ui";
$nodejsmode = $OJ_TEMPLATE == "semantic-ui";
if (isset($_GET['cid'])) $ucid = "&cid=" . intval($_GET['cid']);
else $ucid = "";
if (isset($OJ_LANG)) {
    require_once("./lang/$OJ_LANG.php");
}
if (!isset($_SESSION['user_id'])) {

    $view_errors = "<a href=newloginpage.php>$MSG_Login</a>";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
//	$_SESSION['user_id']="Guest";
}

$pr_flag = false;
$co_flag = false;
$condition = [];
$target = [];
$table_name = "";
$id="";
$iscmode=true;
if($nodejsmode)
{
    if (isset($_COOKIE['lastlang']))
        $lastlang = intval($_COOKIE['lastlang']);
    else
        $lastlang = 0;
    if(isset($_GET['id']))
    {
        $id=intval($_GET['id']);
    }
    $pr_flag=true;
    $row=null;
    require("template/" . $OJ_TEMPLATE . "/newsubmitpage.php");
    exit(0);
}
if (isset($_GET['id'])) {
    // practice
    $id = intval($_GET['id']);
    //require("oj-header.php");
    if (!isset($_SESSION['administrator']) && $id != 1000 && !isset($_SESSION['contest_creator'])) {
        $sql = "SELECT * FROM `problem` WHERE `problem_id`=$id AND `defunct`='N' AND `problem_id` NOT IN (
                                SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN(
                                                SELECT `contest_id` FROM `contest` WHERE `end_time`>'$now' or `private`='1'))
                                ";
        $table_name = "problem";
        $condition["problem_id"] = $id;
        if (!isset($_SESSION['administrator']))
            $condition["defunct"] = "N";
        $condition["problem_id[!]"] = $database->select("contest_problem", "problem_id", [
            "contest_id" => $database->select("contest", "contest_id", [
                "OR" => [
                    "end_time[>]" => $now,
                    "private" => "1"
                ]
            ])
        ]);
    } else {
        $sql = "SELECT * FROM `problem` WHERE `problem_id`=$id";
        $table_name = "problem";
        $target = "*";
        $condition["problem_id"] = $id;
    }
    $pr_flag = true;
} else if (isset($_GET['cid']) && isset($_GET['pid'])) {
    // contest
    $cid = intval($_GET['cid']);
    $pid = intval($_GET['pid']);
    if (!isset($_SESSION['administrator'])) {
        $sql = "SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=$cid AND `start_time`<='$now'";
        $target = ["langmask", "private", "defunct", "vjudge","cmod_visible"];
        $table_name = "contest";
        $condition["defunct"] = "N";
        $condition["contest_id"] = $cid;
        $condition["start_time[<=]"] = $now;
    } else {
        $sql = "SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=$cid";
        $target = "*";
        $table_name = "contest";
        $condition["contest_id"] = $cid;
        //$target = ["langmask", "private", "defunct", "vjudge"];
    }
    if (isset($_GET['debug'])) {
        $result = $database->select($table_name, $target, $condition);
        echo var_dump($result);
        exit(0);
    }
    $result = $database->select($table_name, $target, $condition);
    //echo var_dump($result);
    $iscmode=$result[0]["cmod_visible"]=="1";
    if(isset($_SESSION['administrator']))$iscmode=0;
    if($OJ_CONTEST_MODE&&!$iscmode)
    {
        require_once('./closed.php');
    }
    //$result=mysqli_query($mysqli,$sql);
    $rows_cnt = count($result);
    $row = $result[0];
    $contest_ok = true;
    if ($row[1] && !isset($_SESSION['c' . $cid])) $contest_ok = false;
    if ($row[2] == 'Y') $contest_ok = false;
    if ($row[3] == '1') $vjudge = true;
    if (isset($_SESSION['administrator'])) $contest_ok = true;
    $ok_cnt = $rows_cnt == 1;
    $langmask = $row['langmask'];
    //mysqli_free_result($result);
    if ($ok_cnt != 1) {
        // not started
        $view_errors = "No such Contest!";

        require("template/" . $OJ_TEMPLATE . "/error.php");
        exit(0);
    } else {
        // started
        //$sql="SELECT * FROM `problem` WHERE `defunct`='N' AND `problem_id`=(
        $sql = "SELECT * FROM `problem` WHERE `problem_id`=(
                        SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=$cid AND `num`=$pid
                        )";
        $target = "*";
        $table_name = "problem";
        $condition = [];
        $condition["defunct"] = "N";
        $condition["problem_id"] = $database->select("contest_problem", "problem_id", ["contest_id" => $cid, "num" => $pid]);
        $id = $database->select("contest_problem", "problem_id", ["contest_id" => $cid, "num" => $pid]);
        $id = $id[0];
    }
    // public
    if (!$contest_ok) {

        $view_errors = "Not Invited!";
        require("template/" . $OJ_TEMPLATE . "/error.php");
        exit(0);
    }
    $co_flag = true;
} else if (isset($_GET['tid']) && isset($_GET['pid'])) {
    //special_subject
    $tid = intval($_GET['tid']);
    $pid = intval($_GET['pid']);
    $sql = "SELECT langmask,private,defunct FROM `special_subject` WHERE `defunct`='N' AND `topic_id`=$tid";
    $target = ["langmask", "private", "defunct"];
    $table_name = "special_subject";
    $condition = [];
    $condition["defunct"] = "N";
    $condition["topic_id"] = $tid;
    if (isset($_GET['debug'])) {
        $result = $database->select($table_name, $target, $condition);
        echo var_dump($result);
    }
    $result = $database->select($table_name, $target, $condition);
    $rows_cnt = count($result);
    /*
$result=mysqli_query($mysqli,$sql);
$rows_cnt=mysqli_num_rows($result);
$row=mysqli_fetch_row($result);
*/
    $contest_ok = true;
    // if ($row[1] && !isset($_SESSION['s'.$sid])) $contest_ok=false;
    //if ($row[2]=='Y') $contest_ok=false;
    //  if (isset($_SESSION['administrator'])) $contest_ok=true;
    $ok_cnt = $rows_cnt == 1;
//    $langmask=$row[0];
    $langmask = $result[0]['langmask'];
    //mysqli_free_result($result);
    if ($ok_cnt != 1) {
        // not started
        $view_errors = "No such Contest!";

        require("template/" . $OJ_TEMPLATE . "/error.php");
        exit(0);
    } else {
        // started
        $id = $database->select("special_subject_problem", "problem_id", ["topic_id" => $tid, "num" => $pid]);
        $id = $id[0];
        $sql = "SELECT * FROM `problem` WHERE `defunct`='N' AND `problem_id`=$id";
    }
    // public
    if (!$contest_ok) {

        $view_errors = "Not Invited!";
        require("template/" . $OJ_TEMPLATE . "/error.php");
        exit(0);
    }
    $co_flag = true;
} else {
    $view_errors = "<title>$MSG_NO_SUCH_PROBLEM</title><h2>$MSG_NO_SUCH_PROBLEM</h2>";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
}

$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
if (mysqli_num_rows($result) != 1) {
    $view_errors = "";
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        mysqli_free_result($result);
        $sql = "SELECT  contest.`contest_id` , contest.`title`,contest_problem.num FROM `contest_problem`,`contest` WHERE contest.contest_id=contest_problem.contest_id and `problem_id`=$id and defunct='N'  ORDER BY `num`";
        //echo $sql;
        $result = mysqli_query($mysqli, $sql);
        if ($i = mysqli_num_rows($result)) {
            $view_errors .= "This problem is in Contest(s) below:<br>";
            for (; $i > 0; $i--) {
                $row = mysqli_fetch_row($result);
                $view_errors .= "<a href=newsubmitpage.php?cid=$row[0]&pid=$row[2]>Contest $row[0]:$row[1]</a><br>";

            }
        } else {
            $view_title = "<title>$MSG_NO_SUCH_PROBLEM!</title>";
            $view_errors .= "<h2>$MSG_NO_SUCH_PROBLEM!</h2>";
        }
    } else {
        $view_title = "<title>$MSG_NO_SUCH_PROBLEM!</title>";
        $view_errors .= "<h2>$MSG_NO_SUCH_PROBLEM!</h2>";
    }
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
} else {
    $row = mysqli_fetch_object($result);
    $view_title = $row->title;

}
$view_src = "";
if (isset($_GET['sid'])) {
    $sid = intval($_GET['sid']);
    $sql = "SELECT * FROM `solution` WHERE `solution_id`=" . $sid;
    $result = mysqli_query($mysqli, $sql);
    $trow = mysqli_fetch_object($result);
    if ($trow && $trow->user_id == $_SESSION['user_id']) $ok = true;
    if ($trow) {
        $lastlang = $trow->language;
    }
    if (isset($_SESSION['source_browser']) || isset($_SESSION['administrator'])) $ok = true;
    mysqli_free_result($result);
    if ($ok == true) {
        $sql = "SELECT `source` FROM `source_code_user` WHERE `solution_id`='" . $sid . "'";
        $result = mysqli_query($mysqli, $sql);
        $trow = mysqli_fetch_object($result);
        if ($trow)
            $view_src = $trow->source;
        mysqli_free_result($result);
    }

}
if (isset($id_sql)) {
    $result = mysqli_query($mysqli, $id_sql);
    $row = mysqli_fetch_array($result);
    $id = intval($row['problem_id']);
}
$problem_id = "";
$problem_id = $id;
$view_sample_input = "1 2";
$view_sample_output = "3";
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sample_sql = "select sample_input,sample_output,problem_id from problem where problem_id=$id";
} else if (isset($_GET['cid']) && isset($_GET['pid'])) {
    $cid = intval($_GET['cid']);
    $pid = intval($_GET['pid']);
    if (!is_numeric(intval($id)))
        $id = "";
    $sample_sql = "select sample_input,sample_output,problem_id from problem where problem_id in (select problem_id from contest_problem where contest_id=$cid and num=$pid)";
    $id_sql = "select problem_id from contest_problem where contest_id=$cid and num=$pid";

} else if (isset($_GET['tid']) && isset($_GET['pid'])) {
    $tid = intval($_GET['tid']);
    $pid = intval($_GET['pid']);
    if (!is_numeric(intval($id)))
        $id = "";
    $sample_sql = "select sample_input,sample_output,problem_id from problem where problem_id in (select problem_id from special_subject_problem where topic_id=$tid and num=$pid)";
    $id_sql = "select problem_id from special_subject_problem where topic_id=$tid and num=$pid";
} else {
    $view_errors = "<h2>No Such Problem!</h2>";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
}
if (isset($sample_sql)) {
    //echo $sample_sql;
    $result = mysqli_query($mysqli, $sample_sql);
    $srow = mysqli_fetch_array($result);
    $view_sample_input = $srow[0];
    $view_sample_output = $srow[1];
    $problem_id = $srow[2];
    mysqli_free_result($result);
}

if (!$view_src) {
    if (isset($_COOKIE['lastlang']))
        $lastlang = intval($_COOKIE['lastlang']);
    else
        $lastlang = 0;
    $template_file = "$OJ_DATA/$problem_id/template." . $language_ext[$lastlang];
    if (file_exists($template_file)) {
        $view_src = file_get_contents($template_file);
    }
}
if (!isset($lastlang)) $lastlang = 0;
if ($langmask & pow(2, $lastlang)) {
    $tmp = ~$langmask & (-(~$langmask));
    //echo $tmp;
    if ($tmp)
        $lastlang = log10(~$langmask & (-(~$langmask))) / log10(2);
    else
        $lastlang = $tmp;
    //echo $lastlang;
}
mysqli_free_result($result);
if($OJ_TEMPLATE=="semantic-ui")
{
    $row->time_limit.=" 秒";
    $row->memory_limit.=" MB";
}
/////////////////////////Template
require("template/" . $OJ_TEMPLATE . "/newsubmitpage.php");
/////////////////////////Common foot
if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>

