<!DOCTYPE html>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <?php include("template/$OJ_TEMPLATE/css.php");?>
    <!-- Site Properties -->
    <title><?=$OJ_NAME?> 社团报名</title>
    <?php include("template/$OJ_TEMPLATE/js.php");?>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
<div class="ui padding container pusher">
        <div class="html ui top attached segment">
            <form action="register_club.php" method="post" class="ui large form">
            <br><br>
                <div class="ui form">
                    <div class="two fields">
                        <div class="field">
                            <label><?php echo $MSG_USER_ID ?></label>
                            <div class="ui disabled input">
                            <input type="text" placeholder="请填写真实学号"  name="user_id" id="user_id" value="<?php echo $_SESSION['user_id'] ?>" >
                            </div>
                        </div>
                        <div class="field">
                            <label>姓名</label>
                            <input type="text" name="name" placeholder="请填写真实姓名" value="<?=$name?>">
                        </div>
                    </div>
                    <div class="three fields">
                        <div class="field">
                            <label>专业班级（如计算机17级2班）</label>
                            <input name="class" id="class" type="text" placeholder="必填" value="<?=$class?>">
                        </div>
                    <div class="field">
                        <label>性别</label>
                        <select name="sex">
      <option value="0" <?php if($sex==0)echo "selected" ?>>男</option>
      <option value="1" <?php if($sex==1)echo "selected" ?>>女</option>
    </select>
                    </div>
                    <div class="field">
                        <label>志愿部门(竞赛部需要完成知码全部题目,从其余部门转入)</label>
                        <select name="club">
      <!--<option value="0" <?php if($club==0)echo "selected" ?>>竞赛部</option>-->
      <option value="1" <?php if($club==1)echo "selected" ?>>学术部</option>
      <option value="2" <?php if($club==2)echo "selected" ?>>培训部</option>
    </select>
                    </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>手机号</label>
                            <input name="mobile_phone" id="mobile_phone" placeholder="必填" value="<?=$phone?>">
                        </div>
                        <div class="field">
                            <label>QQ号</label>
                            <input name="qq" id="qq" placeholder="必填" value="<?=$qq?>">
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>微信号</label>
                            <input name="wechat" id="wechat" placeholder="必填" value="<?=$wechat?>">
                        </div>
                        <div class="field">
                            <label>E-mail</label>
                            <input name="email" id="email" placeholder="必填" value="<?=$email?>">
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
                <button class="ui primary button" type="submit" id="submit">Submit</button>
                <button class="ui button" type="reset" name="reset">Reset</button>
                <div class="ui error message"></div>
            <br><br>
            </form></div>
            <script>
            $('.ui.form')
  .form({
    fields: {
      name     : 'empty',
      sex   : 'empty',
      class:'empty',
      email : 'empty',
      wechat : 'empty',
      vcode : 'empty',
      qq   : 'empty',
      mobile_phone:'empty'
    }
  })
;
            </script>
            <?php if(isset($_SESSION['administrator'])){ ?>
            <br>
            <button class="ui primary button" onclick="location.href='export.xls.php'">Save to XLS</button>
            <?php } ?>
</div>
<?php if($sucess==1){ ?>
<script>
alert("提交成功");
</script>
<?php } ?>
<?php include("template/$OJ_TEMPLATE/bottom.php");?>
</body>
</html>