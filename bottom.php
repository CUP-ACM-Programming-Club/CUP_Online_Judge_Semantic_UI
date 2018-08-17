<div class="ui inverted vertical footer segment" style="margin-top:150px">
        <div class="ui container">
            <div class="ui stackable inverted divided equal height stackable grid">
                <div class="five wide column">
                    <h3 class="ui inverted header"><a href="faqs.php" class="white link" target="_blank">关于
                        <div class="sub header">
                            常见问题
                        </div>
                    </a>
                    </h3>
                    <div class="ui inverted link list">
                        <a href="mailto:gxlhybh@gmail.com" class="item">联系开发者</a>
                        <a href="opensource.php" class="item">开放源代码声明</a>
                    </div>
                </div>
                <div class="five wide column">
                    <h3 class="ui inverted header">知识共享许可协议
                        <div class="sub header">
                            <a class="white link" href="https://creativecommons.org/licenses/by-nc-nd/4.0/deed.zh" target="_blank">署名-非商业性使用-禁止演绎 4.0
                            <div class="sub header">(CC BY-NC-ND 4.0)</div>
                             </a>
                        </div>
                    </h3>
                    <div class="ui mini images">
                        
                         <img class="ui image" src="/img/cc_icon_white_x2.png">
                         <img class="ui image" src="/img/attribution_icon_white_x2.png">
                        <img class="ui image" src="/img/nc_white_x2.png">
                        <img class="ui image" src="/img/nd_white_x2.png"> 
                    </div>
                </div>
                <div class="five wide column">
                    <h4 class="ui inverted header">© CUP Online Judge 2017-<?=date("Y")?>
                       <div class="sub header">  Impressed by HUSTOJ & SYZOJ</div>
                        <div class="sub header">  Powered By Vue.js</div>
                        <div class="sub header">Software Designer:<a href="https://github.com/ryanlee2014" target="_blank">Ryan Lee(李昊元)</a></div>
                        </h4>
                </div>
            </div>
        </div>
    </div>
    <script>
        $("form").append("<div id='csrf' class='csrf' />");
	  $(".csrf").load("<?php echo $path_fix?>csrf.php");
	  var $logout=$(".logout");
$logout.on('click',function(){
    $.get("/api/logout",function(data){
        location.href="../logout.php";
    });
});
//$.get("/api");//keep node.js session awake
    </script>