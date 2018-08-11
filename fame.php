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
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php");
?>
    <div class="ui text container">
        <h2 class="ui dividing header">
            Hall of Fame
        </h2>
        <div class="ui segment" id="ccpc2017">
            <h3 class="ui header">2017 秦皇岛(东北大学秦皇岛分校)</h3>
        <div class="left ui rail">
          <div class="ui sticky one">
            <h3 class="ui header"></h3>
            <p></p>
          </div>
        </div>
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
      <h4 class="ui header"></h4>
      <div class="ui segment" id="example2">
        <div class="left ui rail">
          <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
          <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
          <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
          <div class="ui sticky two">
            <h3 class="ui header">Stuck Content</h3>
            <img class="ui wireframe image" src="/images/wireframe/image.png">
          </div>
        </div>
        <div class="right ui rail">
          <div class="ui sticky two">
            <h3 class="ui header">2017 青岛(中国石油大学(华东))</h3>
            
          </div>
        </div>
        <h3 class="ui header">ICPC2017 青岛</h3>
        <img class="ui image" src="/glory_image/QINGDAO.jpg">
      </div>
      <h4 class="ui header">Pushing</h4>
      <p>Specifying <code>pushing: true</code> will have the viewport "push" the sticky content depending on the scroll direction. When scrolling down content will be stuck to the top of the viewport, but in the opposite direction content is stuck to the bottom of the viewport.</p>
      <div class="ui segment" id="example3">
        <div class="left ui rail">
          <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
          <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
          <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
          <div class="ui sticky three">
            <h3 class="ui header">Stuck Content</h3>
            <img class="ui wireframe image" src="/images/wireframe/image.png">
          </div>
        </div>
        <div class="right ui rail">
          <div class="ui sticky three">
            <h3 class="ui header">Stuck Content</h3>
            <img class="ui wireframe image" src="/images/wireframe/image.png">
          </div>
        </div>
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
      </div>
      <div class="ui segment" id="example4">
        <div class="left ui rail">
          <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
          <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
          <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
          <div class="ui sticky four">
            <h3 class="ui header">Stuck Content</h3>
            <img class="ui wireframe image" src="/images/wireframe/image.png">
          </div>
        </div>
        <div class="right ui rail">
          <div class="ui sticky four">
            <h3 class="ui header">Stuck Content</h3>
            <img class="ui wireframe image" src="/images/wireframe/image.png">
          </div>
        </div>
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
        <img class="ui wireframe paragraph image" src="/images/wireframe/paragraph.png">
      </div>
    </div>
    </div>
    <script>
      $('.ui.sticky.one')
        .sticky({
          context: '#ccpc2017',
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
    </script>
<?php include("template/$OJ_TEMPLATE/bottom.php") ?>
</body>
</html>
