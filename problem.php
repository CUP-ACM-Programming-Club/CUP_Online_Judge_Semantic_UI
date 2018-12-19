<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $OJ_NAME?></title>
    <?php include("template/$OJ_TEMPLATE/css.php");?>
    <?php include("template/$OJ_TEMPLATE/extra_css.php") ?>
    <?php include("template/$OJ_TEMPLATE/js.php");?>
    <?php include("template/$OJ_TEMPLATE/extra_js.php") ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="ui container padding">
    <center><h2>
        <?php
        $sinput=str_replace("<","&lt;",$row['sample_input']);
        $sinput=str_replace(">","&gt;",$sinput);
        $soutput=str_replace("<","&lt;",$row['sample_output']);
        $soutput=str_replace(">","&gt;",$soutput);
        if ($pr_flag){
            echo "$id: ".$row['title'];
        }else{
            $PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $id=$row['problem_id'];
            echo "$MSG_PROBLEM $PID[$pid]: ".$row['title'];
        }
        ?>
        </h2>
        <div class='ui labels'>
        <li class='ui label red'><?=$MSG_Time_Limit?>:<?=$row['time_limit']?> 秒 </li>
        <li class='ui label red'><?=$MSG_Memory_Limit?>: <?=$row['memory_limit']?> MB</li>
        <?php
        if ($row['spj']){ ?>
        <li class='ui label orange'>Special Judge</li>
        <?php } ?>
        <li class='ui label grey'><?=$MSG_SUBMIT?>: <?=$row['submit']?></li>
        <li class='ui label green'><?=$MSG_SOVLED?>:<?=$row['accepted']?></li>
        </div>
        <div class='ui buttons'>
        <?php
        if ($pr_flag){
            ?>
            <a href='newsubmitpage.php?id=<?=$id?>' class='ui button blue'><?=$MSG_SUBMIT?></a>&nbsp;
            <?php
        }else if(isset($_GET['tid']))
        {
            ?>
            <a class='ui button blue' href='newsubmitpage.php?tid=<?=$tid?>&pid=<?=$pid?>&langmask=<?=$langmask?>&js'><?=$MSG_SUBMIT?></a>&nbsp;
            <?php
        }
        else{
            ?>
            <a class='ui button blue' href='newsubmitpage.php?cid=<?=$cid?>&pid=<?=$pid?>&langmask=<?=$langmask?>&js'><?=$MSG_SUBMIT?></a>&nbsp;
            <?php
        }
        ?>
        <a href='problemstatus.php?id=<?=$row['problem_id']?>' class='ui button orange'><?=$MSG_STATUS?></a>&nbsp;
        <a href='newsubmitpage.php?<?=$_SERVER['QUERY_STRING']?>' class='ui button black'>切换双屏</a>&nbsp;
        <?php
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
            <a class='ui button purple' href="admin/quixplorer/index.php?action=list&dir=<?php echo $row['problem_id']?>&order=name&srt=yes" >TestData</a>
            <?php
        }
        ?>
        </div>
        </center>
        <h4 class='ui top attached block header hidden'><?=$MSG_Description?></h4><div class='ui bottom attached segment hidden'><?=$row['description']?></div>
        <h4 class='ui top attached block header hidden'><?=$MSG_Input?></h4><div class='ui bottom attached segment hidden'><?=$row['input']?></div>
        <h4 class='ui top attached block header hidden'><?=$MSG_Output?></h4><div class='ui bottom attached segment hidden'><?=$row['output']?></div>
        <h4 class='ui top attached block header hidden'><?=$MSG_Sample_Input?></h4>
<pre class='ui bottom attached segment hidden'><span class=sampledata><?=($sinput)?></span></pre>
            <h4 class='ui top attached block header hidden'><?=$MSG_Sample_Output?></h4>
<pre class='ui bottom attached segment hidden'><span class=sampledata><?=($soutput)?></span></pre>
           <h4 class='ui top attached block header hidden'><?=$MSG_HINT?></h4>
<div class='ui bottom attached segment hidden'><p><?=nl2br($row['hint'])?></p></div>
            <h4 class='ui top attached block header hidden'><?=$MSG_Source?></h4>
<div class='ui bottom attached segment hidden'><p><a href='problemset.php?search=<?=$row['source']?>'><?=nl2br($row['source'])?></a></p></div>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<?php include("template/$OJ_TEMPLATE/bottom.php");?>
</body>
</html>
