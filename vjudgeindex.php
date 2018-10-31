<?php 
require_once("template/$OJ_TEMPLATE/profile.php");
?>
<!DOCTYPE html>
<?php
$homepage="";
?>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <!-- Site Properties -->
    <title><?= $OJ_NAME ?> -- HomePage</title>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <script src="template/<?= $OJ_TEMPLATE ?>/js/countdown.js"></script>
    <script>
        var homepage = true;
        var finished = true;
    </script>
    <style>
        a{
            cursor: pointer;
        }
        .white{
            color:white;
        }
    </style>
</head>
<body>

<!-- Following Menu -->
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>

<!-- Sidebar Menu -->

<!-- Page Contents -->
<div>
    <div class="ui inverted vertical masthead center aligned segment zoomed" id="background">
        <div class="ui container">
            <div class="ui large secondary inverted pointing menu">
            </div>
        </div>

        <div class="ui text container transition">
            
            <h1 class="ui inverted header">
                CUP Virtual Judge
                <a class="ui white top right attached label maintain"></a>
            </h1>
            <!--
            <h2>由 <a class="club white" href="https://github.com/CUP-ACM-Programming-Club" target="_blank">ACM程序设计俱乐部</a> <span class="maintainer">维护开发</span> <a class="support white">点此支持</a><div class='ui flowing popup  hidden'><div class='ui image' style="width:220px"><img src='/img/wechat.png'></div><div class="ui image" style="width:220px"><img src="/img/alipay_2.jpg"></div><div class="ui image" style="width:220px"><img src="/img/alipay.jpg"></div></div></h2>-->
<!--<h4></h4>-->
             <a class="ui huge inverted download button" target="_blank" href='update_log.php'>更新日志</a>
        </div>
        <br>
        <!--
        <div class="ui text shape">
            <div class="sides">
                <div class="ui header side text inverted active typed-element"></div>
            </div>
        </div>
        <br><br>-->
        <div class="packer hidden transition">
            <a href="/software/CUP_Online_Judge_Problem_Creator-darwin-x64.zip" class="ui large button download inverted">
              <i class="icon apple"></i>
              macOS 10.9或以上
            </a>
            <a href="/software/CUP_Online_Judge_Problem_Creator-win32-x64.zip" class="ui large button download inverted">
              <i class="icon windows"></i>
              Windows 7(64位)或以上
            </a>
            <a href="/software/CUP_Online_Judge_Problem_Creator-linux-x64.zip" class="ui large button download inverted">
              <i class="icon linux"></i>
              Linux(x64)
            </a>
            <h4></h4>
        </div>
        <a class="vultr" href="https://www.vultr.com/?ref=7250019" target="_blank"><img src="./image/vultr.png" class="ui small image" style="display:inline-block"></a>
        <!--<div class="ui huge primary button" onclick="location.href='<?php if (isset($_SESSION['user_id'])) echo "problemset.php"; else echo "newloginpage.php"; ?>'"><?php if (!isset($_SESSION['user_id'])) echo "Login"; else echo "Get Started"; ?><i class="right arrow icon"></i></div>-->
    </div>

</div>

<div class="ui vertical stripe segment">
    <div class="ui middle aligned stackable grid container">
        <div class="row">
            <div class="eight wide column">
                <h3 class="ui header">推荐使用<a href="https://www.jetbrains.com" target="_blank">JetBrains系列IDE套件</a>完成编程
                </h3>
                <p>ACM/ICPC World Final 指定IDE:<b>CLion,PyCharm,Intellij IDEA</b><br><br>基于CMake与GNU Compile
                    Collection的CLion,业界推荐的Intellij Idea,简单容易上手的PyCharm，这些跨平台IDE将会减少许多做题过程中不必要的麻烦，更加集中精力打出AK</p>
                <h3 class="ui header">推荐使用高效的<a class="chrome" target="_blank"
                                                href="software/63.0.3239.84_chrome_installer.exe">Chrome浏览器</a>使用评测系统
                </h3>
                <p>一个优秀的浏览器可以更加完美高效的绘制页面，提升使用的舒适度。基于V8引擎的Chrome浏览器能够让每一个题目的启动和提交都能行云流水。</p>
            </div>
            <div class="six wide right floated column">
                <img src="/template/<?= $OJ_TEMPLATE ?>/picture/jetbrains.png"
                     class="ui large borderless rounded image">
            </div>
        </div>
    </div>
</div>

