<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>Status -- <?=$OJ_NAME ?></title>
    <?php include("template/semantic-ui/css.php"); ?>
    <?php include("template/semantic-ui/js.php"); ?>
    <script src="/template/semantic-ui/js/Chart.bundle.min.js"></script>
    <script src="/js/dayjs.min.js"></script>
</head>

<body>
<script type="text/x-template" id="status_table">
    <table class="ui padded selectable unstackable table" align="center" width="90%" v-cloak>
        <thead>
        <tr class='toprow'>
            <th width="8%">{{target.solution_id}}</th>
            <th><div class="ui grid">
            <div class="four wide column"></div><div class="twelve wide column">{{target.user}}</div></div></th>
            <th>{{target.problem_id}}</th>
            <th width="14%">{{target.result}}</th>
            <th v-if="isadmin">{{target.contest_id}}</th>
            <th>{{target.memory}}/{{target.time}}</th>
            <th>{{target.language}}/{{target.length}}</th>
            <th>{{target.submit_time}}/{{target.judger}}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="row in problem_lists" :class="row.sim?'warning':''"
        :data-html="'<b>IP:'+row.ip+'</b><br><p>类型:'+detect_place(row.ip)+'</p><p>用户指纹:<br>'+row.fingerprint+'<br>硬件指纹:<br>'+row.fingerprintRaw+'</p>'">
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
                <div class=center><a :href="'newsubmitpage.php?id='+Math.abs(row.problem_id)">{{Math.abs(row.problem_id)}}</a>
                </div>
            </td>
            <td><a :href="(row.result == 11?'ce':'re')+'info.php?sid='+row.solution_id"
                   v-cloak :class="answer_class[row.result]" title='点击看详细'><i v-cloak :class="answer_icon[row.result]+' icon'"></i>{{result[row.result]}}</a>
                   <br v-if="row.sim||row.pass_rate>0.05 || row.result == 3">
                   <a v-if="row.result == 3 && !!row.pass_point && !!row.total_point" :class="answer_class[row.result]" v-cloak><i :class="answer_icon[row.result]+' icon'" style="opacity:0"></i>({{row.pass_point || 0}}/{{row.total_point || 0}})</a>
                   <a v-if="row.sim" :href="'comparesource.php?left='+row.solution_id+'&right='+row.sim_id" v-cloak :class="answer_class[row.result]">
                   <br v-if="row.result == 3">
                   {{(Boolean(row.sim) === false?'':row.sim_id+' ('+row.sim+'%)')}}
                   </a>
                   <a :class="answer_class[row.result]" v-if="row.result !== 4 && row.pass_rate > 0.05"><i :class="answer_icon[row.result]+' icon'" style="opacity:0"></i>Passed:{{(row.pass_rate*100).toFixed(1)}}%</a>

            </td>
            <td v-if="isadmin"><a v-if="row.contest_id" :href="'contest.php?cid='+row.contest_id">{{row.contest_id}}</a><span
                    v-else>无</span></td>
            <td>
                <div><span class="boldstatus">{{memory_parse(row.memory)}}</span><br><span class="boldstatus">{{time_parse(row.time)}}</span></div>
            </td>
            <td><a class="boldstatus" v-if="self === row.user_id || isadmin || row.share == 1" target=_blank :href="'showsource.php?id='+row.solution_id">查看</a>
                <span class="boldstatus" v-else>{{language_name[row.language]}}</span>
                <span v-if="(self === row.user_id || isadmin || row.share == 1) && row.problem_id"> / </span>
                <a class="boldstatus" v-if="(self === row.user_id || isadmin || row.share == 1) && row.problem_id" target="_blank"
                   :href="'newsubmitpage.php?id='+Math.abs(row.problem_id)+'&sid='+Math.abs(row.solution_id)">编辑</a>
                <br>
                <span class="boldstatus" v-if="(self === row.user_id || isadmin || row.share == 1)">{{language_name[row.language]}} / </span>
                <span class="boldstatus">{{row.length}}B</span>
            </td>
            <td>{{dayjs(row.in_date).format("YYYY-MM-DD HH:mm:ss")}}<br>{{row.judger}}</td>
        </tr>
        </tbody>
    </table>
