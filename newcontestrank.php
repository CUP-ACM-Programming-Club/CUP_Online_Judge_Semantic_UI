<!DOCTYPE html>
<html lang="en"  style="overflow: auto;">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $OJ_NAME?></title>  
    <?php include("template/semantic-ui/css.php");?>	    

 <?php include("template/semantic-ui/js.php");?>
 <script src="/js/dayjs.min.js"></script>

<script src="/js/FileSaver.min.js"></script>
 <!--
 <script src="/js/xlsx.core.min.js"></script>
<script src="/js/tableexport.min.js"></script>
<script src="/js/localforage.js"></script>
-->

<script src="template/semantic-ui/js/iconv-lite.bundle.js"></script>
<script>
/*
    localforage.setDriver([
  localforage.INDEXEDDB,
  localforage.WEBSQL,
  localforage.LOCALSTORAGE
  ]).then(function() {
  
});
*/
</script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    .ui.grey{
        background-color:#A0A0A0;
    }
    .ui.yellow{
        background-color:#FFD700;
    }
    .ui.orange{
        background-color:#FE9A76;
    }
    .accept {
        background-color: #e0ffe4!important;
    }
    .accept.first {
        background-color: #16ab39!important;
    }
    .text.accept {
        color:#21BA45!important;
    }
    .text.accept.first {
        color:#FFFFFF!important;
    }
    .text.red {
        color:#DB2828!important;
    }
    .well{
   background-image:none;
   padding:1px;
}
td{
   white-space:nowrap;
}
    </style>
  </head>
  <body>
<?php include("template/semantic-ui/nav.php");?>
<script type="text/x-template" id="time_tick">
    <h3 class="ui header">
        当前时间:{{current_time}}
    <div class="sub header" v-if="start_time">
        起始时间:{{dayjs(start_time).format("YYYY-MM-DD HH:mm:ss")}},
        {{format_time(dayjs().diff(start_time,"second"))}}
    </div>
    </h3>
</script>
<script type="text/x-template" id="error_handle">
    <div class="ui container padding">
    <h2 class="ui dividing header">
        发生错误
        <div class="sub header">
            请把以下内容反馈给老师、助教或本平台的开发者Ryan Lee(李昊元)
        </div>
    </h2>
    <div class="ui segment">
        <div class="ui error message">
            <div class="header">
                错误信息内容
            </div>
            <br>
            <h5 class="ui header">URL:{{location.href}}</h5>
            <p v-html="errormsg"></p>
        </div>
    </div>
    </div>
</script>
<div id="root">
<error-handle :errormsg="errormsg" v-if="!state"></error-handle>
    <div class="contestrank scoreboard padding" v-cloak v-show="state">
        <h2 class="ui dividing header">
            {{total === 0?"计算中,请稍后":"Contest Rank"}}
            <div class="sub header">
                 {{title}}
            </div>
        </h2>
     
     <div class="ui grid">
         <div class="row">
             <div class="left aligned twelve wide column">
                 <time_pattern
                 :start_time="start_time"
                 ></time_pattern>
             </div>
             <div class="right aligned four wide column">
                 <a class="ui primary mini button" @click="exportXLS">Save to XLS</a>
             </div>
         </div>
         <div class="row">
             <div style="width:100%;height:100%;overflow:auto" class="ranking">
<table id='rank' class="ui small celled table">
    <thead><tr class=toprow align=center><th class="{sorter:'false'}" width=5%>Rank<th width=5%>User</th><th style="min-width:90px">Nick</th><th width=5%>Solved</th><th width=5%>Penalty</th>
