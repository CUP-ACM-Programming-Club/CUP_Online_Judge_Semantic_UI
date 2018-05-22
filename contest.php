<?php
$OJ_CACHE_SHARE = false;
$cache_time=10;
$CONTEST_PAGE=true;

require_once('./include/db_info.inc.php');
require_once('./include/my_func.inc.php');
require_once('./include/const.inc.php');
require_once('./include/setlang.php');
require_once('./closed.php');
require_once('./include/cache_start.php');
if(isset($_SESSION['administrator']))
{
    require_once('./include/set_get_key.php');
}
$title = $MSG_CONTEST;
$vjudge_name=["HDU","POJ","UVA"];
if (!isset($_SESSION['user_id'])) {

    $view_errors = "<a href=newloginpage.php>$MSG_Login</a>";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
//	$_SESSION['user_id']="Guest";
}
function formatTimeLength($length)
{
    $hour = 0;
    $minute = 0;
    $second = 0;
    $result = '';

    if ($length >= 60) {
        $second = $length % 60;
        if ($second > 0) {
            $result = $second . '秒';
        }
        $length = floor($length / 60);
        if ($length >= 60) {
            $minute = $length % 60;
            if ($minute == 0) {
                if ($result != '') {
                    $result = '0分' . $result;
                }
            } else {
                $result = $minute . '分' . $result;
            }
            $length = floor($length / 60);
            if ($length >= 24) {
                $hour = $length % 24;
                if ($hour == 0) {
                    if ($result != '') {
                        $result = '0小时' . $result;
                    }
                } else {
                    $result = $hour . '小时' . $result;
                }
                $length = floor($length / 24);
                $result = $length . '天' . $result;
            } else {
                $result = $length . '小时' . $result;
            }
        } else {
            $result = $length . '分' . $result;
        }
    } else {
        $result = $length . '秒';
    }
    return $result;
}

