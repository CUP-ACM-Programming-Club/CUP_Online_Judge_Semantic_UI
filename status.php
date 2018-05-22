<?php
//header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
//header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

////////////////////////////Common head
$cache_time = 2;
$OJ_CACHE_SHARE = false;
require_once('./include/cache_start.php');
require_once('./include/setlang.php');
require_once('./include/db_info.inc.php');

$view_title = "$MSG_STATUS";
function cmp($a, $b)
{
    if ($a['in_date'] == $b['in_date']) {
        return intval($a['solution_id']) < intval($b['solution_id']);
        // return 0;
    }
    return ($a['in_date'] < $b['in_date']) ? 1 : -1;
}

require_once("./include/my_func.inc.php");
if (isset($OJ_LANG)) {
    require_once("./lang/$OJ_LANG.php");
}
require_once("./include/const.inc.php");
if (!isset($_SESSION['user_id'])) {

    $view_errors = "<a href=newloginpage.php>$MSG_Login</a>";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
//	$_SESSION['user_id']="Guest";
}

if ($OJ_TEMPLATE == "flat-ui")
    $judge_color = Array("btn btn-default", "btn btn-info", "btn btn-warning", "btn btn-warning", "btn btn-success", "btn btn-danger", "btn btn-danger", "btn btn-warning", "btn btn-warning", "btn btn-warning", "btn btn-warning", "btn btn-warning", "btn btn-warning", "btn btn-info");
else {
    $judge_color = ["waiting", "running", "compiling", "running", "accepted", "wrong_answer", "wrong_answer", "time_limit_exceeded", "memory_limit_exceeded", "output_limit_exceeded", "runtime_error", "compile_error", "running", "running", "running"];
    $iconlist = ["hourglass half", "spinner", "spinner", "spinner", "checkmark", "minus", "remove", "clock", "microchip", "print", "bomb", "code", "spinner", "spinner", "spinner"];
}

$str2 = "";
$lock = false;
$lock_time = date("Y-m-d H:i:s", time());
//$sql="SELECT * FROM `solution` WHERE problem_id>0 ";
$condition = [];
if (!(isset($_SESSION['administrator']) || isset($_SESSION['source_browser']))) {
    $condition["problem_id[>]"] = "0";
    $condition["contest_id"] = null;
}
if (isset($_GET['cid'])) {

    $cid = intval($_GET['cid']);
    $result = $database->select("contest", "*", ["contest_id" => $cid, "cmod_visible" => "1"]);
    $row = $result[0];
    if (count($result) == 0 || ($row['private'] && !isset($_SESSION['c' . $cid]))) {
        require_once('./closed.php');
    }
    $condition["contest_id"] = "$cid";
    $condition["num[>=]"] = 0;
    //$sql=$sql." AND `contest_id`='$cid' and num>=0 ";
    $str2 = $str2 . "&cid=$cid";
    //$sql_lock="SELECT `start_time`,`title`,`end_time` FROM `contest` WHERE `contest_id`='$cid'";
    //    $result=mysqli_query($mysqli,$sql_lock) or die(mysqli_error($mysqli));
    // $rows_cnt=mysqli_num_rows($result);
    $start_time = 0;
    $end_time = 0;
    if ($rows_cnt > 0) {
        $row = $result[0];
        $start_time = strtotime($row[0]);
        $title = $row[1];
        $end_time = strtotime($row[2]);
    }
    $lock_time = $end_time - ($end_time - $start_time) * $OJ_RANK_LOCK_PERCENT;
    //$lock_time=date("Y-m-d H:i:s",$lock_time);
    $time_sql = "";
    //echo $lock.'-'.date("Y-m-d H:i:s",$lock);
    if (time() > $lock_time && time() < $end_time) {
        //$lock_time=date("Y-m-d H:i:s",$lock_time);
        //echo $time_sql;
        $lock = true;
    } else {
        $lock = false;
    }

    //require_once("contest-header.php");
} else if (isset($_GET['tid'])) {
    require_once('./closed.php');
    $tid = intval($_GET['tid']);
    //$sql=$sql." AND `topic_id`='$tid' and num>=0 ";
    $str2 = $str2 . "&tid=$tid";
} else {
    require_once('./closed.php');
    //require_once("oj-header.php");
    if (isset($_SESSION['administrator'])
        || isset($_SESSION['source_browser'])
        || (isset($_SESSION['user_id'])
            && (isset($_GET['user_id']) && $_GET['user_id'] == $_SESSION['user_id']))
    ) {
        if ($_SESSION['user_id'] != "guest") {
            //	$sql="SELECT * FROM `solution` WHERE contest_id is null ";
            //$condition["contest_id"] = null;
            if (!(isset($_SESSION['administrator']) || isset($_SESSION['source_browser']))) {
                $condition["problem_id[>]"] = 0;

            }
        }
    } else {
        //$sql="SELECT * FROM `solution` WHERE problem_id>0 and contest_id is null ";

        //$condition["contest_id"] = null;
    }
}

