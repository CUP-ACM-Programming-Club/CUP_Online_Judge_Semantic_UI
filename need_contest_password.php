<!DOCTYPE html>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=1200">
    <?php include("template/$OJ_TEMPLATE/css.php");?>
    <!-- Site Properties -->
    <title>Private Contest -- CUP Online Judge</title>
    <?php include("template/$OJ_TEMPLATE/js.php");?>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
<div class="ui container">
    <h2 class="ui dividing header">私有竞赛/作业</h2>
    <form id='contest_form' method='post' class="ui form">
        <div class="fields">
            <div class="six wide field">
                <label>请输入密码</label>
                <input id='contest_pass' class=input-mini type=password name=password>
            </div>
        </div>
        <input class='ui primary button' type=submit>
        </form>
    <script>$('#contest_form').submit(function() {
        $.post('../api/contest/password/' + getParameterByName("cid"),{
            password:$('#contest_pass').val()
        });
    return true; // return false to cancel form action
});</script>
</div>
<script>
    $('.ui.container.padding').css('min-height', screen.height);
</script>
    <?php include("template/$OJ_TEMPLATE/bottom.php");?>
</body>
</html>
