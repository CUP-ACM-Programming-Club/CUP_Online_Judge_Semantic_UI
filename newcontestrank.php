<!DOCTYPE html>
<html lang="en"  style="overflow: auto;">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $OJ_NAME?></title>  
    <?php include("template/semantic-ui/css.php");?>	    

 <?php include("template/semantic-ui/js.php");?>
 <script src="/js/dayjs.min.js"></script>
<script src="https://fastcdn.org/FileSaver.js/1.1.20151003/FileSaver.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.4/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/TableExport/5.0.0/js/tableexport.min.js"></script>
<script src="https://cdn.rawgit.com/mozilla/localForage/master/dist/localforage.js"></script>
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
        起始时间:{{dayjs(start_time).format("YYYY-MM-DD HH:mm:ss")}},已经过
        {{format_date(dayjs().diff(start_time,"second"))}}
    </div>
    </h3>
</script>
    <div class="contestrank scoreboard padding" v-cloak>
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
<th style="min-width: 85.71px;" v-for="i in Array.from(Array(total).keys())">{{1001 + i}}</th>
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
            <span v-if="p.accept.length > 0" :class="p.first_blood?'first accept text':''">
                {{format_date(p.accept[0].diff(p.start_time,'second'))}}
            </span>
        </td>
    </tr>
</tbody>
</table>
<table id="save" class="ui small celled table" style="display:none">
    <thead><tr class=toprow align=center><th width=5%>Rank<th width=5%>User</th><th style="min-width:90px">Nick</th><th width=5%>Solved</th><th width=5%>Penalty</th>
<th style="min-width: 85.71px;" v-for="i in Array.from(Array(total).keys())">{{1001 + i}}</th>
</thead>
<tbody>
    <tr v-for="row in submitter">
        <td style="text-align:center;font-weight:bold">{{row.rank}}</td>
        <td style="text-align:center"><a :href="'userinfo.php?user='+row.user_id" target="_blank">{{row.user_id}}</a></td>
        <td style="text-align:center"><a :href="'userinfo.php?user='+row.user_id" target="_blank">{{convertHTML(row.nick)}}</a></td>
        <td style="text-align:center"><a :href="'status.php?user_id=' + row.user_id + '&cid=' + cid">{{row.ac}}</a></td>
        <td style="text-align:center">{{format_date(row.penalty_time,1)}}</td>
        <td v-for="p in row.problem" style="text-align:center" 
        :class="p.accept.length > 0?p.first_blood ? 'first accept':'accept':''">
            <b :class="'text '+ (p.accept.length > 0 ? p.first_blood?'first accept':'accept':'red')">
                {{ (p.submit.length > 0)?'(-':''}}{{p.try_time > 0 ? p.try_time + ")" : p.submit.length > 0?p.submit.length + ")" : ""}}{{p.accept.length > 0 ? format_date(p.accept[0].diff(p.start_time,'second')):""}}</b>
        </td>
    </tr>
</tbody>
</table>
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
        
    })
    
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
                   return hour + " : " + minute + " : " + sec;
               }
               else
                    return hour + ":" + minute + ":" + sec;
           }
    }
});
var temp_data = [];
var contestrank = window.contestrank = new Vue({
       el:".contestrank.scoreboard",
       data:{
           cid: getParameterByName("cid"),
           submitter:{},
           total:0,
           start_time:false,
           title:""
       },
       computed:{
           scoreboard:{
               get:function(){
                   
               },
               set:function(val){
                   var that = this;
                   if(val && val.length) {
                   _.forEach(val,function(v){
                        temp_data.push(v);
                   });
                   }
                   else {
                       temp_data.push(val);
                   }
                   val = temp_data;
                    console.log(val);
                   var first_blood = [];
                   for(var i = 0;i<that.total;++i) {
                       first_blood.push(-1);
                   }
                   var len = val.length;
                   var submitter = this.submitter = {};
                   var total = this.total;
                   var that = this;
                   for(var i = 0;i<len;++i)
                   {
                       if(!val[i].nick)continue;
                       val[i].nick = val[i].nick.trim();
                       if(!submitter[val[i].user_id]) {
                           submitter[val[i].user_id] = {
                               ac:0,
                               nick:val[i].nick,
                               problem:{},
                               penalty_time:0
                           }
                           for(var j = 0;j<total;++j) {
                               submitter[val[i].user_id].problem[j] = {
                                   submit:[],
                                   accept:[]
                                }
                           }
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
                       val.rank = rnk++;
                   });
                   window.datas = that.submitter = _submitter;
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
           convertHTML:function(str){
               var d = document.createElement("div");
               d.innerHTML = str;
               return d.innerText;
           },
           exportXLS:function(){
               var table = TableExport(document.getElementById("save"));
               var d = table.getExportData().save.xlsx;
               var filename = "Contest " + this.cid;
               filename = filename.substring(0,31);
               table.export2file(d.data,d.mimeType,filename,d.fileExtension,d.merges)
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
    var finished = false;
    var cstring = cidArr.join(",");
    function work(){
        cid = cidArr.shift();
        $.get("/api/scoreboard/"+cid);
        $.get("/api/scoreboard/"+cid,function(d){
            _.forEach(d.data,function(val,idx){
                val.num += cnt;
                val.start_time = dayjs(d.start_time);
            });
            _.forEach(d.data,function(val){
                data.push(val);
            })
            cnt += d.total;

            if(cidArr.length > 0) {
            work();
            }
            else {
                finished = true;
                window.contestrank.total = cnt;
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
            finished = true;
            window.contestrank.total = d.total;
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
</script>
  </body>
</html>
