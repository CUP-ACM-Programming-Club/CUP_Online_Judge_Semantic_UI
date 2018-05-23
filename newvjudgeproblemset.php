<!DOCTYPE html>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <!-- Site Properties -->
    <title><?= $OJ_NAME ?> -- Problem Set</title>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <script src="/js/dist/g2.min.js"></script>
    <script src="/js/dist/data-set.min.js"></script>
    <style>
        a{
            cursor: pointer;
        }

        .show_tag_controled {
            white-space: nowrap;
            overflow: hidden;
        }
        .half_padding {
            padding-top:0.5em;
            padding-bottom: 0.5em;
        }
    </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
<script type="text/x-template" id="list">
    <div class="ui pagination menu" style="box-shadow: none;">
        <a :class="'icon item '+(current_page?'':'disabled')" @click="current_page&&page($event,-current_page)">
            <i class="angle double left icon"></i>
        </a>
        <a :class="'icon item '+(current_page?'':'disabled')" @click="current_page&&page($event,-1)">
            <i class="left chevron icon"></i>
        </a>
        <div v-for="i in Array.from(Array(Math.min(Math.ceil(total/page_cnt),14)).keys()).map(function(n){if(current_page<7||Math.ceil(total/page_cnt)<=14){return n+1;}else if(current_page+8>Math.ceil(total/page_cnt)){return Math.ceil(total/page_cnt)-13+n}else{return current_page+n-6;}})">
            <a :class="(current_page+1 == i?'active':'')+' item page'" @click="page($event)">
                {{i}}
            </a>
        </div>
        <a :class="'icon item '+(current_page+1==Math.ceil(total/page_cnt)?'disabled':'')"
           @click="(current_page+1<Math.ceil(total/page_cnt))&&page($event,1)">
            <i class="right chevron icon"></i>
        </a>
        <a :class="'icon item '+(current_page+1==Math.ceil(total/page_cnt)?'disabled':'')"
           @click="(current_page+1<Math.ceil(total/page_cnt))&&page($event,Math.ceil(total/page_cnt)-1-current_page)">
            <i class="angle double right icon"></i>
        </a>
    </div>
</script>
<script type="text/x-template" id="selectedTag">
    <div>
        <div class="ui labels">
            <a v-for="label in from" :class="'ui tag label '+datas.color[label]||''">{{label}}<i class="delete icon" @click="remove('from',$event)"></i></a>
            <a  v-for="label in datas.search" :class="'ui label '+datas.color[label]||''">{{label}}<i class="delete icon" @click="remove('search',$event)"></i></a>
            <a  v-for="label in datas.label" :class="'ui label '+datas.color[label]||''">{{label}}<i class="delete icon" @click="remove('label',$event)"></i></a>
        </div>
    </div>
</script>
<script type="text/x-template" id="grid-template">
    <table v-tableUpdated id='problemset' width='90%' class='ui unstackable very basic center aligned large table'>
        <thead>
        <tr class='toprow'>
            <th width='2%'></th>
            <th width='10%' @click="sort('problem_id',$event)">
                <a><i v-show="order_target == 'problem_id'"
                      :class="'angle icon '+(order?'down':'up')"></i><?php echo $MSG_PROBLEM_ID ?></a></th>
            <th width='60%' class="left aligned"><?php echo $MSG_TITLE ?></th>
            <th width='13%'>
                <a @click="sort('accepted',$event,1)"><i v-show="order_target == 'accepted'"
                                                         :class="'angle icon '+(order?'down':'up')"></i><?= $MSG_AC ?>
                </a> / <a @click="sort('submit',$event,1)"><i v-show="order_target == 'submit'"
                                                              :class="'angle icon '+(order?'down':'up')"></i><?= $MSG_SUBMIT ?>
                </a></th>
            <th style="cursor:hand" width='8%'><a @click="sort('present',$event,1)"><i
                            v-show="order_target == 'present'" :class="'angle icon '+(order?'down':'up')"></i>正确率</a>
            </th>
        </tr>
        </thead>

        <tbody>
        <tr style="vertical-align:middle" v-for="row in result.problem"
            v-if="row.ac == 0 || row.ac == -1 || (row.ac == 1 && !hide_currect)">
            <td>
                <i class="checkmark icon" v-if="row.ac == 1"></i>
                <i class="remove icon" v-else-if="row.ac == 0"></i>
            </td>
            <td>
                <div class="center">
                    {{row.source+" "+row.problem_id}}
                </div>
            </td>
            <td>
                <div class="left aligned">
                    <a :href="row.source.toLowerCase()+'submitpage.php?id='+row.problem_id+'&js'" target="_blank">
                        {{row.title}}
                    </a>
                    <div class="show_tag_controled" style="float:right;">
                    <span class="ui header" v-for="_tag in row.label?row.label.split(' '):['标签待整理']">
                        <a @click="tag(_tag,$event)" v-show="show_tag" :class="result.color[_tag]+' ui label'">
                            {{_tag}}
                        </a>
                    </span>
                    </div>
                </div>
            </td>
            <td>
                <div class="center">
                    {{row.accepted}} / {{row.submit}}
                </div>
            </td>
            <td>
                <div class="center">
                    {{(parseInt(row.accepted)/Math.max(parseInt(row.submit),1)*100).toString().substring(0,4)}} %
                </div>
            </td>
        </tr>
        </tbody>
        <div class="ui active inverted dimmer" v-if="dim">
            <div class="ui large text loader">Loading</div>
        </div>
    </table>

