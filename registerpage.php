<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <?php
        if(isset($_SESSION["user_id"])) {
            ?>
            <script>
                alert("请退出登录后注册");
                location.href = "/";
            </script>
            <?php
        }
    ?>
    <?php include("csrf.php") ?>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <script>
        function checkfun() {
            var v = document.getElementById('user_id').value;
            var confiq = document.getElementById('confirmquestion').value;
            var confia = document.getElementById('confirmanswer').value;
            if (true||v.charAt(0) == '2' && v.length == 10 && !(isNaN(v)) && confiq.length != 0 && confia.length != 0) {
                $("#submit").removeClass("disabled");
            }
            else {
                $("#submit").addClass("disabled");
            }

        }
        function checkpassword() {
            var pass = document.getElementById('password').value;
            if (pass.length >= 6) {
                document.getElementById('password_div').className = "control-group success";
            }
            else {
                document.getElementById('password_div').className = "control-group error";
            }
        }
    </script>
    <title><?php echo $OJ_NAME ?></title>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
<div class="padding">

    <?php
    $user_IP = ((isset($_SERVER["HTTP_VIA"]))) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
    $user_IP = ($user_IP) ? $user_IP : $_SERVER["REMOTE_ADDR"];
    //echo $user_IP;
    ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="ui container">
        <?php if (preg_match('/10\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/', $user_IP)) { ?>
            <div class="html ui top attached segment">
            <form action="register.php" method="post" class="ui large form">
            <h4>根据要求，请实名注册账号，非法账号将定期删除，严重者封禁IP</h4>
                <div class="ui form">
                    <div class="two fields">
                        <div class="field">
                            <label><?php echo $MSG_USER_ID ?></label>
                            <input type="text" placeholder="请填写真实学号"  name="user_id" id="user_id" >
                        </div>
                        <div class="field">
                            <label><?php echo $MSG_NICK ?></label>
                            <input type="text" name="nick" placeholder="请填写真实姓名">
                        </div>
                    </div>
                        <div class="field">
                            <label><?php echo $MSG_PASSWORD ?></label>
                            <input name="password" id="password" type="password" placeholder="必填">
                        </div>
                    <div class="field">
                        <label><?php echo $MSG_REPEAT_PASSWORD ?></label>
                        <input name="rptpassword" id="rptpassword" type="password" placeholder="必填">
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>找回密码问题</label>
                            <input name="confirmquestion" id="confirmquestion" placeholder="必填">
                        </div>
                        <div class="field">
                            <label>找回密码答案</label>
                            <input name="confirmanswer" id="confirmanswer" placeholder="必填">
                        </div>
                    </div>
                    <?php if ($OJ_VCODE) { ?>
                        <div class="two fields">
                        <div class="field">
                            <label><?php echo $MSG_VCODE ?></label>
                            <input name="vcode" size=4 type=text>
                        </div>
                            <div class="field">
                                <label>&nbsp;</label>
                                <img  alt="click to change" src="vcode.php"
                                     onclick="this.src='vcode.php?'+Math.random()" width="20%">*
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <button class="ui primary disabled button" type="submit" id="submit">Submit</button>
                <button class="ui button" type="reset" name="reset">Reset</button>
                <div class="ui error message"></div>
            <br><br>
            </form></div><?php
        } else {
            echo "<h2 class='ui header'>注册已关闭，如需注册请联系管理员<a href=\"mailto:gxlhybh@gmail.com\" target=\"_blank\">Ryan</a></h2>";
        }

        ?>
    </div>

</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script>
    $("input").keyup(function(){
        checkfun();
    });
    $("input").keypress(function () {
        checkfun();
    });
    $('.ui.form')
        .form({
            on:'blur',
            fields: {
                name: {
                    identifier: 'user_id',
                    rules: [
                        {
                            type   : 'empty',
                            prompt : 'Please enter your user id'
                        },
                        {
                            type :'regExp[/^201[0-9]{7}$/]',
                            prompt:'Please enter your legal user_id!'
                        }
                    ]
                },
                password: {
                    identifier: 'rptpassword',
                    rules: [
                        {
                            type   : 'match[password]',
                            prompt : 'Please repeat your password correctly!'
                        }
                    ]
                },
                confirmquestion: {
                    identifier: 'confirmquestion',
                    rules: [
                        {
                            type   : 'empty',
                            prompt : 'Please enter your confirm question'
                        }
                    ]
                },
                confirmanswer: {
                    identifier: 'confirmanswer',
                    rules: [
                        {
                            type   : 'empty',
                            prompt : 'Please enter your confirm answer'
                        }
                    ]
                },
                vcode: {
                    identifier: 'vcode',
                    rules: [
                        {
                            type   : 'empty',
                            prompt : 'please enter vcode'
                        }
                    ]
                }
            }
        })
    ;
</script>
<?php include("template/semantic-ui/bottom.php");?>
</body>
</html>
