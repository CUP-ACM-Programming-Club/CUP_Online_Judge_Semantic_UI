<div class="ui inverted vertical footer segment" style="margin-top:150px">
        <div class="ui container">
            <div class="ui stackable inverted equal height stackable grid">
                <div class="three wide column">
                    <h3 class="ui inverted header"><a href="about.php" class="white link" target="_blank">关于
                        <div class="sub header">
                            常见问题
                        </div>
                    </a>
                    </h3>
                    <div class="ui inverted link list">
                        <a href="mailto:gxlhybh@gmail.com" class="item">联系开发者</a>
                        <a href="opensource.php" class="item">开放源代码声明</a>
                        <a href="privacy.php" class="item">隐私政策</a>
                        <a href="copyright.php" class="item">著作权声明</a>
                    </div>
                </div>
                <div class="four wide column">
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
                <div class="four wide column">
                    <h3 class="ui inverted header">
                        ACM Programming Club
                        <div class="sub header">
                            <i class="github icon"></i>
                            GitHub:<a href="https://github.com/CUP-ACM-Programming-Club" target="_blank">CUPACM Programming Club</a>
                        </div>
                        <div class="sub header">
                            <i class="address card outline icon"></i>
                            Current Member:
                            <a href="/ranklist.php?acm" target="_blank">List</a>
                        </div>
                    </h3>
                </div>
                <div class="five wide column">
                    <h3 class="ui inverted header">© CUP Online Judge 2017-2019
                       <div class="sub header">  Impressed by HUSTOJ & SYZOJ & ECNUOJ</div>
                        <div class="sub header">  Powered By Vue.js,Node.js,Semantic-UI</div>
                        <div class="sub header">Software Designer:<a href="https://github.com/ryanlee2014" target="_blank">Ryan Lee(李昊元)</a></div>
                        </h3>
                        <!--<iframe src="https://ghbtns.com/github-btn.html?user=CUP-ACM-Programming-Club&repo=CUP-Online-Judge-Express&type=star&count=true" frameborder="0" scrolling="0" width="80px" height="20px"></iframe>
                        <iframe src="https://ghbtns.com/github-btn.html?user=CUP-ACM-Programming-Club&repo=CUP-Online-Judge-Express&type=fork&count=true" frameborder="0" scrolling="0" width="80px" height="20px"></iframe>-->
                </div>
            </div>
        </div>
    </div>
    <script>
        $("form").append("<div id='csrf' class='csrf' />");
	  $(".csrf").load("csrf.php");
	  var $logout=$(".logout");
$logout.on('click',function(){
    $.get("/api/logout",function(data){
        location.href="../logout.php";
    });
});
//$.get("/api");//keep node.js session awake
    </script>