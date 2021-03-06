<?php $homepage = ""; 
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
    <meta name="viewport" content="width=1200">
    <?php include("template/semantic-ui/css.php"); ?>
    <script>
        var homepage = true;
        var finished = false;
    </script>
    <!-- Site Properties -->
    <title><?= $OJ_NAME ?> -- HomePage</title>
    <?php include("template/semantic-ui/js.php"); ?>
    <script src="template/semantic-ui/js/countdown.js"></script>
    <style>
        a{
            cursor: pointer;
        }
        .white{
            color:white;
        }
        .unvisible{
            display: none;
        }
        .ml14 {
  font-weight: 200;
  font-size: 3.2em;
}

.ml14 .text-wrapper {
  position: relative;
  display: inline-block;
  padding-top: 0.1em;
  padding-right: 0.05em;
  padding-bottom: 0.15em;
}

.ml14 .line {
  opacity: 0;
  position: absolute;
  left: 0;
  height: 2px;
  width: 100%;
  background-color: #fff;
  transform-origin: 100% 100%;
  bottom: 0;
}
#myVideo {
    position: fixed;
    right: 0;
    bottom: 0;
    min-width: 100%; 
    height: 100%;
    width:100%;
    background: white;
    margin:auto;
    min-height: 100%;
        -ms-transition:

            opacity 1s cubic-bezier(0.680, -0.550, 0.265, 1.4) 0s
;
    -moz-transition:

            opacity 1s cubic-bezier(0.680, -0.550, 0.265, 1.4) 0s
;
    -webkit-transition:

            opacity 1s cubic-bezier(0.680, -0.550, 0.265, 1.4) 0s
;
    transition:

            opacity 1.5s cubic-bezier(0.680, -0.550, 0.265, 1.4) 0s
;
}
.ml14 .letter {
  display: inline-block;
  line-height: 1em;
}
    </style>
</head>
<body>
<!-- Following Menu -->
<?php include("template/semantic-ui/nav.php"); ?>

<!-- Sidebar Menu -->

<!-- Page Contents -->
<div>
    <div class="ui inverted vertical masthead center aligned segment gr5" id="background">
        <video autoplay muted id="myVideo" style="display:none">
  <source src="/video/icpc.mp4" type="video/mp4">
</video>
<div class="unvisible" id="main_container">
        <div class="ui container">
            <div class="ui large secondary inverted pointing menu">
            </div>
        </div>
        <div class="ui grid" id="main_masthead" style="transform: translateX(25%)">
        <div id="left" class="ui text container transition main title eight wide column">
            <h1 class="ui inverted header ml14">
  <span class="text-wrapper">
    <span class="letters" data-content="CUP Online Judge"></span>
  </span>
  
</h1>
<div class="column buttonset">
    
<a href="icpc.php" target="_blank" class="ui inverted large button download basic">
              <i class="newspaper outline icon"></i>
              关于ICPC
            </a>
            <a href="fame.php" target="_blank" class="ui inverted large button download basic">
              <i class="chess queen icon"></i>
              Hall of Fame(NEW!)
            </a>
            
            </div>
                    <br>

            <div class="column">
            <a class="ui white basic label maintain" target="_blank" href='update_log.php'></a><!-- Place this tag where you want the button to render. -->
            <iframe class="github_button" src="https://ghbtns.com/github-btn.html?user=CUP-ACM-Programming-Club&repo=CUP-Online-Judge-Express&type=star&count=true" frameborder="0" scrolling="0" width="100px" height="30px"></iframe>
            <a class="github_button" href="https://travis-ci.com/ryanlee2014/CUP-Online-Judge-Express" target="_blank"><img src="https://travis-ci.com/ryanlee2014/CUP-Online-Judge-Express.svg?branch=master" style="vertical-align: middle;"></a>
            </div>
            <br><a class="vultr" href="https://www.vultr.com/?ref=7250019" target="_blank"><img src="./image/vultr.png" class="ui small image main title" style="display:inline-block"></a>
