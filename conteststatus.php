<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title><?php echo $OJ_NAME ?> -- Status</title>
    <?php include("template/semantic-ui/css.php"); ?>
    <?php include("template/semantic-ui/js.php"); ?>
    <script src="/template/semantic-ui/js/Chart.bundle.min.js"></script>
    <style>
        .table-scroll{
            overflow: auto
        }
    </style>
</head>

<body>
    <script type="text/x-template" id="statistic_template">
        <div>
            <h3 class="ui dividing header">
                Result Statistics
            </h3>
    <div class="table-scroll">
            <table class="ui padded selectable unstackable table" style="text-align:center" width="90%" v-if="finish">
        <thead v-cloak>
            <th>Problem</th>
            <th v-for="i in statistics.total_result"><a target="_blank" :href="'status.php?cid='+cid+'&jresult='+i">{{statistics.status[i]}}</a></th>
            <th>Total</th>
        </thead>
        <tbody>
            <tr v-for="i in Array.from(Array(statistics.total_problem + 1).keys())">
                <td><a :href="'status.php?cid='+cid+'&problem_id='+(i)" target="_blank">{{(1001 + i)}}</a></td>
                <td v-for="(row,key) in statistics.stat_data[i]" :class="row>0?'active positive':''"><a :href="'status.php?cid='+cid+'&problem_id='+(i)+'&jresult='+key">{{row}}</a></td>
                 <td>{{statistics.totalSumResult[i]}}</td>
            </tr>
            <tr>
                <td><a :href="'status.php?cid='+cid" target="_blank">Total</a></td>
                <td v-for="(row,key) in statistics.stat_sum" :class="row > 0?'active positive':''">
                    <a :href="'status.php?cid='+cid+'&jresult='+key" target="_blank">{{row}}</a>
                </td>
                <td>{{statistics.total_submit}}</td>
            </tr>
        </tbody>
    </table>
    </div>
    <h3 class="ui dividing header">
                Submit Language Statistics
            </h3>
    <div class="table-scroll">
    <table class="ui padded selectable unstackable table" style="text-align:center" width="90%" v-if="finish">
        <thead v-cloak>
            <th>Problem</th>
            <th v-for="i in statistics.used_lang"><a target="_blank" :href="'status.php?cid='+cid+'&language='+i">{{language_name.local[i]}}</a></th>
            <th>Total</th>
        </thead>
        <tbody>
            <tr v-for="i in Array.from(Array(statistics.total_problem + 1).keys())">
                <td><a :href="'status.php?cid='+cid+'&problem_id='+(1001 + i)" target="_blank">{{(1001 + i)}}</a></td>
                <td v-for="(row,key) in statistics.lang_data[i]" :class="row>0?'active positive':''"><a :href="'status.php?cid='+cid+'&problem_id='+(1001 + i)+'&language='+key">{{row}}</a></td>
                <td>{{statistics.totalSumProblem[i]}}</td>
            </tr>
            <tr>
                <td><a :href="'status.php?cid='+cid" target="_blank">Total</a></td>
                <td v-for="(row,key) in statistics.lang_sum" :class="row > 0?'active positive':''">
                    <a :href="'status.php?cid='+cid+'&language='+key" target="_blank">{{row}}</a>
                </td>
                <td>{{statistics.total_submit}}</td>
            </tr>
        </tbody>
    </table>
    </div>
            </div>
    </script>
