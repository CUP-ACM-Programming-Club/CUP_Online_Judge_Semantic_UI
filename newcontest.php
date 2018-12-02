<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title><?php echo $OJ_NAME ?></title>
    <?php include("template/semantic-ui/css.php"); ?>
    <?php include("template/semantic-ui/js.php"); ?>
</head>

<body>
<?php include("template/semantic-ui/nav.php");?>
<script type="text/x-template" id="contest_detail">
    <div class="ui raised segment">
                    <h1></h1>
                    <h2 class="ui header" style="text-align:center"> <i class="star outline icon"></i>Contest {{cid}}</h2>
  <h2 class="ui header" style="text-align:center">{{title}}</h2>
 <p>{{description}}</p>
 <!--2018年 中国石油大学（北京）团委 不发ICPC/CCPC奖学金-->
 <center>Start Time {{start_time.format("YYYY-MM-DD HH:mm:ss")}}
<br> &nbsp;Now&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id=nowdate >{{now.format("YYYY-MM-DD HH:mm:ss")}}</span>
<br>End Time&nbsp;&nbsp;{{end_time.format("YYYY-MM-DD HH:mm:ss")}}
</center>
<div :class="'ui top right attached label ' + (private ?'red':'green')">{{private?'Private':'Public'}}</div>
<div :class="'ui top left attached ' + (now.isAfter(end_time)?'red':now.isBefore(start_time)?'grey':'green') + ' label'">
    <span class="red" v-if="now.isAfter(end_time)">Ended</span>
    <span class="red" v-else-if="now.isBefore(start_time)">Pending</span>
    <span class="green" v-else>Running</span></div>
                <center>
                <div class="row padding">
                <div class="ui buttons mini">
                    <a class="ui button orange" :href="'copystatus.php?cid='+cid" v-if="admin">判重表</a>
                    <a class="ui button yellow" :href="'copymap.php?cid='+cid" v-if="admin">判重图</a>
                </div>
                </div>
                </center>
</div>
</script>
<script type="text/x-template" id="login_form">
<div>
    <h2 class="ui dividing header">私有竞赛/作业</h2>
    <form id='contest_form' method='post' class="ui form">
        <div class="fields">
            <div class="six wide field">
                <label>请输入密码</label>
                <input id='contest_pass' class="input-mini" type="password" name="password">
            </div>
        </div>
        <input class='ui primary button' type="submit">
        </form>
<div>
</script>
<script type="text/x-template" id="not_start">
<div>
<div class="ui negative message">
  <div class="header"><i class="ban icon"></i>
   竞赛尚未开始
  </div>
  <p>请等待竞赛开始后刷新
</p></div>
</div>
</script>
<div class="ui container"  id="contest_table" v-cloak>
    <not-start v-if="mode === 2"></not-start>
    <login-form v-if="mode === 1"></login-form>
     <div class="padding ui container" v-if="mode === 0">
        <h2 class="ui dividing header">
            Contest Problem Set
        </h2>
        <div class="ui grid">
            <div class="row">
                <div class="eleven wide column">
                    <table id='problemset' class='ui basic unstackable table'  width='95%'>
                <thead>
                <tr class='toprow'>
                    <th @click="orderBy(0)" style="text-align: center;"  width="18%" ><a style="cursor:pointer">
                        <i :class="'sort numeric icon ' + (order > 0?'up':'down')" v-if="type === 0"></i>
                        <?php echo $MSG_PROBLEM_ID?></th></a>
                    <th @click="orderBy(1)" width='44%'><a style="cursor:pointer">
                        <i :class="'sort numeric icon ' + (order > 0?'up':'down')" v-if="type === 1"></i>
                        <?php echo $MSG_TITLE?></a></th>
                        <th width="10%"  v-if="now.isAfter(end_time)">
                        </th>
                    <th style="text-align: center;" width='16%' >
                        <a style="cursor:pointer"><span @click="orderBy(2)">
                            <i :class="'sort numeric icon ' + (order > 0?'up':'down')" v-if="type === 2"></i>
                            正确</span></a>/<a style="cursor:pointer"><span @click="orderBy(3)">
                                <i :class="'sort numeric icon ' + (order > 0?'up':'down')" v-if="type === 3"></i>提交</span></a></th>
                    <th @click="orderBy(4)" style="text-align: center;" width='12%'>
                        <a style="cursor:pointer"><i :class="'sort numeric icon ' + (order > 0?'up':'down')" v-if="type === 4"></i>
                        通过率</a></th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="row in problem_table" :class="row.ac === 1?'positive':row.ac === -1?'negative':''">
                        <td class="center aligned">{{row.oj_name?row.oj_name:row.pid?"LOCAL ":""}}{{row.pid}}<br v-if="row.pid">Problem {{row.pnum + 1001}}</td>
                        <td><i class='checkmark icon' v-if="row.ac === 1"></i><i class="remove icon" v-else-if="row.ac === -1"></i><a v-if="dayjs().isBefore(end_time)" :href="'newsubmitpage.php?cid='+cid+'&pid='+row.pnum">{{row.title}}</a>
                            <a v-else :href="'newsubmitpage.php?id=' + row.pid">{{row.title}}</a>
                        </td>
                        <td v-if="now.isAfter(end_time)">
                            <a :href="'tutorial.php?from=' + (row.oj_name?row.oj_name:'local') + '&id=' + row.pid" target="_blank">题解</a>
                        </td>
                        <td style="text-align:center">{{row.accepted}}/{{row.submit}}</td>
                        <td style="text-align: center;">{{(row.accepted * 100 / Math.max(row.submit,1)).toString().substring(0,4)}} %</td>
                    </tr>
                </tbody>
            </table>
                </div>
                <div class="five wide column">
            <div>
                <contest-detail :start_time="start_time"
                :end_time="end_time"
                :description="description"
                :admin="admin"
                :private="private"
                :title="title"
                :cid="cid"></contest-detail>
                <br>
            </div>
                </div>
            </div>
        </div>
            
    </div>