</script>
<?php include("template/semantic-ui/nav.php"); ?>
<div>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="padding ui container" v-cloak>
        <h2 class="ui dividing header">
            Status
        </h2>
        <div class="ui top attached tabular menu">
            <a v-cloak :class="(current_tag == 'status'?'active':'')+' item'" @click="tag('status',$event)" id="submitstatus">提交状态</a>
            <a v-cloak :class="(current_tag == 'graph'?'active':'')+' item'" @click="tag('graph',$event)" id="graph">提交图表</a>
            <a :class="(current_tag == 'result'?'active':'') + ' item'" @click="tag('result',$event)">
                结果统计
            </a>
            <a :class="(current_tag == 'user'?'active':'') + ' item'" @click="tag('user',$event)">
                用户统计
            </a>
        </div>
        <div class="ui bottom attached segment" v-show="current_tag == 'status'">
            <div align=center class="input-append">
                <form id=simform class="ui form segment" action="status.php" method="get">
                    <div class="four fields">
                        <div class="field">
                            <label>{{target.problem_id}}</label>
                            <input v-model="problem_id" class="form-control" type=text size=4 name=problem_id>
                        </div>
                        <div class="field">
                            <label>{{target.user_id}}</label>
                            <input v-model="user_id" class="form-control" type=text size=4 name=user_id
                                   value=''>
                        </div>
                        <div class="field">
                            <label>{{target.language}}</label>
                            <div class="ui fluid search dropdown selection" size="1">
                                <input v-model="language" @change="language=$event.target.value"
                                       type="hidden" name="language">
                                <i class="dropdown icon"></i>
                                <div class="default text">All</div>
                                <div class="menu">
                                    <div class='item' data-value='-1'>All<i class="dropdown icon"
                                                                            style="visibility: hidden; "></i></div>
                                <div class="item" :data-value="i" v-for="i in Array.from(Array(language_name?language_name.length:0).keys())">
                                        <i :class="language_icon[i]+' color'"></i>
                                        {{language_name[i]}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label>{{target.result}}</label>
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
                    <div :class="(isadmin ? 'five':'four') +' fields center aligned'">
                        <div class="field" style="margin:auto">
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
                        <div v-if="isadmin" class="field" style="margin:auto">
                            <div class="ui toggle checkbox">
                                <input type="checkbox" @click="privilege=!privilege">
                                <label>不显示测试运行</label>
                            </div>
                        </div>
                        <div class="field">
                            <button class="ui labeled icon mini button" @click.prevent="search($event)">
                                <i class="search icon"></i><?= $MSG_SEARCH ?></button>
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
                              :isadmin="isadmin"></status-table>
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
            <div style="width:90%;margin:auto">
                <canvas id="canvas"></canvas>
            </div>
            <div class="ui grid">
                <div class="row">
                    <div class="eight wide column">
                        <div style="width:550px;">
                            <canvas id="subtime"></canvas>
                        </div>
                    </div>
                    <div class="eigth wide column">
                        <div style="width:550px;">
                            <canvas id="logtime"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="ui attached bottom segment" v-show="current_tag == 'result'">
            <div style="width:90%;margin:auto">
                <canvas id="bar"></canvas>    
            </div>
        </div>
        <div class="ui attached bottom segment" v-show="current_tag == 'user'">
            
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
        _.forEach(result,function(i){
            row[i[_label[0]]] = row[i[_label[0]]]||{};
            row[i[_label[0]]][i[_label[1]]] = {
                submit:i.submit,
                accepted:i.accepted||0
            }
        })

        var _labels = [];
        var _submits = [];
        var _accepteds = [];
        var _persent = [];
        _.forEach(row,function(val,i){
            _.forEach(row[i],function(val2,j){
                _labels.push(i.toString()+"-"+j.toString());
                _submits.push(row[i][j].submit);
                _accepteds.push(row[i][j].accepted);
                _persent.push((row[i][j].accepted/row[i][j].submit*100).toString().substring(0,5));
            })
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
                            labelString: '月份'
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
    function drawResult(data,labelName) {
        data.sort(function(a,b){
            return b.cnt - a.cnt;
        });
        console.log(data);
        var color = window.chartColors;
        var colorTocolor = {
            0:color.blue,
            1:color.blue,
            2:color.blue,
            3:color.blue,
            4:color.green,
            5:color.red,
            6:color.red,
            7:color.yellow,
            8:color.yellow,
            9:color.yellow,
            10:color.yellow,
            11:color.grey,
            12:color.blue,
            13:color.blue,
            14:color.red,
            15:color.red
        };
        var dataSet = [];
        var labName = [];
        dataSet.push({
                label:"提交数",
                backgroundColor: [],
                borderColor:[],
                borderWidth:1,
                data:[]
            });
        _.forEach(data,function(val,idx){
            labName.push(labelName[val.result]);
            dataSet[0].backgroundColor.push(colorTocolor[val.result]);
            dataSet[0].borderColor.push("white");
            dataSet[0].data.push(val.cnt)
        });
        var barChartData = {
            labels:labName,
            datasets: dataSet
        }
        barChartData = JSON.parse(JSON.stringify(barChartData))
        var ctx = document.getElementById('bar').getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					legend: {
					    display: false,
						position: 'top',
					},
					title: {
						display: false,
						text: '提交统计'
					}
				}
			});
    }
    function drawBar(data) {
        var login = data.login;
        data = data.submit;
        var color = Chart.helpers.color;
        var config = {
			type: 'radar',
			data: {
				labels: [],
				datasets: [{
					label: '提交分布',
					backgroundColor: color(window.chartColors.red).alpha(0.2).rgbString(),
					borderColor: window.chartColors.red,
					pointBackgroundColor: window.chartColors.red,
					data: []
				}]
			},
			options: {
				legend: {
					position: 'top',
				},
				title: {
					display: true,
					text: '提交时间分布'
				},
				scale: {
					ticks: {
						beginAtZero: true
					}
				}
			}
		};
		_.forEach(data,function(val){
		    config.data.labels.push(val.hour+":00");
		    config.data.datasets[0].data.push(val.cnt);
		});
		var copyconfig = JSON.parse(JSON.stringify(config));
		copyconfig.data.datasets = [{
					label: '登录分布',
					backgroundColor: color(window.chartColors.blue).alpha(0.2).rgbString(),
					borderColor: window.chartColors.blue,
					pointBackgroundColor: window.chartColors.blue,
					data: []
				}];
		_.forEach(login,function(val) {
		    copyconfig.data.datasets[0].data.push(val.cnt);
		});
		copyconfig.options.title.text = "登录时间分布";
		new Chart(document.getElementById('subtime'), config);
		new Chart(document.getElementById('logtime'), copyconfig);
		//console.log(data);
		//console.log(config);
    }
