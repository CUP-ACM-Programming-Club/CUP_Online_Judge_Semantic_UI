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
    <?php include("template/$OJ_TEMPLATE/css.php");?>	    

 <?php include("template/$OJ_TEMPLATE/js.php");?>
 <script src="/js/dayjs.min.js"></script>
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
    </style>
  </head>

  <body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>	   
    <div class="contestrank scoreboard padding" v-cloak>
        <h2 class="ui dividing header">
            Contest Rank
            <div class="sub header">
                 {{title}}
            </div>
        </h2>
     
     <div class="ui grid">
         <div class="row">
             <div class="left aligned twelve wide column">
                 <h3 class="ui header">
                     当前时间:{{current_time}}
                     <div class="sub header">
                         起始时间:{{dayjs(start_time).format("YYYY-MM-DD HH:mm:ss")}},已经过
                         {{format_date(dayjs().diff(start_time,"second"))}}
                     </div>
                 </h3>
             </div>
             <div class="right aligned four wide column">
                 <a class="ui primary mini button" :href="'contestrank.xls.php?cid='+cid">Save to XLS</a>
             </div>
         </div>
         <div class="row">
             <div style="width:100%;height:100%;overflow:auto" class="ranking">
<table id='rank' class="ui small celled table">
    <thead><tr class=toprow align=center><th class="{sorter:'false'}" width=5%>Rank<th width=5%>User</th><th width=20% class="left aligned">Nick</th><th width=5%>Solved</th><th width=5%>Penalty</th>
<th width="5%" v-for="i in Array.from(Array(total).keys())">{{String.fromCharCode(i + 65)}}</th>
</thead>
<tbody>
    <tr v-for="row in submitter">
        <td style="text-align:center;font-weight:bold">{{row.rank}}</td>
        <td style="text-align:center"><a :href="'userinfo.php?user='+row.user_id" target="_blank">{{row.user_id}}</a></td>
        <td><a :href="'userinfo.php?user='+row.user_id" target="_blank">{{convertHTML(row.nick)}}</a></td>
        <td style="text-align:center"><a :href="'status.php?user_id=' + row.user_id + '&cid=' + cid">{{row.ac}}</a></td>
        <td style="text-align:center">{{format_date(row.penalty_time)}}</td>
        <td v-for="p in row.problem" style="text-align:center" 
        :class="p.accept.length > 0?p.first_blood ? 'first accept':'accept':''">
            <b :class="'text '+ (p.accept.length > 0 ? p.first_blood?'first accept':'accept':'red')">
                {{ (p.accept.length > 0 || p.submit.length > 0)?'+':''}}
                {{p.try_time > 0 ? p.try_time : p.submit.length > 0?p.submit.length : ""}}</b>
            <br v-if="p.accept.length > 0">
            <span v-if="p.accept.length > 0" :class="p.first_blood?'first accept text':''">
                {{format_date(p.accept[0].diff(start_time,'second'))}}
            </span>
        </td>
    </tr>
</tbody>
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
</script>
<style>
.well{
   background-image:none;
   padding:1px;
}
td{
   white-space:nowrap;

}
</style>
<script>
    $(".ranking").height(window.screen.availHeight-($(".ui.vertical.center").outerHeight()+$("center").outerHeight())-105);
</script>
<script>
    var cid = getParameterByName("cid");
    $.get("/api/scoreboard/" + cid,function(d) {
    window.contestrank = new Vue({
       el:".contestrank.scoreboard",
       data:{
           cid: getParameterByName("cid"),
           _scoreboard:null,
           submitter:{},
           total:0,
           start_time:dayjs(),
           first_blood:{},
           title:"",
           current_time:dayjs().format("YYYY-MM-DD HH:mm:ss")
       },
       computed:{
           scoreboard:{
               get:function(){
                   
               },
               set:function(val){
                   var len = val.length;
                   var submitter = this.submitter;
                   var total = this.total;
                   var that = this;
                   for(var i = 0;i<len;++i)
                   {
                       val[i].in_date = val[i].in_date;
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
                            )
                       }
                       else {
                           submitter[val[i].user_id].problem[val[i].num].submit.push(
                               val[i].in_date
                           )
                       }
                   }
                   var _submitter = [];
                   _.forEach(submitter,function(val,index){
                       val.user_id = index;
                       _submitter.push(val);
                   })
                   // var _submitter = _.values(submitter);
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
                               var difftime =  v.accept[0].diff(that.start_time,'second');
                               val.penalty_time += difftime;
                               if(~that.first_blood[idx]) {
                                   that.first_blood[idx] = Math.min(that.first_blood[idx],difftime);
                               }
                               else {
                                   that.first_blood[idx] = difftime;
                               }
                           }
                       })
                   })
                   
                   _.forEach(_submitter,function(val){
                       _.forEach(val.problem,function(v,idx){
                           if(v.accept.length > 0 && v.accept[0].diff(that.start_time,'second') === that.first_blood[idx]) {
                               v.first_blood = true;
                           }
                           else {
                               v.first_blood = false;
                           }
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
                   })
                   
                   that.submitter = _submitter;
                   console.log(_submitter);
                   that._scoreboard = val;
               }
           }
       },
       methods:{
           format_date:function(second) {
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
               return hour + ":" + minute + ":" + sec;
           },
           convertHTML:function(str){
               var d = document.createElement("div");
               d.innerHTML = str;
               return d.innerText;
           }
       },
       mounted:function(){
           var that = this;
           
               that.start_time = dayjs(d.start_time);
               that.total = d.total;
               that.title = d.title;
               for(var i = 0;i<that.total;++i) {
                   that.first_blood[i] = -1
               }
               that.scoreboard = d.data;
               that.$nextTick(function(){
                   metal();
                   $(".ranking").height($(window).height()-($(".hidemenu").outerHeight())-150);
               })
               var $title = $("title").html();
               $("title").html("Contest " + that.cid + " - " + that.title + $title);
               setInterval(function(){
                   that.current_time = dayjs().format("YYYY-MM-DD HH:mm:ss");
               },1000);
       },
       created:function(){
           
       }
   })
    })
</script>
  </body>
</html>
