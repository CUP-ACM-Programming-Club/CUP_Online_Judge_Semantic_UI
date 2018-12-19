<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title><?php echo $OJ_NAME ?></title>
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php if(!$OJ_SYSTEM_CLOSED)include("template/$OJ_TEMPLATE/nav.php");

?>

    <!-- Main component for a primary marketing message or call to action -->
    <div class="ui container padding">
        <div class="ui negative message">
  <div class="header"><i class="ban icon"></i>
   <?=$ERROR_MESSAGE_HEADER?>
  </div>
  <p><?=$ERROR_MESSAGE_CONTENT?>
</p></div>
    </div>
 <!-- /container -->
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<div style="display:none">
<?php include("template/$OJ_TEMPLATE/bottom.php") ?>
</div>
</body>
</html>