</script>
<div class="ui container">
    <div class="padding">
        <h2 class="ui dividing header">
            Problem Set
            <div class="sub header" v-cloak>
                 共{{total||0}}题
            </div>
        </h2>
        <div class="ui grid">
            <div class="row">
                <div class="thirteen wide column">
                    <div class="ui grid">
                    <div class="five wide column">
                    <div class="ui search">
                        <div class="ui icon input">
                            <input class="prompt" type="text" name="search" id="psearch"
                                   placeholder="Problem,ID or Keyword" @keypress.enter="enter($event)">
                            <i class="search icon"></i>
                        </div>
                        <div class="results"></div>
                    </div>
                </div>
                <div class="ten wide right aligned aligned column">
                    <selected-tag :color="table.color" :data="label" :search="search_tag" :from="from"></selected-tag>
                </div>
                </div>
                <div class="ui grid">
                    <div class="sixteen wide column half_padding">
                        <pagination
                    :total="total"
                    :page_cnt="page_cnt"
                    :current_page="current_page"></pagination>
                    </div>
                </div>
                <div class="ui grid">
                    <div class="sixteen wide column half_padding">
                        <div class="ui tag labels">
                            <a v-for="val in source_from" class="ui label" @click="change_from">
                                {{val}}
                            </a>
                        </div>
                    </div>
                </div>
                </div>
                <div class="three wide column">
                    <div class="row half_padding">
                        <div class="sixteen wide column">
      <div class="ui toggle checkbox" id="show_tag" @click="check">
                        <input type="checkbox">
                        <label>显示标签</label>
                    </div>
                    </div>
                    </div>
                    <div class="row half_padding">
                        <div class="sixteen wide column">
                            <div class="ui toggle checkbox" id="hide_currect" @click="hide">
                        <input type="checkbox">
                        <label>隐藏通过题目</label>
                    </div>
                        </div>
                    </div>
                    <div class="row half_padding">
                        <div class="sixteen wide column">
                                      <div class="ui toggle checkbox" id="show_cloud" @click="cloud">
                        <input type="checkbox">
                        <label>显示标签云</label>
                    </div>
                        </div>
                    </div>
                    </div>

                </div>
            </div>

        <div class="ui grid">
            <div :class="(show_label_cloud?'twelve':'sixteen')+' wide column'">
                <div class="column">

                    <demo-grid
                            :data="table"
                            :dim="dim"
                            :show_tag="show_tag"
                            :order="order"
                            :order_target="order_target"
                            :hide_currect="hide_currect"
                    >
                    </demo-grid>
                </div>
            </div>
            <div class="four wide column" v-show="show_label_cloud">
                <div class="ui sticky element">
                <h4 class="ui top attached block header">Label Cloud</h4>
                <div class="ui bottom attached segment">
                <div id="word-cloud">
                </div>
                </div>
                </div>
            </div>
            <div class="row">

            </div>
        </div>
        <div>
            <!--
        <div class="ui pagination menu" style="box-shadow:none">
            <a class="<?php if ($page == 1) echo "disabled"; ?> icon item" id="page_prev" href="<?php if (intval($page) > 1) echo "problemset.php?page=" . ($page - 1); else echo "javascript:void(0)"; ?>">
                <i class="left chevron icon"></i>
            </a>
            <br>
                <?php
            for ($i = max($page - 14, 1); $i <= min(max($page + 14, 20), $view_total_page); $i++) {
                if ($i == $page)
                    echo "<a class='active item' href='#'>$i</a>";
                else
                    echo "<a class='item' href='problemset.php?page=" . $i . "'>" . $i . "</a>";
            }
            ?>
            <a class="<?php if (intval($page) == intval($view_total_page)) echo "disabled"; ?> icon item" href="<?php if (intval($page) < intval($view_total_page)) echo "problemset.php?page=" . (intval($page) + 1); else echo "javascript:void(0)"; ?>" id="page_next">
                <i class="right chevron icon"></i>
            </a>
        </div>-->
        </div>
    </div>
