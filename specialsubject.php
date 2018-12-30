<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <script src="js/jquery.min.js"></script>
    <script src="js/flat-ui.min.js"></script>
    <!--<script src="js/json2.js"></script>-->
    <script src="js/jquery.color.js"></script>
    <script src="template/<?php echo $OJ_TEMPLATE ?>/js/base64.js"></script>
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
<?php include("template/semantic-ui/nav.php"); ?>
<div class="pusher">
    <!-- Main component for a primary marketing message or call to action -->
    <div id="main-content" class="padding ui container">
        <h2 class="ui block top attached header">ACM专题练习
        <div class="sub header">这里有点AC状态的小坑，有机会再修</div></h2>
        <div class="ui bottom attached segment">
            <div class="ui four cards">
                <?php
                $cnt = 0;

                foreach ($result as $row) { ?>
                    <a class="card" href="specialsubject.php?tid=<?php echo $row['topic_id']; ?>">
                        <div class="content">
                            <div class="header"><?php echo $row['topic_id'] . "." . $row['title']; ?></div>
                            <div class="meta">
                                <?php if ($row['private'] == '0') {
                                    echo "公开";
                                } else {
                                    echo "私有";
                                } ?>
                            </div>
                            <div
                                class="description"><?php if ($row['vjudge'] == "1") echo "Virtual Judge"; else echo "Local"; ?>
                            </div>
                        </div>
                    </a>
                <?php }
                mysqli_free_result($result);
                ?>
            </div>
        </div>
    </div>
</div>
<?php include("template/$OJ_TEMPLATE/bottom.php"); ?>
</body>

</html>
