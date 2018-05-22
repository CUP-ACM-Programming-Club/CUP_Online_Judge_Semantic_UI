<?php
$cache_time = 10;
$OJ_CACHE_SHARE = false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
require_once('./include/setlang.php');
if(isset($_GET['code']))
{
    $code=intval($_GET['code']);
    $view_errors='<img class="ui center aligned medium rounded image" style="margin:auto" src="/img/maji.jpg">';
        $view_errors.='<h2 class="ui block header center aligned">マジやばくね</h2>';
    if($code==404)
    {
        $view_errors.='<div class="ui large warning message">
  <div class="header">
    Error Code:404
  </div>
  您所访问的页面不存在
</div>';
    }
    else if($code==403)
    {
        $view_errors.='<div class="ui large warning message">
  <div class="header">
    Error Code:403
  </div>
  您没有权限访问该页面
</div>';
    }
    else if($code==500)
    {
        $view_errors.='<div class="ui large warning message">
  <div class="header">
    Error Code:500
  </div>
  内部错误
</div>';
    }
}
require("template/" . $OJ_TEMPLATE . "/error.php");
/////////////////////////Common foot
if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>