</div>
<script>
    Vue.component('demo-grid', {
        template: '#grid-template',
        props: {
            data: Array,
            dim: Boolean,
            show_tag: Boolean,
            order: Boolean,
            order_target: String,
            hide_currect: Boolean
        },
        data: function () {
            return {
                dim: false
            }
        },
        computed: {
            result: function () {
                var data = this.data;
                var _color = data.color;
                var color = {};
                for (var i in _color) {
                    color[i] = _color[i];
                }
                color["标签待整理"] = "black";
                var val = {
                    color: color,
                    problem: data.problem
                }
                return val;
            }
        },
        methods: {
            sort: function (target, event, default_order = 0) {
                this.$root.sort(target, event, default_order)
            },
            tag: function (label, event) {
                this.$root.tag(label, event);
            }
        }
    });
    Vue.component('selected-tag', {
        template: "#selectedTag",
        props: {
            data: Array,
            color: Array,
            show_tag: Boolean,
            dim: Boolean,
            from:Array,
            search:Array
        },
        data: function () {
            return {};
        },
        computed: {
            datas: function () {
                var color = this.color;
                var label = this.data;
                var search = this.search;
                return {
                    label: label ? label : [],
                    color: color,
                    search: search?[search] : []
                }
            }
        },
        methods: {
            remove: function () {
                this.$root.remove(...arguments);
            }
        }
    });
    Vue.component('pagination', {
        template: "#list",
        props: {
            total: Number,
            page_cnt: Number,
            current_page: Number
        },
        methods: {
            page: function (event, arrow) {
                if (arrow) {
                    this.$root.page(null, arrow);
                }
                else {
                    this.$root.page(parseInt(event.target.innerText) - 1);
                }
            }
        }
    })
    handler = {
        activate: function () {
            if (!$(this).hasClass('dropdown browse')) {
                $(this)
                    .addClass('active')
                    .closest('.ui.menu')
                    .find('.item')
                    .not($(this))
                    .removeClass('active')
                ;
            }
        }

    }

    var query_string = parseQueryString(window.location.hash.substring(1));
    
