<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title><?php echo $view_title?></title>
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
        <div class="ui three column grid center aligned">
            <div class="fifteen wide column center aligned">
                <h2>ACM Rating Board</h2>
                <h4>10分钟更新一次榜单</h4>
                <h4>数据暂不完整</h4>
                <?php echo $view_table; ?>
            </div>
        </div>
    </div>
</div> <!-- /container -->
<?php include("template/$OJ_TEMPLATE/bottom.php");?>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script language="javascript" type="text/javascript" src="include/jquery.flot.js"></script>

</body>
</html>
