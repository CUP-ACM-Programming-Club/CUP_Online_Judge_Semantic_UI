<?php
$url = basename($_SERVER['REQUEST_URI']);
//require_once('name_var.php');
$dir = basename(getcwd());
require_once("template/$OJ_TEMPLATE/profile.php");
$path_fix = "";
$ACTIVE = "class='active'";
$acmmanager = false&&preg_match('/acmman/', $url);
$vjudge = preg_match("/vjudge/", $url) || preg_match("/hdu/", $url) || preg_match("/poj/", $url)||isset($oj_signal);
$suffix="";
if($OJ_CONTEST_MODE&&!isset($_SESSION['administrator']))
$suffix="?my";
?>
<canvas class="fireworks" style="z-index:-999"></canvas>
<div class="following bar">
<div class="ui <?=isset($homepage)?"inverted":""?> borderless network secondary menu" id="navbar-large">
    <div class="ui container">
        <div class="msg header item">
            <?php if(!isset($homepage)){ ?>
            <a href="/" style="color:black"><?php 
            if($vjudge)
            {
                echo "CUP Virtual Judge";
            }
            else
            {
                echo $OJ_NAME;
            }
            ?></a>
            <?php } ?>
        </div>
        <?php if (isset($_GET['cid']) || isset($_GET['tid']) || $vjudge || $acmmanager){ ?>
        <div class="ui dropdown item" tabindex="0">
            <div class="text">通用</div>
            <i class="dropdown icon"></i>
            <div class="menu">
                <?php } ?>
                <a class="item <?php if ($url == "faqs.php") echo "active"; ?>" href="faqs.php"><i class="help icon"></i><?= $MSG_FAQ ?></a>
                <a class="item <?php if ($url == "problemset.php") echo "active"; ?>"
                   href="problemset.php"><i class="browser icon"></i><?= $MSG_PROBLEM ?></a>
                <a class="item <?php if ($url == "status.php") echo "active"; ?>"
                   href="status.php"><i class="tasks icon"></i><?= $MSG_STATUS ?></a>
                <a class="item <?php if ($url == "ranklist.php") echo "active"; ?>"
                   href="<?php echo $path_fix ?>ranklist.php"><i class="trophy icon"></i><?= $MSG_RANKLIST ?></a>
                   <?php if(isset($_SESSION["user_id"])) { ?>
                   <a class="item <?=$url == "discuss.php"?"active":""?>" href="discuss.php"><i class="comment alternate icon"></i>讨论</a>
                   <?php } ?>
                <div
                    class="<?php if (!(isset($_GET['cid']) || isset($_GET['tid']) || $vjudge || $acmmanager)) echo 'ui dropdown' ?> item"
                    tabindex="0">
                    <?php if (isset($_GET['cid']) || isset($_GET['tid']) || $vjudge || $acmmanager) { ?>
                        <i class="right dropdown icon"></i>
                    <?php } ?>
                    <div class="text"><i class="options icon"></i>功能</div>
                    <?php if (!(isset($_GET['cid']) || isset($_GET['tid']) || $vjudge || $acmmanager)) { ?>
                        <i class="dropdown icon"></i>
                    <?php } ?>
                    <div class="menu">
                        <a class="item <?php if ($url == "contest.php") echo "active"; ?>"
                           href="contest.php<?=$suffix?>"><i class="puzzle icon"></i><?= $MSG_CONTEST ?></a>
                        <a class="item <?php if ($url == "specialsubject.php") echo "active"; ?>"
                           href="specialsubject.php"><i class="rocket icon"></i><?= $MSG_SPECIALSUBJECT ?></a>
                        <a class="item <?php if ($url == "recent-contest.php") echo "active"; ?>"
                           href="recent-contest.php"><i class="sitemap icon"></i><?= $MSG_RECENT_CONTEST ?></a>
                           <a class="item <?php if ($url == "acmmanager.php") echo "active"; ?>" href="acmmanager.php"><i class="road icon"></i>ACM管理系统</a>
                           <a class="item <?php if($url=="software.php")echo "active"; ?>" href="software.php"><i class="plug icon"></i>常用软件</a>
                           <a class="item <?php if($url=="whiteboard.php")echo "active" ?>" href="whiteboard.php"><i class="calendar outline icon"></i>白板(β版测试)</a>
                           <?php if(!$OJ_CONTEST_MODE||isset($_SESSION['administrator'])){ ?>
                <a class="item tutorial" target="_blank"><i class="help circle outline icon"></i>C/C++语言参考手册</a>
                                <a class="item" href="//wiki.cupacm.com"><i class="book icon"></i>Wiki</a>

                <?php } ?>
                           
                        
                    </div>
                </div>
                <a class="item <?php if ($url == "vjudgeindex.php") echo "active"; ?>" href="/vjudgeindex.php"><i class="lab icon"></i>Virtual
                            Judge</a>
                
                <?php if (isset($_GET['cid']) || isset($_GET['tid']) || $vjudge || $acmmanager){ ?>  </div>
        </div>
    <?php } ?>
        <?php if (isset($_GET['cid'])) {
            $cid = intval($_GET['cid']);
            ?>
            <a class="item" href="<?php echo $path_fix ?>contest.php?cid=<?php echo $cid ?>">
                <?php echo "比赛" . $MSG_PROBLEMS ?>
            </a>
            <a class="item" href="<?php echo $path_fix ?>status.php?cid=<?php echo $cid ?>">
                <?php echo "比赛提交" . $MSG_STATUS ?>
            </a>
            <a class="item" href="<?php echo $path_fix ?>contestrank.php?cid=<?php echo $cid ?>">
                <?php echo "比赛" . $MSG_RANKLIST ?>
            </a>
            <a class="item" href="<?php echo $path_fix ?>conteststatistics.php?cid=<?php echo $cid ?>">
                <?php echo "比赛" . $MSG_STATISTICS ?>
            </a>
            <a class="item" href="<?=$path_fix?>contestclarification.php?cid=<?=$cid?>">比赛问询(开发中)</a>
        <?php } else if (isset($_GET['tid'])) {
            ?>
            <a class="item" href="<?php echo $path_fix ?>specialsubject.php?tid=<?php echo $tid ?>">
                <?php echo $MSG_PROBLEMS ?>
            </a>
            <a class="item" href="<?php echo $path_fix ?>specialsubjectrank.php?tid=<?php echo $tid ?>">
                <?php echo $MSG_RANKLIST ?>
            </a>
            <a class="item back subject"
               href="<?php echo $path_fix ?>specialsubject.php?<?php if (preg_match("/specialsubject/", $url) == 0) echo "tid=" . $tid ?>">
                返回专题<?php if (preg_match("/specialsubject/", $url) == 0) echo $tid ?>
            </a>
        <?php } else if ($vjudge) {
            ?>
            <a class="item <?php if ($url == "vjudgeproblemset.php") echo "active"; ?>" href="<?php echo $path_fix ?>vjudgeproblemset.php"><i class="browser icon"></i>
                <?php echo $MSG_PROBLEMS ?>
            </a>
            <a class="item <?php if ($url == "hdu_status.php") echo "active"; ?>" href="hdu_status.php"><i class="tasks icon"></i>
                <?php echo $MSG_STATUS ?>
            </a>
            <a class="item <?php if ($url == "vjudgeranklist.php") echo "active"; ?>"
                   href="<?php echo $path_fix ?>vjudgeranklist.php"><i class="trophy icon"></i><?= $MSG_RANKLIST ?></a>
        <?php } ?>
        <div class="right menu">
            <?php if (isset($_SESSION['user_id'])) { ?>
            <a v-html="message" class="item <?php if ($url == "newonline.php") echo "active"; ?> online_num" v-cloak
               href="<?php if (!isset($_SESSION['administrator'])) { ?>javascript:void(0)<?php } else { ?>newonline.php<?php } ?>"><i class="remove icon"></i></a><?php } ?>
            <?php if (isset($_SESSION["user_id"])) { ?>
                <div class="ui dropdown item detail" tabindex="0">
                    <div class="text"><span class="profile_group"><?=$profile_control?></span></div>
                    <i class="dropdown icon"></i>
                </div>
            <?php } else { ?>
                <div class="item">
                    <a class="ui button" href="newloginpage.php"><?= $MSG_LOGIN ?></a>
                </div>
                <div class="item">
                    <a class="ui primary button" href="registerpage.php"><?= $MSG_REGISTER ?></a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<div class="ui <?=isset($homepage)?"inverted":""?> borderless network secondary menu" id="navbar-small">
    <div class="ui container">
        <div class="msg header item">
            <?php if(!isset($homepage)){ ?>
            <a href="/" style="color:black"><?php 
            if($vjudge)
            {
                echo "CUP Virtual Judge";
            }
            else
            {
                echo $OJ_NAME;
            }
            ?></a>
            <?php } ?>
        </div>
        <?php if (isset($_GET['cid']) || isset($_GET['tid']) || $vjudge || $acmmanager){ ?>
        <div class="ui dropdown item" tabindex="0">
            <div class="text">通用</div>
            <i class="dropdown icon"></i>
            <div class="menu">
                <?php } ?>
                <a class="item <?php if ($url == "faqs.php") echo "active"; ?>" href="faqs.php"><?= $MSG_FAQ ?></a>
                <a class="item <?php if ($url == "problemset.php") echo "active"; ?>"
                   href="problemset.php"><?= $MSG_PROBLEM ?></a>
                <a class="item <?php if ($url == "status.php") echo "active"; ?>"
                   href="status.php"><?= $MSG_STATUS ?></a>
                <a class="item <?php if ($url == "ranklist.php") echo "active"; ?>"
                   href="<?php echo $path_fix ?>ranklist.php"><?= $MSG_RANKLIST ?></a>
                <div
                    class="<?php if (!(isset($_GET['cid']) || isset($_GET['tid']) || $vjudge || $acmmanager)) echo 'ui dropdown' ?> item"
                    tabindex="0">
                    <?php if (isset($_GET['cid']) || isset($_GET['tid']) || $vjudge || $acmmanager) { ?>
                        <i class="right dropdown icon"></i>
                    <?php } ?>
                    <div class="text">其他</div>
                    <?php if (!(isset($_GET['cid']) || isset($_GET['tid']) || $vjudge || $acmmanager)) { ?>
                        <i class="dropdown icon"></i>
                    <?php } ?>
                    <div class="menu">
                        <a class="item <?php if ($url == "contest.php") echo "active"; ?>"
                           href="contest.php<?=$suffix?>"><?= $MSG_CONTEST ?></a>
                        <a class="item <?php if ($url == "specialsubject.php") echo "active"; ?>"
                           href="specialsubject.php"><?= $MSG_SPECIALSUBJECT ?></a>
                        <a class="item <?php if ($url == "recent-contest.php") echo "active"; ?>"
                           href="recent-contest.php"><?= $MSG_RECENT_CONTEST ?></a>
                           <a class="item <?php if ($url == "acmmanager.php") echo "active"; ?>" href="acmmanager.php">ACM管理系统</a>
                           <a class="item <?php if($url=="software.php")echo "active"; ?>" href="software.php">常用软件</a>
                           <a class="item <?php if($url=="whiteboard.php")echo "active" ?>" href="whiteboard.php">白板(β版测试)</a>
                            <a class="item <?php if ($url == "vjudgeproblemset.php") echo "active"; ?>" href="/vjudgeproblemset.php">Virtual
                            Judge</a>
                
                <a class="item" href="//wiki.cupacm.com">Wiki</a>
                <?php if(!$OJ_CONTEST_MODE||isset($_SESSION['administrator'])){ ?>
                <a class="item tutorial" href="cppreference/en/" target="_blank">C/C++语言参考手册</a>
                <?php } ?>
                    </div>
                </div>
                <?php if (isset($_GET['cid']) || isset($_GET['tid']) || $vjudge || $acmmanager){ ?>  </div>
        </div>
    <?php } ?>
        <?php if (isset($_GET['cid'])) {
            $cid = intval($_GET['cid']);
            ?>
            <a class="item" href="<?php echo $path_fix ?>contest.php?cid=<?php echo $cid ?>">
                <?php echo "比赛" . $MSG_PROBLEMS ?>
            </a>
            <a class="item" href="<?php echo $path_fix ?>status.php?cid=<?php echo $cid ?>">
                <?php echo "比赛提交" . $MSG_STATUS ?>
            </a>
            <a class="item" href="<?php echo $path_fix ?>contestrank.php?cid=<?php echo $cid ?>">
                <?php echo "比赛" . $MSG_RANKLIST ?>
            </a>
            <a class="item" href="<?php echo $path_fix ?>conteststatistics.php?cid=<?php echo $cid ?>">
                <?php echo "比赛" . $MSG_STATISTICS ?>
            </a>
            <a class="item" href="<?=$path_fix?>contestclarification.php?cid=<?=$cid?>">比赛问询(开发中)</a>
        <?php } else if (isset($_GET['tid'])) {
            ?>
            <a class="item" href="<?php echo $path_fix ?>specialsubject.php?tid=<?php echo $tid ?>">
                <?php echo $MSG_PROBLEMS ?>
            </a>
            <a class="item" href="<?php echo $path_fix ?>specialsubjectrank.php?tid=<?php echo $tid ?>">
                <?php echo $MSG_RANKLIST ?>
            </a>
            <a class="item subject back"
               href="<?php echo $path_fix ?>specialsubject.php?<?php if (preg_match("/specialsubject/", $url) == 0) echo "tid=" . $tid ?>">
                返回专题<?php if (preg_match("/specialsubject/", $url) == 0) echo $tid ?>
            </a>
        <?php } else if ($vjudge) {
            ?>
            <a class="item <?php if ($url == "vjudgeproblemset.php") echo "active"; ?>" href="<?php echo $path_fix ?>vjudgeproblemset.php">
                <?php echo $MSG_PROBLEMS ?>
            </a>
            <a class="item <?php if ($url == "hdu_status.php") echo "active"; ?>" href="hdu_status.php">
                <?php echo $MSG_STATUS ?>
            </a>
            <a class="item <?php if ($url == "vjudgeranklist.php") echo "active"; ?>"
                   href="<?php echo $path_fix ?>vjudgeranklist.php"><?= $MSG_RANKLIST ?></a>
        <?php } ?>
        <div class="right menu">
            <?php if (isset($_SESSION['user_id'])) { ?>
            <a v-html="message" class="item <?php if ($url == "newonline.php") echo "active"; ?> online_num1" v-cloak
               href="<?php if (!isset($_SESSION['administrator'])) { ?>javascript:void(0)<?php } else { ?>newonline.php<?php } ?>"></a><?php } ?>
            <?php if (isset($_SESSION["user_id"])) { ?>
                <div class="ui dropdown item detail" tabindex="0">
                    <div class="text"><span class="profile_group"><?=$profile_control?></span></div>
                    <i class="dropdown icon"></i>
                </div>
            <?php } else { ?>
                <div class="item">
                    <a class="ui button" href="newloginpage.php"><?= $MSG_LOGIN ?></a>
                </div>
                <div class="item">
                    <a class="ui primary button" href="registerpage.php"><?= $MSG_REGISTER ?></a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<div class="ui <?=isset($homepage)?"inverted":""?> borderless network secondary menu" id="navbar-nano">
    <div class="ui container">
        <div class="msg header item">
            <?php if(!isset($homepage)){ ?>
            <a href="/" style="color:black"><?php 
            if($vjudge)
            {
                echo "CUP Virtual Judge";
            }
            else
            {
                echo $OJ_NAME;
            }
            ?></a>
            <?php } ?>
        </div>
        <div class="ui dropdown item" tabindex="0">
            <div class="text">通用</div>
            <i class="dropdown icon"></i>
            <div class="menu">
                <a class="item <?php if ($url == "faqs.php") echo "active"; ?>" href="faqs.php"><?= $MSG_FAQ ?></a>
                <a class="item <?php if ($url == "problemset.php") echo "active"; ?>"
                   href="problemset.php"><?= $MSG_PROBLEM ?></a>
                <a class="item <?php if ($url == "status.php") echo "active"; ?>"
                   href="status.php"><?= $MSG_STATUS ?></a>
                <a class="item <?php if ($url == "ranklist.php") echo "active"; ?>"
                   href="<?php echo $path_fix ?>ranklist.php"><?= $MSG_RANKLIST ?></a>
                <div class="item" tabindex="0">
                        <i class="right dropdown icon"></i>
                    <div class="text">其他</div>
                    <div class="menu">
                        <a class="item <?php if ($url == "contest.php") echo "active"; ?>"
                           href="contest.php<?=$suffix?>"><?= $MSG_CONTEST ?></a>
                        <a class="item <?php if ($url == "specialsubject.php") echo "active"; ?>"
                           href="specialsubject.php"><?= $MSG_SPECIALSUBJECT ?></a>
                        <a class="item <?php if ($url == "recent-contest.php") echo "active"; ?>"
                           href="recent-contest.php"><?= $MSG_RECENT_CONTEST ?></a>
                           <a class="item <?php if ($url == "acmmanager.php") echo "active"; ?>" href="acmmanager.php">ACM管理系统</a>
                           <a class="item <?php if($url=="software.php")echo "active"; ?>" href="software.php">常用软件</a>
                           <a class="item <?php if($url=="whiteboard.php")echo "active" ?>" href="whiteboard.php">白板(β版测试)</a>
                            <a class="item <?php if ($url == "vjudgeindex.php") echo "active"; ?>" href="/vjudgeindex.php">Virtual
                            Judge</a>
                
                <a class="item" href="//wiki.cupacm.com">Wiki</a>
                <?php if(!$OJ_CONTEST_MODE||isset($_SESSION['administrator'])){ ?>
                <a class="item tutorial" href="cppreference/en/" target="_blank">C/C++语言参考手册</a>
                <?php } ?>
                    </div>
                </div>
                  </div>
        </div>
        <?php if (isset($_GET['cid'])) {
            $cid = intval($_GET['cid']);
            ?>
            <a class="item" href="<?php echo $path_fix ?>contest.php?cid=<?php echo $cid ?>">
                <?php echo "比赛" . $MSG_PROBLEMS ?>
            </a>
            <a class="item" href="<?php echo $path_fix ?>status.php?cid=<?php echo $cid ?>">
                <?php echo "比赛提交" . $MSG_STATUS ?>
            </a>
            <a class="item" href="<?php echo $path_fix ?>contestrank.php?cid=<?php echo $cid ?>">
                <?php echo "比赛" . $MSG_RANKLIST ?>
            </a>
            <a class="item" href="<?php echo $path_fix ?>conteststatistics.php?cid=<?php echo $cid ?>">
                <?php echo "比赛" . $MSG_STATISTICS ?>
            </a>
            <a class="item" href="<?=$path_fix?>contestclarification.php?cid=<?=$cid?>">比赛问询(开发中)</a>
        <?php } else if (isset($_GET['tid'])) {
            ?>
            <a class="item" href="<?php echo $path_fix ?>specialsubject.php?tid=<?php echo $tid ?>">
                <?php echo $MSG_PROBLEMS ?>
            </a>
            <a class="item" href="<?php echo $path_fix ?>specialsubjectrank.php?tid=<?php echo $tid ?>">
                <?php echo $MSG_RANKLIST ?>
            </a>
            <a class="item back subject"
               href="<?php echo $path_fix ?>specialsubject.php?<?php if (preg_match("/specialsubject/", $url) == 0) echo "tid=" . $tid ?>">
                返回专题<?php if (preg_match("/specialsubject/", $url) == 0) echo $tid ?>
            </a>
        <?php } else if ($vjudge) {
            ?>
            <a class="item <?php if ($url == "vjudgeproblemset.php") echo "active"; ?>" href="<?php echo $path_fix ?>vjudgeproblemset.php">
                <?php echo $MSG_PROBLEMS ?>
            </a>
            <a class="item <?php if ($url == "hdu_status.php") echo "active"; ?>" href="hdu_status.php">
                <?php echo $MSG_STATUS ?>
            </a>
            <a class="item <?php if ($url == "vjudgeranklist.php") echo "active"; ?>"
                   href="<?php echo $path_fix ?>vjudgeranklist.php"><?= $MSG_RANKLIST ?></a>
        <?php } ?>
        <div class="right menu">
            <?php if (isset($_SESSION['user_id'])) { ?>
            <a v-html="message" class="item <?php if ($url == "newonline.php") echo "active"; ?> online_num2" v-cloak
               href="<?php if (!isset($_SESSION['administrator'])) { ?>javascript:void(0)<?php } else { ?>newonline.php<?php } ?>"></a><?php } ?>
            <?php if (isset($_SESSION["user_id"])) { ?>
                <div class="ui dropdown item detail" tabindex="0">
                    <div class="text"><span class="profile_group"><?=$profile_control?></span></div>
                    <i class="dropdown icon"></i>
                </div>
            <?php } else { ?>
                <div class="item">
                    <a class="ui button" href="newloginpage.php"><?= $MSG_LOGIN ?></a>
                </div>
                <div class="item">
                    <a class="ui primary button" href="registerpage.php"><?= $MSG_REGISTER ?></a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
</div>
<div class="ui flowing popup hidden dropdown_menu_group">
     <?=$dropdown_control?>
</div>
<?php if (!isset($homepage)) { ?>
    <div class="ui vertical center aligned segment" style="border-bottom:0px">
        <div class="ui container">
            <div class="ui inverted borderless large pointing menu" style="opacity:0">
                
            </div>
        </div>
    </div>
<?php } ?>
<div class="ui basic small test modal">
    <div class="ui icon header">
<i class="code icon"></i>
语言选择
    </div>
    <div class="content">
      <p>请选择参考手册的语言</p>
    </div>
    <div class="actions">
      <div class="ui blue cancel inverted button">
        中文
      </div>
      <div class="ui green ok inverted button">
        英文
      </div>
    </div>
  </div>
  <script>
      $('.ui.test.modal')
  .modal({
    onDeny    : function(){
      location.href = "/cppreference/zh-cn";
     // return false;
    },
    onApprove : function() {
      location.href = "/cppreference/en";
    }
  })
;
$(".tutorial")
.on("click",function(e){
    e.preventDefault();
    $(".ui.test.modal").modal('show')

})
  </script>
<script src="template/<?php echo $OJ_TEMPLATE ?>/js/cookie.js"></script>
<script src="template/<?php echo $OJ_TEMPLATE ?>/js/platform.js?ver=1.0.1"></script>
<?php 
require_once("template/$OJ_TEMPLATE/websocket.php");
?>