Vue.directive('tableUpdated', {
    bind: function (el) {
        //console.log('bind')
    },
    inserted: function (el) {
       // console.log('inserted')
    },
    update: function (el) {
        //console.log('update')
    },
    componentUpdated: function (el) {
        $('.ui.sticky.element')
  .sticky({
    context: '#problemset',
    offset:40
  })
;
    },
    unbind: function (el) {
        //console.log('unbind')
    }
})
    var problemSet = new Vue({
        el: ".padding",
        data: function(){return {
            table: [],
            dim: false,
            current_page: query_string.page || 1,
            search_tag: query_string.search || getParameterByName("tag") || "",
            label:query_string.label || getParameterByName("label") || [],
            order: query_string.order || 0,
            order_target: query_string.target || "problem_id",
            show_tag: localStorage.getItem('virtual_show_tag') === 'true',
            page_cnt: query_string.page_cnt || 50,
            total: 0,
            hide_currect: localStorage.getItem('virtual_hide_currect') === 'true',
            show_label_cloud:true,
            chart:undefined,
            from:[],
            source_from:[]
        }},
        computed: {
            tables: {
                get: function () {
                    if (this.table.length == 0) {
                        if ($("tbody")[0].innerText.length) {
                            return $("tbody").html();
                        }
                        this.dim = false;
                        return "<br><br><br>";
                    }
                    this.setQuery();
                    var color = this.table.color;
                    color["标签待整理"] = "black";
                    var problem = this.table.problem;
                    var len = problem.length;
                    var tot_html = "";
                    for (var i = 0; i < len; ++i) {
                        var html = "<tr style='vertical-align:middle'><td>";
                        if (problem[i].ac == true) {
                            html += "<i class='checkmark icon'></i>";
                        }
                        else if (problem[i].ac == false) {
                            html += "<i class='remove icon'></i>";
                        }
                        html += "</td>";
                        html += "<td><div class='center'>" + problem[i].problem_id + "</div></td><td><div class='left aligned'><a href='newsubmitpage.php?id=" + problem[i].problem_id + "&js' target='_blank'>" + problem[i].title;
                        html += "</a>";
                        var label = problem[i].label ? problem[i].label.split(" ") : ["标签待整理"];
                        html += "<div class='show_tag_controled' style='float: right; '><span class='ui header'>";
                        for (var j = 0; j < label.length; ++j) {
                            html += "<a @click='tag(\"" + label[j] + "\",$event)' class='ui " + color[label[j]] + " label' target='_blank'>" + label[j] + "</a>";
                        }
                        html += "</span>";
                        html += "</div></div></td><td  class='hidden-xs'>	</td><td><div class='center'>" + problem[i].accepted + " / " + problem[i].submit + "<br>" + (parseInt(problem[i].accepted) / Math.max(parseInt(problem[i].submit), 1) * 100).toString().substring(0, 4) + " %</div></td></tr>";
                        tot_html += html;
                    }
                    return tot_html;
                },
                set: function (_t) {
                    this.page_cnt = _t.step;
                    this.total = _t.total;
                    this.table = _t;
                    this.source_from = _t.from;
                }
            }
        },
        methods: {
            setQuery:function(){
                var queryObject = {};
                if(this.page !== 0)
                    queryObject["page"] = this.current_page+1;
                if(this.search_tag && this.search_tag !== "" && this.search_tag.length > 0)
                    queryObject["tag"] = this.search_tag;
                var url = location.origin+location.pathname+"?"+$.param(queryObject);
                if(url !== location.origin+location.pathname+"?")
                    history.pushState({},0,url);
            },
            page: function (num, arrow) {
                this.dim = true;
                var page = this.current_page = arrow ? this.current_page + arrow : num;
                var search_tag = this.search_tag || "none";
                var order = this.order;
                var order_target = this.order_target;
                //handler.activate.call(obj.target);
                var that = this;
                this.setQuery();
                $.get("../api/problemset/" + page + "/" + search_tag + "/" + order_target + "/" + order + "?source=virtual&label="+this.label.join("")+"&from="+this.from.join(""), function (data) {
                    that.dim = false;
                    that.tables = data;
                })
            },
            sort: function (target, event, default_order = 0) {
                this.dim = true;
                var prev_target_equal_to_current = this.order_target == target;
                this.order_target = target;
                var page = this.current_page = 0;
                var search_tag = this.search_tag || "none";
                var order = this.order = prev_target_equal_to_current ? (-this.order + 1) : default_order;
                var that = this;
                $.get("../api/problemset/" + page + "/" + search_tag + "/" + target + "/" + order + "?source=virtual&label="+this.label.join("")+"&from="+this.from.join(""), function (data) {
                    that.dim = false;
                    that.tables = data;
                })
            },
            change_from:function($event){
                this.from = [$event.target.innerText];
                this.dim = true;
                var order_target = this.order_target;
                var label = this.search_tag || "none";
                var page = this.current_page = 0;
                var order = this.order;
                var that = this;
                this.setQuery();
                $.get("../api/problemset/" + page + "/" + label + "/" + order_target + "/" + order + "?source=virtual&label="+this.label.join("")+"&from="+this.from.join(""), function (data) {
                    that.dim = false;
                    that.tables = data;
                })
            },
            labels: function(label,event) {
                this.label = [label];
                this.dim = true;
                var order_target = this.order_target;
                var page = this.current_page = 0;
                var order = this.order;
                var that = this;
                this.setQuery();
                $.get("../api/problemset/" + page + "/" + (this.search_tag || "none") + "/" + order_target + "/" + order + "?source=virtual&label="+this.label.join("")+"&from="+this.from.join(""), function (data) {
                    that.dim = false;
                    that.tables = data;
                })
            },
            
            tag: function (label, event) {
                if (label == "标签待整理") {
                    return;
                }
                this.search_tag = label;
                this.dim = true;
                var order_target = this.order_target;
                var page = this.current_page = 0;
                var order = this.order;
                var that = this;
                this.setQuery();
                $.get("../api/problemset/" + page + "/" + label + "/" + order_target + "/" + order + "?source=virtual&label="+this.label.join("")+"&from="+this.from.join(""), function (data) {
                    that.dim = false;
                    that.tables = data;
                })
            },
            remove: function (target,$event) {
                if(target == "label") {
                    this.label = [];
                }
                else if(target == "search") {
                    this.search_tag = "";
                }
                else
                {
                    this.from = [];
                }
                this.dim = true;
                var order_target = this.order_target;
                var page = this.current_page;
                var order = this.order;
                this.setQuery();
                var that = this;
                $.get("../api/problemset/" + page + "/" + (this.search_tag || "none") + "/" + order_target + "/" + order + "?source=virtual&label="+this.label.join("")+"&from=" + this.from.join(""), function (data) {
                    that.dim = false;
                    that.tables = data;
                })
            },
            check: function () {
                this.show_tag = Boolean(-this.show_tag + 1);
                localStorage.setItem("virtual_show_tag", Boolean(this.show_tag));
            },
            hide: function () {
                this.hide_currect = Boolean(!this.hide_currect);
                localStorage.setItem("virtual_hide_currect", Boolean(this.hide_currect))
            },
            cloud:function() {
                this.show_label_cloud = Boolean(!this.show_label_cloud);
                //localStorage.setItem("label_cloud", Boolean(this.show_label_cloud));
            },
            enter: function (obj) {
                var val = obj.target.value;
                this.tag(val);
            }
        },
        created: function () {
            var that = this;
            var page = parseInt(getParameterByName("page") || query_string.page || "1") - 1;
            $(document).ready(function () {
                $("#show_tag").checkbox((that.show_tag ? "" : "un") + "check");
                $("#hide_currect").checkbox((that.hide_currect ? "" : "un") + "check");
                $("#show_cloud").checkbox((that.show_label_cloud ? "" : "un") + "check");
            })
            $("#show_tag").checkbox((this.show_tag ? "" : "un") + "check");
            $("#hide_currect").checkbox((that.hide_currect ? "" : "un") + "check");
            $("#show_cloud").checkbox((that.show_label_cloud ? "" : "un") + "check");

            this.current_page = page;
            $.get("../api/problemset/" + page + "/" + (this.search_tag || "none") + "/" + this.order_target + "/" + this.order + "?source=virtual", function (data) {
                that.tables = data;
            });
        },
        mounted: function () {
            var that = this;
            new Promise(function(resolve){
                $.get("/api/problem/local/?label=true&vjudge=true",function(d){
                    var data = [];
                    for(var i = 0;i<d.data.length;++i) {
                        data.push({
                            tag:d.data[i],
                            count:Math.random() * 10 * Math.random() * 10
                        })
                    }
                    var dv = new DataSet.View().source(data);
                    var range = dv.range('count');
                    var min = range[0];
                    var max = range[1];
                    dv.transform({
                        type: 'tag-cloud',
                        fields: ['tag', 'count'],
                        font: 'Open Sans',
                        forceFit: true,
                        size: [$("#word-cloud").width(), HEIGHT],
                        padding: 0,
                        timeInterval: 5000, // max execute time
                        rotate() {
                            return 0;
                        },
                        fontSize(d) {
                            return ((d.count - min) / (max - min)) * (MAX_SIZE - MIN_SIZE) + MIN_SIZE;
                        }
                    });
                    var chart = new G2.Chart({
                        container: 'word-cloud',
                        forceFit: true,
                        height: HEIGHT,
                        padding: 0
                    });
                    chart.source(dv);
                    chart.legend(false);
                    chart.axis(false);
                    chart.tooltip({
                        showTitle: false
                    });
                    chart.coord().reflect();
                    chart.point()
                        .position('x*y')
                        .color('tag', ['#21BA45', '#009c95', '#2185D0', '#6435C9', '#e61a8d'])
                        .shape('cloud')
                        .tooltip(false)
                        .style({
                            cursor: 'pointer', // 设置鼠标手势
                        }); // 'tag*count'

                    chart.render();
                    chart.on('point:click', function(ev) {
                        that.labels(ev.data._origin['text']);
                        //location.href = "?tag=" + encodeURI();
                    });
                });
            });
        }
    });
    $('#show_tag')
        .checkbox()
        .first().checkbox({
        onChecked: function () {
        },
        onUnchecked: function () {
        }
    });
</script>
<script>
    var HEIGHT = 300;
    var MAX_SIZE = 40, MIN_SIZE = 20;

    function getTextAttrs(cfg) {
        return _.assign({}, {
            fillOpacity: cfg.opacity,
            fontSize: cfg.origin._origin.size,
            rotate: cfg.origin._origin.rotate,
            text: cfg.origin._origin.text,
            textAlign: 'center',
            fontFamily: cfg.origin._origin.font,
            fill: cfg.color,
            textBaseline: 'Alphabetic'
        }, cfg.style);
    }

    G2.Shape.registerShape('point', 'cloud', {
        drawShape(cfg, container) {
            var attrs = getTextAttrs(cfg);
            return container.addShape('text', {
                attrs: _.assign(attrs, {
                    x: cfg.x,
                    y: cfg.y
                })
            });
        }
    });


</script>
<?php include("template/$OJ_TEMPLATE/bottom.php"); ?>
</body>
</html>