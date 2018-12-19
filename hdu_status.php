<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title><?php echo $OJ_NAME ?> -- Status</title>
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <script type="text/javascript" src="include/jquery.tablesorter.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
<div class="ui container padding">

    <!-- Main component for a primary marketing message or call to action -->
    <h2 class="ui dividing header">Status</h2>
    <div>
        <div class="ui top attached tabular menu">
            <a class="active item" id="submitstatus">提交状态</a>
        </div>
        <div class="ui bottom attached segment">
            <div align=center class="input-append">
                <form id=simform class="ui form segment" action="hdu_status.php" method="get">
                    <div class="four fields">
                        <div class="field">
                            <label> <?php echo $MSG_PROBLEM_ID ?></label>
                            <input class="form-control" type=text size=4 name=problem_id
                                   value='<?php echo htmlspecialchars($problem_id, ENT_QUOTES) ?>'>
                        </div>
                        <div class="field">
                            <label><?php echo $MSG_USER ?></label>
                            <input class="form-control" type=text size=4 name=user_id
                                   value='<?php echo htmlspecialchars($user_id, ENT_QUOTES) ?>'>
                            <?php if (isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>"; ?>
                        </div>
                        <div class="field">
                            <label><?php echo $MSG_LANG ?></label>
                            <select class="ui search dropdown" size="1" name="language" data-toggle='select'>
                                <?php if (isset($_GET['language'])) $language = intval($_GET['language']);
                                else $language = -1;
                                if ($language < 0 || $language >= count($language_name)) $language = -1;
                                if ($language == -1) echo "<option value='-1' selected>All</option>";
                                else echo "<option value='-1'>All</option>";
                                $i = 0;
                                foreach ($language_name as $lang) {
                                    if ($i == $language)
                                        echo "<option value=$i selected>$language_name[$i]</option>";
                                    else
                                        echo "<option value=$i>$language_name[$i]</option>";
                                    $i++;
                                }
                                ?>
                            </select>
                        </div>
                        <div class="field">
                            <label><?php echo $MSG_RESULT ?></label>
                            <select class="ui search dropdown" size="1" name="jresult">
                                <?php if (isset($_GET['jresult'])) $jresult_get = intval($_GET['jresult']);
                                else $jresult_get = -1;
                                if ($jresult_get >= 12 || $jresult_get < 0) $jresult_get = -1;
                                /*if ($jresult_get!=-1){
                                $sql=$sql."AND `result`='".strval($jresult_get)."' ";
                                $str2=$str2."&jresult=".strval($jresult_get);
                                }*/
                                if ($jresult_get == -1) echo "<option value='-1' selected>All</option>";
                                else echo "<option value='-1'>All</option>";
                                for ($j = 0; $j < 12; $j++) {
                                    $i = ($j + 4) % 12;
                                    if ($i == $jresult_get) echo "<option value='" . strval($jresult_get) . "' selected>" . $jresult[$i] . "</option>";
                                    else echo "<option value='" . strval($i) . "'>" . $jresult[$i] . "</option>";
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="center aligned">
                        <button class="ui labeled icon mini button" type="submit"><i
                                class="search icon"></i><?= $MSG_SEARCH ?></button>
                    </div>
                </form>
            </div>
            <br>
            <div id=center>
                <table id=result-tab class="ui padded selectable selectable table hidden" align=center width=80%>
                    <thead>
                    <tr class='toprow'>
                        <th><?php echo $MSG_RUNID ?>
                        <th><?php echo $MSG_USER ?>
                        <th><?php echo $MSG_PROBLEM ?>
                        <th width="10%"><?php echo $MSG_RESULT ?>
                        <?php if(isset($_SESSION['administrator'])) {?>
                        <th>CONTEST_ID
                        <?php } ?>
                        <th class='hidden-xs'><?php echo $MSG_MEMORY;
                            ?>
                        <th class='hidden-xs'><?php echo $MSG_TIME ?>
                        <th class='hidden-xs'><?php echo $MSG_LANG ?>
                        <th class='hidden-xs'><?php echo $MSG_CODE_LENGTH ?>
                        <th><?php echo $MSG_SUBMIT_TIME ?>
                        <th class='hidden-xs'><?php echo $MSG_JUDGER ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $cnt = 0;
                    foreach ($view_status as $row) {
                        if ($cnt)
                            echo "<tr class='oddrow'>";
                        else
                            echo "<tr class='evenrow'>";
                        $i = 0;
                        foreach ($row as $table_cell) {
                            if ($i > 3 && $i != 8)
                                echo "<td class='hidden-xs'>";
                            else if ($i == 8)
                                echo "<td class='need_to_be_rendered' datetime='$table_cell'>";
                            else
                                echo "<td>";
                            echo $table_cell;
                            echo "</td>";
                            $i++;
                        }
                        echo "</tr>\n";
                        $cnt = 1 - $cnt;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div id=center>
                <?php echo "[<a href=hdu_status.php?" . $str2 . ">Top</a>]&nbsp;&nbsp;";
                if (isset($_GET['prevtop']))
                    echo "[<a href=hdu_status.php?" . $str2 . "&top=" . intval($_GET['prevtop']) . ">Previous Page</a>]&nbsp;&nbsp;";
                else
                    echo "[<a href=hdu_status.php?" . $str2 . "&top=" . ($top + 20) . ">Previous Page</a>]&nbsp;&nbsp;";
                echo "[<a href=hdu_status.php?" . $str2 . "&top=" . $bottom . "&prevtop=$top>Next Page</a>]";
                ?>
            </div>

        </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <script>var i = 0;
        var judge_result = [<?php
            foreach ($judge_result as $result) {
                echo "'$result',";
            }
            ?>''];

        var judge_color = [<?php
            foreach ($judge_color as $result) {
                echo "'$result',";
            }
            ?>''];
    </script>
    <script>
        $('.toggle.checkbox')
            .checkbox()
            .first().checkbox({
            onChecked: function () {
                timeago(null, 'zh_CN').render($('.need_to_be_rendered'));
            },
            onUnchecked: function () {
                timeago.cancel();
                var render = document.querySelectorAll(".need_to_be_rendered");
                render.forEach(function (e) {
                    e.innerHTML = e.getAttribute("datetime");
                });
            }
        })
        ;
        $(".rejudge").on('click',function(data){
            console.log(data);
        })
    </script>
    <script src="template/<?php echo $OJ_TEMPLATE ?>/auto_refresh_hdu.js?ver=1.0"></script>
    </div>
    <?php include("template/$OJ_TEMPLATE/bottom.php"); ?>
</body>
</html>
