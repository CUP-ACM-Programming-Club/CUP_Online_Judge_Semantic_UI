<!DOCTYPE html>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <!-- Site Properties -->
    <title>LoginPage -- CUP Online Judge</title>
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <script src="../dist/components/transition.js"></script>
    <script src="/template/semantic-ui/js/cookie.js"></script>
    <style type="text/css">
        body {
            background-color: #DADADA;
        }

        body > .grid {
            height: 100%;
        }

        .image {
            margin-top: -100px;
        }

        .column {
            max-width: 450px;
        }
    </style>
    <script>
        function check() {
            $(".ui.error.message").hide();
            $(".ui.fluid.large.teal.button").addClass("loading");
            var jsonstring = JSON.stringify({
                user_id:$("#user_id").val(),
                password:$("#password").val(),
                vcode:$("#vcode").val()
            });
            $.post("/api/login","msg=" + Base64.encode(Base64.encode(jsonstring)));
            var send={
                msg:Base64.encode(Base64.encode(jsonstring)),
                csrf:Cookies.get("token")
            }
            console.log(send);
            $.post("login.php",send,function(response){
                    $(".ui.fluid.large.teal.button").removeClass("loading");
                    if (response === "true") {
                        $(".ui.error.message").hide();
                        $.post("/api/login/newpassword","user_id="+$("#user_id").val()+"&password="+$("#password").val());
                        var timetick = setTimeout(function () {
                           location.href="/";
                        }, 700);
                    }
                    else if (response == "vcode false") {
                        $(".ui.error.message").html("验证码错误!").show();
                        $(".ui.middle.aligned.center.aligned.grid .column").transition("shake");
                        $("#vcode_graph").attr("src",'vcode.php?' + Math.random());
                    }
                    else {
                        $("#vcode_graph").attr("src",'vcode.php?' + Math.random());
                        $(".ui.middle.aligned.center.aligned.grid .column").transition("shake")
                        $(".ui.error.message").html("账号或密码错误！<a href='lostpassword.php'>找回密码</a>").show();
                    }
            })
        }

        function enterpress(e) {
            if (e.keyCode === 13) {
                check();
            }
        }

    </script>
</head>
<body>

<div class="ui middle aligned center aligned grid masthead zoomed" id="background">
    <div class="column">
        <h2 class="ui teal image header">
            <!--<img src="assets/images/logo.png" class="image">-->
            <div class="content">
                Log-in to your account
            </div>
        </h2>
        <div class="ui large form">
            <div class="ui stacked segment">
                <div class="field">
                    <div class="ui left icon input">
                        <i class="user icon"></i>
                        <input type="text" name="user_id" id="user_id" placeholder="username">
                    </div>
                </div>
                <div class="field">
                    <div class="ui left icon input">
                        <i class="lock icon"></i>
                        <input type="password" name="password" id="password" placeholder="Password"
                               onkeypress="return enterpress(event)">
                    </div>
                </div>
                <div class="field">
                    <div class="ui left input">
                        <input name="vcode" type="text" placeholder="验证码" style="width:70%" id="vcode"
                               onkeypress="return enterpress(event)"><img alt="click to change" id="vcode_graph"
                                                                          src="vcode.php"
                                                                          onclick="this.src='vcode.php?'+Math.random()"
                                                                          height="40px">
                    </div>
                </div>
                <div class="ui fluid large teal button" onclick="check()">Login</div>
            </div>

            <div class="ui error message"></div>

        </div>
<div class="ui bottom attached warning message">
  <i class="icon help"></i>
  New to us? <a href="registerpage.php">Sign Up</a>
  <br>
  <i class="icon help"></i>
  Forgot password? <a href="lostpassword.php">Reset your password</a>
</div>
    </div>
</div>

</body>
<script>
    $(document).ready(function () {
        $.get("/api/login");
        setTimeout(function () {
            var getRandomInt = function (min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            };
            var background = "bg" + getRandomInt(1, 4);
            $("#background")
                .addClass(background)
                .removeClass('zoomed')
            ;
        }, 0);
    });

</script>
</html>