<!--<h4></h4>-->
             <!--<a class="ui huge inverted download button" href="cprogrammingcontest.php">查看复赛情况</a>-->
        </div>
        <div id="right" class="ui text container transition main title eight wide column" style="opacity: 0">
            <div class="ui grid">
                <div class="two wide column"></div>
                <div class="twelve wide column">
                    <div class="ui warning message" style="margin-top:15em">
                        <!--<i class="close icon"></i>-->
                        <div class="header">维护提示</div>
                        <p style="font-size:1em">为保证考试过程中系统的稳定，<br>本平台于2019年1月1日至1月4日将停止一般用户的访问。</p>
                        <!--
                        <ul class="list">
    <li>考试/测验请访问http://acm.cup.edu.cn</li>
    <li>普通用户/IPv6用户请访问https://www.cupacm.com</li>
    <li>外网用户请访问https://oj.cupacm.com</li>
  </ul>
                        -->
                        </div>
                    <div class="row">
                        
                    </div>
                </div>
                <div class="two wide column">
                    
                </div>
            </div>
            
        </div>
        </div>
        <div class="ui vertical divider inverted" id="divider" style="opacity: 0">
    
  </div>
        <br>
        <!--
        <div class="ui text shape">
            <div class="sides">
                <div class="ui header side text inverted active typed-element"></div>
            </div>
        </div>
        <br><br>-->
        
        </div>
        <!--<div class="ui huge primary button" onclick="location.href='<?php if (isset($_SESSION['user_id'])) echo "problemset.php"; else echo "newloginpage.php"; ?>'"><?php if (!isset($_SESSION['user_id'])) echo "Login"; else echo "Get Started"; ?><i class="right arrow icon"></i></div>-->
    </div>

</div>

<div class="ui vertical stripe segment">
    <div class="ui middle aligned stackable grid container">
        <div class="row">
            <div class="eight wide column">
                <h3 class="ui header">推荐使用<a href="https://www.jetbrains.com" target="_blank">JetBrains系列IDE套件</a>完成编程
                </h3>
                <p>ICPC World Final 指定IDE:<b>CLion,PyCharm,Intellij IDEA</b><br><br>基于CMake与GNU Compile
                    Collection的CLion,业界推荐的Intellij Idea,简单容易上手的PyCharm，这些跨平台IDE将会减少许多做题过程中不必要的麻烦，更加集中精力打出AK</p>
                <h3 class="ui header">推荐使用高效的<a class="chrome" target="_blank"
                                                href="software/63.0.3239.84_chrome_installer.exe">Chrome浏览器</a>使用评测系统
                </h3>
                <p>一个优秀的浏览器可以更加完美高效的绘制页面，提升使用的舒适度。基于V8引擎的Chrome浏览器能够让每一个题目的启动和提交都能行云流水。</p>
            </div>
            <div class="six wide right floated column">
                <img src="/template/semantic-ui/picture/jetbrains.png"
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
        <h3 class="ui header">ICPC</h3>
        <p>
国际大学生程序设计竞赛（英语：International Collegiate Programming Contest, ICPC）是一项旨在展示大学生创新能力、团队精神和在压力下编写程序、分析和解决问题能力的年度竞赛。经过30多年的发展，国际大学生程序设计竞赛已经发展成为最具影响力的大学生计算机竞赛。赛事之前仅由IBM公司赞助，2017年新增JetBrains公司赞助, 2018年起，美国计算机协会（ACM）不再赞助ICPC。（Wikipedia）</p>
        <a class="ui large button" href="icpc.php" target="_blank">More Information</a>
        <h4 class="ui horizontal header divider">
            <a href="#">Developer</a>
        </h4>
        <h3 class="ui header">平台开发</h3>
        <h4>由 <a class="club" href="https://github.com/CUP-ACM-Programming-Club" target="_blank">ACM程序设计俱乐部</a> <span class="maintainer">维护开发</span> <a class="support">点此支持</a><div class='ui flowing popup  hidden'><div class='ui image' style="width:220px"><img src='/img/wechat.png'></div><div class="ui image" style="width:220px"><img src="/img/alipay_2.jpg"></div><div class="ui image" style="width:220px"><img src="/img/alipay.jpg"></div></div></h4>
        <h4 class="ui content">技术栈:Linux + C/C++ + MySQL + PHP + Node.js + ExpressJS + Apache</h4>
        <h4 class="ui content">由Vue.js强力驱动</h4>
        <a class="ui large button" href="https://github.com/CUP-ACM-Programming-Club" target="_blank"><i class="github icon"></i>GitHub</a>
    </div>
</div>

<?php include("template/semantic-ui/bottom.php"); ?>

</div>

