<!DOCTYPE html>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <?php include("template/$OJ_TEMPLATE/css.php");?>
    <!-- Site Properties -->
    <title>ProblemSet</title>
    <?php include("template/$OJ_TEMPLATE/js.php");?>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
<div class="ui container padding">
        <?php echo $view_errors?>
        <p>
        </p>
        </div>
        <script>
        $('.ui.container.padding').css('min-height', screen.height);
        </script>
    <?php include("template/$OJ_TEMPLATE/bottom.php");?>
</body>
</html>
