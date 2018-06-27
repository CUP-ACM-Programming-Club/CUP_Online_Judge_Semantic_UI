<!DOCTYPE html>
<?php 
$pass_st=["negative","","positive"];
?>
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
<div class="pusher">

    <!-- Main component for a primary marketing message or call to action -->
    <div class="padding ui container">
        <h2 class="ui dividing header">
            Contest Problem Set
        </h2>
        <div class="ui grid">
            <div class="row">
                <div class="eleven wide column">
                    <table id='problemset' class='ui padded celled selectable table'  width='90%'>
                <thead>
                <tr align=center class='toprow'>
                    <th style="cursor:hand" onclick="sortTable('problemset', 1, 'int');" width="13%" ><?php echo $MSG_PROBLEM_ID?>
                    <th width='60%'><?php echo $MSG_TITLE?></th>
                    <th style="cursor:hand" onclick="sortTable('problemset', 4, 'int');" width='8%'>
                        正确</th>
                    <th style="cursor:hand" onclick="sortTable('problemset', 5, 'int');" width='8%'><?php echo $MSG_SUBMIT?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $cnt=0;
                foreach($view_problemset as $row){
                     $count=0;
                    if ($cnt)
                        echo "<tr class='oddrow ".$pass_st[intval($row[0])+1]."'>";
                    else
                        echo "<tr class='evenrow ".$pass_st[intval($row[0])+1]."'>";
                    foreach($row as $table_cell){
                        if($count++==0)continue;
                        echo "<td>";
                        echo "\t".$table_cell;
                        echo "</td>";
                    }
                    echo "</tr>";
                    $cnt=1-$cnt;
                }
                ?>
                </tbody>
            </table>
                </div>
                <div class="five wide column">
            <div>
                <div class="ui raised segment">
                    <h1></h1>
                    <h2 class="ui header" style="text-align:center"> <i class="star outline icon"></i>Contest&nbsp;<?php echo $view_cid?></h2>
  <h2 class="ui header" style="text-align:center"><?php echo $view_title ?></h2>
  <center>
 <p><?php echo $view_description?></p>
 <!--2017年 中国石油大学（北京）团委 不发蓝桥杯奖学金-->
 </center>
 <center>Start Time <?php echo $view_start_time?>
<br> &nbsp;Now&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id=nowdate ><?php echo date("Y-m-d H:i:s")?></span>
<br>End Time&nbsp;&nbsp;<?php echo $view_end_time?>
</center>
<div class="ui top right attached <?php echo $view_private=='0'?"green":"red" ?>  label"><?php
                if ($view_private=='0')
                    echo "Public";
                else
                    echo "Private";
                ?></div>
<div class="ui top left attached <?php echo $now>$end_time?"red":$now<$start_time?"grey":"green" ?> label"><?php
                if ($now>$end_time)
                    echo "<span class=red>Ended</span>";
                else if ($now<$start_time)
                    echo "<span class=red>Not Started</span>";
                else
                    echo "<span class=red>Running</span>";
                ?></div>
                <center>
                <div class="row padding">
                <div class="ui buttons mini">
                <?php if(isset($_SESSION['administrator'])){ ?>
                <a class="ui button orange" href='copystatus.php?cid=<?php echo $view_cid?>'>判重</a>
                <?php } ?>
                </div>
                </div>
                </center>
</div>
                <br>
                
            </div>
                </div>
            </div>
        </div>
            
    </div>

</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script src="include/sortTable.js"></script>
<script>
    var diff=new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
    //alert(diff);
    function clock()
    {
        var x,h,m,s,n,xingqi,y,mon,d;
        var x = new Date(new Date().getTime()+diff);
        y = x.getYear()+1900;
        if (y>3000) y-=1900;
        mon = x.getMonth()+1;
        d = x.getDate();
        xingqi = x.getDay();
        h=x.getHours();
        m=x.getMinutes();
        s=x.getSeconds();
        n=y+"-"+mon+"-"+d+" "+(h>=10?h:"0"+h)+":"+(m>=10?m:"0"+m)+":"+(s>=10?s:"0"+s);
//alert(n);
        document.getElementById('nowdate').innerHTML=n;
        setTimeout("clock()",1000);
    }
    clock();
</script>
<?php include("template/$OJ_TEMPLATE/bottom.php");?>
</body>
</html>