$start_first = true;
$order_str = " ORDER BY `solution_id` DESC ";


// check the top arg
if (isset($_GET['top'])) {
    $top = strval(intval($_GET['top']));
    if ($top != -1) {
        // $sql=$sql."AND `solution_id`<='".$top."' ";
        $condition["solution_id[<=]"] = "$top";
    }
}

// check the problem arg
$problem_id = "";
if (isset($_GET['problem_id']) && $_GET['problem_id'] != "") {

    if (isset($_GET['cid'])) {
        $problem_id = htmlentities($_GET['problem_id'], ENT_QUOTES, 'UTF-8');
        if (strlen($problem_id) != 1) $problem_id = "";
        if (isset($_GET['debug'])) {
            echo $problem_id . "<br>";
        }
        $num = strpos($PID, $problem_id);
        //	$sql=$sql."AND `num`='".$num."' ";
        $condition["num"] = "$num";
        $str2 = $str2 . "&problem_id=" . $problem_id;

    } else {
        $problem_id = strval(intval($_GET['problem_id']));
        if ($problem_id != '0') {
            //         $sql=$sql."AND `problem_id`='".$problem_id."' ";
            $condition["problem_id"] = "$problem_id";
            $str2 = $str2 . "&problem_id=" . $problem_id;
        } else $problem_id = "";
    }
}
// check the user_id arg
$user_id = "";
if (isset($_GET['user_id'])) {
    $user_id = trim($_GET['user_id']);
    if (is_valid_user_name($user_id) && $user_id != "") {
        //    $sql=$sql."AND `user_id`='".$user_id."' ";
        $condition["user_id"] = $user_id;
        if ($str2 != "") $str2 = $str2 . "&";
        $str2 = $str2 . "user_id=" . $user_id;
    } else $user_id = "";
}
if (isset($_GET['language'])) $language = intval($_GET['language']);
else $language = -1;

if ($language > count($language_ext) || $language < 0) $language = -1;
if ($language != -1) {
    //  $sql=$sql."AND `language`='".strval($language)."' ";
    $condition["language"] = strval($language);
    $str2 = $str2 . "&language=" . $language;
}
if (isset($_GET['jresult'])) $result = intval($_GET['jresult']);
else $result = -1;

if ($result > 12 || $result < 0) $result = -1;
if ($result != -1 && !$lock) {
    //   $sql=$sql."AND `result`='".strval($result)."' ";
    $condition["result"] = strval($result);
    $str2 = $str2 . "&jresult=" . $result;
}


