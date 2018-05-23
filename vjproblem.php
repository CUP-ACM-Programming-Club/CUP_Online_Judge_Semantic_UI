<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $OJ_NAME?></title>
    <?php include("template/$OJ_TEMPLATE/css.php");?>
    <?php include("template/$OJ_TEMPLATE/js.php");?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div>

    <!-- Main component for a primary marketing message or call to action -->
    <div class="ui container padding">
        <center>
        <?php if ($pr_flag) { ?>
                        <h2><?= $row->source ?> <?= $pid ?>: <?= $row->title ?></h2>
                    <?php } else {
                        $PID = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                        echo "<h2>$MSG_PROBLEM $PID[$cpid]: $row->title</h2>";
                    } ?>
                    <?php
        echo "<div class='ui labels'>";
        echo "<li class='ui label red'>$MSG_Time_Limit:".$row->time_limit." 秒 </li>";
        echo "<li class='ui label red'>$MSG_Memory_Limit: ".$row->memory_limit." MB</li> ";
        if ($row->spj) echo "<li class='ui label orange'>Special Judge</li>";
        echo "<li class='ui label grey'>$MSG_SUBMIT: ".$row->submit."</li>";
        echo "<li class='ui label green'>$MSG_SOVLED:".$row->accepted." </li>";
        echo "</div>";
        echo "<div class='ui buttons'>";
        if ($pr_flag){
            echo "<a href='".strtolower($oj_signal)."submitpage.php?".$_SERVER['QUERY_STRING']."' class='ui button blue'>$MSG_SUBMIT</a>&nbsp;";
        }else if(isset($_GET['tid']))
        {
            echo "<a class='ui button blue' href='".strtolower($oj_signal)."submitpage.php?".$_SERVER['QUERY_STRING']."'>$MSG_SUBMIT</a>&nbsp;";
        }
        else{
            echo "<a class='ui button blue' href='".strtolower($oj_signal)."submitpage.php?".$_SERVER['QUERY_STRING']."'>$MSG_SUBMIT</a>&nbsp;";
        }
        echo "<a href='problemstatus.php?id=".$row->problem_id."' class='ui button orange'>$MSG_STATUS</a>&nbsp;";
            echo "<a href='".strtolower($oj_signal)."submitpage.php?".$_SERVER['QUERY_STRING']."' class='ui button black'>切换双屏</a>&nbsp;";
        //echo "<a class='btn btn-lg btn-default' href='bbs.php?pid=".$row->problem_id."$ucid'>$MSG_BBS</a>&nbsp;";
        if(isset($_SESSION['administrator'])){
            require_once("include/set_get_key.php");
            ?>
            <?php
            if(isset($_GET['cid']))
            {
                $PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                ?>
                <a class="ui button green" href="status.php?problem_id=<?php echo $PID[$pid] ?>&cid=<?php echo $cid ?>&jresult=4">AC代码</a>
            <?php }
            else{
                ?>
                <a class="ui button green" href="status.php?problem_id=<?php echo $id ?>&jresult=4">AC代码</a>
            <?php } ?>
            <a class='ui button violet' href="admin/problem_edit.php?id=<?php echo $id?>&getkey=<?php echo $_SESSION['getkey']?>" >Edit</a>
            <a class='ui button purple' href="admin/quixplorer/index.php?action=list&dir=<?php echo $row->problem_id?>&order=name&srt=yes" >TestData</a>
            <?php
        }
        echo "</div>";
        echo "</center>";
        echo "<h4 class='ui top attached block header hidden'>$MSG_Description</h4><div class='ui bottom attached segment hidden'>".$row->description."</div>";
        echo "<h4 class='ui top attached block header hidden'>$MSG_Input</h4><div class='ui bottom attached segment hidden'>".$row->input."</div>";
        echo "<h4 class='ui top attached block header hidden'>$MSG_Output</h4><div class='ui bottom attached segment hidden'>".$row->output."</div>";
        $sinput=str_replace("<","&lt;",$row->sample_input);
        $sinput=str_replace(">","&gt;",$sinput);
        $soutput=str_replace("<","&lt;",$row->sample_output);
        $soutput=str_replace(">","&gt;",$soutput);
        $sinput=html_entity_decode($sinput);
        $soutput=html_entity_decode($soutput);

        if(strlen($sinput)) {
            echo "<h4 class='ui top attached block header hidden'>$MSG_Sample_Input</h4>
<pre class='ui bottom attached segment hidden'><span class=sampledata>".($sinput)."</span></pre>";
        }
        if(strlen($soutput)){
            echo "<h4 class='ui top attached block header hidden'>$MSG_Sample_Output</h4>
<pre class='ui bottom attached segment hidden'><span class=sampledata>".($soutput)."</span></pre>";
        }
        if ($pr_flag||true)
            echo "<h4 class='ui top attached block header hidden'>$MSG_HINT</h4>
<div class='ui bottom attached segment hidden'><p>".nl2br($row->hint)."</p></div>";
        if ($pr_flag)
            echo "<h4 class='ui top attached block header hidden'>$MSG_Source</h4>
<div class='ui bottom attached segment hidden'><p><a href='vjudge.php?search=".$row->source."'>".nl2br($row->source)."</a></p></div>";
        ?>
    </div>

</div> <!-- /container -->
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<?php include("template/$OJ_TEMPLATE/bottom.php");?>
</body>
</html>