<script type="text/x-template" id="status_table">
    <table class="ui padded selectable unstackable table" align="center" width="90%" v-if="finish">
        <thead v-cloak>
        <tr class='toprow'>
            <th width="7%">{{target.solution_id}}</th>
            <th width="15%"><div class="ui grid">
            <div class="four wide column"></div><div class="twelve wide column">{{target.user}}</div></div></th>
            <th width="7%">{{target.problem_id}}</th>
            <th width="18%">{{target.result}}</th>
            <th width="10%">{{target.memory+"/"+target.time}}</th>
            <th width="13%">{{target.language+"/"+target.length}}</th>
            <th width="18%">{{target.submit_time}}</th>
            <th width="10%">{{target.judger}}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="row in problem_lists" :class="row.sim?'warning need_popup':'need_popup'" :data-html="'<b>IP:'+row.ip+'</b><br><p>类型:'+detect_place(row.ip)+'</p><p>用户指纹:<br>'+row.fingerprint+'<br>硬件指纹:<br>'+row.fingerprintRaw+'</p>'">
            <td>{{row.solution_id}}</td>
            <td><div class="ui grid">
            <div class="three wide column" style="margin:auto">
            <img class="ui avatar image" :src="'../avatar/'+row.user_id+'.jpg'" v-if="row.avatar||user[row.user_id].avatar" style="object-fit: cover;">
            <img class="ui avatar image" src="../image/default-user.png" v-else style="object-fit: cover;">
            </div>
            <div class="twelve wide column">
            <a :href="'userinfo.php?user='+row.user_id">{{row.user_id}}<br>{{row.nick}}</a>
            </div>
            </div>
            </td>
            <td>
                <div class=center><a :href="(row.oj_name === 'local'?'new':(!row.oj_name?'new':row.oj_name.toLowerCase()))+'submitpage.php?cid='+row.contest_id+'&pid='+row.num">{{end?((row.oj_name === "local"?"":row.oj_name.toUpperCase())+row.problem_id):(row.num + 1001)}}</a>
                </div>
            </td>
            <td><a :href="(row.result == 11?'ce':'re')+'info.php?sid='+row.solution_id"
                   v-cloak :class="answer_class[row.result]" title='点击看详细'><i v-cloak :class="answer_icon[row.result]+' icon'"></i>{{result[row.result]}}</a>
                   <a v-if="row.sim" :href="'comparesource.php?left='+row.solution_id+'&right='+row.sim_id" v-cloak :class="answer_class[row.result]"><br>
                   {{(Boolean(row.sim) === false?'':row.sim_id+' ('+row.sim+'%)')}}
                   </a>
                   <br>
                   <a :class="answer_class[row.result]" v-if="row.result !== 4 && row.pass_rate > 0.05"><i :class="answer_icon[row.result]+' icon'" style="opacity:0"></i>Passed:{{(row.pass_rate*100).toString().substring(0,4)}}%</a>

            </td>
            <td>
                <div id=center>
                <span class="boldstatus">{{memory_parse(row.memory)}}</span>
                <br>
                <span class="boldstatus">{{time_parse(row.time)}}</span></div>
            </td>
            <td><a class="boldstatus" v-if="self === row.user_id || isadmin" target=_blank :href="'showsource.php?'+((!row.oj_name || row.oj_name === 'local')?'':'h')+'id='+row.solution_id">查看</a>
                <span class="boldstatus" v-else>{{language_name[(!row.oj_name?'local':row.oj_name.toLowerCase())][row.language]}}</span>
                <span v-if="(self === row.user_id || isadmin) && row.problem_id"> / </span>
                <a class="boldstatus" v-if="(self === row.user_id || isadmin) && row.problem_id" target="_blank"
                   :href="(row.oj_name === 'local'?'new':(!row.oj_name?'local':row.oj_name.toLowerCase()))+'submitpage.php?cid='+Math.abs(row.contest_id)+'&pid='+row.num+'&sid='+row.solution_id">编辑</a>
                <br>
                <span class="boldstatus" v-if="self === row.user_id || isadmin">{{language_name[(!row.oj_name?'local':row.oj_name.toLowerCase())][row.language]}}  / </span>
                <span class="boldstatus">{{row.length}}B</span>
            </td>
            <td>{{new Date(row.in_date).toLocaleString()}}<br><p>类型:{{detect_place(row.ip)}}</p></td>
            <td>{{row.judger}}</td>
        </tr>
        </tbody>
    </table>