$left_join = [];
if ($OJ_SIM) {
    //  $old=$sql;
    $condition["ORDER"] = ["solution_id" => "DESC"];
    $condition["LIMIT"] = 1000;
    //  $sql="select * from ($sql order by solution_id desc limit 1000) solution left join `sim` on solution.solution_id=sim.s_id WHERE 1 ";
    $left_join["[>]sim"] = [
        "solution_id" => "s_id"
    ];
    /*if(isset($_GET['showsim'])&&intval($_GET['showsim'])>0){
            $showsim=intval($_GET['showsim']);
            $sql="select * from ($old ) solution
                 left join `sim` on solution.solution_id=sim.s_id WHERE result=4 and sim>=$showsim limit 1000";
                 $left_join["[>]sim"]=[
                     "result"=>4,
                     "sim[>=]"=>$showsim
                     ];
            $sql="SELECT * FROM ($sql) `solution`
                    left join(select solution_id old_s_id,user_id old_user_id from solution limit 1000) old
                    on old.old_s_id=sim_s_id WHERE  old_user_id!=user_id and sim_s_id!=solution_id ";
            $str2.="&showsim=$showsim";
    }*/
    //$sql=$sql.$order_str." LIMIT 20";
}
//echo var_dump($condition);

//$left_join["[>]users"]=[
//    "user_id"=>"user_id"
//    ];
$condition["LIMIT"] = 20;
//$sql=$sql.$order_str." LIMIT 20";
//echo $sql;
//echo var_dump($database->select("solution",$left_join,"*",$condition));

if (isset($_GET['debug'])) {
    echo var_dump($condition);
    echo var_dump($left_join);
    // echo $sql;
    exit(0);
}

