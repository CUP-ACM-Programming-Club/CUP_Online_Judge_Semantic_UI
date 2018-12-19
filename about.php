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
    <?php include("template/semantic-ui/css.php"); ?>
    <?php include("template/semantic-ui/js.php"); ?>
</head>

<body>
<?php include("template/semantic-ui/nav.php");?>
<div class="ui container">
    <h2 class="ui dividing header">关于</h2>
    <div class="ui basic segment">
        <h3 class="ui dividing header">版权声明</h3>
        <div class="ui segment">
            <h4>Software:</h4>
            CUPOJ,CUP Online Judge,CUP Virtual Judge,CUP Online Judge Packer,CUP Online Judge Semantic UI,CUP Online Judge docker-judger,CUP Online Judge Judger
            <br>
            ©️2016-2018 Ryan Lee <a href="mailto:gxlhybh@gmail.com">gxlhybh@gmail.com</a><br>
            This program is free software; you can redistribute it and/or modify it under the GPL or Apache Licence.
            <h4>Problem:</h4>
            Local Problem,HDOJ,POJ,UVa Online Judge,etc.<br>
            <a href="https://creativecommons.org/licenses/by-nc-nd/4.0/deed.zh">CC BY-NC-ND 4.0</a>
        </div>
        <h3 class="ui dividing header">开放源代码声明</h3>
        <div class="ui segment">
            <a href="opensource.php">开放源代码声明</a>
        </div>
    </div>
</div>

<?php include("template/semantic-ui/bottom.php") ?>
</body>
</html>
