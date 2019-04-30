<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>
    </title>
    <?php include("template/semantic-ui/css.php"); ?>
    <?php include("template/semantic-ui/js.php"); ?>
    <script src="/js/fingerprint2.min.js?ver=2.0"></script>
    <script src="/template/semantic-ui/js/clipboard.min.js"></script>
    <!--<script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>-->
    <script src="ace-builds/src-min-noconflict/ace.js?ver=1.1" type="text/javascript" charset="utf-8"></script>
    <script src="ace-builds/src-min-noconflict/ext-language_tools.js" type="text/javascript"
            charset="utf-8"></script>
    <script src="ace-builds/src-min-noconflict/ext-error_marker.js" type="text/javascript" charset="utf-8"></script>
    <script src="ace-builds/src-min-noconflict/ext-statusbar.js?ver=1.0" type="text/javascript"
            charset="utf-8"></script>
    <script src="ace-builds/src-min-noconflict/ext-emmet.js" type="text/javascript" charset="utf-8"></script>
    <script src="ace-builds/src-min-noconflict/ext-static_highlight.js"></script>
    <script src="/js/lang_detector.js"></script>
    <script src="/js/mermaid.min.js"></script>
    <script>
        mermaid.initialize({ theme: "default", startOnLoad: false });
    </script>
    <style>
        .ui.modal {
            top: 3%;
        }

        script {
            display: none;
        }

        .not-compile {
            display: none;
        }

        .sample_input {
            color: #ad1457;
        }

        .sample_output {
            color: #ad1457;
        }

        .following.bar.title.light.fixed {
            top: 40px;
            transition: top 0.4s;
        }

        .following.bar.title {
            top: 60px;
            transition: top 0.4s;
        }

        .ui.vertical.center.aligned.grid {
            padding-top: 1em;
            padding-bottom: 2em;
        }

        .row.no.padding {
            padding-top: 0em;
            padding-bottom: 0em;
        }

        .main.submit.layout img {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
<?php include("template/semantic-ui/nav.php"); ?>
<div id="cmod" class="ui container">
    <contest-mode v-if="contest_mode"></contest-mode>
    <limit-hostname v-if="limit"></limit-hostname>
    </div>
<div class="main screen" v-cloak>
    
    <script>
        function qsa(sel) {
            return Array.apply(null, document.querySelectorAll(sel));
        }

        function decodeHTML(str) {
            var doc = document.createElement("div");
            doc.innerHTML = str;
            return doc.innerText;
        }
    
        var loadVue = new Promise(function (resolve, reject) {
            $.get("/api/problem/local" + window.location.search, function (data) {
                if (data['status'] == "error") {
                    if(data.redirect) {
                        location.href = data.redirect;
                    }
                    if(data['statement']) {
                        alert(data['statement']);
                    }
                    new Vue({
                        el:"#cmod",
                        data:function(){return {contest_mode:data.contest_mode,limit:false};},
                        mounted:function(){}
                    });
                    return;
                }
                var addr = data.limit_hostname;
                if (data.isadmin) {
                    addr = null;
                }
                if(addr && location.href.indexOf(addr) == -1) {
                    Vue.component("limit-hostname",VueMaker("访问限制","根据管理员设置的策略，本次contest请使用" + addr + "访问"));
                    new Vue({
                        el:"#cmod",
                        data:{contest_mode:false,limit:true},
                        mounted:function(){}
                    });
                    //alert("根据管理员设置的策略，本次contest请使用" + addr + "访问");
                    return;
                }
                var cid = null, pid = null, id = null, tid = null;
                if ((cid = getParameterByName("cid"))) {
                    pid = getParameterByName("pid");
                }
                else if (tid = getParameterByName("tid")) {
                    pid = getParameterByName("pid");
                }
                else {
                    id = getParameterByName("id");
                }
                var d = data.problem;
                var source_code = data.source;
                var iseditor = data.editor;
                var isadmin = data.isadmin;
                var problemsubmitter = window.problemsubmitter = new Vue({
                    el: ".main.screen",
                    data: function () {
                        var _data = {
                            _title: d.title,
                            problem_id: d.problem_id,
                            original_id: d.problem_id,
                            iscontest: getParameterByName("cid") !== null,
                            description: d.description,
                            original_content:{
                                description:d.description,
                                input: d.input,
                                output: d.output,
                                hint: d.hint
                            },
                            time: "时间限制:" + d.time_limit + "秒",
                            memory: "内存限制:" + d.memory_limit + "MB",
                            input: d.input,
                            output: d.output,
                            uploader:d.uploader,
                            sampleinput: d.sample_input,
                            sampleoutput: d.sample_output,
                            test_run_sampleinput: d.sample_input,
                            test_run_sampleoutput: d.sample_output,
                            hint: d.hint,
                            fingerprint:"",
                            auto_detect: false,
                            fingerprintRaw: "",
                            submit: "提交:" + d.submit,
                            accepted: "正确:" + d.accepted,
                            source: d.source,
                            show_style: "preview",
                            not_show_toolbar: false,
                            spj: Boolean(parseInt(d.spj)),
                            single_page: false,
                            bodyOnTop: true,
                            isRenderBodyOnTop: false,
                            source_code: source_code,
                            language_name: d.language_name,
                            prepend: d.prepend,
                            append: d.append,
                            langmask: d.langmask,
                            isadmin: isadmin,
                            iseditor: iseditor,
                            share: false,
                            selected_language: localStorage.getItem("lastlang") && Boolean(1 << parseInt(localStorage.getItem("lastlang")) & (~d.langmask)) ? parseInt(localStorage.getItem("lastlang")) : Math.log2(~d.langmask & -~d.langmask),
                            language_template: d.language_template,
                            fontSize: 18,
                            hide_warning: true,
                            confirm_text: "",
                            submitDisabled: false,
                            resume_time: 0,
                            finished: false,
                            current_prepend: "",
                            current_append: ""
                        };
                        if (_data.input && _data.input.indexOf(".in") !== -1) {
                            _data.input += '\n\n**(输入文件不需要用户调用文件操作，仅需使用标准输入输出即可)**'
                        }
                        if (_data.output && _data.output.indexOf(".out") !== -1) {
                            _data.output += '\n\n**(输出文件不需要用户调用文件操作，仅需使用标准输入输出即可)**'
                        }
                        if (id) {
                            _data.problem_id = d.problem_id;
                        }
                        else {
                            _data.problem_id = 1001 + parseInt(pid);
                        }
                        if (_data.prepend && _data.prepend[_data.selected_language]) {
                            _data.current_prepend = _data.prepend[_data.selected_language];
                        }
                        if (_data.append && _data.append[_data.selected_language]) {
                            _data.current_append = _data.append[_data.selected_language];
                        }
                        $("title").html(_data.problem_id + ":" + _data._title + " -- CUP Online Judge");
                        return _data;
                    },
                    computed: {
                        title: function () {
                            return this.problem_id + ": " + markdownIt.renderRaw(d.title);
                        },
                        lang_list: function () {
                            var len = this.language_name.length - 1;
                            var _langmask = ~this.langmask;
                            let result = [];
                            for (var cnt = 0; cnt < len; ++cnt) {
                                if (_langmask & (1 << cnt)) {
                                    result.push({
                                        num: cnt,
                                        name: this.language_name[cnt]
                                    })
                                }
                            }
                            result.sort(function (a, b) {
                                if (a.name < b.name) return -1;
                                else if (a.name > b.name) return 1;
                                else return 0;
                            })
                            return result;
                        }
                    },
                    watch: {
                        selected_language: function (newVal, oldVal) {
                            if (newVal === oldVal) {
                                return;
                            }
                            this.initHighlight();
                            var $value = newVal;
                            localStorage.setItem("lastlang", $value);
                            $("#language").dropdown("set selected", $value.toString())
                            this.selected_language = $value;
                            var editor = window.editor;
                            var langn = $value;
                            var file_n = language_ext[langn];
                            editor.getSession().setMode("ace/mode/" + language[langn]);
                            var prepend = this.prepend;
                            var append = this.append;
                            if (prepend && prepend[newVal] !== this.current_prepend) {
                                console.log("change");
                                this.current_prepend = prepend[newVal];
                            }
                            if (append && append[newVal] !== this.current_append) {
                                this.current_append = append[newVal];
                            }
                        },
                        auto_detect: function(newVal, oldVal){
                            var that = this;
                            if (newVal === oldVal) {
                                return;
                            }
                            if(newVal) {
                            function detectLanguage() {
    detected_lang = detectLang(window.editor.getSession().getValue(),  window.problemsubmitter.lang_list.map(function(e) {return e.num}));
      if (that.selected_language != detected_lang) {
        that.selected_language = detected_lang;
    }
  }
  var detectLanguageDebouncer = _.debounce(detectLanguage, 100);
    detectLanguageDebouncer();
  window.editor.on("change", function (event) {
    detectLanguageDebouncer();
  });
                            }
                            else {
                                window.editor.off("change");
                            }
                        },
                        current_append: function (newVal, oldVal) {
                            this.initHighlight();
                            var highlight = this.highlight;
                            var dom = this.aceDom;
                            this.$nextTick(function () {
                                _.forEach(qsa(".append.code"), function (val, index) {
                                    highlight(val, {
                                        mode: val.getAttribute("ace-mode"),
                                        theme: val.getAttribute("ace-theme"),
                                        startLineNumber: 1,
                                        showGutter: val.getAttribute("ace-gutter"),
                                        trim: true
                                    }, function (highlighted) {
                                    });
                                });
                            })

                        },
                        current_prepend: function (newVal, oldVal) {
                            this.initHighlight();
                            var highlight = this.highlight;
                            var dom = this.aceDom;
                            this.$nextTick(function () {
                                _.forEach(qsa(".prepend.code"), function (val, index) {
                                    highlight(val, {
                                        mode: val.getAttribute("ace-mode"),
                                        theme: val.getAttribute("ace-theme"),
                                        startLineNumber: 1,
                                        showGutter: val.getAttribute("ace-gutter"),
                                        trim: true
                                    }, function (highlighted) {
                                    });
                                });
                            })

                        }
                    }
                    ,
                    methods: {
                        iRender: function(){
                            this.description = this.descriptionMarkdownIt.render(this.original_content.description || '');
                        this.input = this.inputMarkdownIt.render(this.original_content.input || '');
                        this.output = this.outputMarkdownIt.render(this.original_content.output || '');
                        this.hint = this.hintMarkdownIt.render(this.original_content.hint || '');
                        },
                        initHighlight: function () {
                            if (!this.highlight) {
                                this.highlight = ace.require("ace/ext/static_highlight");
                            }
                            if (!this.aceDom) {
                                this.aceDom = ace.require("ace/lib/dom");
                            }
                        },
                        flush_theme: function () {
                            var that = this;
                            this.initHighlight();
                            var highlight = this.highlight;
                            var dom = this.aceDom;
                            this.current_prepend = "";
                            this.current_append = "";
                            this.$forceUpdate();
                            this.$nextTick(function () {
                                that.current_prepend = that.prepend &&
                                that.prepend[that.selected_language] ? that.prepend[that.selected_language] : "";
                                that.current_append = that.append && that.append[that.selected_language] ? that.append[that.selected_language] : "";
                                that.$forceUpdate();
                                that.$nextTick(function () {
                                    qsa(".prepend.code").forEach(function (codeEl) {
                                        highlight(codeEl, {
                                            mode: codeEl.getAttribute("ace-mode"),
                                            theme: codeEl.getAttribute("ace-theme"),
                                            startLineNumber: 1,
                                            showGutter: codeEl.getAttribute("ace-gutter"),
                                            trim: true
                                        }, function (highlighted) {
                                            $("#left-side").css({
                                                height:$("#right-side").height()
                                            });
                                        });
                                    });
                                    qsa(".append.code").forEach(function (codeEl) {
                                        highlight(codeEl, {
                                            mode: codeEl.getAttribute("ace-mode"),
                                            theme: codeEl.getAttribute("ace-theme"),
                                            startLineNumber: 1,
                                            showGutter: codeEl.getAttribute("ace-gutter"),
                                            trim: true
                                        }, function (highlighted) {
                                            $("#left-side").css({
                                                height:$("#right-side").height()
                                            });
                                        });
                                    });
                                })
                            })

                        },
                        judgeLength: function (str, flag) {
                            var alphaNum = ["one", "two", "three", "four", "five", "six", "seven", "eight"]
                            var ans = 0;
                            if (flag) ans = 8;
                            var isalpha = true;
                            _.forEach(str, function (val, index) {
                                if (!(val >= "a" && val <= "z" || val >= "A" && val <= "Z" || val == " ")) {
                                    isalpha = false;
                                }
                            });
                            if (isalpha) {
                                ans -= str.length / 5;
                            }
                            return alphaNum[Math.abs(ans) - 1];
                        },
                        switch_screen: function ($event) {
                            this.single_page = !this.single_page;
                            document.documentElement.scrollTop = 0;
                            if (this.single_page) {
                                $(".topmenu").css({
                                    borderBottom: "none",
                                    boxShadow: "none"
                                });
                            }
                            else {
                                $(".topmenu").css({
                                    borderBottom: "",
                                    boxShadow: ""
                                });
                            }
                        },
                        resize: function ($event) {
                            var size = $event.target.value;
                            console.log(size);
                            if (!isNaN(size)) {
                                localStorage.setItem('font-size', size);
                                this.fontSize = size;
                            }
                        },
                        wsfs_result: function (data) {
                            console.log(data);
                            var solution_id = data["solution_id"];
                            sid = solution_id;
                            var state = data["state"];
                            var time = data["time"];
                            var memory = data["memory"];
                            var test_run_result = data["test_run_result"];
                            var compile_info = data["compile_info"];
                            /*
                            var tb = window.document.getElementById('result');
                            var loader = "<img style='inline-block' width=18 src=image/loader.gif>";
                            var tag = "span";
                            if (state < 4) tag = "span disabled=true";
                            else tag = "a";
                            if (state == 11) {
                                tb.innerHTML = "<" + tag + " href='ceinfo.php?sid=" + solution_id + "' class='badge badge-info' target=_blank>" + judge_result[state] + "</" + tag + ">";
                                tb.className = "ui button " + judge_color[state];
                            }
                            else {
                                tb.innerHTML = "<" + tag + " href='reinfo.php?sid=" + solution_id + "' class='badge badge-info' target=_blank>" + judge_result[state] + "</" + tag + ">";
                                tb.className = "ui button " + judge_color[state];
                            }

                            if (state < 4) tb.innerHTML += loader;
                            tb.innerHTML += "Memory:" + memory + "kb&nbsp;&nbsp;";
                            tb.innerHTML += "Time:" + time + "ms";
                            */
                            if (state >= 4) {
                                if (test_run_result || compile_info) {
                                    $("#out").text("运行结果:\n" + (test_run_result || "") + (compile_info || ""));
                                }
                                //    else
                                //      window.setTimeout("print_result(" + solution_id + ")", 2000);
                                count = 0;
                            }
                        },
                        nl2br: function (str, is_xhtml) {
                            var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
                            return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
                        },
                        wsresult: function (data) {
                            //var res = data.split(',');
                            var status = parseInt(data["state"]);
                            var compile_info = data["compile_info"]
                            var pass_point = data["pass_point"];
                            var time = data["time"];
                            var memory = data["memory"];
                            var sim = data.sim;
                            var sim_s_id = data.sim_s_id;
                            var pass_rate = (data["pass_rate"] ? data["pass_rate"] : 1) * 100;
                            if (status > 3) {
                                count = 0;
                                this.submitDisabled = false;
                                this.resume();
                            }
                            if (status > 4 && status != 13) {
                                $("#right-side").transition("shake");
                            }
                            if (status == 0) {
                                $(".progess_text").text(judge_result[status]);
                                //setTimeout("frush_result(" + runner_id + ")", 250);
                                $('.progress.result').progress({
                                    percent: 20
                                });
                            }
                            else if (status == 2) {
                                $(".progess_text").text(judge_result[status]);
                                //setTimeout("frush_result(" + runner_id + ")", 250);
                                $('.progress.result').progress({
                                    percent: 40
                                });
                            }
                            else if (status == 3) {
                                $(".progess_text").text(judge_result[status] + " 已通过测试点:" + pass_point + "  通过率:" + pass_rate.toString().substring(0, 3) + "%");
                                // setTimeout("frush_result(" + runner_id + ")", 250);
                                $('.progress.result').progress({
                                    percent: 40
                                });
                            }
                            else if (status == 4) {
                                //count=0;
                                var str = judge_result[status] + " 内存使用:" + memory + "KB 运行时间:" + time + "ms";
                                if (sim) {
                                    str += " 触发判重 与运行号: " + sim_s_id + "代码重复 重复率:" + sim + "%";
                                }
                                $(".progess_text").text(str);
                                $('.progress.result').progress({
                                    percent: 100
                                });
                                $(".progress.result").progress('set success');
                                if (getParameterByName("cid") && parseInt(getParameterByName("cid")) > 1000) {
                                    $.get("contest_problem_ajax.php?cid=" + getParameterByName("cid"), function (data) {
                                        var json = JSON.parse(data);
                                        setTimeout(function () {
                                            $(".mainwindow").html("").animate({width: 0, borderRadius: 0, padding: 0});
                                        }, 500);
                                        var str = "<a class='item'><h3>剩下未完成的题目</h3></a>";
                                        if (json.length == 0) {
                                            str += "<a class='item'><h2>恭喜AK</h2>";
                                        }
                                        else {
                                            console.log(typeof json);
                                            json.sort(function (a, b) {
                                                if (a['num'] > b['num']) return 1;
                                                else if (a['num'] == b['num']) return 0;
                                                else return -1;
                                            })
                                        }
                                        for (i in json) {
                                            str += "<a class='item' href='" + json[i]['url'] + "'><div class='ui small teal label'>通过:&nbsp;" + json[i]['accept'] + "</div><div class='ui small label'>提交:&nbsp;" + json[i]['submit'] + "</div>" + json[i]['num'] + " . " + json[i]['title'] + "</a>";
                                        }
                                        var plain_html = '<div id="next_problem"><div class="ui massive vertical menu" style="position:relative;float:left;margin-left:20px">'+ str +'</div></div>';
                                        if($(".ui.massive.vertical.menu").length == 0) {
                                            $("#total_control").append(plain_html);
                                        }
                                        else {
                                            $(".ui.massive.vertical.menu").html(str).fadeIn();
                                        }
                                    });
                                }
                            }
                            else if (status == 5 || status == 6) {
                                //count=0;
                                $(".progess_text").text("在第" + (pass_point + 1) + "个测试点发生 " + judge_result[status] + "  通过率:" + pass_rate.toString().substring(0, 3) + "%");
                                $('.progress.result').progress({
                                    percent: 100
                                });
                                $(".progress.result").progress('set error');
                            }
                            else if(status == 13) {
                                if (typeof compile_info != "undefined") compile_info = this.nl2br(compile_info);
                                else compile_info = "";
                                $(".progess_text").text("在第" + (pass_point + 1) + "个测试点发生 " + judge_result[status] + " 内存使用:" + memory + "KB 运行时间:" + time + "ms");
                                if (compile_info && compile_info.trim().length > 0) {
                                    $(".compile.header").html("<br>" + compile_info);
                                    $(".warning.message").show();
                                }
                                $('.progress.result').progress({
                                    percent: 100
                                });
                                $(".progress.result").progress('set warning');
                            }
                            else {
                                //count=0;
                                if (typeof compile_info != "undefined") compile_info = "<br>" + this.nl2br(compile_info);
                                else compile_info = "";
                                $(".progess_text").text("在第" + (pass_point + 1) + "个测试点发生 " + judge_result[status] + "  通过率:" + pass_rate.toString().substring(0, 3) + "%");
                                if (compile_info.length > 0) {
                                    $(".compile.header").html(compile_info);
                                    $(".warning.message").show();
                                }
                                $('.progress.result').progress({
                                    percent: 100
                                });
                                $(".progress.result").progress('set warning');
                            }
                        },

                        do_submit: function () {
                            if (!window.connected) {
                                alert("WebSocket服务未启动，请等待服务启动后提交\nWebSocket服务启动标志未:\n右上角显示在线人数");
                                return;
                            }
                            this.hide_warning = true;
                            var that = this;
                            if (editor.getValue().length < 15) {
                                $('.ui.basic.confirms.modal')
                                    .modal({
                                        offset: 400,
                                        onShow: function () {
                                            that.confirm_text = "<h2><center>代码过短</center></h2>";
                                        }, onApprove: function () {
                                            that.presubmit();
                                        }
                                    })
                                    .modal('show')
                            }
                            else {
                                this.presubmit();
                            }
                        },
                        checkJava: function (submit_language) {
                            switch (submit_language) {
                                case 3:
                                case 23:
                                case 24:
                                case 27:
                                    var code = window.editor.getValue();
                                    if (code.indexOf("Main") === -1 || code.indexOf("main") === -1 || code.indexOf("package") !== -1) {
                                        alert("Java语言提交时必须使用Main作为主类\n使用static main作为入口函数\n并且不能存在包名(如package helloworld)");
                                        return false;
                                    }
                                default:
                                    return true;
                            }
                            return true;
                        },
                        presubmit: function () {
                            var that = this;
                            var qstring = getParameterByName;
                            var isadmin = this.isadmin;
                            var submit_language = parseInt($("#language").val());
                            if (!this.checkJava(submit_language)) {
                                return;
                            }
                            var type = "problem";
                            if (qstring("cid")) {
                                type = "contest";
                            }
                            else if (qstring("tid")) {
                                type = "topic"
                            }
                            this.submitDisabled = true;
                            $(".ui.teal.progress").show();
                            $(".progess_text").text("提交");
                            $('.progress.result').progress({
                                percent: 0
                            });
                            $(".progress.result").progress('set active');
                            var postdata = {
                                id: qstring("id"),
                                cid: qstring("cid"),
                                tid: qstring("tid"),
                                pid: qstring("pid"),
                                input_text: $("#ipt").val(),
                                language: submit_language,
                                source: window.editor.getValue(),
                                share: this.share,
                                type: type,
                                fingerprintRaw: that.fingerprintRaw,
                                fingerprint: that.fingerprint
                            };
                            window.postdata = postdata;
                            var that = this;
                            if (true) {
                                window.socket.emit("submit", {
                                            submission_id: "",
                                            val: window.postdata
                                        });
                            }
                            else {
                            $.post("submit.php", {
                                json: JSON.stringify(postdata)
                            }, function (data) {
                                var running_id = parseInt(data);
                                if (isNaN(running_id)) {
                                    alert("提交被服务器拒绝\n请重新正确的访问题面\n");
                                }
                                else {
                                    if (typeof window.socket == "object" && socket.connected) {
                                        window.socket.emit("submit", {
                                            submission_id: parseInt(running_id),
                                            val: window.postdata
                                        });
                                    }
                                    else
                                        frush_result(running_id);
                                    that.resume_time = 20;
                                    window.handler_interval = setTimeout(that.resume, 1000);
                                }
                            });
                            }
                        },
                        error_callback: function(data) {
                            alert(data.statement);
                            this.resume();
                            $(".ui.teal.progress").hide();
                        },
                        resume: function () {
                            if (--this.resume_time <= 0) {
                                this.submitDisabled = false;
                                clearInterval(window.handler_interval);
                            }
                            else {
                                window.handler_interval = setTimeout(this.resume, 1000);
                            }
                        },
                        pre_test_run: function() {
                            if (!window.connected) {
                                alert("WebSocket服务未启动，请等待服务启动后提交\nWebSocket服务启动标志未:\n右上角显示在线人数");
                                return;
                            }
                            //localStorage.removeItem("test_run");
                            var test_run_time = parseInt(localStorage.getItem("test_run_time"));
                            var now = parseInt(dayjs() + "");
                            
                            if(false && test_run_time && test_run_time > now)
                            {
                                if(test_run_time < now + 300 * 1000) {
                                    var tmp = (test_run_time - now) / 1000;
                                alert("两次测试运行间必须间隔5分钟\n请" + parseInt((tmp / 60)) + "分" + parseInt((tmp % 60)) + "秒后再测试运行");
                                return;
                                }
                            }
                            localStorage.setItem("test_run_time",now + 300 * 1000);

                            
                            var submit_language = parseInt($("#language").val());
                            if (!this.checkJava(submit_language)) {
                                return;
                            }
                            var that = this;
                            if (editor.getValue().length < 15) {
                                $('.ui.basic.confirms.modal')
                                    .modal({
                                        offset: 400,
                                        onShow: function () {
                                            that.confirm_text = "<h2><center>代码过短</center></h2>";
                                        }
                                    })
                                    .modal('show')
                                return;
                            }
                            $("#test_run_modal")
                                .modal({
                                    blurring: false
                                })
                                .modal('show')
                            ;
                            //this.test_run();
                        },
                        test_run: function () {
                            var that = this;
                            
                            $("#out").html("样例输出为:\n" + this.sampleoutput);
                            if(that.test_run_sampleinput && that.test_run_sampleinput.length > 1000) {
                                alert("测试运行输入用例长度不能超过1000字!");
                                return;
                            }
                            if (window.handler_interval) window.clearInterval(handler_interval);
                            //var loader = "<img style='display:inline-block' width=18 src=image/loader.gif>";
                            if (editor.getValue().length < 10) return alert("too short!");
                            //var tb = window.document.getElementById('result');
                            //tb.innerHTML = loader;
                            var qstring = getParameterByName;
                            var type = "problem";
                            if (qstring("cid")) {
                                type = "contest";
                            }
                            else if (qstring("tid")) {
                                type = "topic"
                            }
                            this.hide_warning = true;
                            if (typeof window.socket === "object") {
                                var postdata = {
                                    id: -Math.abs(parseInt(qstring("id"))) || -Math.abs(parseInt(that.original_id)),
                                    cid: -Math.abs(parseInt(qstring("cid"))) || null,
                                    tid: -Math.abs(parseInt(qstring("tid"))) || null,
                                    pid: qstring("pid"),
                                    share: this.share,
                                    input_text: window.problemsubmitter.$data.test_run_sampleinput,
                                    language: $("#language").val(),
                                    source: window.editor.getValue(),
                                    type: type,
                                    fingerprint: that.fingerprint,
                                    fingerprintRaw: that.fingerprintRaw
                                };
                                window.postdata = postdata;
                            }
                            console.log(window.postdata);
                            var isadmin = this.isadmin;
                            if (true) {
                                window.socket.emit("submit", {
                                            submission_id: "",
                                            val: window.postdata
                                        });
                            }
                            else {
                                $.post("submit.php?ajax", {
                                json: JSON.stringify(window.postdata)
                            }, function (data) {
                                if (!isNaN(parseInt(data)) && typeof window.socket == "object" && window.socket.connected) {
                                    window.socket.emit("submit", {submission_id: parseInt(data), val: window.postdata});
                                }
                                else {
                                    //TODO:wait for edit
                                    //fresh_result(data);
                                }
                            });
                            }
                            this.submitDisabled = true;
                            this.resume_time = 20;
                            window.handler_interval = setTimeout(that.resume, 1000);
                            $("#test_run_modal").modal("set").clickaway()
                        }
                    },
                    updated: function () {
                        var that = this;
                        this.$nextTick(function () {
                            if (that.single_page && !that.isRenderBodyOnTop) {
                                that.isRenderBodyOnTop = true;
                                $('.ui.vertical.center.aligned.segment.single')
                                    .visibility({
                                        once: false,
                                        offset: 30,
                                        observeChanges: false,
                                        continuous: false,
                                        refreshOnLoad: true,
                                        refreshOnResize: true,
                                        onTopPassedReverse: function () {
                                            that.bodyOnTop = true;
                                        },
                                        onTopPassed: function () {
                                            that.bodyOnTop = false;
                                        }
                                    });
                                that.bodyOnTop = true;
                            }
                            if (!that.single_page) {
                                $("#left-side").css({
                                    height:$("#right-side").height()
                                });
                            }
                            if(that.prepend || that.append) {
                                $('#Submit')
  .popup({
    position : 'top center',
    title    : '本题包含前后置代码',
    content  : '本题仅需提交代码中需要补充的部分，无需将前后置代码一同填入提交框提交,判题机会自动补充前后置代码到你编写的程序'
  })
;
                            }
                        })
                    },
                    mounted: function () {
                        var that = this;
                        $(document).on("click", function() { $(".mermaid").each(function(el, v) { if($(v).is(":visible")) { mermaid.init(undefined, v)}}) });
                        var descriptionMarkdownIt = this.descriptionMarkdownIt = markdownIt.newInstance("description", that.original_id);
                        var inputMarkdownIt = this.inputMarkdownIt = markdownIt.newInstance("input", that.original_id);
                        var outputMarkdownIt = this.outputMarkdownIt = markdownIt.newInstance("output", that.original_id);
                        var hintMarkdownIt = this.hintMarkdownIt = markdownIt.newInstance("hint", that.original_id);
                        $.get("/api/photo/description/" + id, function(data){
                        if(data.status == "OK") {
                            descriptionMarkdownIt.__image = {};
                            _.forEach(data.data, function(val, idx){
                            descriptionMarkdownIt.__image[val.name] = val.data;
                            that.description = descriptionMarkdownIt.render(that.original_content.description || '');
                        });
                        }
                    });
                    $.get("/api/photo/input/" + id, function(data){
                        if(data.status == "OK") {
                            inputMarkdownIt.__image = {};
                            _.forEach(data.data, function(val, idx){
                            inputMarkdownIt.__image[val.name] = val.data;
                            that.input = inputMarkdownIt.render(that.original_content.input || '');
                        });
                        }
                    });
                    $.get("/api/photo/output/" + id, function(data){
                        if(data.status == "OK") {
                            outputMarkdownIt.__image = {};
                            _.forEach(data.data, function(val, idx){
                            outputMarkdownIt.__image[val.name] = val.data;
                            that.output = outputMarkdownIt.render(that.original_content.output || '');
                            });
                        }
                    });
                    $.get("/api/photo/hint/" + id, function(data){
                        if(data.status == "OK") {
                            hintMarkdownIt.__image = {};
                            _.forEach(data.data, function(val, idx){
                            hintMarkdownIt.__image[val.name] = val.data;
                            that.hint = hintMarkdownIt.render(that.original_content.hint || '');
                            });
                        }
                    });
                        Fingerprint2.get(function (components) {
                            var values = components.map(function (component) { return component.value })
                            that.fingerprintRaw = Fingerprint2.x64hash128(values.join(''), 31);
                            $.get("../api/status/ip",function(data){
                                var ip = data.ip;
                                values.push(ip);
                                that.fingerprint = Fingerprint2.x64hash128(values.join(''), 31);
                            })
                        })
                        
                        this.description = descriptionMarkdownIt.render(this.description || '');
                        this.input = inputMarkdownIt.render(this.input || '');
                        this.output = outputMarkdownIt.render(this.output || '');
                        this.hint = hintMarkdownIt.render(this.hint || '');
                        this.bodyOnTop = true;
                        $(".not-compile").removeClass("not-compile");
                        $(".loading.dimmer").remove();

                        function load_editor() {
                            ace.require("ace/ext/language_tools");
                            var editor = ace.edit("source");
                            window.editor = editor;
                            var StatusBar = ace.require("ace/ext/statusbar").StatusBar;
                            var statusBar = new StatusBar(editor, document.getElementById("statusBar"));
                            editor.$blockScrolling = Infinity;
                            if (localStorage.getItem('theme')) {
                                editor.setTheme(localStorage.getItem('theme'));
                                qsa(".code").forEach(function (codeEl) {
                                    codeEl.setAttribute("ace-theme", localStorage.getItem('theme'));
                                });
                                setTimeout(function () {
                                    console.log("刷新前后置代码主题");
                                    that.flush_theme();
                                }, 0);
                            }
                            else {
                                editor.setTheme("ace/theme/monokai");
                                console.log("使用默认主题作为前后置代码主题");
                                that.flush_theme();
                            }
                            editor.setOptions({
                                enableBasicAutocompletion: true,
                                enableSnippets: true,
                                enableLiveAutocompletion: true,
                                enableEmmet: true
                            });
                            editor.getSession().setMode("ace/mode/" + language[that.selected_language]);
                            console.log(language[that.selected_language]);
                            //editor.setValue("//Please paste your code here");
                            /*editor.addEventListener("focus",function(){
                             var v=editor.getValue();
                             if(v=="//Please paste your code here")
                             {
                             editor.setValue("");
                             }
                             });*/
                            // editor.addEventListener("blur",function(){
                            // var v=editor.getValue();
                            // if(v=="")
                            // {
                            //      editor.setValue("//Please paste your code here");
                            //  }
                            //  });
                            if (localStorage.getItem('font-size')) {
                                document.getElementById('source').style.fontSize = localStorage.getItem('font-size') + 'px';
                                document.getElementById('fontsize').value = localStorage.getItem('font-size');
                                console.log("从Cookie中获取字体大小成功");
                                //  document.getElementById('prepend').style.fontSize='18px';
                                // document.getElementById('append').style.fontSize='18px';
                            }
                            else {
                                document.getElementById('source').style.fontSize = '18px';
                                console.log("未设置字体大小，使用默认字体大小");
                                // document.getElementById('prepend').style.fontSize='18px';
                                //  document.getElementById('append').style.fontSize='18px';
                            }
                            var theme_n;
                            if (localStorage.getItem('theme')) {
                                theme_n = localStorage.getItem('theme');
                            }
                            else {
                                theme_n = "ace/theme/monokai";
                            }
                            //console.log(theme_n);
                            var arr = document.getElementsByTagName("option");
                            var len = arr.length;
                            for (var i = 0; i < len; i++) {
                                //  console.log(arr[i].value);
                                if (arr[i].value == theme_n) {
                                    arr[i].selected = true;
                                    break;
                                }
                            }
                            $("#theme").on('change', function () {
                                var this_theme = this.value;
                                localStorage.setItem('theme', this.value);
                                console.log("设置主题成功！主题为:" + this.value);
                                localStorage.setItem('theme-name', $(this).find("option:selected").text());
                                console.log("设置主题名称成功！主题名称为:" + $(this).find("option:selected").text());
                                editor.setTheme(this.value);
                                qsa(".code").forEach(function (codeEl) {
                                    codeEl.setAttribute("ace-theme", this_theme);
                                });
                                that.flush_theme();
                                setTimeout(function () {
                                    $("#total_control").height($("#right-side").height());
                                }, 0);
                            });
                            var fonts = document.getElementById('source').style.fontSize;
                            $("#fontsize").val(fonts.substring(0, fonts.indexOf("px")));
                        }
                        try {
                        load_editor();
                        }
                        catch (e) {
                            if(e.message.indexOf("ace.edit") !== -1) {
                                location.href = location.href;
                            }
                        }
                        window.editor.getSession().setValue(this.source_code);
                        $('.ui.accordion')
                            .accordion({
                                'exclusive': false
                            });
                        $('.ui.dropdown.selection').dropdown({
                            on: 'hover'
                        });

                        resolve();
                        $("#left-side").css({
                            height:$("#right-side").height()
                        });
                        if (getParameterByName("sid")) {
                            $.get("/api/status/solution?sid=" + getParameterByName("sid"), function (data) {
                                that.selected_language = parseInt(data.data.language);
                                // that.reloadtemplate(null, data.data.language)
                            })
                        }
                    }
                });
                var copy_content = new Clipboard(".copy.context", {
                    text: function (trigger) {
                        return $(trigger).parent().next().text().trim();
                    }
                });
                copy_content.on("success", function (e) {
                    $(e.trigger)
                        .popup({
                            title: 'Finished',
                            content: 'Context is in your clipboard',
                            on: 'click'
                        })
                        .popup("show");
                })
                window.problemsubmitter = problemsubmitter;
                window.problem_detail = data;
                //MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
            });
        })
            .then(function (resolve) {
                var obj = document.getElementById('clipbtn');
                if (obj) {
                    var clipboard = new Clipboard(obj, {
                        text: function (trigger) {
                            var data = problemsubmitter.$data;
                            var mergetext = data.prepend[data.selected_language];
                            mergetext += "\n/*请在下方编写你的代码,仅需提交填写的部分*/\n";
                            if (editor.getValue().length !== 0) {
                                mergetext += window.editor.getValue();
                            }
                            else {
                                mergetext += "\n\n\n\n";
                            }
                            mergetext += "\n/*请在上方填写你的代码,仅需提交填写的部分*/\n";
                            mergetext += data.append[data.selected_language];
                            //document.getElementById('clipcp').innerHTML = mergetext;
                            return mergetext;
                        }
                    });
                    clipboard.on('success', function (e) {
                        alert("复制到剪贴板成功!");
                        console.log(e);
                    });
                    clipboard.on('error', function (e) {
                        console.error(e);
                        console.log("复制失败！请手动复制代码");
                        console.log("Firefox 有一定几率报错，待修复");
                    });
                }
            });
        var judge_result = [<?php
            foreach ($judge_result as $result) {
                echo "'$result',";
            }
            ?>''];
        var judge_color = [<?php
            foreach ($judge_color as $result) {
                echo "'$result',";
            }
            ?>''];
    </script>
    <div class="ui basic modal confirms hidden">
        <div class="ui icon header">
            <i class="archive icon"></i>提示
        </div>
        <div class="content" id="confirm_box" v-html="confirm_text">
        </div>
        <div class="actions">
            <div class="ui red basic cancel inverted button">
                <i class="remove icon"></i>
                返回
            </div>
            <div class="ui green ok inverted button">
                <i class="checkmark icon"></i>
                确认提交
            </div>
        </div>
    </div>
    <div class="ui modal" style="background-color: #ffffff" id="test_run_modal">
        <i class="close icon"></i>
        <div class="header">测试运行</div>
        <div class="content">
            <div class="ui grid" style="height:70%;margin:auto;">
                <div class="row">
                    <div class="ui message" style="width: 100%; padding: 1rem">
                        <div class="header">
                            使用说明
                        </div>
                        在测试输入框中输入测试数据后，点击执行，会根据输入的测试数据运行，得出输出结果。
                    </div>
                </div>
                <div class="row">
                    <div class="ten wide column" style="margin: auto">
                        <div class="ui header" style="margin: auto">测试输入</div>
                    </div>
                    <div class="six wide right aligned column">
                        <div class="ui basic button" @click="test_run_sampleinput = sampleinput">复制样例输入</div>
                    </div>
                </div>
                <div class="row" style="padding: 1rem">
                    <textarea style="width:100%;resize: none;border-radius:10px;font-family:SF Mono,Monaco,monospace;" cols=40
                                                       rows=7
                                                       id="input_text" v-model="test_run_sampleinput"></textarea>
                </div>
                <div class="row">
                    <div class="ten wide column" style="margin: auto">
                        <div class="ui header" style="margin: auto">测试结果</div>
                    </div>
                    <div class="six wide right aligned column">
                        <div class="ui basic button" style="opacity: 0"></div>
                    </div>
                </div>
                <div class="row" style="padding: 1rem">
                    <textarea style="width:100%;resize: none;border-radius:10px;font-family:SF Mono,Monaco,monospace" cols="40" rows="7" id="out" name="out"
                              :placeholder="'SHOULD BE:\n'+sampleoutput">
                        </textarea>
                </div>
                <div class="row" style="padding: 1rem">
                    <a class="ui basic button" @click.prevent="test_run">执行</a>
                </div>
                <div class="row" style="padding:1rem;margin: auto;text-align:right;">
                    <div class="ui teal progress result" style="width: 100%" data-value="0" data-total="3" id="progress">
                    <div class="bar">
                        <div class="progress"></div>
                    </div>
                    <div class="label progess_text" id="progess_text"></div>
                </div>
                <div class="ui warning message" :class="'ui warning message '+(hide_warning?'hidden':'')">
                    <i class="close icon"></i>
                    <div class="header compile">
                    </div>
                </div>
                    <!--<div id="result" class="ui blue button right" style="text-align: right">状态</div>-->
                    &nbsp;
                </div>
            </div>
        </div>
    </div>
    <div class="main submit layout">
        <style type="text/css">
            .code {
                width: 50%;
                white-space: pre-wrap;
                border: solid lightgrey 1px
            }
        </style>

        <!-- Main component for a primary marketing message or call to action -->
        <div v-show="single_page === true" class="ui container not-compile" v-cloak>
            <div class="not-compile">
                <div class="following bar title" v-show="!bodyOnTop"
                     :style="(!bodyOnTop?'opacity:1;':'opacity:0;') + 'z-index:99'">
                    <div :class="'ui vertical center aligned grid'">
                        <div class="row no padding">
                            <div :class="'sixteen wide center aligned column'">
                                <div class="ui header" id="probid" v-html="title" style="font-size:1.71428571rem"></div>
                                </div>
                            <div class="eight wide center aligned column">
                                <div class='ui labels'>
                                    <li class='ui label red' id="tlimit"
                                        v-text="time"></li>
                                    <li class='ui label red' id="mlimit"
                                        v-text="memory"></li>
                                    <li class='ui label orange' id="spj" v-cloak v-show="spj">Special Judge</li>
                                    <li class='ui label grey' id="totsub"
                                        v-text="submit"></li>
                                    <li class='ui label green' id="totac"
                                        v-text="accepted"></li>
                                </div>
                            </div>
                        </div>
                        <div class="row no padding">
                            <div class="column">
                                <div class='ui buttons'>
                                    <a :href="'problemstatus.php?id='+original_id"
                                       class='ui button orange'><?= $MSG_STATUS ?></a>
                                    <a @click.prevent="switch_screen($event)"
                                       href='problem.php?<?= $_SERVER['QUERY_STRING'] ?>'
                                       class='ui button blue'>切换双屏</a>
                                    <a v-if="!getParameterByName('cid') && !getParameterByName('tid')"
                                       :href="'tutorial.php?id='+original_id" class="ui button teal">
                                        查看题解
                                    </a>

                                    <a class='ui button violet' v-if="iseditor||isadmin"
                                       :href="'/problem_edit.php'+location.search" target="_blank">Edit</a>
                                    <?php
                                    if (isset($_SESSION['administrator'])) {
                                        require_once("include/set_get_key.php");
                                        ?>
                                        <a class='ui button purple'
                                           :href="'admin/quixplorer/index.php?action=list&dir='+original_id+'&order=name&srt=yes'">TestData</a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ui vertical center aligned segment single" :style="bodyOnTop?'opacity:1':'opacity:0'">
                    <div class="ui header" id="probid" v-html="title"  style="font-size:1.71428571rem"></div>
                    <div class='ui labels'>
                        <li class='ui label red' id="tlimit"
                            v-text="time"></li>
                        <li class='ui label red' id="mlimit"
                            v-text="memory"></li>
                        <li class='ui label orange' id="spj" v-cloak v-show="spj">Special Judge</li>
                        <li class='ui label grey' id="totsub"
                            v-text="submit"></li>
                        <li class='ui label green' id="totac"
                            v-text="accepted"></li>
                    </div>
                    <br>
                    <div class='ui buttons'>
                        <a :href="'problemstatus.php?id='+original_id" class='ui button orange'><?= $MSG_STATUS ?></a>
                        <a @click.prevent="switch_screen($event)" href='problem.php?<?= $_SERVER['QUERY_STRING'] ?>'
                           class='ui button blue'>切换双屏</a>
                        <a v-if="!getParameterByName('cid') && !getParameterByName('tid')"
                           :href="'tutorial.php?id='+original_id" class="ui button teal">
                            查看题解
                        </a>

                        <a class='ui button violet' v-if="iseditor||isadmin"
                           :href="'/problem_edit.php?id='+original_id" target="_blank">Edit</a>
                        <?php
                        if (isset($_SESSION['administrator'])) {
                            require_once("include/set_get_key.php");
                            ?>
                            <a class='ui button purple'
                               :href="'admin/quixplorer/index.php?action=list&dir='+original_id+'&order=name&srt=yes'">TestData</a>
                            <?php
                        }
                        ?>
                    </div>
                </div>

                <h2 class='ui header hidden'><?= $MSG_Description ?></h2>
                <div class='ui hidden' v-html="description"></div>
                <h2 class='ui header hidden'><?= $MSG_Input ?></h2>
                <div class='ui hidden' v-html="input||''"></div>
                <h2 class='ui header hidden'><?= $MSG_Output ?></h2>
                <div class='ui hidden' v-html="output||''"></div>
                <h2 class='ui header hidden'><?= $MSG_Sample_Input ?></h2>
                <div class="ui bottom attached segment hidden sample_input">
                    <div class="ui top attached label"><a data-clipboard-target=".sample_input" class="copy context">Copy
                            Sample Input</a></div>
                    <pre v-text='sampleinput'></pre>
                </div>
                <h2 class='ui header'><?= $MSG_Sample_Output ?></h2>
                <div class="ui bottom attached segment">
                    <div class="ui top attached label"><a data-clipboard-target=".sample_output" class="copy context">Copy
                            Sample Output</a></div>
                    <pre class='sample_output' v-text='sampleoutput'></pre>
                </div>
                <h2 class='ui header'><?= $MSG_HINT ?></h2>
                <div class='ui' v-html="hint"></div>
                <h2 class='ui header'><?= $MSG_Source ?></h2>
                <div class='ui'><p><a :href='"problemset.php?tag="+encodeURI(source)' id='problem_source'
                                                   v-text='source'></a></p>
                <p>上传者:<a target="_blank" v-if="uploader!=='Administrator'" :href="'userinfo.php?user='+uploader">{{uploader}}</a><a v-else>Administrator</a></p>
                </div>
            </div>
        </div>
        <div v-show="single_page === false"
             style="max-width:1300px;position:relative;margin:auto;height: 540px;border-radius: 10px"
             id="total_control">
            <div class="padding ui container mainwindow"
                 style="height:100%;width: 35%;overflow-y: auto;float:left;-webkit-border-radius: ;-moz-border-radius: ;border-radius: 10px;" id="left-side">
                <div class="ui vertical center aligned segment">
                    <div class="ui header" id="probid" v-html="title" style="font-size:1.71428571rem"></div>
                    <div class='ui labels'>
                        <li class='ui label red' id="tlimit"
                            v-text="time"></li>
                        <li class='ui label red' id="mlimit"
                            v-text="memory"></li>
                        <li class='not-compile' :class="'ui label orange'" id="spj" v-cloak v-show="spj">Special Judge
                        </li>
                        <li class='ui label grey' id="totsub"
                            v-text="submit"></li>
                        <li class='ui label green' id="totac"
                            v-text="accepted"></li>
                    </div>
                    <br>
                    <div class='ui buttons'>
                        <a :href="'problemstatus.php?id='+original_id" class='ui button orange'><?= $MSG_STATUS ?></a>
                        <a @click.prevent="switch_screen($event)" href='problem.php?<?= $_SERVER['QUERY_STRING'] ?>'
                           class='ui button blue'>切换单屏</a>
                        <a v-if="!getParameterByName('cid') && !getParameterByName('tid')"
                           :href="'tutorial.php?id='+original_id" class="ui button teal">
                            查看题解
                        </a>
                        <a v-if="iseditor||isadmin" class='ui button violet'
                           :href="'/problem_edit.php?id='+original_id" target="_blank">Edit</a>
                        <?php
                        if (isset($_SESSION['administrator'])) {
                            require_once("include/set_get_key.php");
                            ?>

                            <a class='ui button purple'
                               :href="'admin/quixplorer/index.php?action=list&dir='+original_id+'&order=name&srt=yes'">TestData</a>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <br>
                <div class="ui styled accordion">
                    <div class='title'><?= $MSG_Description ?><i class="dropdown icon"></i></div>
                    <div class='content' id='problem_description' v-html="description">
                    </div>
                    <div class='title'><?= $MSG_Input ?><i class="dropdown icon"></i></div>
                    <div class='content' id='problem_input' v-html="input||''">
                    </div>
                    <div class='title'><?= $MSG_Output ?><i class="dropdown icon"></i></div>
                    <div class='content' id='problem_output' v-html="output||''"></div>
                    <div class='title'><?= $MSG_Sample_Input ?><i class="dropdown icon"></i>
                    </div>
                    <div class='content'>
                        <div class="ui bottom attached segment">
                            <div class="ui top attached label"><a data-clipboard-target=".sample_input"
                                                                  class="copy context">Copy Sample Input</a></div>
                            <pre><span id='problem_sample_input' class='sample_input'
                                       v-text='sampleinput'></span></pre>
                        </div>
                    </div>
                    <div class='title'><?= $MSG_Sample_Output ?><i class="dropdown icon"></i></div>
                    <div class='content'>
                        <div class="ui bottom attached segment">
                            <div class="ui top attached label"><a data-clipboard-target=".sample_output"
                                                                  class="copy context">Copy Sample Output</a></div>
                            <pre><span id='problem_sample_output'
                                       class='sample_output'
                                       v-text='sampleoutput'></span></pre>
                        </div>
                    </div>
                    <div class='title'><?= $MSG_HINT ?><i class="dropdown icon"></i></div>
                    <div class='content' v-html="hint">
                    </div>
                    
                        <div class='title'><?= $MSG_Source ?><i class="dropdown icon"></i></div>
                        <div class='content'><p><a :href='"problemset.php?tag="+encodeURI(source)' id='problem_source'
                                                   v-text='source'></a></p>
                                                   <p>上传者:<a target="_blank" v-if="uploader!=='Administrator'" :href="'userinfo.php?user='+uploader">{{uploader}}</a><a v-else>Administrator</a></p></div>
                        
                </div>
            </div>
            <div style="width:65%;position:relative;float:left; border-radius: " id="right-side">
                <script src="template/semantic-ui/js/editor_config.js?ver=1.0.8.1"></script>
                <textarea style="display:none" cols=40 rows=5 name="input_text"
                          id="ipt" class="sample_input">样例输入</textarea>
                <div id="modeBar" style="margin: 0;
        padding: 0;
        position: relative;
        height: 35px;
        color: black;width:100%;text-align:right" class="ui menu borderless">
                    <div class="item not-compile">
                        <select class="not-compile" v-cloak :class="'ui dropdown selection'" id="language"
                                name="language" v-model="selected_language">
                            <option v-for="language in lang_list" :value="language.num">{{language.name}}</option>
                        </select>
                        <div class="item">
                            <div class="ui toggle checkbox" v-cloak>
                                <input type="checkbox" name="auto_detect" v-model="auto_detect">
                                <label>自动选择</label>
                            </div>
                        </div>
                        <a
                                :class="'item'" class="not-compile" v-cloak id="clipbtn" data-clipboard-action="copy"
                                style="float:left;" v-if="prepend||append">复制代码</a>
                                
                    </div>
                    <div class="right menu">
                        <div class="item">
                        </div>
                        
                        <div class="item">
                            <span>主题:</span><select class="ui selection dropdown search" id="theme" size="1">

                                <optgroup label="Bright">
                                    <option value="ace/theme/chrome">Chrome</option>
                                    <option value="ace/theme/clouds">Clouds</option>
                                    <option value="ace/theme/crimson_editor">Crimson Editor</option>
                                    <option value="ace/theme/dawn">Dawn</option>
                                    <option value="ace/theme/dreamweaver">Dreamweaver</option>
                                    <option value="ace/theme/eclipse">Eclipse</option>
                                    <option value="ace/theme/github">GitHub</option>
                                    <option value="ace/theme/iplastic">IPlastic</option>
                                    <option value="ace/theme/solarized_light">Solarized Light</option>
                                    <option value="ace/theme/textmate">TextMate</option>
                                    <option value="ace/theme/tomorrow">Tomorrow</option>
                                    <option value="ace/theme/xcode">XCode</option>
                                    <option value="ace/theme/kuroir">Kuroir</option>
                                    <option value="ace/theme/katzenmilch">KatzenMilch</option>
                                    <option value="ace/theme/sqlserver">SQL Server</option>
                                </optgroup>
                                <optgroup label="Dark">
                                    <option value="ace/theme/ambiance">Ambiance</option>
                                    <option value="ace/theme/chaos">Chaos</option>
                                    <option value="ace/theme/clouds_midnight">Clouds Midnight</option>
                                    <option value="ace/theme/cobalt">Cobalt</option>
                                    <option value="ace/theme/gruvbox">Gruvbox</option>
                                    <option value="ace/theme/idle_fingers">idle Fingers</option>
                                    <option value="ace/theme/kr_theme">krTheme</option>
                                    <option value="ace/theme/merbivore">Merbivore</option>
                                    <option value="ace/theme/merbivore_soft">Merbivore Soft</option>
                                    <option value="ace/theme/mono_industrial">Mono Industrial</option>
                                    <option value="ace/theme/monokai">Monokai</option>
                                    <option value="ace/theme/pastel_on_dark">Pastel on dark</option>
                                    <option value="ace/theme/solarized_dark">Solarized Dark</option>
                                    <option value="ace/theme/terminal">Terminal</option>
                                    <option value="ace/theme/tomorrow_night">Tomorrow Night</option>
                                    <option value="ace/theme/tomorrow_night_blue">Tomorrow Night Blue</option>
                                    <option value="ace/theme/tomorrow_night_bright">Tomorrow Night Bright</option>
                                    <option value="ace/theme/tomorrow_night_eighties">Tomorrow Night 80s</option>
                                    <option value="ace/theme/twilight">Twilight</option>
                                    <option value="ace/theme/vibrant_ink">Vibrant Ink</option>
                                </optgroup>
                            </select></div>
                    </div>
                </div>
                <div v-if="prepend" class="prepend code"
                     style="width: 100%;padding:0px;line-height:1.2;text-align:left;margin-bottom:0px;"
                     :ace-mode="'ace/mode/'+language_template[selected_language]"
                     ace-theme="ace/theme/monokai"
                     id="prepend" v-text="current_prepend">
                </div>
                <div style="width:100%;height:460px" :style="{width:'100%',height:'460px',fontSize:fontSize+'px'}"
                     cols=180 rows=20
                     id="source"></div>
                <div v-if="append" id="append" class="append code"
                     style="width: 100%; padding:0px; line-height:1.2;text-align:left;margin-bottom:0px;"
                     :ace-mode="'ace/mode/'+language_template[selected_language]"
                     ace-theme="ace/theme/monokai" v-html="current_append">
                </div>
                <div id="statusBar" style="margin: 0;
        padding: 0;
        position: relative;
        height: 30px;
        color: black;width:100%" class="ui menu borderless">
                    <div style="text-align:center;" class="item">
                        CUP Online Judge&nbsp;&nbsp;
                        <div class="item">
                            <div class="ui toggle checkbox" v-cloak v-if="!iscontest">
                                <input type="checkbox" name="share" v-model="share">
                                <label>允许他人查看代码</label>
                            </div>
                        </div>
                        <div class="item"><span class="item">字号:</span>
                            <div class="ui input"><input type="text" value=""
                                                         style="width:60px;text-align:center;height:30px"
                                                         id="fontsize" @keyup="resize($event)"></div>
                        </div>
                    </div>
                    <div class="ui right menu">
                        <div class="ui buttons">
                            <input id="Submit" class="ui button green " :disabled="submitDisabled" type=button
                                   value="提交"
                                   @click="do_submit">
                            <div class="or"></div>
                            <input id="TestRun" class="ui button blue" @click="pre_test_run" :disabled="submitDisabled"
                                   type=button value="测试运行"
                            >&nbsp;<!--<span class="btn" id=result>状态</span>-->
                        </div>
                    </div>
                </div>
                <div class="ui teal progress result" data-value="0" data-total="3" id="progress" style="display:none">
                    <div class="bar">
                        <div class="progress"></div>
                    </div>
                    <div class="label progess_text"></div>
                </div>
                <div class="ui warning message hidden" :class="'ui warning message '+(hide_warning?'hidden':'')">
                    <i class="close icon"></i>
                    <div class="header compile">
                    </div>
                </div>
                <br>
                <script>
                    var sid = 0;
                    var i = 0;
                    var using_blockly = false;
                    var count = 0;

                    console.timeEnd("function");
                </script>
                <div id="clipcp" style="display:none"></div>
            </div>
            
        </div>
    </div>
</div>
<?php include("template/semantic-ui/bottom.php"); ?>
<!-- /container -->
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

</body>
<script>

</script>
</html>