<th style="min-width: 85.71px;" v-for="i in Array.from(Array(total).keys())">{{1001 + i}}</th></tr>
</thead>
<tbody>
    <tr v-for="row in submitter">
        <td style="text-align:center;font-weight:bold">{{row.rank}}</td>
        <td style="text-align:center"><a :href="'userinfo.php?user='+row.user_id" target="_blank">{{row.user_id}}</a></td>
        <td style="text-align:center"><a :href="'userinfo.php?user='+row.user_id" target="_blank">{{convertHTML(row.nick)}}</a></td>
        <td style="text-align:center"><a :href="'status.php?user_id=' + row.user_id + '&cid=' + cid">{{row.ac}}</a></td>
        <td style="text-align:center">{{format_date(row.penalty_time)}}</td>
        <td v-for="p in row.problem" style="text-align:center" 
        :class="p.accept.length > 0?p.first_blood ? 'first accept':'accept':''">
            <b :class="'text '+ (p.accept.length > 0 ? p.first_blood?'first accept':'accept':'red')">
                {{ (p.accept.length > 0 || p.submit.length > 0)?'+':''}}
                {{p.try_time > 0 ? p.try_time : p.submit.length > 0?p.submit.length : ""}}</b>
            <br v-if="p.accept.length > 0">
            <span v-if="p.accept.length > 0 && typeof p.accept[0].diff === 'function'" :class="p.first_blood?'first accept text':''">
                {{format_date(p.accept[0].diff(p.start_time,'second'))}}
            </span>
        </td>
    </tr>
</tbody>
</table>
<table id="save" style="display:none;vnd.ms-excel.numberformat:@">
<tbody>
    <tr class=toprow align=center><td width=5%>Rank<td width=5%>User</td><td>Nick</td><td width=5%>Solved</td><td width=5%>Penalty</td><td>环境指纹数</td><td>硬件指纹数</td><td>IP总数</td><td>地点</td>
<td v-for="i in Array.from(Array(total).keys())">{{1001 + i}}</td></tr>
    <tr v-for="row in submitter">
        <td align="center">{{row.rank}}</td>
        <td align="center">{{row.user_id}}</td>
        <td align="center">{{convertHTML(row.nick)}}</td>
        <td align="center">{{row.ac}}</td>
        <td>{{format_date(row.penalty_time)}}</td>
        <td>{{row.fingerprintSet.size}}</td>
        <td>{{row.handwareFingerprintSet.size}}</td>
        <td>{{row.ipSet.size}}</td>
        <td>{{row.ipSet.size === 1 ? detect_place(Array.from(row.ipSet)[0]) : row.ipSet.size === 0?"无":"略"}}</td>
        <td :bgcolor="'#FF' + (format_color(Math.max(Math.floor((1 << 8) - (256 * Math.max(p.sim - 69,0) / 31.0)) - 1, 0)))" v-for="p in row.problem" align="left">
                {{ (p.submit.length > 0)?'(-':''}}{{p.try_time > 0 ? p.try_time + ")" : p.submit.length > 0?p.submit.length + ")" : ""}}{{p.accept.length > 0 ? format_date(p.accept[0].diff(p.start_time,'second')):""}}{{p.sim > 0?"("+p.sim + "%) ":''}}
        </td>
    </tr>
</tbody>
</table>
</div>
         </div>
     </div>
    
</div>
</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
   
<script>
function getTotal(rows){
var total=0;
for(var i=0;i<rows.length&&total==0;i++){
try{
total=parseInt(rows[rows.length-i].cells[0].innerHTML);
if(isNaN(total)) total=0;
}catch(e){
}
}
return total;
}
function metal(){
var tb=window.document.getElementById('rank');
var rows=tb.rows;
try{
var total=getTotal(rows);
//alert(total);
for(var i=1;i<rows.length;i++){
var cell=rows[i].cells[0];
var acc=rows[i].cells[3];
var ac=parseInt(acc.innerText);
if (isNaN(ac)) ac=parseInt(acc.textContent);
if(cell.innerHTML!="*"&&ac>0){
var r=parseInt(cell.innerHTML);
if(r==1){
cell.innerHTML="Winner";
//cell.style.cssText="background-color:gold;color:red";
cell.className="ui yellow";
}
if(r>1&&r<=total*.10+1)
cell.className="ui yellow";
if(r>total*.10+1&&r<=total*.30+1)
cell.className="ui grey";
if(r>total*.30+1&&r<=total*.60+1)
cell.className="ui orange";
/*
if(r>total*.45+1&&ac>0)
cell.className="ui grey";
*/
}
}
}catch(e){
//alert(e);
}
}
    $(".ranking").height(window.innerHeight-($(".ui.vertical.center").outerHeight()+$(".dividing.header").outerHeight())-85);
    window.addEventListener("resize",function(){
        setTimeout(function(){
            $(".ranking").height(window.innerHeight-($(".ui.vertical.center").outerHeight()+$(".dividing.header").outerHeight())-85);
        },100);
        
    });
    var convert_flag = false;
