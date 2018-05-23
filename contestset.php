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
    <style>
        .admin_hide{
            opacity:0.3;
        }

    </style>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="ui padding pusher container">
    <!-- Main component for a primary marketing message or call to action -->
    <h2 class="ui dividing header">
            Contest Set
        </h2>
        <!--<form method=post action=contest.php >
                <?php echo $MSG_SEARCH;?>:&nbsp;<div class="ui action input">
                    <input name=keyword type=text >
                </div>
                &nbsp;&nbsp;
                <input class="ui teal button" type=submit value="查询">
            </form>ServerTime:<span id=nowdate></span>-->
            <div class="ui top attached tabular menu">
                <a class="active item">测验</a>
            </div>
 
            <div class="ui bottom attached segment">
                <div class="ui icon message">
  <i class="notched circle loading icon"></i>
  <div class="content">
    <div class="header">
      当前服务器时间
    </div>
    <p class="server_time"></p>
  </div>
</div>
            <table class='ui padded celled unstackable selectable table' width=90%>
                <thead>
                <tr class=toprow align=center><th width=10%>ID<th width=40%>Name<th width=30%>Status
                <?php if(isset($_SESSION['administrator'])){ ?>
                <th width=10%>Available
                <?php } ?>
                <th width=10%>Private<th>Creator</tr>
                </thead>
                <tbody>
                <?php
                $cnt=0;
                $count=0;
                foreach($view_contest as $row){
                    $str=$admin_pri[$count]?"active":"";
                    $running="";
                    if(isset($row["running"]))
                    {
                        $running="positive";
                        unset($row["running"]);
                    }
                    if ($cnt)
                        echo "<tr class='oddrow $str $running'>";
                    else
                        echo "<tr class='evenrow $str $running'>";
                    foreach($row as $table_cell){
                        echo "<td style='text-align: center'>";
                        echo "\t".$table_cell;
                        echo "</td>";
                    }
                    echo "</tr>";
                    $cnt=1-$cnt;
                    ++$count;
                }
                ?>
                </tbody>
            </table></div>
</div> <!-- /container -->
<?php include("template/$OJ_TEMPLATE/bottom.php");?>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script>
    var diff=new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
    function f(){
        var text;
        if(!window.timeclock)
            text = $(".server_time").text();
        var time = new Date(text||window.timeclock);
        window.timeclock = time;
        time.setSeconds(time.getSeconds()+1);
        $(".server_time").text(time.toLocaleString());
    }
   // f();
    //setInterval(f,1000);
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
        $(".server_time").text(n)
//alert(n);
       // document.getElementById('nowdate').innerHTML=n;
        setTimeout("clock()",1000);
    }
    clock();
    <?php if(isset($_SESSION['administrator'])){ ?>
    $(".ui.padded.table").popup({
        title:"管理员视图",
        content:"白色背景竞赛属于普通用户可见竞赛，灰色背景竞赛为不可见竞赛,绿色背景竞赛为正在进行中的竞赛",
        position:"top center",
        boundary:'body'
    })
    <?php }?>
    $(".visible.tag").popup({
        content:"点击可切换显示隐藏"
    });
    $(".private.tag").popup({
        content:"点击可切换私有公有属性，公有属性不限制用户访问，私有属性需输入密码或列入列表才允许访问"
    })
    $('.toggle.checkbox')
        .checkbox()
        .first().checkbox({
        onChecked: function () {
            timeago(null, 'zh_CN').render($('.need_to_be_rendered'));
        },
        onUnchecked: function () {
            timeago.cancel();
            var render = document.querySelectorAll(".need_to_be_rendered");
            for (var i = 0; i < render.length; i++) {
                render[i].innerHTML = render[i].getAttribute("datetime");
            }
        }
    })
    ;

</script>
</body>
</html>