if (isset($_GET['cid'])) {
    $cid = intval($_GET['cid']);
    $view_cid = $cid;
    //	print $cid;


    // check contest valid
    //$sql="SELECT * FROM `contest` WHERE `contest_id`='$cid' ";
    $result=$database->select("contest","*",["contest_id"=>$cid]);
    $rows_cnt=count($result);
    $row=$result[0];
    //$rows_cnt = $database->count("contest", [
      //  "contest_id" => "$cid"]);
    //$row = ($database->select("contest", "*", [
      //  "contest_id" => "$cid"]))[0];
    //$result=mysqli_query($mysqli,$sql);
    //	$rows_cnt=mysqli_num_rows($result);
    $vjudge="";
    $contest_ok = true;
    $password = "";
    $isvjudge=false;
    $privilege = false;
    $iscmode=$row["cmod_visible"]=="1";
    if(isset($_SESSION['administrator']))
    $iscmode=0;
    if (isset($_POST['password'])) $password = $_POST['password'];
    if (get_magic_quotes_gpc()) {
        $password = stripslashes($password);
    }
    if ($rows_cnt == 0) {
        mysqli_free_result($result);
        $view_title = "比赛已经关闭!";

    } else {

        $view_private = $row['private'];
        if ($password != "" && $password == $row['password']) $_SESSION['c' . $cid] = true;
        if ($row['private'] && !isset($_SESSION['c' . $cid])) $contest_ok = false;
        if ($row['defunct'] == 'Y') $contest_ok = false;
        if (isset($_SESSION['administrator'])||isset($_SESSION['contest_manager']))
        {
            $contest_ok = true;
            $privilege = true;
        }
        $now = time();
        if($row['vjudge']=='1')
        {
            $vjudge="vjudge_";
            $isvjudge=true;
        }
        if($row["cmod_visible"]=="1")
        {
            $iscmode=1;
        }
        $start_time = strtotime($row['start_time']);
        $end_time = strtotime($row['end_time']);
        $view_description = $row['description'];
        $view_title = $row['title'];
        $view_start_time = $row['start_time'];
        $view_end_time = $row['end_time'];
        if(!isset($_SESSION['administrator']))
        {
        if(($row["cmod_visible"]=="1"&&$OJ_CONTEST_MODE==0)||(($row["cmod_visible"]=="0"||$end_time<time())&&$OJ_CONTEST_MODE==1)
        )
        {

            $ERROR_MESSAGE_HEADER="对不起，您无权访问";
            $ERROR_MESSAGE_CONTENT="";
            require("template/$OJ_TEMPLATE/closed.php");
            exit(0);
        }
        }
        if($OJ_CONTEST_MODE&&!$iscmode)
        {
            unset($CONTEST_PAGE);
            require('./closed.php');
        }
        if (!$privilege && $now < $start_time) {
            $ERROR_MESSAGE_HEADER="$MSG_PRIVATE_WARNING";
            $ERROR_MESSAGE_CONTENT="请在考试期间打开页面";
            require("template/" . $OJ_TEMPLATE . "/closed.php");
            exit(0);
        }
    }
    if (!$contest_ok) {
        $view_errors = "<h2>$MSG_PRIVATE_WARNING <br><a href=contestrank.php?cid=$cid>$MSG_WATCH_RANK</a></h2>";
        $view_errors .= "<form method=post action='contest.php?cid=$cid'>$MSG_CONTEST $MSG_PASSWORD:<input class=input-mini type=password name=password><input class='btn btn-primary' type=submit></form>";
        require("template/" . $OJ_TEMPLATE . "/error.php");
        exit(0);
    }
    //	$database->select($database->select(""));
    /*$sql="select * from (SELECT `problem`.`title` as `title`,`problem`.`problem_id` as `pid`,source as source,contest_problem.num as pnum

FROM `contest_problem`,`problem`

WHERE `contest_problem`.`problem_id`=`problem`.`problem_id` 

AND `contest_problem`.`contest_id`=$cid ORDER BY `contest_problem`.`num` 
        ) problem
        left join (select problem_id pid1,count(1) accepted from solution where result=4 and contest_id=$cid group by pid1) p1 on problem.pid=p1.pid1
        left join (select problem_id pid2,count(1) submit from solution where contest_id=$cid  group by pid2) p2 on problem.pid=p2.pid2
order by pnum
        
        ";//AND `problem`.`defunct`='N'


    $result=mysqli_query($mysqli,$sql);*/
    $sql="";
    if($isvjudge)
    {
        
    $sql="select * from (SELECT `problem`.`title` as `title`,`problem`.`problem_id` as `pid`,source as source,contest_problem.num as pnum

		FROM `contest_problem`,`problem`

		WHERE `contest_problem`.`problem_id`=`problem`.`problem_id` 

		AND `contest_problem`.`contest_id`=$cid AND `contest_problem`.`oj_name` IS NULL ORDER BY `contest_problem`.`num` 
                ) problem
                left join (select problem_id pid1,num,count(1) accepted from solution where result=4 and contest_id=$cid group by pid1) p1 on problem.pid=p1.pid1
                left join (select problem_id pid2,num,count(1) submit from solution where contest_id=$cid  group by pid2) p2 on problem.pid=p2.pid2
union all
select * from (SELECT `vjudge_problem`.`title` as `title`,`vjudge_problem`.`problem_id` as `pid`,source as source,contest_problem.num as pnum FROM
 `contest_problem`,`vjudge_problem`
WHERE `contest_problem`.`problem_id`=`vjudge_problem`.`problem_id`
AND `contest_problem`.`contest_id`=$cid AND `contest_problem`.`oj_name`=`vjudge_problem`.`source` ORDER BY `contest_problem`.`num`) vproblem
left join(select problem_id pid1,num,count(1) accepted from vjudge_solution where result=4 and contest_id=$cid group by num) vp1 on vproblem.pid=vp1.pid1 and vproblem.pnum=vp1.num
left join(select problem_id pid2,num,count(1) submit from vjudge_solution where contest_id=$cid group by num) vp2 on vproblem.pid=vp2.pid2 and vproblem.pnum=vp2.num
order by pnum;
";
}
else
{
    $sql="select * from (SELECT `problem`.`title` as `title`,`problem`.`problem_id` as `pid`,source as source,contest_problem.num as pnum

		FROM `contest_problem`,`problem`

		WHERE `contest_problem`.`problem_id`=`problem`.`problem_id` 

		AND `contest_problem`.`contest_id`=$cid ORDER BY `contest_problem`.`num` 
                ) problem
                left join (select problem_id pid1,num,count(1) accepted from solution where result=4 and contest_id=$cid group by pid1) p1 on problem.pid=p1.pid1
                left join (select problem_id pid2,num,count(1) submit from solution where contest_id=$cid  group by pid2) p2 on problem.pid=p2.pid2
