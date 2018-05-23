<!DOCTYPE html>
<?php


$authcode = "";
if (isset($_SESSION['user_id'])) {
    $authcode = generate_password(16);
    $database->update("users", ["authcode" => $authcode], ["user_id" => $_SESSION['user_id']]);
}
?>
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
    <?php include("csrf.php")?>
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
            if($(".ui.error.message").html().length>0)$(".ui.error.message").html("");
            $(".ui.fluid.large.teal.button").addClass("loading");
            var a = document.getElementById("user_id").value;
            var b = document.getElementById("password").value;
            var c = document.getElementById("vcode").value;
            var asypost;
            var arr = [];
            var response;
            arr.user_id = a;
            arr.password = b;
            var jsonstring;
            <?php if($OJ_VCODE){?>
            var obj = {
                user_id: a,
                password: b,
                vcode: c,
                authcode:"<?=$authcode?>"
            };
            //$.post("/api/login/token","token="+Base64.encode(JSON.stringify(obj)));
            jsonstring = JSON.stringify(obj);
            <?php } else{?>
            var obj = {
                user_id: a,
                password: b
                ,csrf:"<?=$token?>"
            };
            jsonstring = JSON.stringify(obj);
            <?php }?>
            $.post("/api/login","msg=" + Base64.encode(Base64.encode(jsonstring)));
            if (window.XMLHttpRequest) {
                asypost = new XMLHttpRequest();
            } else {
                asypost = new ActiveXObject("Microsoft.XMLHTTP");
            }
            var send={
                msg:Base64.encode(Base64.encode(jsonstring)),
                csrf:"<?=$token?>"
            }
            console.log(send);
            $.post("login.php",send,function(response){
                <?php if(isset($_SESSION['administrator'])){ ?>
                    console.log(response);
                    <?php } ?>
                    $(".ui.fluid.large.teal.button").removeClass("loading");
                    if (response === "true") {
                        //window.session=Base64.encode(Base64.encode(jsonstring);
                        $(".ui.error.message").html("");
                        $.post("/api/login/newpassword","user_id="+$("#user_id").val()+"&password="+$("#password").val());
                        var timetick = setTimeout(function () {
                           location.href="/";
                        }, 700);
                    }
                    else if (response == "vcode false") {
                        $(".ui.error.message").html("验证码错误!").show();
                        document.getElementById('vcode_graph').src = 'vcode.php?' + Math.random();
                    }
                    else {
                        document.getElementById('vcode_graph').src = 'vcode.php?' + Math.random();
                        $(".ui.error.message").html("账号或密码错误！<a href='lostpassword.php'>找回密码</a>").show();
                    }
            })
            /*
            asypost.onreadystatechange = function () {
                if (asypost.readyState === 4 && asypost.status === 200) {
                    //response = asypost.responseText;
                    
                }
            };
            console.log(jsonstring);
            asypost.open("POST", "login.php", true);
            asypost.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            asypost.send("msg=" + Base64.encode(Base64.encode(jsonstring))+"&csrf=<?=$token?>");*/
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