if ($OJ_TEMPLATE != "semantic-ui" && !isset($_GET["cid"])) {
    $result = $database->select("solution", $left_join, "*", $condition);
    if (isset($_GET['cid'])) {
        $aresult = $database->select("vjudge_solution", $left_join, "*", $condition);
        foreach ($aresult as $v) {
            array_push($result, $v);
        }
        uasort($result, "cmp");
    }
//echo var_dump($database->select("vjudge_solution",$left_join,"*",$condition));
    $top = $bottom = -1;
    $cnt = 0;
    if ($start_first) {
        $row_start = 0;
        $row_add = 1;
    } else {
        $row_start = $rows_cnt - 1;
        $row_add = -1;
    }
    if (isset($_GET['solution_id'])) {
        $row = mysqli_fetch_array($result);
        echo $row['solution_id'];
        exit(0);
    }
    $view_status = Array();
    $last = 0;
    $i = 0;
    $name = [];
    foreach ($result as $row) {
        $nick = "";
        if ($name[$row['user_id']]) {
            $nick = $name[$row['user_id']];
        } else {
            $result = $database->select("users", "nick", ["user_id" => $row['user_id']]);
            $nick = $result[0];
            $name[$row['user_id']] = $nick;
        }
        //$row=mysqli_fetch_array($result);
        //$view_status[$i]=$row;
        //echo $row['solution_id']."\n";
        if ($i == 0 && $row['result'] < 4) $last = $row['solution_id'];


        if ($top == -1) $top = $row['solution_id'];
        $bottom = $row['solution_id'];
        $flag = (!is_running(intval($row['contest_id']))) ||
            isset($_SESSION['source_browser']) ||
            isset($_SESSION['administrator']) ||
            (isset($_SESSION['user_id']) && !strcmp($row['user_id'], $_SESSION['user_id']));

        $cnt = 1 - $cnt;
        $admin = isset($_SESSION['administrator']) || isset($_SESSION['source_browser']) || isset($_SESSION['contest_id']);
        $view_status[$i][0] = $row['solution_id'];

        if ($row['contest_id'] > 0) {
            $view_status[$i][1] = "<a href='contestrank.php?cid=" . $row['contest_id'] . "&user_id=" . $row['user_id'] . "#" . $row['user_id'] . "'>" . $row['user_id'] . "<br>" . $nick . "</a>";
        } else {
            $view_status[$i][1] = "<a href='userinfo.php?user=" . $row['user_id'] . "'>" . $row['user_id'] . "<br>" . $nick . "</a>";
        }

        if ($row['contest_id'] > 0) {
            $view_status[$i][2] = "<div class=center><a href='";
            if ($OJ_TEMPLATE != "flat-ui") {
                if (isset($row['runner_id'])) {
                    $view_status[$i][2] .= strtolower($row['oj_name']) . "submitpage";
                } else
                    $view_status[$i][2] .= "newsubmitpage";
            } else $view_status[$i][2] .= "problem";
            $view_status[$i][2] .= ".php?cid=" . $row['contest_id'] . "&pid=" . $row['num'] . "'>";
            if (isset($cid)) {
                $view_status[$i][2] .= $PID[$row['num']];
            } else {
                $view_status[$i][2] .= $row['problem_id'];
            }
            $view_status[$i][2] .= "</div></a>";
        } else {
            $view_status[$i][2] = "<div class=center><a href='";
            if ($OJ_TEMPLATE != "flat-ui") $view_status[$i][2] .= "newsubmitpage";
            else $view_status[$i][2] .= "problem";
            $view_status[$i][2] .= ".php?id=" . $row['problem_id'] . "'>" . $row['problem_id'] . "</a></div>";
        }


        $view_status[$i][3] = "";
        if (intval($row['result']) == 11 && (isset($_SESSION['source_browser']) || isset($_SESSION['administrator']))) {
            $view_status[$i][3] .= "<a href='ceinfo.php?sid=" . $row['solution_id'] . "' class='" . $judge_color[$row['result']] . "'  title='$MSG_Click_Detail'><i class='" . $iconlist[$row['result']] . " icon'></i>" . $MSG_Compile_Error . "</a>";
        } else if ((((intval($row['result']) == 5 || intval($row['result']) == 6) && $OJ_SHOW_DIFF) || $row['result'] == 10 || $row['result'] == 13) && ((isset($_SESSION['user_id']) && $row['user_id'] == $_SESSION['user_id'])) || (isset($_SESSION['source_browser']) || isset($_SESSION['administrator']))) {

            $view_status[$i][3] .= "<a href='reinfo.php?sid=" . $row['solution_id'] . "' class='" . $judge_color[$row['result']] . "' title='$MSG_Click_Detail'><i class='" . $iconlist[$row['result']] . " icon'></i>" . $judge_result[$row['result']] . "</a>";
            if ($OJ_SIM && $row['sim'] > 80) {
                $res = $database->select("solution", "user_id", [
                    "solution_id" => $row['sim_s_id']
                ]);
                $view_status[$i][3] .= "&nbsp;<br><a href=comparesource.php?left=" . $row['sim_s_id'] . "&right=" . $row['solution_id'] . "  class='btn btn-info'  target='_blank' title='" . $row['sim_s_id'] . "'>" . $res[0] . "(" . $row['sim'] . "%)</a>";
            }
        } else {
            if (!$lock || $lock_time > $row['in_date'] || $row['user_id'] == $_SESSION['user_id']) {
                if ($OJ_SIM && $row['sim'] > 80 && $row['sim_s_id'] != $row['s_id']) {
                    $view_status[$i][3] .= "<span class='" . $judge_color[$row['result']] . "'>*<i class='" . $iconlist[$row['result']] . " icon'></i>" . $judge_result[$row['result']] . "</span>";
                    $res = $database->select("solution", "user_id", [
                        "solution_id" => $row['sim_s_id']
                    ]);
                    if (isset($_SESSION['source_browser']) || isset($_SESSION['administrator'])) {

                        $view_status[$i][3] .= "&nbsp;<br><a href=comparesource.php?left=" . $row['sim_s_id'] . "&right=" . $row['solution_id'] . "  class='btn btn-info'  target=original title='" . $row['sim_s_id'] . "'>" . $res[0] . "(" . $row['sim'] . "%)</a>";

                    } else {

                        $view_status[$i][3] .= "&nbsp;<br><span class='btn btn-info' title='" . $row['sim_s_id'] . "'>" . $res[0] . "</span>&nbsp;<span class='btn btn-info'>" . $row['sim'] . "%</span>";

                    }
                    if (isset($_GET['showsim']) && isset($row[13])) {
                        //  $view_status[$i][3].= "$row[13]";

                    }
                } else {

                    $view_status[$i][3] = "<span class='" . $judge_color[$row['result']] . "'><i class='" . $iconlist[$row['result']] . " icon'></i>" . $judge_result[$row['result']] . "</span>";
                }
            } else {
                echo "<td>----";
            }

        }
        if ($row['result'] != 4 && isset($row['pass_rate']) && $row['pass_rate'] > 0 && $row['pass_rate'] < .98)
            $view_status[$i][3] .= "<br><span class='btn btn-info'>" . (100 - $row['pass_rate'] * 100) . "%</span>";
        if (isset($_SESSION['http_judge'])) {
            $view_status[$i][3] .= "<form class='http_judge_form form-inline' >
					<input type=hidden name=sid value='" . $row['solution_id'] . "'>";
            $view_status[$i][3] .= "</form>";
        }
        if (!isset($_GET['cid']) && !isset($_GET['tid']) && isset($_SESSION['administrator'])) {
            //$view_status[$i][4]="<span>";
            if (isset($row["contest_id"]) && $row["contest_id"])
                $view_status[$i][4] = "<a href='contest.php?cid=" . $row["contest_id"] . "' target='_blank'>" . $row["contest_id"] . "</a>";
            else
                $view_status[$i][4] = "无";
            //$view_status[$i][4].="</span>";
        }


        if ($flag || (isset($_SESSION['administrator']) || isset($_SESSION['source_browser']) || isset($_SESSION['contest_id']))) {

            if (($row['result'] >= 4 && !strcmp($row['user_id'], $_SESSION['user_id'])) || $admin || $flag) {
                $cnt = 0;
                $memory = ["KB", "MB", "GB"];
                $NUM_M = intval($row['memory']);
                while ($NUM_M / 1024 >= 1) {
                    $cnt++;
                    $NUM_M /= 1024;
                }
                $ctime = ["ms", "s", "min"];
                $s_t = intval($row['time']);
                $t_cnt = 0;
                while ($s_t / 1000 >= 1) {
                    $t_cnt++;
                    $s_t /= 1000;
                }
                $view_status[$i][5] = "<div id=center class=red>" . number_format($NUM_M, 2) . $memory[$cnt] . "</div>";
                $view_status[$i][6] = "<div id=center class=red>" . number_format($s_t, $t_cnt ? 3 : 0) . $ctime[$t_cnt] . "</div>";
                //echo "=========".$row['memory']."========";
            } else {
                $view_status[$i][5] = "---";
                $view_status[$i][6] = "---";

            }
            //echo $row['result'];
            if (!(isset($_SESSION['user_id']) && strtolower($row['user_id']) == strtolower($_SESSION['user_id']) || isset($_SESSION['source_browser']) || isset($_SESSION['administrator']) || !strcmp($row['user_id'], $_SESSION['user_id']))) {
                //$view_status[$i][7]=$language_name[$row['language']];
                $view_status[$i][7] = $language_name[$row['language']];
            } else {

                $view_status[$i][7] = "<a target=_blank href=showsource.php?";
                if (isset($row['runner_id'])) $view_status[$i][7] .= "h";
                $view_status[$i][7] .= "id=" . $row['solution_id'] . ">";
                if (isset($row['runner_id']))
                    $view_status[$i][7] .= $vjudge_language_name[strtolower($row["oj_name"])][$row['language']];
                else
                    $view_status[$i][7] .= $language_name[$row['language']];
                $view_status[$i][7] .= "</a><br>";
                if ($row["problem_id"] > 0) {
                    if (isset($cid)) {
                        $view_status[$i][7] .= "<a target=_self href=\"";
                        if ($OJ_TEMPLATE != "flat-ui") {
                            if (isset($row['runner_id'])) {
                                $view_status[$i][7] .= strtolower($row['oj_name']) . "submitpage";
                            } else
                                $view_status[$i][7] .= "newsubmitpage";
                        } else $view_status[$i][7] .= "submitpage";
                        $view_status[$i][7] .= ".php?cid=" . $cid . "&pid=" . $row['num'] . "&sid=" . $row['solution_id'] . "\">Edit</a>";
                    } else {
                        $view_status[$i][7] .= "<a target=_self href=\"";
                        if ($OJ_TEMPLATE != "flat-ui") $view_status[$i][7] .= "newsubmitpage";
                        else $view_status[$i][7] .= "submitpage";
                        $view_status[$i][7] .= ".php?id=" . $row['problem_id'] . "&sid=" . $row['solution_id'] . "\">Edit</a>";
                    }
                }
            }
            $code = intval($row['code_length']);
            $cnt = 0;
            $clength = ["B", "KB", "MB"];
            while ($code / 1024 >= 1) {
                $cnt++;
                $code /= 1024;
            }
            if (!strcmp($row['user_id'], $_SESSION['user_id']) || $flag)
                $view_status[$i][8] = number_format($code, $cnt) . $clength[$cnt];
            else
                $view_status[$i][8] = "----";
        } else {
            $view_status[$i][5] = "----";
            $view_status[$i][6] = "----";
            $view_status[$i][7] = "----";
            $view_status[$i][8] = "----";
        }
        $view_status[$i][9] = $row['in_date'];
        $view_status[$i][10] = $row['judger'];


        $i++;

    }

    $sql = "SELECT date(in_date) md,count(1) c FROM `solution` where result=4 and in_date>'" . date("Y-m-d", strtotime(date("Y-m-d", time()) . " -12 month")) . "' group by md order by md desc";
    $result = mysqli_query($mysqli, $sql);//mysql_escape_string($sql));
    $chart_data_ac = array();
//echo $sql;
    while ($row = mysqli_fetch_array($result)) {
        $chart_data_ac[$row['md']] = $row['c'];
    }
    $arr = [];
    foreach ($chart_data_ac as $k => $v) {
        $arr[date("Y-m", strtotime($k))][1] += intval($v);
    }
    $sql = "SELECT date(in_date)  md,count(1) c FROM `solution` where in_date>'" . date("Y-m-d", strtotime(date("Y-m-d", time()) . " -12 month")) . "'  group by md order by md desc";
    $result = mysqli_query($mysqli, $sql);//mysql_escape_string($sql));
    $chart_data_all = array();
//echo $sql;

    while ($row = mysqli_fetch_array($result)) {
        $chart_data_all[$row['md']] = $row['c'];
    }
    foreach ($chart_data_all as $k => $v) {
        $arr[date("Y-m", strtotime($k))][0] += intval($v);
    }
    for ($i = 12; $i >= 0; --$i) {
        if (!isset($arr[date("Y-m", strtotime(date("Y-m-d", time()) . " -" . $i . " month"))][0]))
            $arr[date("Y-m", strtotime(date("Y-m-d", time()) . " -" . $i . " month"))][0] = 0;
        if (!isset($arr[date("Y-m", strtotime(date("Y-m-d", time()) . " -" . $i . " month"))][1]))
            $arr[date("Y-m", strtotime(date("Y-m-d", time()) . " -" . $i . " month"))][1] = 0;
    }
}
?>

<?php
/////////////////////////Template
if (isset($_GET['cid']))
    require("template/" . $OJ_TEMPLATE . "/conteststatus.php");
else
    require("template/" . $OJ_TEMPLATE . "/status.php");
/////////////////////////Common foot
if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>