Vue.component('error-handle',{
    template:"#error_handle",
    props:{
        errormsg:String
    },
    data:function(){return{};}
});
    
Vue.component('time_pattern',{
    template:"#time_tick",
    props:{
        start_time:Object
    },
    data:function(){return{
        current_time:dayjs().format("YYYY-MM-DD HH:mm:ss")
    };},
    mounted:function(){
        var that = this;
        setInterval(function(){
            that.current_time = dayjs().format("YYYY-MM-DD HH:mm:ss");
        },1000);
    },
    methods:{
        format_time: function(second) {
            var arr = ["还有", "已经过"];
            var passed = Number(second > 0);
            return arr[passed] + this.format_date(second);
        },
        format_date:function(second,mode = 0) {
               var fill_zero = function(str) {
                   if(str.length < 2) {
                       return "0" + str;
                   }
                   else {
                       return str;
                   }
               }
               second = Math.abs(second);
               var hour = String(parseInt(second / 3600));
               hour = fill_zero(hour);
               
               var minute = String(parseInt(( second - hour * 3600 ) / 60));
               minute = fill_zero(minute);
               var sec = String(second % 60);
               sec = fill_zero(sec);
               if(mode) {
                   return hour + " : " + minute + " : " + sec;
               }
               else
                    return hour + ":" + minute + ":" + sec;
           }
    }
});
var temp_data = [];
var contestrank = window.contestrank = new Vue({
       el:"#root",
       data:{
           cid: getParameterByName("cid"),
           submitter:{},
           total:0,
           start_time:false,
           title:"",
           users:[],
           state:true,
           errormsg:""
       },
       computed:{
           scoreboard:{
               get:function(){
                   
               },
               set:function(val){
                   var that = this;
                   var total = this.total;
                   try {
                   if(val && val.length) {
                   _.forEach(val,function(v){
                        temp_data.push(v);
                   });
                   }
                   else {
                       temp_data.push(val);
                   }
                   val = temp_data;
                   var first_blood = [];
                   for(var i = 0;i<that.total;++i) {
                       first_blood.push(-1);
                   }
                   var submitter = this.submitter = {};
                   _.forEach(this.users,function(val){
                       if(!submitter[val.user_id]) {
                           submitter[val.user_id] = {
                               ac:0,
                               nick:val.nick ? val.nick.trim():"未注册",
                               problem:{},
                               penalty_time:0,
                               fingerprintSet:new Set(),
                               handwareFingerprintSet:new Set(),
                               ipSet: new Set()
                           }
                           for(var j = 0;j<total;++j) {
                               submitter[val.user_id].problem[j] = {
                                   submit:[],
                                   accept:[],
                                   sim: 0
                                }
                           }
                       }
                   });
                   var len = val.length;
                   var private_contest = this.users.length > 0;
                   for(var i = 0;i<len;++i)
                   {
                       if(!val[i].nick)continue;
                       val[i].nick = val[i].nick.trim();
                       if(!submitter[val[i].user_id]) {
                           if(private_contest) {
                                continue;
                           }
                           submitter[val[i].user_id] = {
                               ac:0,
                               nick:val[i].nick,
                               problem:{},
                               penalty_time:0,
                               fingerprintSet:new Set(),
                               handwareFingerprintSet:new Set(),
                               ipSet: new Set()
                           }
                           for(var j = 0;j<total;++j) {
                               submitter[val[i].user_id].problem[j] = {
                                   submit:[],
                                   accept:[],
                                   sim: 0
                                }
                           }
                       }
                       if(!!val[i].fingerprint) {
                            submitter[val[i].user_id].fingerprintSet.add(val[i].fingerprint);
                       }
                       if(!!val[i].fingerprintRaw) {
                            submitter[val[i].user_id].handwareFingerprintSet.add(val[i].fingerprintRaw);
                       }
                       if(!!val[i].ip) {
                           submitter[val[i].user_id].ipSet.add(val[i].ip);
                       }
                       if(val[i].sim !== null) {
                           submitter[val[i].user_id].problem[val[i].num].sim = parseInt(val[i].sim);
                       }
                       if(submitter[val[i].user_id].problem[val[i].num] === undefined) {
                            continue;
                        }
                       if(val[i].result == 4) {
                            submitter[val[i].user_id].problem[val[i].num].accept.push(
                                val[i].in_date
                            );
                            submitter[val[i].user_id].problem[val[i].num].start_time = val[i].start_time;
                       }
                       else if(val[i].result >= 6 && val[i].result <= 10){
                           submitter[val[i].user_id].problem[val[i].num].submit.push(
                               val[i].in_date
                           )
                           submitter[val[i].user_id].problem[val[i].num].start_time = val[i].start_time;
                       }
                   }
                   var _submitter = [];
                   _.forEach(submitter,function(val,index){
                       if(!index) {
                           console.log(val);
                           console.log(index);
                       }
                       else {
                       val.user_id = index;
                       _submitter.push(val);
                       }
                   })
                   _.forEach(submitter,function(value,key){
                       var problems = submitter[key].problem;
                       _.forEach(problems,function(value,index){
                           _.forEach(value.submit,function(val,key){
                               value.submit[key] = dayjs(val);
                           })
                           if(value.accept.length > 0) {
                               _.forEach(value.accept,function(val,key){
                                   value.accept[key] = dayjs(val);
                               })
                               var accept_submit = value.accept[0];
                               var penalty_time = 0;
                               _.forEach(value.submit,function(val,key){
                                   if(val.isBefore(accept_submit)) {
                                       ++penalty_time;
                                   }
                               })
                               value.try_time = penalty_time;
                               penalty_time *= 1200;
                               ++submitter[key].ac;
                               submitter[key].penalty_time += penalty_time;
                           }
                       })
                        // console.log(submitter[key]);
                   })
                   // console.log(submitter);
                   
                   _.forEach(_submitter,function(val){
                       _.forEach(val.problem,function(v,idx){
                           if(v.accept.length > 0) {
                               var difftime =  v.accept[0].diff(v.start_time,'second');
                               val.penalty_time += difftime;
                               if(~first_blood[idx]) {
                                   first_blood[idx] = Math.min(first_blood[idx],difftime);
                               }
                               else {
                                   first_blood[idx] = difftime;
                               }
                           }
                       })
                   })
                   
                   _.forEach(_submitter,function(val){
                       _.forEach(val.problem,function(v,idx){
                           v.first_blood = Boolean(v.accept.length > 0 && v.accept[0].diff(v.start_time,'second') === first_blood[idx]);
                       })
                   })
                   
                   _submitter.sort(function(a,b){
                       if(a.ac != b.ac) {
                           return b.ac - a.ac;
                       }
                       else {
                            return a.penalty_time - b.penalty_time;
                       }
                   });
                   
                   var rnk = 1;
                   _.forEach(_submitter,function(val){
                       if(val.ac > 0)
                            val.rank = rnk++;
                        else
                            val.rank = rnk;
                   });
                   window.datas = that.submitter = _submitter;
                   }
                   catch (e) {
                       that.state = false;
                       that.submitter = {};
                       console.log(typeof e);
                       var str = e.stack;
                       str = str.replace(/\n/g,"<br>");
                       that.errormsg = str;
                   }
               }
           }
       },
       methods:{
           format_date:function(second,mode = 0) {
               var fill_zero = function(str) {
                   if(str.length < 2) {
                       return "0" + str;
                   }
                   else {
                       return str;
                   }
               }
               var hour = String(parseInt(second / 3600));
               hour = fill_zero(hour);
               
               var minute = String(parseInt(( second - hour * 3600 ) / 60));
               minute = fill_zero(minute);
               var sec = String(second % 60);
               sec = fill_zero(sec);
               if(mode) {
                   return hour + "：" + minute + "：" + sec;
               }
               else
                    return hour + ":" + minute + ":" + sec;
           },
           format_color:function(num){
               var str = num.toString(16);
               if(num < 16) {
                   return "0" + str + "0" + str;
               }
               else {
                   return "" + str + "" + str;
               }
           },
        detect_place: function(ip) {
                if(!ip) {
                    return "未知";
                }
                var tmp = {
                    intranet_ip:ip,
                    place:""
                };
                if (tmp.intranet_ip.match(/10\.10\.[0-9]{2}\.[0-9]{1,3}/)) {
                    tmp.place = "润杰有线";
                }
                else if(tmp.intranet_ip == "202.204.193.82") {
                    tmp.place = "网络中心出口";
                }
                else if(tmp.intranet_ip === "10.200.25.101" && tmp.intranet_ip.match(/10\.200\.25\.1[0-9]{2}/) || tmp.intranet_ip === "10.200.25.200") {
                    tmp.place = "403机房";
                }
                else if(tmp.intranet_ip.match(/10\.200\.26\./)) {
                    var ip = tmp.intranet_ip.substring(tmp.intranet_ip.lastIndexOf(".") + 1);
                    if(parseInt(ip) <= 100) {
                    tmp.place ="404机房";
                    }
                    else {
                        tmp.place = "405机房";
                    }
                }
                else if (tmp.intranet_ip.match(/10\.200\.28\.[0-9]{1,3}/) || tmp.intranet_ip.match(/10\.200\.26\.[0-9]{1,3}/)
                    || tmp.intranet_ip.match(/10\.200\.25\.[0-9]{1,3}/)) {
                    if (tmp.intranet_ip.match(/10\.200\.26\.[0-9]{1,3}/)) {
                        tmp.place = "405机房";
                    }
                    else if (tmp.intranet_ip.match(/10\.200\.28\.[0-9]{1,3}/)) {
                        var ip = tmp.intranet_ip.substring(tmp.intranet_ip.lastIndexOf(".") + 1);
                        if(parseInt(ip) <= 80) {
                            tmp.place = "502机房";
                        }
                        else if(parseInt(ip) < 172 && parseInt(ip) >= 101) {
                            tmp.place = "503机房";
                        }
                        else {
                            tmp.place = "机房";
                        }
                    }
                    else {
                        tmp.place = "机房";
                    }
                }
                else if (tmp.intranet_ip.match(/10\.110\.[0-9]{1,3}\.[0-9]{1,3}/)) {
                    tmp.place = "润杰公寓Wi-Fi";
                }
                else if (tmp.intranet_ip.match(/10\.102\.[0-9]{1,3}\.[0-9]{1,3}/)) {
                    tmp.place = "第三教学楼Wi-Fi";
                }
                else if (tmp.intranet_ip.match(/10\.103\.[0-9]{1,3}\.[0-9]{1,3}/)) {
                    tmp.place = "地质楼Wi-Fi";
                }
                else if (tmp.intranet_ip.match(/10\.1[0-9]{2}\.[0-9]{1,3}\.[0-9]{1,3}/)) {
                    tmp.place = "其他Wi-Fi";
                }
                else if (tmp.intranet_ip.match(/10\.200\.33\.[0-9]{1,3}/)) {
                    tmp.place = "润杰机房六楼";
                }
                else if (tmp.intranet_ip.match(/172\.16\.[\s\S]+/)) {
                    tmp.place = "VPN";
                }
                else if (tmp.intranet_ip && tmp.ip && tmp.intranet_ip != tmp.ip) {
                    tmp.place = "外网";
                }
                else if (tmp.intranet_ip.match(/2001:[\s\S]+/)) {
                    tmp.place = "校园网IPv6";
                }
                else if (tmp.intranet_ip.match(/10\.3\.[\s\S]+/)) {
                    tmp.place = "地质楼";
                }
                else {
                    tmp.place = "未知";
                }
                return tmp.place;
            },
           convertHTML:function(str){
               var d = document.createElement("div");
               d.innerHTML = str;
               return d.innerText;
           },
           exportXLS:function(){
               var doc = document.getElementById("save");
               var plain_text = "<html><head><meta http-equiv='Content-Type' content='application/vnd.ms-excel; charset=utf-8' /></head>";
               plain_text += "<center><h3>Contest "+ this.cid + " " + this.title +"</h3></center>";
               plain_text += "<table border=1>" + doc.innerHTML.replace("<tbody>","").replace("</tbody>","");
               plain_text += "<tr><td colspan='8'>环境指纹指根据用户的硬件环境及IP地址不同而产生的不同的指纹</td></tr>";
               plain_text += "<tr><td colspan='8'>硬件指纹指的是不受IP影响的指纹</td></tr>";
               plain_text += "<tr><td colspan='8'>若环境指纹与硬件指纹均唯一，代表用户使用相同设备在相同地点完成提交</td></tr>";
               plain_text += "<tr><td colspan='8'>若硬件指纹唯一而环境指纹不唯一，代表同型号机器在不同IP地址提交</td></tr>";
               plain_text += "<tr><td colspan='8'>若硬件指纹不唯一，代表使用了多台设备进行提交</td></tr>";
               plain_text += "</table></html>";
               console.log(plain_text);
               var blob;
               blob = new Blob([plain_text], {type: 'application/excel'});
               if(convert_flag) {
                   saveAs(blob, "Contest " + this.cid + " 多个contest.xls" );
               }
               else
               saveAs(blob, "Contest " + this.cid + " " + this.title + ".xls" );
               //var table = TableExport(document.getElementById("save"));
               //var d = table.getExportData().save.xlsx;
               //var filename = "Contest " + this.cid;
               //filename = filename.substring(0,31);
               //table.export2file(d.data,d.mimeType,filename,d.fileExtension,d.merges)
           },
           handleNewSubmit:function(data){
               if(parseInt(data.contest_id) === parseInt(this.cid)) {
                   if(data.finish === 1) {
                       var ndata = {
                           nick:data.nick,
                           user_id:data.user_id,
                           start_time:this.start_time,
                           avatar:0,
                           in_date:data.in_date,
                           num:parseInt(data.num),
                           result:data.state
                       };
                       this.scoreboard = ndata;
                   }
               }
           }
       },
       updated:function(){
            metal();
            $("title").html("ContestRank: " + this.title);
       },
       mounted:function(){
           var that = this;
               that.$nextTick(function(){
                   $(".ranking").height($(window).height()-($(".hidemenu").outerHeight())-150);
               })
       },
       created:function(){
           
       }
   })
    var cid = getParameterByName("cid");
    var cidArr = [];
    if(cid.indexOf(",")!== -1) {
        cidArr = cid.split(",");
    }
    else {
        cidArr = [cid];
    }
    var cnt = 0;
    var data = [];
    var users = new Set();
    var finished = false;
    var cstring = cidArr.join(",");
    function work(){
        cid = cidArr.shift();
        $.get("/api/scoreboard/"+cid);
        $.get("/api/scoreboard/"+cid,function(d){
            if(d.status != "OK" && !d.statement) {
                var that = window.contestrank;
                that.state = false;
                that.submitter = {};
                var str ="根据设置，内容非公开";
                str = str.replace(/\n/g,"<br>");
                that.errormsg = str;
                return;
            }
            _.forEach(d.data,function(val,idx){
                val.num += cnt;
                val.start_time = dayjs(d.start_time);
            });
            _.forEach(d.data,function(val){
                data.push(val);
            });
            _.forEach(d.users, function(val){
                users.add(val);
            });
            cnt += d.total;

            if(cidArr.length > 0) {
                convert_flag = true;
            work();
            }
            else {
                finished = true;
                window.contestrank.total = cnt;
                window.contestrank.users = Array.from(users);
                window.contestrank.scoreboard = data;
            }
        });
    }
  function build_data(_data)
  {
      _data.start_time = window.contestrank.start_time;
      return _data;
  }
    if(cidArr.length > 1) {
        window.contestrank.title = cidArr.join(",");
        work();
    }
    else {
        cid = cidArr.shift();
        $.get("/api/scoreboard/"+cid);
        $.get("/api/scoreboard/"+cid,function(d){
            if(d.status != "OK" && !d.statement) {
                var that = window.contestrank;
                that.state = false;
                that.submitter = {};
                var str ="根据设置，内容非公开";
                str = str.replace(/\n/g,"<br>");
                that.errormsg = str;
                return;
            }
            finished = true;
            window.contestrank.total = d.total;
            window.contestrank.users = d.users;
            window.contestrank.start_time = window.start_time = dayjs(d.start_time);
            _.forEach(d.data,function(val){
                val.start_time = dayjs(d.start_time);
            })
            window.contestrank.scoreboard = d.data;
            window.temp_data = d.data;
            data = d.data;
            window.contestrank.title = d.title;
        });
    }
    var $logout=$(".logout");
    $logout.on('click',function(){
    $.get("/api/logout",function(data){
        location.href="../logout.php";
    });
});
</script>
  </body>
</html>