</script>
<script>
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
            isadmin: Boolean
        },
        data: function () {
            return {
                user:{}
            };
        },
        methods: {
            memory_parse: function (_memory) {
                var unit = ["KB", "MB", "GB"];
                var cnt = 0;
                var memory = parseInt(_memory);
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
                else if (tmp.intranet_ip.match(/10\.105\.[0-9]{1,3}\.[0-9]{1,3}/)) {
                    tmp.place = "逸夫楼Wi-Fi";
                }
                else if (tmp.intranet_ip.match(/10\.103\.[0-9]{1,3}\.[0-9]{1,3}/)) {
                    tmp.place = "地质楼Wi-Fi";
                }
                else if (tmp.intranet_ip.match(/10\.1[0-9]{2}\.[0-9]{1,3}\.[0-9]{1,3}/)) {
                    tmp.place = "其他Wi-Fi";
                }
                else if (tmp.intranet_ip.match(/172\.16\.[\s\S]+/)) {
                    tmp.place = "VPN";
                }
                else if (tmp.intranet_ip.match(/10\.200\.33\.[0-9]{1,3}/)) {
                    tmp.place = "润杰机房六楼";
                }
                else if (tmp.intranet_ip && tmp.ip && tmp.intranet_ip != tmp.ip) {
                    tmp.place = "外网";
                }
                else if (tmp.intranet_ip.match(/2001:[\s\S]+/)) {
                    tmp.place = "IPv6";
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
                _.forEach(this.problem_list,function(val,i){
                    doc.innerHTML = that.problem_list[i].nick;
                    that.problem_list[i].nick = doc.innerText;
                })
                return this.problem_list;
            }
        }
    })
    var problemStatus = window.problemStatus = new Vue({
        el: ".ui.container.padding",
        data: {
            problem_list: [],
            icon_list: [],
            judge_color: [],
            judge_icon:[],
            target: {},
            language_name: [],
            result: [],
            language_icon:[],
            self: "",
            isadmin: false,
            problem_id: null,
            user_id: null,
            language: -1,
            sim_checkbox:0,
            privilege:0,
            problem_result: -1,
            auto_refresh: true,
            page_cnt: 0,
            current_tag : "status",
            dim:false
        },
        watch: {
            sim_checkbox: function(newVal, oldVal) {
                var that = this;
                this.search().then(function(){that.search()});
            },
            privilege: function(newVal, oldVal) {
                var that = this;
                this.search().then(function(){that.search()});
            },
            auto_refresh: function(newVal, oldVal) {
                var that = this;
                if(newVal) {
                    this.search().then(function(){that.search()});
                }
            }
        },
        computed: {},
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
                var queryobject = {};
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
                that.target = data.const_list.language.cn.status;
                that.judge_icon = data.const_list.judge_icon;
                that.language_icon = data.const_list.language_icon;
                that.language_name = data.const_list.language_name.local;
                that.result = data.const_list.result.cn;
                that.self = data.self;
                that.isadmin = data.isadmin || data.browse_code
            },
            search: function ($event) {
                var that = this;
                this.dim = true;
                this.page_cnt = 0;
                var problem_id = this.problem_id || "null";
                var user_id = this.user_id || "null";
                var language = this.language == -1 ? "null" : this.language;
                var result = this.problem_result == -1 ? "null" : this.problem_result;
                var page_cnt = this.page_cnt * 20;
                var sim = Number(this.sim_checkbox);
                var pri = Number(this.privilege);
                return new Promise(function(resolve,reject){
                    $.get("../api/status/" + problem_id + "/" + user_id + "/" + language + "/" + result + "/" + page_cnt + "/" + sim + "/" + pri, function (data) {
                        that.dim = false;
                        that.search_func(data);
                        resolve();
                    })
                });
            },
            page: function (num, $event) {
                this.dim = true;
                this.page_cnt += num;
                var problem_id = this.problem_id || "null";
                var user_id = this.user_id || "null";
                var language = this.language == -1 ? "null" : this.language;
                var result = this.problem_result == -1 ? "null" : this.problem_result;
                var page_cnt = this.page_cnt * 20;
                var sim_checkbox = Number(this.sim_checkbox);
                var pri = Number(this.privilege);
                var that = this;
                $.get("../api/status/" + problem_id + "/" + user_id + "/" + language + "/" + result + "/" + page_cnt + "/" + sim_checkbox + "/" + pri, function (data) {
                    that.dim = false;
                    that.search_func(data);
                    $.get("../api/status/" + problem_id + "/" + user_id + "/" + language + "/" + result + "/" + page_cnt + "/" + sim_checkbox + "/" + pri, function (data) {
                        that.dim = false;
                        that.search_func(data);
                    })
                })
                
            },
            submit: function (data) {
                if(!this.auto_refresh) {
                    return;
                }
                if(!this.privilege && (data.val.id <= 0 || (data.val.cid && data.val.cid <= 0))) {
                    return;
                }
                if((!this.user_id || this.user_id === data.user_id) && (this.problem_result === -1) && (this.language === -1 || this.language === data.val.language) && !this.page_cnt && (!this.problem_id || parseInt(this.problem_id) === Math.abs(data.val.id))) {
                var obj = {};
                obj.problem_id = Math.abs(data.val.id);
                obj.solution_id = data.submission_id;
                obj.nick = data.nick;
                obj.user_id = data.user_id;
                obj.length = data.val.source.length;
                obj.language = data.val.language;
                obj.memory = obj.time = 0;
                obj.in_date = new Date().toISOString();
                obj.judger = "RATH";
                obj.result = 0;
                obj.ip = data.val.ip;
                obj.total_point = 0;
                obj.fingerprint = data.val.fingerprint;
                obj.sim = false;
                obj.avatar = !!data.val.avatar;
                obj.contest_id = data.val.cid ? Math.abs(data.val.cid):null;
                obj.sim_id = null;
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
                var sim = data.sim;
                var ip = data.ip;
                var total_point = parseInt(data.total_point) || 0;
                var fingerprint = data.fingerprint;
                var pass_point = data.pass_point;
                var that = this;
                _.forEach(this.problem_list,function(val,key){
                    var i = that.problem_list[key];
                    if (i.solution_id == solution_id) {
                        i.result = status;
                        i.time = time;
                        i.memory = memory;
                        i.sim = data.sim;
                        i.sim_id = data.sim_s_id;
                        i.pass_rate = pass_rate;
                        i.total_point = total_point;
                        i.pass_point = pass_point;
                        i.contest_id = data.contest_id ? Math.abs(data.contest_id):null;
                        i.ip = ip;
                        i.fingerprint = fingerprint;
                        return;
                    }
                })
            },
            tag:function(tag_name,$event){
                this.current_tag = tag_name;
            }
        },
        created: function () {
            var that = this;
            var problem_id = (this.problem_id = this.getProblemID()||null) || "null";
            var user_id = (this.user_id = this.getUserId()||null) || "null";
            var language = (this.language = this.getLanguage()||-1)==-1 ? "null" : this.getLanguage();
            var result = (this.problem_result = this.getResult()||-1) == -1 ? "null" : this.problem_result;
            $.get("../api/status/" + problem_id + "/" + user_id + "/" + language + "/" + result + "/0/", function (data) {
                    that.dim = false;
                    that.search_func(data);
                    $.get("../api/status/result",function(dat){
                        drawResult(dat.data,data.const_list.result.cn);
                    });
                    $.get("../api/status/submit_hour",function(dat){
                        var func = function(a,b){
                            return a.hour - b.hour;
                        };
                        dat.data.submit.sort(func);
                        dat.data.login.sort(func);
                        drawBar(dat.data);
                    })
                    $.get("../api/status/" + problem_id + "/" + user_id + "/" + language + "/" + result + "/0/", function (data) {
                    that.dim = false;
                    that.search_func(data);
                })
                });
        },
        updated: function() {
            $("tr").popup({
                    on: 'hover',
                    hoverable  : true,
                    positon: "top center"
                });
        },
        mounted: function () {
            $.get("../api/status/graph",function(data){
                draw(data);
            });
        }
    })
</script>
<?php include("template/semantic-ui/bottom.php"); ?>
</body>
</html>