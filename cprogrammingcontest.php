<!DOCTYPE html>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <?php include("template/$OJ_TEMPLATE/css.php");?>
    <!-- Site Properties -->
    <title><?=$OJ_NAME?> 程序设计竞赛报名</title>
    <?php include("template/$OJ_TEMPLATE/js.php");?>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
<div class="ui padding container pusher">
    <div class="ui dividing header">程序设计竞赛报名<?=$hassubmit?"(已报名)":""?></div>
        <div class="html ui top attached segment">
            <!--<form action="cprogrammingcontest.php" method="post" class="ui large form">-->
            <div class="ui ordered steps">
  <div class="completed step">
    <div class="content">
      <div class="title">报名竞赛</div>
      <div class="description">填写完整信息</div>
    </div>
  </div>
  <div class="completed step">
    <div class="content">
      <div class="title">查看考试信息</div>
      <div class="description">确认考场以及座位信息</div>
    </div>
  </div>
  <div class="completed step">
    <div class="content">
      <div class="title">参加考试</div>
      <div class="description"></div>
    </div>
  </div>
  <div class="active step">
    <div class="content">
      <div class="title">查看复赛信息</div>
      <div class="description"></div>
    </div>
  </div>
  <div class="step">
    <div class="content">
      <div class="title">参加复赛</div>
      <div class="description"></div>
    </div>
  </div>
</div>
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
                            <div class="ui disabled input">
                            <input type="text" name="name" placeholder="请填写真实姓名" value="<?=$name?>">
                            </div>
                        </div>
                    </div>
                    <?php $passed = $database->count("cprogram",["user_id"=>$_SESSION["user_id"],"pass"=>true]);
                    $passed = $passed>0;
                    if($passed){
                    ?>

                    <div class="ui success message" style="display:block">
                      <div class="header">
                        恭喜
                      </div>
                  <p>您成功晋级复赛</p>
                    </div>
                    <?php }else{ ?>
                    <div class="ui message">
                      <div class="header">
                       很抱歉
                      </div>
                  <p>您未能通过初试</p>
                    </div>
                    <?php } ?>
                    <!--<div class="two fields">
                        <div class="field">
                            <div class="ui statistic">
                          <div class="label">
                                考场(第三教学楼)
  </div>
  <div class="value">
    <?php //($result = $database->select("cprogram",["room","seat"],["user_id"=>$_SESSION["user_id"]]))[0]["room"]?>
  </div>
</div>
                        </div>
                        <div class="field">
                            <div class="ui statistic">
                          <div class="label">
                                座位
  </div>
  <div class="value">
    <?php //$result[0]["seat"]?>
  </div>
</div>
                        </div>
                    </div>-->
                    <!--
                    <div class="three fields">
                        <div class="field">
                        <label>性别</label>
                        <select name="sex">
      <option value="0" <?php if($sex==0)echo "selected" ?>>男</option>
      <option value="1" <?php if($sex==1)echo "selected" ?>>女</option>
    </select>
                    </div>
                    <div class="field">
                        <label>出生年(请输入数字)</label>
                        <input type="text" name="year" placeholder="请填写出生年" value="<?=$year?>">
                    </div>
                    <div class="field">
                        <label>出生月(请输入数字)</label>
                        <input type="text" name="month" placeholder="请填写出生月" value="<?=$month?>">
                    </div>
                    </div>
                    <div class="three fields">
                        <div class="field">
                            <label>学院（请填写全称）</label>
                            <input name="scholar" id="class" type="text" placeholder="必填" value="<?=$scholar?>">
                        </div>
                        <div class="field">
                            <label>专业（请填写全称）</label>
                            <input name="subject" id="class" type="text" placeholder="必填" value="<?=$subject?>">
                        </div>
                        <div class="field">
                            <label>专业班级(如计算机17级2班 如研究生请写(计算机17级2班研)</label>
                            <input name="class" id="class" type="text" placeholder="必填" value="<?=$class?>">
                        </div>
                    </div>
                    <div class="three fields">
                        
                    
                    <div class="field">
                        <label>志愿组别(请认真选择)</label>
                        <select name="group">
                            <option value="" selected>请选择组别</option>
      <option value="1" <?php if($group==1)echo "selected" ?>>C语言组</option>
      <option value="2" <?php if($group==2)echo "selected" ?>>Java语言组</option>
    </select>
                    </div>
                    <div class="field">
                        <label>指导老师</label>
                        <input type="text" name="teacher" placeholder="选填" value="<?=$teacher?>">
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
                    -->
                </div>
                <!--<button class="ui primary button" type="submit" id="submit"><?=$hassubmit?"修改":"提交"?></button>
                <button class="ui button" type="reset" name="reset">重置</button>
                <div class="ui error message"></div>-->
            <br><br>
            <!--</form>--></div>
            <div class="ui success message">
  <div class="header">
    复赛时间
  </div>
  <p>2018年4月14日</p>
</div>
            <script>
            $('.ui.form')
  .form({
     on: 'blur',
    fields: {
      name     : 'empty',
      sex   : 'empty',
      class:'empty',
      email : 'empty',
      group:{
          identifier: 'group',
          rules:[{
              type   : 'integer[1..2]',
            prompt : '请选择组别'
          }]
      },
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
            <button class="ui primary button" onclick="location.href='export.program.php'">Save to XLS</button>
            <?php } ?>
</div>
<script>
<?php if($sucess==1){ ?>

alert("提交成功");

<?php }else if($sucess==0){ ?>
alert("<?=$errmsg?>");
<?php } ?>
</script>
<?php include("template/$OJ_TEMPLATE/bottom.php");?>
</body>
</html>