</div>
<script>
Vue.component("login-form",{
    template:"#login_form",
    props:{
        
    },
    data:function(){return{};},
    mounted:function(){
        console.log(this.$root);
        var that = this;
        $('#contest_form').submit(function() {
            $.post('../api/contest/password/' + getParameterByName("cid"),{
            password:$('#contest_pass').val()
            },function(data){
            if(data.status=="OK") {
                that.$root.mode = 0;
            }
            });
            return false; // return false to cancel form action
        });
    }
});
Vue.component("not-start",{
    template:"#not_start",
    props:{},
    data:function(){return{};},
    mounted:function(){}
});
Vue.component("contest-detail",{
    template:"#contest_detail",
    props:{
        start_time:Object,
        end_time:Object,
        description:String,
        title:String,
        admin:Boolean,
        private:Boolean,
        cid:Number
    },
    data:function(){
        return {
            now:dayjs()
        };
    },
    mounted:function(){
        var that = this;
        setInterval(function(){
            that.now = dayjs();
        },1000);
    },
    methods:{
        
    }
})
    window.contestList = new Vue({
        el:"#contest_table",
        data: function(){
            
            return {
                cid:0,
                problem_table:[],
                start_time:dayjs(),
                end_time:dayjs(),
                description:"",
                title:"",
                now:dayjs(),
                contest_mode:0,
                current_mode:0,
                order:1,
                type:0,
                admin:false,
                private:0
            }
        },
        computed:{
            mode:{
                get:function(){
                    return this.current_mode;
                },
                set:function(val) {
                    var diff = val !== this.current_mode;
                    this.current_mode = val;
                    if(diff) {
                        this.run(this.run);
                    }
                }
            }
        },
        mounted:function(){
            this.run(this.run);
        },
        updated:function(){
            
        },
        methods:{
            run:function(resolve){
                var contest_id = getParameterByName("cid");
                var that = this;
                this.cid = parseInt(contest_id);
                $.get("/api/contest/problem/" + contest_id,function(_d){
                if(_d.status !== "OK") {
                    if(_d.statement === "Permission denied") {
                        that.mode = 1;
                        return;
                    }
                    else if(_d.error_code === 101){
                        that.mode = 2;
                        return;
                    }
                }
                _.forEach(_d.data,function(val){
                    if(!val.accepted)val.accepted = 0;
                    if(!val.submit)val.submit = 0;
                })
                that.problem_table = _d.data;
                
                var info = _d.info;
                that.start_time = dayjs(info.start_time);
                that.end_time = dayjs(info.end_time);
                that.title = info.title;
                that.description = info.description;
                that.admin = _d.admin;
                that.contest_mode = info.contest_mode;
                that.private = Boolean(info.private);
                if(typeof resolve === "function") {
                    resolve();
                }
            });
            },
            orderBy: function(type) {
                var that = this;
                if(that.type === type) {
                    that.order = -that.order;
                }
                var problemIdComparator = function(a,b) {
                    return that.order * (a.pnum - b.pnum);
                }
                var titleComparator = function(a,b) {
                    return that.order * (a.title > b.title ? 1:(a.title < b.title ? -1:0));
                }
                var submitComparator = function(a,b) {
                    return that.order * (a.submit - b.submit);
                }
                var acceptedComparator = function(a,b) {
                    return that.order * (a.accepted - b.accepted);
                }
                var correctPercentageComparator = function(a,b) {
                    var currentA = a.accepted / Math.max(a.submit,1);
                    var currentB = b.accepted / Math.max(b.submit,1);
                    return that.order * (currentA - currentB);
                };
                var comparatorSet = [problemIdComparator,titleComparator,acceptedComparator,submitComparator,correctPercentageComparator];
                that.problem_table.sort(comparatorSet[type]);
                that.type = type;
            }
        }
    })
</script>

<?php include("template/semantic-ui/bottom.php") ?>
</body>
</html>
