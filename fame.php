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
    <?php include("template/semantic-ui/css.php"); ?>
    <?php include("template/semantic-ui/js.php"); ?>
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php");
?>
    <div class="ui text container">
        <div class="ui basic segment" id="mainContent">
            <h2 class="ui dividing header">
            Hall of Fame
            <div class="sub header">姓名不分先后</div>
        </h2>
            <!--<div class="left ui rail" id="contents">
            <div class="ui segment">
                
            </div>
            </div>-->
            <div class="ui segment" id="icpc2015changchun">
            <h3 class="ui header contents">2015 长春(东北师范大学)</h3>
            <div class="right ui rail">
                <div class="ui sticky changchun">
                    <h3 class="ui header">ICPC2015 长春</h3>
                    <h4>纪念奖</h4>
                    <p>队名:启元(START)</p>
                    <p>成员:王国霞 贾林鹏 刘兴</p>
                </div>
            </div>
            <img class="ui image" src="/glory_image/2015CHANGCHUN.jpg">
        </div>
        <div class="ui segment" id="icpc2015beijing">
            <h3 class="ui header contents">2015 北京(北京大学)</h3>
            <div class="right ui rail">
                <div class="ui sticky beijing">
                    <h3 class="ui header">ICPC2015 北京</h3>
                    <h4>纪念奖</h4>
                    <p>队名:START</p>
                    <p>成员:王国霞 贾林鹏 刘兴</p>
                </div>
            </div>
            <img class="ui image" src="/glory_image/2015BEIJING.jpg">
        </div>
        <div class="ui segment" id="ccpc2017">
            <h3 class="ui header contents">2017 秦皇岛(东北大学秦皇岛分校)</h3>
        <div class="right ui rail">
          <div class="ui sticky one">
            <h3 class="ui header">CCPC2017 秦皇岛</h3>
            <h4>铜奖</h4>
            <p>队名:罚吹留下了悔恨的泪水</p>
            <p>成员:冯云豪 吕博枫 李昊元</p>
            
            </div>
        </div>
        <img class="ui image" src="/glory_image/QINHUANGDAO.jpg">
      </div>
      <div class="ui segment" id="example2">
        <div class="right ui rail">
          <div class="ui sticky two">
            <h3 class="ui header">ICPC2017 青岛</h3>
            <h4>铜奖</h4>
            <p>队名:我们教练还没来得及给我们取</p>
            <p>成员:陈哲 季来虎 王智健</p>
          </div>
        </div>
        <h3 class="ui header contents">ICPC2017 青岛(中国石油大学(华东))</h3>
        <img class="ui image" src="/glory_image/QINGDAO.jpg">
      </div>
      <div class="ui segment" id="icpc2017beijing">
            <h3 class="ui header contents">2017 北京(北京大学)</h3>
            <div class="right ui rail">
                <div class="ui sticky 2017beijing">
                    <h3 class="ui header">ICPC2017 北京</h3>
                    <h4>铜奖</h4>
                    <p>队名:XLK</p>
                    <p>成员:许思睿 连浩丞 夏方略</p>
                </div>
            </div>
            <img class="ui image" src="/glory_image/2017BEIJING.jpg">
        </div>
        <div class="ui segment" id="icpc2018ningxia">
            <h3 class="ui header contents">2018 宁夏(宁夏理工学院)</h3>
            <div class="right ui rail">
                <div class="ui sticky 2018ningxia">
                    <h3 class="ui header">ICPC2018 Multi-Province 宁夏</h3>
                    <h4>银奖 铜奖</h4>
                    <p>队名:我为祖国献石油<br>队名:我为石油献代码</p>
                    <p>成员:冯云豪 李昊元 吕博枫<br>成员:许思睿 连浩丞 夏方略</p>
                </div>
            </div>
            <img class="ui image" src="/glory_image/2018NINGXIA.jpg">
        </div>
        </div>
        
    </div>
    </div>
    <script>
      $('.ui.sticky.one')
        .sticky({
          context: '#ccpc2017',
          offset: 50
        });
        $(".ui.sticky.beijing")
        .sticky({
            context:"#icpc2015beijing",
            offset: 50
        });
        $(".ui.sticky.2017beijing")
        .sticky({
            context:"#icpc2017beijing",
            offset: 50
        });
        $(".ui.sticky.2018ningxia")
        .sticky({
            context:"#icpc2018ningxia",
            offset: 50
        });
        $(".ui.sticky.changchun")
        .sticky({
            context:"#icpc2015changchun",
            offset: 50
        });
        $('.ui.sticky.two')
        .sticky({
          context: '#example2',
          offset: 50
        });
        $('.ui.sticky.three')
        .sticky({
          context: '#example3',
          offset: 50
        });
        $('.ui.sticky.four')
        .sticky({
          context: '#example4',
          offset: 50
        });
        //var headerArr = $(".ui.container.text .ui.segment .ui.header.contents");
        //var len = headerArr.length;
        //for(var i = 0;i<len;++i)
        //{
            //$("#contents .segment").append("<a href='#" + headerArr.eq(i).parent().attr("id")+"'>"+headerArr.eq(i).text()+"</a><br>");
        //}
        /*$("#contents")
        .sticky({
            context:"#mainContent",
            offset:50,
            observeChanges: false,
            refreshOnLoad: true,
            refreshOnResize: true,
        })*/
    </script>
<?php include("template/semantic-ui/bottom.php") ?>
</body>
</html>