</body>
<script>

    function getRandomIntInclusive(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min; //The maximum is inclusive and the minimum is inclusive 
    }

    $(document).ready(function () {
        /*
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
    $result=$database->query("select mtime as t,msg,version,vj_version from maintain_info order by t desc limit 1")->fetchAll();
    $time=$result[0]['t'];
    $msg=$result[0]['msg'];
    $ver=$result[0]['version'];
    $vj_ver=$result[0]['vj_version'];
    ?>
    
    $('.maintain').html("Version:<?=$time?>").attr("data-html", "<div class='ui header'>"+"升级维护内容"+"<div class='sub header'>引擎版本:<?=$ver?></div><div class='sub header'>VJ版本:<?=$vj_ver?></div></div><div class='content'><?=str_replace("\n","",str_replace("&gt;",">",str_replace("&lt;","<",htmlentities($msg,ENT_COMPAT))))?></div>")
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
    });
    $('.ml14 .letters').each(function(){
  $(this).html($(this).attr("data-content").replace(/([^\x00-\x80]|\w)/g, "<span class='letter'>$&</span>"));
});

//document.getElementById('myVideo').addEventListener('ended',false);

(function(){
    finished = true;
    window.picid = 5;
    $(".ui.borderless.network.secondary.menu").addClass("inverted");
    document.getElementById('myVideo').style.opacity = "0.4";
    $.get("../api/login/",function(data){
        var logined = data.logined;
        if(!logined) {
        window.backpic = setInterval(function(){
            var index = getRandomIntInclusive(1,5);
           // while(index === window.picid) {
           //     index = getRandomIntInclusive(1,5);
           // }
            if(index > 0) {
                $("#myVideo").css({
                    opacity:"0"
                });
                $("#background").addClass('zoomed');
                if(window.picid > 0) {
                    setTimeout(function(){
                        $("#background")
                        .removeClass("gr" + window.picid)
                        .addClass("gr"+(window.picid = index))
                        .removeClass("zoomed");
                    },4000)
                }
                else {
                    setTimeout(function(){
                        $("#background")
                        .removeClass("gr" + window.picid)
                        .addClass("gr"+(window.picid = index))
                        .removeClass("zoomed");
                    },4000)
                }
            }
            else {
                $("#background")
                .addClass("zoomed")
                setTimeout(function(){
                    $("#background")
                    .removeClass("gr" + window.picid)
                    $("#myVideo").css({
                    opacity:"0.4",
                    display:"inline-block"
                });
                },4000);
                
                window.picid = index;
            }
        },15000);
        }
        setTimeout(function(){
        $("#main_container").removeClass("unvisible");
        var main_timeline = anime.timeline({loop: false})
  .add({
    targets: '.ml14 .line',
    scaleX: [0,1],
    opacity: [0.5,1],
    easing: "easeInOutExpo",
    duration: 900
  }).add({
    targets: '.ml14 .letter',
    opacity: [0,1],
    translateX: [40,0],
    translateZ: 0,
    scaleX: [0.3, 1],
    textShadow:"3px 3px 3px #555555",
    easing: "easeOutExpo",
    duration: 800,
    offset: '-=600',
    delay: function(el, i) {
      return 0 + 25 * i;
    }
  }).add({
      targets: '.maintain',
      opacity:[0,1],
      easing: [.91,-0.54,.29,1.56],
      duration:500,
      offset: '-=600'
  }).add({
      targets: '.buttonset',
      opacity:[0,1],
      easing: [.91,-0.54,.29,1.56],
      duration:500,
      offset: '-=600'
  }).
  add({
      targets: '.vultr',
      opacity:[0,1],
      easing: "easeOutExpo",
      duration: 500,
      offset: '-=300'
  }).
  add({
      targets: '.github_button',
      opacity:[0,1],
      translateY:17,
      paddingLeft:20,
      scale:1.25,
      duration: 500,
      offset: '-=600',
  });
  if(logined) {
  main_timeline.
  add({
      targets: '#main_masthead',
      translateX:["25%", 0],
      easing: 'easeInOutQuart',
      duration: 1000,
      offset: '-=300',
      complete: function(){
      }
  }).
  add({
      targets: "#right",
      opacity:[0,1],
      easing: 'easeInOutQuart',
      duration: 1000,
      offset: '-=300'
  }).
  add({
      targets: "#divider",
      opacity:[0,1],
      easing: 'easeInOutQuart',
      duration:500,
      offset: '-=300',
      complete: function() {
          var $style = $("style");
          var $html = $style.eq($style.length - 1).html();
          $html += "\n.gr5::after{\nopacity:0.5;\n}\n";
          $style.eq($style.length - 1).html($html);
      }
  });
  }
    },0);
    })
    
   })();
  /*.
  add({
      targets: '.main.title',
      translateX:[0,-200],
      duration:500,
      easing: "easeOutExpo"
  })*/;
</script>
</html>