</script>
<?php include("template/semantic-ui/nav.php"); ?>
<div>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="padding ui container">
        <h2 class="ui dividing header">
            Status
        </h2>
        <div class="ui top attached tabular menu">
            <a v-cloak :class="(current_tag == 'status'?'active':'')+' item'" @click="tag('status',$event)" id="submitstatus">提交状态</a>
            <a v-cloak :class="(current_tag == 'graph'?'active':'')+' item'" @click="tag('graph',$event)" id="graph">提交图表</a>
            <a v-cloak :class="(current_tag == 'statistics'?'active':'')+' item'" @click="tag('statistics',$event)" id="statistics">提交统计</a>
        </div>
        <div class="ui bottom attached segment" v-show="current_tag == 'status'">
            <div align=center class="input-append">
                <form id=simform class="ui form segment" action="status.php" method="get">
                    <div class="four fields">
                        <div class="field">
                            <label>编号</label>
                            <div class="ui fluid search dropdown selection" size="1" id="cur_problem">
                                <input v-model="problem_id" @change="problem_id=$event.target.value"
                                       type="hidden" name="problem_id">
                                <i class="dropdown icon"></i>
                                <div class="default text">All</div>
                                <div class="menu">
                                    <div class='item' data-value=''>未选择</div>
                                    <div v-for="i in Array.from(Array(total).keys())" class="item" :data-value="i">
                                        {{1001 + i}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label>用户</label>
                            <input v-model="user_id" class="form-control" type=text size=4 name=user_id
                                   value=''>
                        </div>
                        <div class="field">
                            <label>语言</label>
                            <div class="ui fluid search dropdown selection" size="1">
                                <input v-model="language" @change="language=$event.target.value"
                                       type="hidden" name="language">
                                <i class="dropdown icon"></i>
                                <div class="default text">All</div>
                                <div class="menu">
                                    <div class='item' data-value='-1'>All<i class="dropdown icon"
                                                                            style="visibility: hidden; "></i></div>
                                    <div class="item" :data-value="i" v-for="i in Array.from(Array(language_name?language_name.local?language_name.local.length:0:0).keys())">
                                        <i :class="language_icon[i]+' color'"></i>
                                        {{language_name.local[i]}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label>结果</label>
                            <div class="ui fluid search dropdown selection" size="1">
                                <input v-model="problem_result" @change="problem_result=$event.target.value"
                                       type="hidden" name="jresult">
                                <i class="dropdown icon"></i>
                                <div class="default text">All</div>
                                <div class="menu">
                                    <div class='item' data-value='-1'>All<i class="dropdown icon"
                                                                            style="visibility: hidden; "></i></div>
                                    <div class="item" :data-value="i" v-for="i in Array.from(Array(judge_color ? judge_color.length : 0).keys())">
                                        <span :class="judge_color[i]">
                                        <i :class="judge_icon[i]+' icon'"></i>
                                        {{result[i]}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="four fields center aligned">
                        <div class="field">
                            <div class="ui toggle checkbox">
  <input type="checkbox" @click="auto_refresh=!auto_refresh" checked="true">
  <label>自动刷新</label>
</div>
                        </div>
                        <div class="field" style="margin:auto">
                        <div class="ui toggle checkbox">
  <input type="checkbox" @click="sim_checkbox=!sim_checkbox">
  <label>仅显示判重提交</label>
</div>
</div>
                        <div class="field">
                        <button class="ui labeled icon mini button" @click.prevent="search($event)"><i
                                class="search icon"></i><?= $MSG_SEARCH ?></button>
                                </div>
                        <div class="field"></div>
                    </div>
                </form>
            </div>
            <br>
            <div class="row">
                <status-table :target="target" :problem_list="problem_list"
                              :answer_class="judge_color" :answer_icon="icon_list" :language_name="language_name"
                              :self="self"
                              :result="result"
                              :end="end"
                              :isadmin="isadmin"
                              :finish="finish"></status-table>
                <div class="ui active inverted dimmer" v-if="dim"><div class="ui large text loader">Loading</div></div>
            </div>
            <br>
            <div class="row">
                <a v-cloak :class="'ui button '+(page_cnt == 0?'disabled':'')" @click="page_cnt != 0 && page(-page_cnt,$event)">Top</a>
                <div class="ui buttons">
                    <button v-cloak :class="'ui left labeled icon button '+(page_cnt == 0?'disabled':'')"
                            @click="page_cnt!=0&&page(-1,$event)">
                        <i class="left arrow icon"></i>
                        Prev
                    </button>
                    <div class="or" v-cloak></div>
                    <button v-cloak :class="'ui right labeled icon button '+(problem_list.length == 0?'disabled':'')"
                            @click="problem_list.length != 0 && page(1,$event)">
                        <i class="right arrow icon"></i>
                        Next
                    </button>
                </div>
            </div>
        </div>
        <div class="ui attached bottom segment" v-show="current_tag == 'graph'">
            <div style="width:75%;margin:auto">
                <canvas id="canvas"></canvas>
            </div>
        </div>
        <div class="ui attached bottom segment" v-show="current_tag == 'statistics'">
            <statistic-table :statistics="statistics" :cid="cid" :finish="finish" :language_name="language_name"></statistic-table>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script>
    function draw(_result) {
        var result = _result.result;
        var _label = _result.label;
        var row = {};
        var _labels = [];
        var _submits = [];
        var _accepteds = [];
        var _persent = [];
        _.forEach(result,function(i){
            if(i[_label[0]]&&i[_label[1]]){
                _labels.push(i[_label[0]]+"-"+i[_label[1]]);
                _submits.push(i.submit||0);
                _accepteds.push(i.accepted||0);
                _persent.push((i.accepted/i.submit*100).toString().substring(0,5));
            }
        })
        var config = {
            type: 'line',
            data: {
                labels: _labels,
                datasets: [{
                    label: "总提交",
                    yAxisID: "submit",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: _submits,
                    fill: false,
                }, {
                    label: "正确",
                    yAxisID: "submit",
                    fill: false,
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
                    data: _accepteds,
                }, {
                    label: "正确率",
                    fill: false,
                    yAxisID: "per",
                    backgroundColor: window.chartColors.green,
                    borderColor: window.chartColors.green,
                    data: _persent,
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: '统计信息'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: _label[0]+" - "+_label[1]
                        }
                    }],
                    yAxes: [{
                        id: "submit",
                        position: "left",
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: '提交'
                        }
                    }, {
                        id: 'per',
                        type: 'linear',
                        position: 'right',
                        scaleLabel: {
                            display: true,
                            labelString: '正确率'
                        },
                        ticks: {
                            max: 100,
                            min: 0
                        }
                    }]
                }
            }
        };
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myLine = new Chart(ctx, config);
    }