<!--
    <div class="ui vertical stripe quote segment">
        <div class="ui equal width stackable internally celled grid">
            <div class="center aligned row">
                <div class="column">
                    <h3>"What a Company"</h3>
                    <p>That is what they all say about us</p>
                </div>
                <div class="column">
                    <h3>"I shouldn't have gone with their competitor."</h3>
                    <p>
                        <img src="assets/images/avatar/nan.jpg" class="ui avatar image"> <b>Nan</b> Chief Fun Officer Acme Toys
                    </p>
                </div>
            </div>
        </div>
    </div>-->

<div class="ui vertical stripe segment">
    <div class="ui text container">
        <h3 class="ui header">ACM/ICPC</h3>
        <p>ACM国际大学生程序设计竞赛（英语：ACM International Collegiate Programming Contest,
            ICPC）是由美国电脑协会（ACM）主办的，一项旨在展示大学生创新能力、团队精神和在压力下编写程序、分析和解决问题能力的年度竞赛。经过30多年的发展，ACM国际大学生程序设计竞赛已经发展成为最具影响力的大学生计算机竞赛。（Wikipedia）</p>
        <a class="ui large button" href="icpc.php" target="_blank">More Information</a>
        <h4 class="ui horizontal header divider">
            <a href="#">Developer</a>
        </h4>
        <h3 class="ui header">平台开发</h3>
        <h4 class="ui content">技术栈:Linux + C/C++ + MySQL + PHP + Node.js + ExpressJS + Apache</h4>
        <h4 class="ui content">Vue.js/Backbone.js(Developing)</h4>
        <a class="ui large disabled button" href="https://coding.net/u/RyanLee/p/CUPOJ" target="_blank">部分代码暂未开源</a>
    </div>
</div>

<?php include("template/$OJ_TEMPLATE/bottom.php"); ?>

</div>

</body>
<script>

    function getRandomIntInclusive(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min; //The maximum is inclusive and the minimum is inclusive 
    }
    $(".ui.borderless.network.secondary.menu").addClass("inverted");

    $(document).ready(function () {
        window.picid = 'bg' + getRandomIntInclusive(14, 18);
        $("#background")
        .addClass(window.picid)
         //   .addClass(picid)
            .removeClass('zoomed')
        ;
        /*
        $(".masthead.segment").ready(
            function () {
                $(".pointing.menu").transition({
                    animation:'fade up',
                    duration:'1s',
                });
                $(".text.container").transition({
                    animation:'fade up',
                    duration:'1s'
                });
            }
        );
        */
        //  setInterval(function(){
        //$('.shape').shape('vertical flip right');
        //    },5000);
        $('.image').visibility({
            type: 'image',
            transition: 'vertical flip in',
            duration: 500
        });
        /*
        window.backpic = setInterval(function () {
                $("#background").addClass('zoomed');

            setTimeout(function () {
                    $("#background")
                        .removeClass(window.picid)
                        .addClass((window.picid = 'bg' + getRandomIntInclusive(14, 18)))
                        .removeClass('zoomed')
                    ;
                }, 1900);

            }
            , 15000)*/
            
    });
    $('.club').attr("data-title", "点击访问GitHub")
        .popup({
            position: 'top center',
            on: 'hover'
        })
    ;
    $('.maintainer').attr("data-html","<div class='header'>Ryan Lee</div><div class='content'>Maintainer & Developer</div>")
    .popup({
        position:'top center',
        on:'hover'
    });
    <?php
    $result=$database->query("select mtime as t,msg from maintain_info order by t desc limit 1")->fetchAll();
    $time=$result[0]['t'];
    $msg=$result[0]['msg'];
    ?>
    $('.maintain').html("Version:<?=$time?>").attr("data-html", "<div class='header'>"+"升级维护内容"+"</div><div class='content'><?=str_replace("\n","",str_replace("&gt;",">",str_replace("&lt;","<",htmlentities($msg,ENT_COMPAT))))?></div>")
        .popup({
            position: 'top center',
            on: 'hover'
        })
    ;

    $('.support')
                .popup({
                    position:'bottom center',
                    inline:true,
                    on:'hover',
                    hoverable:true
                });
    $('.chrome').popup({
       title:"点击下载" ,
       position:'top center',
       on:'hover'
    });
    $(".generator").on("click",function(){
        $(".packer").transition("fade down");
        $(".vultr").transition("fade up");
    })
</script>
</html>
