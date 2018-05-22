<?php
$OJ_CACHE_SHARE = false;
$cache_time = 60;
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once('./closed.php');
// require_once('./include/cache_start.php');
if (!isset($_SESSION['user_id'])) {

    $view_errors = "<a href=newloginpage.php>$MSG_Login</a>";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
//	$_SESSION['user_id']="Guest";
}
if($OJ_TEMPLATE == "semantic-ui")
{}
else{
$view_title = "Problem Set";
$first = 1000;
$page_cnt = 50;
if(isset($mem)){
    $_label_color = $mem->get("label_color");
}
if($_label_color)
{
    $_label_color = json_decode($_label_color);
}
else{
    $_label_color = $database->select("global_setting","value",["label"=>"label_color"])[0];
    $mem->set("label_color",$_label_color,0,3600);
    $_label_color = json_decode($_label_color);
}

$label_color = [];
foreach($_label_color as $k=>$v)
{
    $label_color[$k]=$v;
}
if (isset($_COOKIE['page_cnt'])) {
    $page_cnt = intval($_COOKIE['page_cnt']);
}
if (isset($_GET['pcnt'])) {
    if (is_numeric(intval($_GET['pcnt'])) && intval($_GET['pcnt']) > 50) {
        $page_cnt = intval($_GET['pcnt']);
        setcookie("page_cnt", $page_cnt);
    }
}
//$result = $database->count("problem");
//$result = $database->max("problem", "problem_id");


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
    }
} else {
    if (isset($_SESSION['user_id'])) {
        $result = $database->select("users", "volume", [
            "user_id" => $_SESSION['user_id']
        ]);
        $page = intval($result);
    }
    if (!is_numeric($page) || $page < 0)
        $page = '1';
}
//end of remember page


$pstart = $page_cnt * intval($page) - $page_cnt;
$pend = $pstart + $page_cnt;

$sub_arr = Array();
// submit
if (isset($_SESSION['user_id'])) {
    $result = $database->select("solution", "problem_id", [
        "user_id" => $_SESSION['user_id'],
        "GROUP" => "problem_id"
    ]);
    foreach ($result as $value)
        $sub_arr[$value] = true;
}

$acc_arr = Array();
// ac
if (isset($_SESSION['user_id'])) {
    $result = $database->select("solution", "problem_id", [
        "user_id" => $_SESSION['user_id'],
        "result" => 4,
        "GROUP" => "problem_id"
    ]);
    foreach ($result as $value)
        $acc_arr[$value] = true;
}
$result = "";
if (isset($_GET['search']) && trim($_GET['search']) != "") {
    $search = mysqli_real_escape_string($mysqli, $_GET['search']);
    if (!preg_match('/AND/', $search) && !preg_match('/1=1/', $search)) {
        $filter_sql = [
            "title[~]" => $search,
            "source[~]" => $search,
            "problem_id" => $search,
            "description[~]" => $search,
            "label[~]" => $search
        ];
    } else {
        $filter_sql = ["ORDER" => "problem_id"];
    }

} else if (isset($_GET['tag']) && trim($_GET['tag']) != "") {
    $tag = mysqli_real_escape_string($mysqli, $_GET['tag']);
    if (!preg_match('/AND/', $tag) && !preg_match('/1=1/', $tag)) {
        $filter_sql = [
            "label[~]" => $tag
        ];
    } else {
        $filter_sql = ["ORDER" => "problem_id"];
    }
}
else{

}

if (isset($_SESSION['administrator'])) {
    $statement=[
        "ORDER" => "problem_id",
        "LIMIT"=>[$pstart,$page_cnt]
        ];
        if(count($filter_sql)>0){
            $statement["OR"]=$filter_sql;
        }
    $result = $database->select("problem", [
        "problem_id",
        "title",
        "source",
        "submit",
        "accepted",
        "label"
    ],$statement
        );
} else {
    $now = strftime("%Y-%m-%d %H:%M", time());
    $statement=[
            "AND" => [
                "defunct" => "N",
                
                "problem_id[!]" => $database->select("contest_problem", "problem_id", [
                    "contest_id" => $database->select("contest", "contest_id", [
                        "AND" => [
                            "OR" => [
                                "end_time[>]" => $now,
                                "private" => "1"
                            ],
                            "defunct" => "N"
                        ]
                    ])
                ])
            ],
            "ORDER"=>["problem_id"=>"ASC"],
            "LIMIT"=>[$pstart,$page_cnt],
        ];
        if(count($filter_sql)>0){
            $statement["AND"]["OR"]= $filter_sql;
        }
        //echo var_dump($statement);
    $result = $database->select("problem", [
        "problem_id",
        "title",
        "label",
        "submit",
        "accepted",
        "label"
    ],
        $statement);
}
unset($statement["LIMIT"]);
$cnt = $database->count("problem",$statement);
$cnt = ceil(($cnt+0.0) / $page_cnt);
$view_total_page = $cnt;
$cnt = 0;
$view_problemset = Array();
if (isset($_GET['debug'])) {
    echo var_dump($filter_sql);
    exit(0);
}
$i = 0;
foreach ($result as $row) {
    $view_problemset[$i] = Array();
    if (isset($sub_arr[$row['problem_id']])) {
        if (isset($acc_arr[$row['problem_id']]))
            $view_problemset[$i][0] = "<i class=\"checkmark icon\"></i>";
        else
            $view_problemset[$i][0] = "<i class=\"remove icon\"></i>";
    } else {
        $view_problemset[$i][0] = "<div class=none> </div>";
    }
    $view_problemset[$i][1] = "<div class='center'>" . $row['problem_id'] . "</div>";
    if ($OJ_TEMPLATE == 'flat-ui') $view_problemset[$i][2] = "<div class='left aligned'><a href='problem.php?id=" . $row['problem_id'] . "'>" . $row['title'] . "</a></div>";
    else
        $view_problemset[$i][2] = "<div class='left aligned'><a href='newsubmitpage.php?id=" . $row['problem_id'] . "&js'>" . $row['title'] . "</a>";
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
                    <a href="problemset.php?tag=' . $labels[$ii] . '" class="ui '.$label_color[$labels[$ii]].' label">
                      ';
            $view_problemset[$i][2] .= $labels[$ii];
            $view_problemset[$i][2] .= '
                    </a>
                  </span>
              </div>';
        }
    }
    $view_problemset[$i][2].="</div>";
    $view_problemset[$i][3] = "";
    $view_problemset[$i][4] = "<div class='center'>" . $row['accepted'] . " / " . $row['submit'] . "<br>" . (intval($row['submit']) == 0 ? "0" : substr(intval($row['accepted']) * 100 / intval($row['submit']), 0, min(4, strlen(strval(intval($row['accepted']) * 100 / intval($row['submit'])))))) . " %</div>";
    $i++;
}
}
require("template/" . $OJ_TEMPLATE . "/problemset.php");
if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>