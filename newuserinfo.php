<?php
$special_subject = $database->select("special_subject", "*", ["topic_id[>=]" => 4,"topic_id[<=]"=>22]);
$ss = [];
foreach ($special_subject as $v) {
    $ss[] = ["title" => $v['title'], "id" => $v['topic_id']];
}
// print_r($special_subject);
// print_r($ss);
foreach ($ss as $k => $v) {
    $result = $database->select("special_subject_problem", "*", ["topic_id" => $v['id']]);
    $ss[$k]["problem"]=$result;
}

foreach ($ss as $k => $v) {
    foreach ($v['problem'] as $s => $e) {
        //echo var_dump($v['problem']);
        $result = $database->count("vjudge_solution", ["user_id" => $user_mysql, "result" => 4, "problem_id" => $e['problem_id'], "oj_name" => $e['oj_name']]);
        $result += $database->count("vjudge_record", ["user_id" => $user_mysql, "problem_id" => $e['problem_id'], "oj_name" => $e['oj_name']]);
        //echo $result;
        $ss[$k]['problem'][$s]['ac'] = $result > 0 ? 1 : 0;
    }
}
foreach ($ss as $k => $v) {
    $cnt = 0;
    $tot=0;
    foreach ($v['problem'] as $s => $e) {
        $cnt += intval($e['ac']);
        $tot++;
    }
    if($tot==0)$tot=1;
    $ss[$k]['ac_num'] = $cnt;
    $ss[$k]['tot']=$tot;
  //  echo "<br>".$cnt."<br>".$tot."<br>".$ss[$k]['title'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $OJ_NAME ?></title>
    <link href="template/<?php echo $OJ_TEMPLATE ?>/css/semantic.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../template/<?php echo $OJ_TEMPLATE; ?>/css/home.css">
    <link href="../template/<?= $OJ_TEMPLATE ?>/css/katex.min.css" rel="stylesheet">
    <style type="text/css">
        .padding {
            padding-left: 1em;
            padding-right: 1em;
            padding-top: 1em;
            padding-bottom: 1em;
        }

        .hidden.menu {
            display: none;
        }

        .masthead.segment {
            min-height: 700px;
            padding: 1em 0em;
        }

        .masthead .logo.item img {
            margin-right: 1em;
        }

        .masthead .ui.menu .ui.button {
            margin-left: 0.5em;
        }

        .masthead h1.ui.header {
            margin-top: 3em;
            margin-bottom: 0em;
            font-size: 4em;
            font-weight: normal;
        }

        .masthead h2 {
            font-size: 1.7em;
            font-weight: normal;
        }

        .ui.vertical.stripe {
            padding: 8em 0em;
        }

        .ui.vertical.stripe h3 {
            font-size: 2em;
        }

        .ui.vertical.stripe .button + h3,
        .ui.vertical.stripe p + h3 {
            margin-top: 3em;
        }

        .ui.vertical.stripe .floated.image {
            clear: both;
        }

        .ui.vertical.stripe p {
            font-size: 1.33em;
        }

        .ui.vertical.stripe .horizontal.divider {
            margin: 3em 0em;
        }

        .quote.stripe.segment {
            padding: 0em;
        }

        .quote.stripe.segment .grid .column {
            padding-top: 5em;
            padding-bottom: 5em;
        }

        .footer.segment {
            padding: 5em 0em;
        }

        .secondary.pointing.menu .toc.item {
            display: none;
        }

        .ui.large.secondary.inverted.pointing.menu {
            border-width: 0px;
        }

        @media only screen and (max-width: 700px) {
            .ui.fixed.menu {
                display: none !important;
            }

            .secondary.pointing.menu .item,
            .secondary.pointing.menu .menu {
                display: none;
            }

            .secondary.pointing.menu .toc.item {
                display: block;
            }

            .masthead.segment {
                min-height: 350px;
            }

            .masthead h1.ui.header {
                font-size: 2em;
                margin-top: 1.5em;
            }

        }


    </style>
    <?php include("template/$OJ_TEMPLATE/js.php"); 
          include("template/$OJ_TEMPLATE/css.php");
    ?>
    <script src="/template/semantic-ui/js/Chart.bundle.min.js"></script>
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
<div class="ui container pusher">
    <div class="padding">
        <div class="ui grid">
            <div class="row">
                <div class="five wide column">
                    <div class="ui card" style="width: 100%; " id="user_card">
                        <div class="blurring dimmable image" id="avatar_container">
                            <img style=" "
                                 src="<?php if (file_exists("./avatar/$user.jpg")) { ?>./avatar/<?= $user ?>.jpg<?php
                                 } else {
                                     ?>./assets/images/wireframe/white-image.png<?php
                                 }
                                 ?>">
                        </div>
                        <div class="content">
                            <div
                                class="header"><?php echo stripslashes($nick) ?>&nbsp;&nbsp;<a
                                    href="mail.php?to_user=<?= $user ?>"><i class="mail icon"></i></a></div>
                            <div class="meta">
                                <i class="user circle outline icon"></i><a class="group"><?php if ($privilege) {
                                        echo "管理员";
                                    } else {
                                        echo "普通用户";
                                    } ?></a>
                                <?php
                                $result = $database->select("acm_member","level", ["user_id" => $user]);
                                if (count($result) > 0) {
                                    echo "<a class='group'><i class='user icon'></i>ACM";
                                    if($result[0]=="0")echo "后备";
                                    else echo "正式";
                                    echo "队员</a>";
                                }
                                ?>
                                <?php 
                                $result=$database->select("award","*",["user_id"=>$user]);
                                if(count($result)>0)
                                {
                                    foreach($result as $v)
                                    {
                                        echo "<br><a class='group'><i class='trophy icon'></i>".$v["year"]."年".$v["award"]."</a>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="extra content">
                            <a><i class="check icon"></i>通过 <?php
                                $acres=$database->select("vjudge_record",["problem_id","oj_name"],["user_id"=>$user_mysql]);
                                $acproblem=[];
                                foreach($acres as $v)
                                {
                                    $acproblem[$v['oj_name'].$v['problem_id']]=1;
                                }
                                $aclocal=$database->select("vjudge_solution",["problem_id","oj_name"],["user_id"=>$user_mysql]);
                              //  echo count($acproblem);
                              //  echo print_r($acproblem);
                                foreach($aclocal as $v)
                                {
                                    $acproblem[$v['oj_name'].$v['problem_id']]=1;
                                }
                                $result=count($acproblem);
                                echo intval($AC + $result) . "&nbsp;题&nbsp;";
                                if ($result > 0) {
                                    echo "(" . $AC . "+" . $result . ")";
                                }
                                ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i
                                    class="line chart icon"></i>Rank:&nbsp;<?= $Rank ?></a>
                        </div>
                    </div>
                </div>
                <div class="eleven wide column">
                    <div class="ui grid">
                        <div class="row">
                            <div class="sixteen wide column">
                                <div class="ui grid">
                                    <div class="eight wide column">
                                        <div class="ui grid">
                                            <div class="row">
                                                <div class="column">
                                                    <h4 class="ui top attached block header"><i
                                                            class="id card icon"></i>用户名</h4>
                                                    <div class="ui attached segment"><?= $user ?></div>
                                                    <h4 class="ui attached block header"><i class="university icon"></i>学校
                                                    </h4>
                                                    <div class="ui attached segment"><?= $school ?></div>
                                                    <h4 class="ui attached block header"><i
                                                            class="mail square icon"></i>邮件</h4>
                                                    <div class="ui attached segment"><a
                                                            href="mailto:<?= $email ?>"><?= $email ?></a></div>
                                                    <h4 class="ui attached block header">
                                                        <i class="newspaper icon"></i>Blog
                                                    </h4>
                                                    <div class="ui attached segment">
                                                        <a target="_blank" href="<?=$blog?>"><?=$blog?></a>
                                                    </div>
                                                    <h4 class="ui attached block header">
                                                        <i class="github icon"></i>GitHub
                                                    </h4>
                                                    <div class="ui bottom attached segment">
                                                        <a target="_blank" href="<?=$github?>"><?=$github?></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="column">
                                                    <h4 class="ui top attached block header"><?= $OJ_NAME ?></h4>
                                                    <div class="ui attached segment">
                                                        <script language='javascript'>
                                                            function p(id) {
                                                                document.write("<a href=problem.php?id=" + id + ">" + id + " </a>");
                                                            }
                                                            <?php
                                                            $result = array_unique($database->select("solution", "problem_id", ["user_id" => $user_mysql, "result" => 4, "ORDER" => ["problem_id" => "ASC"]]));
                                                            foreach ($result as $v) {
                                                                if ($v)
                                                                    echo "p(" . $v . ");";
                                                            }
                                                            ?>
                                                        </script>
                                                    </div>
                                                    <h4 class="ui attached block header">HDU</h4>
                                                    <div class="ui attached segment">
                                                        <script language='javascript'>
                                                        function vp(id,oj) {
                                                                document.write("<a href="+oj+"submitpage.php?pid=" + id + ">" + id + " </a>");
                                                            }
                                                            <?php
                                                            $result = array_unique($database->select("vjudge_solution", "problem_id", ["user_id" => $user_mysql, "result" => 4, "oj_name" => "HDU", "ORDER" => ["problem_id" => "ASC"]]));
                                                            $datas = $database->select("vjudge_record", "problem_id", ["user_id" => $user_mysql, "oj_name" => "HDU"]);
                                                            foreach ($datas as $v) {
                                                                array_push($result, $v);
                                                            }
                                                            $result = array_unique($result);
                                                            sort($result);
                                                            foreach ($result as $v) {
                                                                if (is_numeric($v))
                                                                    echo "vp(" . $v . ",\"hdu\");";
                                                            }
                                                            ?>
                                                        </script>
                                                    </div>
                                                    <h4 class="ui attached block header">POJ</h4>
                                                    <div class="ui attached segment">
                                                        <script language='javascript'>
                                                            <?php
                                                            $result = array_unique($database->select("vjudge_solution", "problem_id", ["user_id" => $user_mysql, "result" => 4, "oj_name" => "POJ", "ORDER" => ["problem_id" => "ASC"]]));
                                                            $datas = $database->select("vjudge_record", "problem_id", ["user_id" => $user_mysql, "oj_name" => "POJ"]);
                                                            foreach ($datas as $v) {
                                                                array_push($result, $v);
                                                            }
                                                            $result = array_unique($result);
                                                            sort($result);
                                                            foreach ($result as $v) {
                                                                if (is_numeric($v)>0)
                                                                    echo "vp(" . $v . ",\"poj\");";
                                                            }
                                                            ?>
                                                        </script>
                                                    </div>
                                                    <h4 class="ui attached block header">UVA</h4>
                                                    <div class="ui attached segment">
                                                        <script language='javascript'>
                                                        <?php 
                                                        $result = array_unique($database->select("vjudge_solution", "problem_id", ["user_id" => $user_mysql, "result" => 4, "oj_name" => "UVA", "ORDER" => ["problem_id" => "ASC"]]));
                                                            $datas = $database->select("vjudge_record", "problem_id", ["user_id" => $user_mysql, "oj_name" => "UVA"]);
                                                            foreach ($datas as $v) {
                                                                array_push($result, $v);
                                                            }
                                                            $result = array_unique($result);
                                                            sort($result);
                                                            foreach ($result as $v) {
                                                                if (is_numeric($v)>0)
                                                                    echo "vp(" . $v . ",\"uva\");";
                                                            }
                                                        ?>
                                                        </script>
                                                        </div>
                                                    <h4 class="ui attached block header">其他</h4>
                                                    <div class="ui bottom attached segment">
                                                        <script language='javascript'>
                                                        function p(id) {
                                                                document.write("<a href='javascript:void(0)'>" + id + " </a>");
                                                            }
                                                            <?php
                                                            $result = $database->select("vjudge_record", ["oj_name", "problem_id"], ["user_id" => $user_mysql, "oj_name[!]" => ["POJ", "HDU","UVA"]]);
                                                            sort($result);
                                                            foreach ($result as $v) {
                                                                echo "p('" . $v["oj_name"] . " " . $v["problem_id"] . "');";
                                                            }
                                                            ?>
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                            </div>
                                            <div class="row">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="eight wide column">
                                        <h4 class="ui top attached block header"><i class="pie chart icon"></i>提交统计</h4>
                                        <div class="ui bottom attached segment">
                                            <div id="pie_chart_legend">

                                            </div>
                                            <div>
                                                <iframe class="chartjs-hidden-iframe" tabindex="-1"
                                                        style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                <canvas style="width: 260px; display: block; height: 260px;"
                                                        id="pie_chart" width="455" height="455"></canvas>
                                            </div>
                                        </div>
                                        <h4 class="ui top attached block header"><i class="pie chart icon"></i>编程语言</h4>
                                        <div class="ui bottom attached segment">
                                            <div id="pie_chart_language_legend">

                                            </div>
                                            <div>
                                                <iframe class="chartjs-hidden-iframe" tabindex="-1"
                                                        style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                <canvas style="width: 260px; display: block; height: 260px;"
                                                        id="pie_chart_language" width="455" height="455"></canvas>
                                            </div>
                                        </div>
                                        <h4 class="ui top attached block header"><i class="pie chart icon"></i>提交频率</h4>
                                        <div class="ui bottom attached segment">
                                            <div class="hidden" id="drawgraphitem">
                                                <div style="margin:auto">
                                                    <canvas id="canvas"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <h4 class="ui top attached block header">
                                            <i class="pie chart icon"></i>
                                            能力雷达图
                                        </h4>
                                        <div class="ui attached segment">
                                            <div style="width:100%;">
                                                <canvas id="canvat"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$status = [];
foreach ($view_userstat as $row) {
    if ($row[0] == 4) {
        $status["AC"] = $row[1];
    } else if ($row[0] == 5) {
        $status["PE"] = $row[1];
    } else if ($row[0] == 6) {
        $status["WA"] = $row[1];
    } else if ($row[0] == 7) {
        $status["TLE"] = $row[1];
    } else if ($row[0] == 8) {
        $status["MLE"] = $row[1];
    } else if ($row[0] == 9) {
        $status["OLE"] = $row[1];
    } else if ($row[0] == 10) {
        $status["RE"] = $row[1];
    } else if ($row[0] == 11) {
        $status["CE"] = $row[1];
    } else if ($row[0] == 13) {
        $status["TR"] = $row[1];
    }
}
?>
<script>
    $(function () {
        var pie = new Chart(document.getElementById('pie_chart').getContext('2d'), {
            type: 'pie',
            data: {
                datasets: [
                    {
                        data: [
                            <?=$status["AC"]?>,
                            <?=$status["WA"]?>,
                            <?=$status["RE"]?>,
                            <?=$status["TLE"]?>,
                            <?=$status["MLE"]?>,
                            <?=$status["OLE"]?>,
                            <?=$status["CE"]?>,
                            <?=$status["PE"]?>
                        ],
                        backgroundColor: [
                            "#66dd66",
                            "#ff6384",
                            "darkorchid",
                            "#ffce56",
                            "#00b5ad",
                            "#35a0e8",
                            "#F7464A",
                            "#D4CCC5"
                        ]
                    }
                ],
                labels: [
                    "Accepted",
                    "Wrong Answer",
                    "Runtime Error",
                    "Time Limit Exceeded",
                    "Memory Limit Exceeded",
                    "Output Limit Exceeded",
                    "Compile Error",
                    "Presentation Error"
                ]
            },
            options: {
                responsive: true,
                legend: {
                    display: false
                },
                legendCallback: function (chart) {
                    var text = [];
                    text.push('<ul style="list-style: none; padding-left: 20px; margin-top: 0; " class="' + chart.id + '-legend">');

                    var data = chart.data;
                    var datasets = data.datasets;
                    var labels = data.labels;

                    if (datasets.length) {
                        for (var i = 0; i < datasets[0].data.length; ++i) {
                            text.push('<li style="font-size: 12px; width: 50%; display: inline-block; color: #666; "><span style="width: 10px; height: 10px; display: inline-block; border-radius: 50%; margin-right: 5px; background-color: ' + datasets[0].backgroundColor[i] + '; "></span>');
                            if (labels[i]) {
                                text.push(labels[i]);
                            }
                            text.push('</li>');
                        }
                    }

                    text.push('</ul>');
                    return text.join('');
                }
            },
        });

        document.getElementById('pie_chart_legend').innerHTML = pie.generateLegend();
        var lang = new Chart(document.getElementById('pie_chart_language').getContext('2d'), {
            type: 'pie',
            data: {
                datasets: [
                    {
                        data: [
                            <?=$aclang[0]+$aclang[21]?>,
                            <?=$aclang[1]+$aclang[20]+$aclang[19]?>,
                            <?=$aclang[3]?>,
                            <?=$aclang[6]?>,
                            <?=$aclang[13]?>,
                            <?=$aclang[14]?>,
                            <?=$aclang[2]?>
                        ],
                        backgroundColor: [
                            "#66dd66",
                            "#ff6384",
                            "darkorchid",
                            "#ffce56",
                            "#00b5ad",
                            "#35a0e8",
                            "#E2EAE9"
                        ]
                    }
                ],
                labels: [
                    "GCC",
                    "G++",
                    "Java",
                    "Python",
                    "Clang",
                    "Clang++",
                    "Pascal"
                ]
            },
            options: {
                responsive: true,
                legend: {
                    display: false
                },
                legendCallback: function (chart) {
                    var text = [];
                    text.push('<ul style="list-style: none; padding-left: 20px; margin-top: 0; " class="' + chart.id + '-legend">');

                    var data = chart.data;
                    var datasets = data.datasets;
                    var labels = data.labels;

                    if (datasets.length) {
                        for (var i = 0; i < datasets[0].data.length; ++i) {
                            text.push('<li style="font-size: 12px; width: 50%; display: inline-block; color: #666; "><span style="width: 10px; height: 10px; display: inline-block; border-radius: 50%; margin-right: 5px; background-color: ' + datasets[0].backgroundColor[i] + '; "></span>');
                            if (labels[i]) {
                                text.push(labels[i]);
                            }
                            text.push('</li>');
                        }
                    }

                    text.push('</ul>');
                    return text.join('');
                }
            },
        });

        document.getElementById('pie_chart_language_legend').innerHTML = lang.generateLegend();
    });
    var config = {
        type: 'line',
        data: {
            labels: [<?php
                $cnt = 0;
                ksort($arr);
                foreach ($arr as $k => $v) {
                    if ($cnt > 0) echo ",";
                    echo "\"" . $k . "\"";
                    $cnt++;
                }
                ?>],
            datasets: [{
                label: "总提交",
                backgroundColor: window.chartColors.red,
                borderColor: window.chartColors.red,
                data: [<?php
                    $cnt = 0;
                    foreach ($arr as $k => $v) {
                        if ($cnt > 0) echo ",";
                        echo $v[0];
                        $cnt++;
                    }
                    ?>],
                fill: false,
            }, {
                label: "正确",
                fill: false,
                backgroundColor: window.chartColors.blue,
                borderColor: window.chartColors.blue,
                data: [<?php
                    $cnt = 0;
                    foreach ($arr as $k => $v) {
                        if ($cnt > 0) echo ",";
                        echo $v[1];
                        $cnt++;
                    }
                    ?>],
            }]
        },
        options: {
            responsive: true,
            title: {
                display: false,
                text: '统计信息'
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: false,
                        labelString: '月份'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: false,
                        labelString: '提交'
                    }
                }]
            }
        }
    };
    var ctx = document.getElementById("canvas").getContext("2d");
    window.myLine = new Chart(ctx, config);
    var color = Chart.helpers.color;
    var config = {
        type: 'radar',
        data: {
            labels: [
                <?php
                $cnt = 0;
                foreach ($ss as $v) {
                    if ($cnt++ > 0) echo ",";
                    echo "\"" . $v['title'] . "\"";
                }
                ?>
            ],
            datasets: [{
                label: "指标",
                backgroundColor: color(window.chartColors.red).alpha(0.2).rgbString(),
                borderColor: window.chartColors.red,
                pointBackgroundColor: window.chartColors.red,
                data: [
                    <?php
                    $cnt = 0;
                    foreach ($ss as $v) {
                        if ($cnt++ > 0) echo ",";
                        echo substr(strval($v['ac_num']/$v['tot']*100),0,min(5,strlen(strval($v['ac_num']/$v['tot']*100))));
                    }
                    ?>
                ]
            }]
        },
        options: {
            legend: {
                position: 'bottom',
                display: false
            },
            title: {
                display: false,
                text: '能力图'
            },
            scale: {
                ticks: {
                    beginAtZero: true
                }
            },
            layout: {
            padding: {
                left: 0,
                right: 0,
                top: 0,
                bottom: 10
            }
        },
        tooltips: {
            mode: 'nearest'
        }
        }
    };

    window.myRadar = new Chart(document.getElementById("canvat"), config);
</script>
<?php include("template/$OJ_TEMPLATE/bottom.php") ?>
</body>
</html>
