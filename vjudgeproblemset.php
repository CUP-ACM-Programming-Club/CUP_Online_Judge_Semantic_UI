<?php
$OJ_CACHE_SHARE = true;
$cache_time = 60;
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once('./closed.php');
//require_once('./include/cache_start.php');
if (!isset($_SESSION['user_id'])) {

    $view_errors = "<a href=newloginpage.php>$MSG_Login</a>";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
//	$_SESSION['user_id']="Guest";
}
if(false){
$view_title = "Problem Set";
$first = 1000;
//if($OJ_SAE) $first=1;
//$sql="SELECT max(`problem_id`) as upid FROM `problem`";
$page_cnt = 50;
if (isset($_COOKIE['page_cnt'])) {
    $page_cnt = intval($_COOKIE['page_cnt']);
}
if (isset($_GET['pcnt'])) {
    if (is_numeric(intval($_GET['pcnt'])) && intval($_GET['pcnt']) > 50) {
        $page_cnt = intval($_GET['pcnt']);
        setcookie("page_cnt", $page_cnt);
    }
}
//$result=mysqli_query($mysqli,$sql);
$result = $database->max("vjudge_problem", "problem_id");
//echo mysqli_error($mysqli);
//$row=mysqli_fetch_object($result);
$cnt = intval($result) - $first;
$cnt = $cnt / $page_cnt;

//remember page
$page = "1";
if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
    if (isset($_SESSION['user_id'])) {
        $database->update("users", [
            "volume" => $page
        ],
            [
                "user_id" => $_SESSION['user_id']
            ]);
        // $sql="update users set volume=$page where user_id='".$_SESSION['user_id']."'";
        //mysqli_query($mysqli,$sql);
    }
} else {
    if (isset($_SESSION['user_id'])) {
        $result = $database->select("users", "volume", [
            "user_id" => $_SESSION['user_id']
        ]);
        //$sql="select volume from users where user_id='".$_SESSION['user_id']."'";
        //$result=@mysqli_query($mysqli,$sql);
        //$row=mysqli_fetch_array($result);
        $page = intval($result);
    }
    if (!is_numeric($page) || $page < 0)
        $page = '1';
}
//end of remember page


$pstart = $first + $page_cnt * intval($page) - $page_cnt;
$pend = $pstart + $page_cnt;

$sub_arr = Array();
// submit
if (isset($_SESSION['user_id'])) {
    $result = $database->select("vjudge_solution", ["problem_id", "oj_name"], [
        "user_id" => $_SESSION['user_id'],
        "GROUP" => ["problem_id", "oj_name"]
    ]);
//$sql="SELECT `problem_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."'".
    //  " AND `problem_id`>='$pstart'".
    // " AND `problem_id`<'$pend'".
//	" group by `problem_id`";
//$result=@mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
    foreach ($result as $value)
        $sub_arr[$value['oj_name'] . $value['problem_id']] = true;
}