</script>
<script>
Vue.component("statistic-table", {
    template: "#statistic_template",
    props: {
        statistics: Object,
        cid: String,
        finish: Boolean,
        language_name: Object
    },
    data:function(){return{};}
})
    Vue.component("status-table", {
        template: "#status_table",
        props: {
            problem_list: Array,
            answer_icon: Array,
            answer_class: Array,
            target: Object,
            language_name: Array,
            result: Array,
            self: String,
            isadmin: Boolean,
            end:Boolean,
            finish:Boolean
        },
        data: function () {
            return {
                problem_alpha:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789",
                user:{}
            };
        },
        methods: {
            memory_parse: function (_memory) {
                var unit = ["KB", "MB", "GB"];
                var cnt = 0;
                var memory = parseInt(_memory);
                if(isNaN(memory)){
                    return _memory;
                }
                while (memory > 1024) {
                    memory /= 1024;
                    ++cnt;
                }
                return memory.toString().substring(0, 5) + unit[cnt];
            },
            time_parse: function (_time) {
                var unit = ["ms", "s"];
                var cnt = 0;
                var time = parseInt(_time);
                if(isNaN(time)){
                    return _time;
                }
                while (time > 1000) {
                    ++cnt;
                    time /= 1000;
                }
                return time.toString().substring(0, 5) + unit[cnt];
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
            }
        },
        computed: {
            problem_lists: function () {
                var that = this;
                _.forEach(this.problem_list,function(i){
                    that.user[i.user_id] = that.user[i.user_id] || i;
                })
                var doc = document.createElement("div");
                for(let i in this.problem_list) {
                    doc.innerHTML = this.problem_list[i].nick;
                    this.problem_list[i].nick = doc.innerText;
                }
                return this.problem_list;
            }
        }
    })
    var problemStatus = window.problemStatus = new Vue({
        el: ".ui.container.padding",
        data: function(){
            return {
            problem_list: [],
            icon_list: [],
            judge_color: [],
            target: {},
            language_name: [],
            language_icon:[],
            judge_icon:[],
            auto_refresh:true,
            result: [],
            self: "",
            isadmin: false,
            problem_id: null,
            user_id: null,
            language: -1,
            problem_result: -1,
            sim_checkbox:false,
            page_cnt: 0,
            current_tag : "status",
            dim:false,
            end:false,
            finish:false,
            total:0,
            stat:[],
            cid:getParameterByName("cid"),
            res:["WT","WR","CPL","RN","AC","PE","WA","TLE","MLE","OLE","RE","CE","CF","TR",
            "SP","SR","SE"]
            }
        },
        watch: {
            sim_checkbox: function(newVal, oldVal) {
                this.search();
            }
        },
        computed: {
            statistics:{
                get:function(){
                    var that = this;
                    var lang = {};
                    var status = {};
                    var maxResult = 0;
                    var minResult = 19;
                    var maxNum = 0;
                    var used_lang = {};
                    _.forEach(this.stat,function(val,index){
                        if(!status[val.num]) {
                            status[val.num] = {};
                        }
                        if(!lang[val.num]) {
                            lang[val.num] = {};
                        }
                        maxResult = Math.max(maxResult,val.result);
                        minResult = Math.min(minResult,val.result);
                        maxNum = Math.max(maxNum,val.num);
                        if(!status[val.num][val.result])
                        status[val.num][val.result] = 0;
                        lang[val.num][val.language] = 0;
                        used_lang[val.language] = val.language;
                    });
                    used_lang = _.values(used_lang);
                    used_lang.sort(function(a,b){return a-b;});
                    for(var i = 0;i<=maxNum;++i) {
                        if(!status[i]) {
                            status[i] = {};
                        }
                    }
                    _.forEach(status,function(val,index){
                        for(var i = minResult;i<= maxResult;++i) {
                            status[index][i] = 0;
                        }
                    })
                    for(var i = 0;i<=maxNum;++i) {
                        if(!lang[i]) {
                            lang[i] = {};
                        }
                        _.forEach(used_lang,function(val){
                            lang[i][val] = 0;
                        })
                    }
                    _.forEach(this.stat,function(val,index){
                        ++status[val.num][val.result];
                        ++lang[val.num][val.language];
                    });
                    _.forEach(lang,function(val,index){
                        _.forEach(used_lang,function(val){
                            if(!lang[index][val]){
                                lang[index][val] = 0;
                            }
                        })
                    })
                    var totalSumProblem = {};
                    _.forEach(status,function(val,index){
                        if(!totalSumProblem[index]) {
                            totalSumProblem[index] = 0;
                        }
                        _.forEach(status[index],function(val2){
                            totalSumProblem[index]+=val2;
                        })
                    })
                    var totalSumResult = {};
                    _.forEach(lang,function(val,index){
                        if(!totalSumResult[index]) {
                            totalSumResult[index] = 0;
                        }
                        _.forEach(lang[index],function(val2){
                            totalSumResult[index] += val2;
                        })
                    })
                    var langsum = {};
                    _.forEach(lang,function(val){
                        _.forEach(val,function(v,idx){
                            if(!langsum[idx]) {
                                langsum[idx] = v;
                            }
                            else {
                                langsum[idx] += v;
                            }
                        });
                    });
                    var statsum = {};
                    _.forEach(status,function(val){
                        _.forEach(val,function(v,idx){
                            if(!statsum[idx]) {
                                statsum[idx] = v;
                            }
                            else {
                                statsum[idx] += v;
                            }
                        })
                    })
                    var d = _.reduce(totalSumProblem,function(a,b){return a + b},0);
                    if(maxResult === 0) {
                        maxNum = -1;
                    }
                    var tot_res = [];
                    for(var i = minResult;i<=maxResult;++i)tot_res.push(i);
                    return {
                        total_problem:maxNum||0,
                        total_result:tot_res||[],
                        status:this.res||[],
                        stat_data:status||[],
                        used_lang:used_lang,
                        lang_data:lang,
                        lang_sum:langsum||[],
                        stat_sum:statsum||[],
                        totalSumResult:totalSumResult,
                        totalSumProblem:totalSumProblem,
                        total_submit:d
                    }
                },
                set:function(val) {
                    if(val.status == "OK") {
                        this.total = val.total;
                        this.stat = JSON.parse(JSON.stringify(val.data));
                    }
                }
            }
        },
        methods: {
            getUserId:function(){
                return getParameterByName("user_id")
            },
            getResult:function(){
                return getParameterByName("jresult")
            },
            getProblemID:function(){
                return getParameterByName("problem_id")
            },
            getLanguage:function(){
                return getParameterByName("language")
            },
            setQuery:function(){
                var queryobject = {
                    cid:this.cid
                };
                if(this.user_id && this.user_id.length > 0)
                    queryobject["user_id"] = this.user_id;
                if(this.problem_result && this.problem_result!== -1)
                    queryobject["jresult"] = this.problem_result;
                if(this.problem_id && this.problem_id !== 0)
                    queryobject["problem_id"] = this.problem_id;
                if(this.language && this.language !== -1)
                    queryobject["language"] = this.language;
                var url = location.origin+location.pathname+"?"+$.param(queryobject);
                if(url !== location.origin+location.pathname+"?")
                    history.pushState({},0,url);
            },
            search_func: function (data) {
                var that = this;
                this.setQuery();
                that.problem_list = data.result;
                that.icon_list = data.const_list.icon_list;
                that.judge_color = data.const_list.judge_color;
                that.judge_icon = data.const_list.judge_icon;
                that.language_icon = data.const_list.language_icon;
                
                that.target = data.const_list.language.cn.status;
                that.language_name = data.const_list.language_name;
                that.result = data.const_list.result.cn;
                that.self = data.self;
                that.end = data.end;
                that.isadmin = data.isadmin || data.browse_code
            },
            search: function ($event) {
                this.dim = true;
                this.page_cnt = 0;
                var problem_id = this.problem_id || "null";
                var user_id = this.user_id || "null";
                var language = this.language == -1 ? "null" : this.language;
                var result = this.problem_result == -1 ? "null" : this.problem_result;
                var page_cnt = this.page_cnt * 20;
                var cid = this.cid||"";
                var sim = Number(this.sim_checkbox);
                var that = this;
                $.get("../api/status/" + problem_id + "/" + user_id + "/" + language + "/" + result + "/" + page_cnt+"/"+cid+ "/" + sim, function (data) {
                    that.dim = false;
                    that.search_func(data);
                })
            },
            page: function (num, $event) {
                this.dim = true;
                this.page_cnt += num;
                var problem_id = this.problem_id || "null";
                var user_id = this.user_id || "null";
                var language = this.language == -1 ? "null" : this.language;
                var result = this.problem_result == -1 ? "null" : this.problem_result;
                var page_cnt = this.page_cnt * 20;
                var cid = this.cid||"";
                var sim_checkbox = Number(this.sim_checkbox);
                var that = this;
                $.get("../api/status/" + problem_id + "/" + user_id + "/" + language + "/" + result + "/" + page_cnt+"/"+cid + "/" + sim_checkbox, function (data) {
                    that.dim = false;
                    that.search_func(data);
                })
            },
            submit: function (data) {
                if(!this.auto_refresh) {
                    return;
                }
                var obj = {};
                if((!this.user_id || this.user_id === data.user_id)&&(!~this.problem_result||data.val.result === this.problem_result) && (this.language === -1 || this.language === data.val.language) && !this.page_cnt && (!this.problem_id || parseInt(this.problem_id) === Math.abs(data.val.pid))) {
                obj.problem_id = Math.abs(data.val.id);
                obj.solution_id = data.submission_id;
                obj.nick = data.nick;
                obj.user_id = data.user_id;
                obj.contest_id = parseInt(data.contest_id);
                obj.num = parseInt(data.val.pid);
                obj.length = data.val.source.length;
                obj.sim = false;
                obj.language = data.val.language;
                obj.memory = obj.time = 0;
                obj.in_date = new Date().toISOString();
                obj.ip = data.val.ip;
                obj.fingerprint = data.val.fingerprint;
                obj.judger = "RATH";
                obj.avatar = !!data.val.avatar;
                obj.result = 0;
                this.problem_list.pop();
                this.problem_list.unshift(obj);
                }
            },
            update: function (data) {
                if(!this.auto_refresh) {
                    return;
                }
                var solution_id = data.solution_id;
                var status = data.state;
                var time = data.time;
                var memory = data.memory;
                var pass_rate = data.pass_rate;
                var contest_id = parseInt(data.contest_id);
                var num = parseInt(data.num);
                var sim = parseInt(data.sim);
                var sim_s_id = parseInt(data.sim_s_id);
                for (let i of this.problem_list) {
                    if (i.solution_id == solution_id) {
                        i.result = status;
                        i.time = time;
                        i.memory = memory;
                        i.pass_rate = pass_rate;
                        i.contest_id = contest_id;
                        i.num = num;
                        i.sim = sim;
                        i.sim_id = sim_s_id;
                        return;
                    }
                }
            },
            tag:function(tag_name,$event){
                this.current_tag = tag_name;
            }
        },
        updated: function() {
            $("tr").popup({
                    on: 'hover',
                    positon: "top center"
                });
            this.$nextTick(function(){
                $("#cur_problem").dropdown("set selected",this.problem_id);
            })
        },
        created: function () {
            var that = this;
            var problem_id = (this.problem_id = this.getProblemID()||null) || "null";
            var user_id = (this.user_id = this.getUserId()||null) || "null";
            var language = (this.language = this.getLanguage()||-1)==-1 ? "null" : this.getLanguage();
            var result = (this.problem_result = this.getResult()||-1) == -1 ? "null" : this.problem_result;
            var cid = this.cid||"";
            $.get("../api/status/" + problem_id + "/" + user_id + "/" + language + "/" + result + "/0/"+cid, function (data) {
                    that.dim = false;
                    that.finish = true;
                    that.search_func(data);
                })
                $.get("../api/status/" + problem_id + "/" + user_id + "/" + language + "/" + result + "/0/"+cid, function (data) {
                    that.dim = false;
                    that.search_func(data);
                })
        },
        mounted: function () {
            var that = this;
            $.get("../api/status/graph?cid="+this.cid,function(data){
                draw(data);
            })
            $.get("../api/contest/statistics/"+this.cid,function(data){
                that.statistics = data;
            })
        }
    })
</script>
<?php include("template/semantic-ui/bottom.php"); ?>
</body>
</html>