order by pnum;
";
}
    $result = $database->query($sql)->fetchAll();
    $view_problemset = Array();

    $cnt = 0;
    foreach ($result as $row) {
        $is_vjudge_problem=in_array($row['source'],$vjudge_name);
        $view_problemset[$cnt][0] = "";
        if (isset($_SESSION['user_id']))
            if($is_vjudge_problem)
                $view_problemset[$cnt][0]=check_ac($cid,$cnt,$vjudge_name);
            else
                $view_problemset[$cnt][0] = check_ac($cid, $cnt);
        if($privilege||$now>$end_time)
        {
            if($is_vjudge_problem)
                $view_problemset[$cnt][1].=$row['source']." ";
            else
                $view_problemset[$cnt][1].="LOCAL ";
            $view_problemset[$cnt][1].= $row['pid'] . "<br>Problem &nbsp;" . $PID[$cnt];
        }
        else
            $view_problemset[$cnt][1].= "Problem &nbsp;" . $PID[$cnt];
        if ($OJ_TEMPLATE != "flat-ui"){
            if(isset($_SESSION['user_id']))
                if($view_problemset[$cnt][0]=="1")
                    $view_problemset[$cnt][2].="<i class='checkmark icon'></i>";
                else if($view_problemset[$cnt][0]=="-1")
                    $view_problemset[$cnt][2].="<i class='remove icon'></i>";
            if($is_vjudge_problem)
                $view_problemset[$cnt][2].="<a href='".strtolower($row['source'])."submitpage.php?";
            else
                $view_problemset[$cnt][2].= "<a href='newsubmitpage.php?";
            if ($now > $end_time)
                $view_problemset[$cnt][2].="id=".$row['pid']."'";
            else
                $view_problemset[$cnt][2].="cid=$cid&pid=$cnt&js'";
            $view_problemset[$cnt][2].=" target='_blank'>";
            if($iscmode)
                $view_problemset[$cnt][2].="Problem &nbsp;" . $PID[$cnt];
            else
                $view_problemset[$cnt][2].=$row['title'] . "</a>";
           // $row['title'] . "</a>";
        }
        else{
            $view_problemset[$cnt][2] = "<a href='problem.php?cid=$cid&pid=$cnt'>";
            if($iscmode)
                $view_problemset[$cnt][2].="Problem &nbsp;" . $PID[$cnt];
            else
                $view_problemset[$cnt][2].=$row['title'] . "</a>";
        }
        $view_problemset[$cnt][3] = $row['accepted'];
        $view_problemset[$cnt][4] = $row['submit'];
        $cnt++;
    }

   // mysqli_free_result($result);

} else {
    $keyword = "";
    if (isset($_POST['keyword'])) {
        $keyword = mysqli_real_escape_string($mysqli, $_POST['keyword']);
    }
    //echo "$keyword";
    $mycontests = "";
    foreach ($_SESSION as $key => $value) {
        if (($key[0] == 'm' || $key[0] == 'c') && intval(substr($key, 1)) > 0) {
//      echo substr($key,1)."<br>";
            $mycontests .= "," . intval(substr($key, 1));
        }
    }
    if (strlen($mycontests) > 0) $mycontests = substr($mycontests, 1);
//  echo "$mycontests";
    $wheremy = "";
    if (isset($_GET['my'])) $wheremy = " and contest_id in ($mycontests)";


    $sql = "SELECT * FROM `contest` WHERE `defunct`='N' ORDER BY `contest_id` DESC limit 1000";
    $admin_str="";
    if(!isset($_SESSION['administrator']))
    {
        $admin_str="ctest.defunct='N' and ";
    }
    if($OJ_CONTEST_MODE&&!isset($_SESSION['administrator']))
    {
        $admin_str.="ctest.cmod_visible='1' and ";
    }
    elseif(!isset($_SESSION['administrator']))
    {
        $admin_str.="ctest.cmod_visible='0' and";
    }
    $sql = "select * from (select *  from contest where start_time<NOW() and end_time>NOW())ctest left join (select user_id,rightstr from privilege where rightstr like 'm%') p on concat('m',contest_id)=rightstr where $admin_str title like '%$keyword%' $wheremy  order by end_time asc limit 1000;";
    $result = $database->query($sql)->fetchAll();
    $sql = "select * from (select * from contest where contest_id not in (select contest_id  from contest where start_time<NOW() and end_time>NOW()))ctest left join (select user_id,rightstr from privilege where rightstr like 'm%') p on concat('m',contest_id)=rightstr where $admin_str title like '%$keyword%' $wheremy  order by contest_id desc limit 1000;";
    $result = array_merge($result,$database->query($sql)->fetchAll());
    //$result=mysqli_query($mysqli,$sql);
    $view_contest = Array();
    $i = 0;
    $admin_pri=[];
    foreach ($result as $row) {
        if($row['defunct']=='N')$admin_pri[]=false;
        else $admin_pri[]=true;
        $view_contest[$i][0] = $row['contest_id'];
        if(isset($_SESSION['administrator']))
        {
            $view_contest[$i][0].=$row["cmod_visible"]=="1"?"(EXAM)":"";
        }
        $view_contest[$i][1] = "<a href='contest.php?cid=" . $row['contest_id'] . "'>" . $row['title'] . "</a>";
        $start_time = strtotime($row['start_time']);
        $end_time = strtotime($row['end_time']);
        $now = time();


        $length = $end_time - $start_time;
        $left = $end_time - $now;
        // past

        if ($now > $end_time) {
            $view_contest[$i][2] = "<span class=green>$MSG_Ended@" . $row['end_time'] . "</span>";
            
            // pending

        } else if ($now < $start_time) {
            $view_contest[$i][2] = "<span class=blue>$MSG_Start@" . $row['start_time'] . "</span><br>";
            $view_contest[$i][2] .= "<span  class=green>$MSG_TotalTime" . formatTimeLength($length) . "</span>";
            // running

        } else {
            $view_contest[$i][2] = "<span class=red> $MSG_Running</font>&nbsp;";
            $view_contest[$i]["running"]=true;
            $view_contest[$i][2] .= "<span class='green need_to_be_rendered' datetime='".date("Y-m-d h:i:s", $end_time)."'> $MSG_LeftTime " . formatTimeLength($left) . " </span>结束";
        }
        if(isset($_SESSION['administrator']))
        {
            $view_contest[$i][3]="<a class='visible tag' href='/admin/contest_df_change.php?cid=".$row['contest_id']."&getkey=".$_SESSION['getkey']."'>";
        $view_contest[$i][3].=$row['defunct']!='N'?"隐藏":"显示";
        $view_contest[$i][3].="</a>";
        }
        $private = intval($row['private']);
        if(isset($_SESSION['administrator']))
        $view_contest[$i][4]="<a  href='/admin/contest_pr_change.php?cid=".$row['contest_id']."&getkey=".$_SESSION['getkey']."'>";
        if ($private == 0)
            $view_contest[$i][4] .= "<span class='private tag'>$MSG_Public</span>";
        else
            $view_contest[$i][4] .= "<span class='private tag'>$MSG_Private</span>";
        if(isset($_SESSION['administrator']))
        $view_contest[$i][4].="</a>";
        $view_contest[$i][6] = "<a href='userinfo.php?user=".$row['user_id']."' target='_blank'>".$row['user_id']."</a>";


        $i++;
    }

    //mysqli_free_result($result);

}


/////////////////////////Template
if (isset($_GET['cid'])) {
    require("template/" . $OJ_TEMPLATE . "/contest.php");
} else
    require("template/" . $OJ_TEMPLATE . "/contestset.php");
/////////////////////////Common foot
if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>