$acc_arr = Array();
// ac
if (isset($_SESSION['user_id'])) {
    $result = $database->select("vjudge_solution", ["problem_id", "oj_name"], [
        "user_id" => $_SESSION['user_id'],
        "result" => 4,
        "GROUP" => ["problem_id", "oj_name"]
    ]);
//$sql="SELECT `problem_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."'".
    //  " AND `problem_id`>='$pstart'".
    //  " AND `problem_id`<'$pend'".
//	" AND `result`=4".
//	" group by `problem_id`";
//$result=@mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
    foreach ($result as $value)
        $acc_arr[$value['oj_name'] . $value['problem_id']] = true;
//while ($row=mysqli_fetch_array($result))
//	$acc_arr[$row[0]]=true;
}
$result = "";
$and_sql = [];
if (isset($_GET['search']) && trim($_GET['search']) != "") {
    $search = mysqli_real_escape_string($mysqli, $_GET['search']);
    if (!preg_match('/AND/', $search) && !preg_match('/1=1/', $search)) {
        $VOJ_NAME = ["HDU" => 1, "POJ" => 1, "UVA" => 1];
        $oj_name = "";
        if ($VOJ_NAME[strtoupper(substr($search, 0, 3))]) {
            $oj_name = substr($search, 0, 3);
            $search = substr($search, 3, strlen($search) - 3);
        }
        $filter_sql = [
            "title[~]" => $search,
            "source[~]" => $search,
            "problem_id" => $search
        ];
        if ($oj_name != "") {
            $and_sql["source"] = $oj_name;
        }
        //$filter_sql=" ( title like '%$search%' or source like '%$search%' or problem_id like '%$search%')";
    } else {
        $filter_sql = ["ORDER" => "problem_id"];
        // $filter_sql=" 1=1 ";
    }

} else {
    $filter_sql = ["AND" => [
        "problem_id[>=]" => strval($pstart),
        "problem_id[<]" => strval($pend)
    ]];
    //$filter_sql="  `problem_id`>='".strval($pstart)."' AND `problem_id`<'".strval($pend)."' ";
}
$and_sql["OR"] = $filter_sql;
if (isset($_SESSION['administrator'])) {
    // $filter_sql=["AND"=>[
    //    "problem_id[>]"=>strval($pstart),
    //   "problem_id[<]"=>strval($pend)
    //   ]];
    $result = $database->select("vjudge_problem", "*",
        ["AND" => $and_sql, "ORDER" => "problem_id"]);
    // echo var_dump($result);
    //echo var_dump($filter_sql);
    //$sql="SELECT `problem_id`,`title`,`source`,`submit`,`accepted` FROM `problem` WHERE $filter_sql ";

} else {
    $now = strftime("%Y-%m-%d %H:%M", time());
    $result = $database->select("vjudge_problem", "*",
        ["AND" => $and_sql, "ORDER" => "problem_id"]);
    $sql = "SELECT `problem_id`,`title`,`source`,`submit`,`accepted` FROM `vjudge_problem` " .
        "WHERE $filter_sql ";
}
$view_total_page = $cnt + 1;

$cnt = 0;
$view_problemset = Array();
if (isset($_GET['debug'])) {
    echo var_dump($filter_sql);
    exit(0);
}
$i = 0;
foreach ($result as $row) {
    $view_problemset[$i] = Array();
    if (isset($sub_arr[$row['source'] . $row['problem_id']])) {
        if (isset($acc_arr[$row['source'] . $row['problem_id']]))
            $view_problemset[$i][0] = "<div class='btn btn-success accepted'>Y</div>";
        else
            $view_problemset[$i][0] = "<div class='btn btn-danger wrong_answer'>N</div>";
    } else {
        $view_problemset[$i][0] = "<div class=none> </div>";
    }
    $view_problemset[$i][1] = "<div class='center'>" . $row['problem_id'] . "</div>";
    $view_problemset[$i][2] = "<div class='left'><a href='" . strtolower($row['source']) . "submitpage.php?pid=" . $row['problem_id'] . "' target='_blank'>" . $row['title'] . "</a>";
    if (strlen($row['label']) == 0) {
        $view_problemset[$i][2] .= '<div class="show_tag_controled" style="float: right; ">
                  <span class="ui header">
                    <a href="#" class="ui black label">
                      ';
        $view_problemset[$i][2] .= "标签待整理";
        $view_problemset[$i][2] .= '
                    </a>
                  </span>
              </div>';
    } else {
        $labels = explode(" ", $row['label']);
        $len = count($labels);
        for ($ii = 0; $ii < $len; ++$ii) {
            $view_problemset[$i][2] .= '<div class="show_tag_controled" style="float: right; ">
                  <span class="ui header">
                    <a href="vjudge.php?tag=' . $labels[$ii] . '" class="ui blue label">
                      ';
            $view_problemset[$i][2] .= $labels[$ii];
            $view_problemset[$i][2] .= '
                    </a>
                  </span>
              </div>';
        }
    }
    $view_problemset[$i][3] = "<div class='center'><nobr>" . mb_substr($row['source'], 0, strlen($row['source']), 'utf8') . "</nobr></div >";
    $view_problemset[$i][4] = "<div class='center'><a href='hdu_status.php?problem_id=" . $row['problem_id'] . "&jresult=4'>" . $row['accepted'] . "</a></div>";
    $view_problemset[$i][5] = "<div class='center'><a href='hdu_status.php?problem_id=" . $row['problem_id'] . "'>" . $row['submit'] . "</a></div>";
    $i++;
}
}
require("template/" . $OJ_TEMPLATE . "/newvjudgeproblemset.php");
if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>
