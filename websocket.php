            <?php


$authcode = "";
if (isset($_SESSION['user_id'])) {
    $authcode = generate_password(16);
    $mem = new Memcache;
    $mem->connect($OJ_MEMSERVER,$OJ_MEMPORT);
    $mem->set($_SESSION['user_id'],$authcode,0,100);
    $database->update("users", ["authcode" => $authcode], ["user_id" => $_SESSION['user_id']]);
}
?>

<?php if (isset($_SESSION['user_id'])) { ?>

<script src="/socket.io/socket.io.js" id="socket_io"></script>
    <script>
   // $(document).ready(function(){
   $("#socket_io").ready(function(){
       var auth={
            user_id:"<?=$_SESSION['user_id']?>",
            token:"<?=$authcode?>"
        }
        <?php
        $ip = "";
        if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        ?>
        var auth_msg={
            user_id: "<?=$_SESSION['user_id']?>",
            id: "<?php if (isset($_SESSION['administrator'])) echo "admin" ?>",
            url: "<?=$_SERVER['REQUEST_URI']?>",
            authcode: "<?=$authcode?>",
            intranet_ip:"<?=$ip?>",
            version:window.navigator.appVersion,
            nick:"<?=stripslashes(trim($_SESSION['nick']))?>",
            platform:window.navigator.platform,
            browser_core:window.navigator.product,
            useragent:window.navigator.userAgent,
            screen:{
                width:screen.availWidth,
                height:screen.availHeight
            }
        };
        //$.post("/api/login/token","token="+Base64.encode(JSON.stringify(auth)));
        window.socket = io(location.hostname);
        //window.socket = io("school.haoyuan.info");
        var socket=window.socket;
        window.online_list = [];
        var vonline_num = new Vue({
           el:".following.bar",
           data:{
               message: '<i class="remove icon"></i>',
               user:0,
               judger:0
           },
           computed:{
               users:{
                   get:function(){
                       
                   },
                   set:function(val) {
                       this.user = val;
                       this.update();
                   }
               },
               judgers:{
                   get:function(){
                       
                   },
                   set:function(val) {
                       this.judger = val;
                       this.update();
                   }
               }
           },
           methods:{
               update:function(){
                   this.message = "<i class='users icon'></i>" + this.user + "人" + "&nbsp;<i class='microchip icon'></i>"+this.judger;
               }
           },
           mounted:function(){
               binding_method();
           }
        });
        window.connected = false;
        socket.on('connect',function(data){
            vonline_num.message="<i class='feed icon'></i>";
            socket.emit("auth",auth_msg);
            setTimeout(function(){
                window.addEventListener("blur", function(event){ 
        var socket = window.socket;
        if(socket && socket.connected && socket.io.engine.transport.query.transport === "polling") {
            socket.close();
        }
    });
    window.addEventListener("focus", function(event){
        var socket = window.socket;
        if(socket && !socket.connected) {
            socket.connect();
        }
    })
            },1000);
            window.connected = true;
        });
        socket.on('error',function(error){
            console.error(error);
        })
        socket.on('disconnect',function(data){
            if(!socket.windowDestroyClose) {
                vonline_num.message = "与服务器连接丢失";
            }
            window.connected = false;
        })
        socket.on('judgerChange',function(data){
            vonline_num.judgers = data.length;
        })
        socket.on('user', function (data) {
            var user = data.user;
            var judger = data.judger;
            vonline_num.users = user['user_cnt'];
            vonline_num.judgers = judger.length;
           // vonline_num.message="<i class='users icon'></i>" + user['user_cnt'] + "人" + "&nbsp;<i class='microchip icon'></i>"+judger.length;
            <?php if(isset($_SESSION['administrator'])){ ?>
            var receive_data = user;
            console.log(receive_data)
            window.online_list = receive_data["user"];
            if (online_list&&typeof list_online=="function" && $("#online_user_table").attr("refresh") == "true")
            {
                list_online();
            }
            <?php } ?>
            if(typeof checkOnline == "function") {
            checkOnline(receive_data["user"]);
            }
        });
        
        socket.on("judger",function(data){
            console.log(data);
        });
        
        socket.on("submit",function(data){
            console.log(data);
            if(typeof status_func=="function")
            {
                status_func(data);
            }
            if(typeof window.problemStatus == "object" && typeof window.problemStatus.submit == "function")
            {
                problemStatus.submit(data);
            }
        })
        
        socket.on("result",function(data){
            console.log(data);
            if(typeof problemsubmitter == "object" && problemsubmitter.wsresult)
            {
                problemsubmitter.wsresult(data);
            }
            if(typeof problemsubmitter == "object" && problemsubmitter.wsfs_result)
            {
                problemsubmitter.wsfs_result(data);
            }
            if(typeof problemStatus == "object" && problemStatus.update)
            {
                problemStatus.update(data);
            }
        })
        socket.on('msg',function(data){
            console.log(data);
            $(".msg.header.item").attr("data-html","<div class='header'>From:"+data['user_id']+"<br>"+data['nick']+"</div><div class='content'>"+data['content']+"</div>")
            .popup("show").popup("set position","bottom center");
        });
        $("body").on('click',function(){
            $(".msg.header.item").popup("hide").removeAttr("data-html");
        })
        socket.on('chat',function(data){
            console.log(data);
        })
   })
   var url=location.href;
   var arr=url.split("/");
   var protocol=arr[0];
   window.addEventListener("beforeunload", function (event) {
       window.socket.windowDestroyClose = true;
       window.socket.close();
});
  // $.getScript( protocol+"//"+location.hostname+"/socket.io/socket.io.js",function(){
        
  // });
        //socket.emit("auth", auth_msg);
    //})
    </script>
<?php } ?>
