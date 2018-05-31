<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <?php include("csrf.php"); ?>
    <title>
    </title>
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <link href="/css/github-markdown.min.css" rel="stylesheet" type="text/css">
    <link href="/js/styles/github.min.css" rel="stylesheet" type="text/css">
    <link href="/template/semantic-ui/css/katex.min.css" rel="stylesheet">
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <script src="/template/semantic-ui/js/clipboard.min.js"></script>
    <script src="ace-builds/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
    <script src="ace-builds/src-min-noconflict/ext-language_tools.js" type="text/javascript"
            charset="utf-8"></script>

    <script src="ace-builds/src-min-noconflict/ext-error_marker.js" type="text/javascript" charset="utf-8"></script>
    <script src="ace-builds/src-min-noconflict/ext-statusbar.js?ver=1.0" type="text/javascript"
            charset="utf-8"></script>
    <script src="ace-builds/src-min-noconflict/ext-emmet.js" type="text/javascript" charset="utf-8"></script>

    <script src="template/semantic-ui/js/cookie.js"></script>
    <script src="ace-builds/src-min-noconflict/ext-static_highlight.js"></script>
    <style>
        .ui.modal {
            top: 30%;
        }

        script {
            display: none;
        }

        .not-compile {
            display: none;
        }
        .sample_input{
            color:#ad1457;
        }
        .sample_output{
            color:#ad1457;
        }
    </style>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
<div class="main screen" v-cloak>
    <script>
        window.lastlang = localStorage.getItem("lastlang") || 19;

        function qsa(sel) {
            return Array.apply(null, document.querySelectorAll(sel));
        }

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
                <?php if(!isset($is_vjudge) || (isset($is_vjudge) && !$is_vjudge)){  ?>
                $(document).ready(function () {
                    console.log("页面加载完毕，进行AJAX POST请求");
                    var file_n = language_ext[document.getElementById('language').value];
                    var qstring = getParameterByName;
                    $.post("getfile.php?ajax",
                        {
                            file_name: file_n,
                            id: qstring("id"),
                            cid: qstring("cid"),
                            pid: qstring("pid"),
                            csrf: "<?=$token?>"
                        }, function (data) {
                            console.log("POST请求得到响应");
                            var res = JSON.parse(data);
                            if (res["empty"] == false) {
                                console.log(res);
                                prepend_file_context = res["prepend"];
                                append_file_context = res["append"];
                                var empty = res["empty"];
                                if (empty != "true") {
                                    $("#prepend").text(prepend_file_context);
                                    $("#prepend_hide").text(prepend_file_context);
                                    $("#append").text(append_file_context);
                                    $("#append_hide").text(append_file_context);
                                }
                                setTimeout(function () {
                                    console.log("刷新前后置代码主题");
                                    flush_theme();
                                }, 0);
                            }
                        });
                    setTimeout(function () {
                        console.log("刷新前后置代码主题");
                        flush_theme();
                    }, 0);
                });
                <?php } ?>
            }
            else {
                editor.setTheme("ace/theme/monokai");
                console.log("使用默认主题作为前后置代码主题");
                flush_theme();
            }
            editor.setOptions({
                enableBasicAutocompletion: true,
                enableSnippets: true,
                enableLiveAutocompletion: true,
                enableEmmet: true
            });
            editor.getSession().setMode("ace/mode/" + language[window.lastlang]);
            console.log(language[window.lastlang]);
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
                var prepend_code = "";
                <?php if(isset($OJ_APPENDCODE) && $OJ_APPENDCODE && file_exists($prepend_file))
                {?>
                prepend_code = document.getElementById('prepend_hide').innerHTML;
                console.log("成功设置前置代码");
                <?php } ?>
                var append_code = "";
                <?php if(isset($OJ_APPENDCODE) && $OJ_APPENDCODE && file_exists($append_file))
                {?>
                append_code = document.getElementById('append_hide').innerHTML;
                console.log("成功设置后置代码");
                <?php } ?>
                var this_theme = this.value;
                localStorage.setItem('theme', this.value);
                console.log("设置主题成功！主题为:" + this.value);
                localStorage.setItem('theme-name', $(this).find("option:selected").text());
                console.log("设置主题名称成功！主题名称为:" + $(this).find("option:selected").text());
                editor.setTheme(this.value);
                qsa(".code").forEach(function (codeEl) {
                    codeEl.setAttribute("ace-theme", this_theme);
                });
                <?php if(isset($OJ_APPENDCODE) && $OJ_APPENDCODE && file_exists($prepend_file))
                {
                ?>
                document.getElementById('prepend').innerHTML = prepend_code;
                console.log("加入前置代码");
                <?php

                } ?>
                <?php if(isset($OJ_APPENDCODE) && $OJ_APPENDCODE && file_exists($append_file))
                {
                ?>
                document.getElementById('append').innerHTML = append_code;
                console.log("加入后置代码");
                <?php

                } ?>
                flush_theme();
                setTimeout(function () {
                    $("#total_control").height($("#right-side").height());
                }, 0);
            });
            var fonts = document.getElementById('source').style.fontSize;
            $("#fontsize").val(fonts.substring(0, fonts.indexOf("px")));


        }

        var loadVue = new Promise(function (resolve, reject) {
            $.get("/api/problem/local" + window.location.search, function (data) {
                console.log(data);
                if (data['status'] == "error") {
                    alert(data['statement']);
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
                            description: d.description,
                            time: "时间限制:" + d.time_limit + "秒",
                            memory: "内存限制:" + d.memory_limit + "MB",
                            input: d.input,
                            output: d.output,
                            sampleinput: d.sample_input,
                            sampleoutput: d.sample_output,
                            hint: d.hint,
                            submit: "提交:" + d.submit,
                            accepted: "正确:" + d.accepted,
                            source: d.source,
                            show_style: "preview",
                            not_show_toolbar: false,
                            spj: Boolean(parseInt(d.spj)),
                            single_page: false,
                            source_code: source_code,
                            language_name: d.language_name,
                            prepend: d.prepend,
                            append: d.append,
                            langmask: d.langmask,
                            isadmin: isadmin,
                            iseditor: iseditor,
                            selected_language: localStorage.getItem("lastlang") && Boolean(parseInt(localStorage.getItem("lastlang")) & (~d.langmask)) ? parseInt(localStorage.getItem("lastlang")) : Math.log2(~d.langmask & -~d.langmask),
                            language_template: d.language_template,
                            fontSize: 18,
                            hide_warning: true,
                            confirm_text: "",
                            submitDisabled: false,
                            resume_time: 0
                        };
                        if (id) {
                            _data.problem_id = d.problem_id;
                        }
                        else {
                            _data.problem_id = String.fromCharCode(parseInt(pid) + "A".charCodeAt(0));
                        }
                        $("title").html(_data.problem_id + ":" + _data._title + " -- CUP Online Judge");
                        return _data;
                    },
                    computed: {
                        title: function () {
                            return this.problem_id + ": " + d.title;
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
                    methods: {
                        switch_screen: function ($event) {
                            this.single_page = !this.single_page;
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
                            var tb = window.document.getElementById('result');
                            var loader = "<img width=18 src=image/loader.gif>";
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
                            if (state >= 4) {
                                if (test_run_result || compile_info) {
                                    $("#out").text("运行结果  :\n" + (test_run_result || "") + (compile_info || ""));
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
                            var pass_rate = data["pass_rate"] * 100;
                            if (status > 3) {
                                count = 0;
                                this.submitDisabled = false;
                                this.resume();
                            }
                            if (status > 4 && status != 13) {
                                $("#right-side").transition("shake");
                            }
                            if (status == 0) {
                                $("#progess_text").text(judge_result[status]);
                                //setTimeout("frush_result(" + runner_id + ")", 250);
                                $('#progress').progress({
                                    percent: 20
                                });
                            }
                            else if (status == 2) {
                                $("#progess_text").text(judge_result[status]);
                                //setTimeout("frush_result(" + runner_id + ")", 250);
                                $('#progress').progress({
                                    percent: 40
                                });
                            }
                            else if (status == 3) {
                                $("#progess_text").text(judge_result[status] + " 已通过测试点:" + pass_point + "  通过率:" + pass_rate.toString().substring(0, 3) + "%");
                                // setTimeout("frush_result(" + runner_id + ")", 250);
                                $('#progress').progress({
                                    percent: 40
                                });
                            }
                            else if (status == 4) {
                                //count=0;
                                $("#progess_text").text(judge_result[status] + " 内存使用:" + memory + "KB 运行时间:" + time + "ms");
                                $('#progress').progress({
                                    percent: 100
                                });
                                $("#progress").progress('set success');
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
                                        $(".ui.massive.vertical.menu").html(str).fadeIn();
                                    });
                                }
                            }
                            else if (status == 5 || status == 6) {
                                //count=0;
                                $("#progess_text").text("在第" + (pass_point + 1) + "个测试点发生 " + judge_result[status] + "  通过率:" + pass_rate.toString().substring(0, 3) + "%");
                                $('#progress').progress({
                                    percent: 100
                                });
                                $("#progress").progress('set error');
                            }
                            else {
                                //count=0;
                                if (typeof compile_info != "undefined") compile_info = "<br>" + this.nl2br(compile_info);
                                else compile_info = "";
                                $("#progess_text").text("在第" + (pass_point + 1) + "个测试点发生 " + judge_result[status] + "  通过率:" + pass_rate.toString().substring(0, 3) + "%");
                                if (compile_info.length > 0) {
                                    $(".compile.header").html(compile_info);
                                    $(".warning.message").show();
                                }
                                $('#progress').progress({
                                    percent: 100
                                });
                                $("#progress").progress('set warning');
                            }
                        },
                        reloadtemplate: function ($event, _value) {
                            var $value;
                            if ($event) {
                                $value = $event.target.value;
                            }
                            else {
                                $value = _value;
                            }
                            localStorage.setItem("lastlang", $value);
                            $("#language").dropdown("set selected", $value.toString())
                            this.selected_language = $value;
                            var editor = window.editor;
                            var langn = $value;
                            var file_n = language_ext[langn];
                            editor.getSession().setMode("ace/mode/" + language[langn]);
                            var highlight = ace.require("ace/ext/static_highlight")
                            var dom = ace.require("ace/lib/dom")
                            qsa(".code").forEach(function (codeEl) {
                                codeEl.innerHTML = "";
                                codeEl.setAttribute("ace-mode", "ace/mode/" + language[langn])
                            });
                        },
                        do_submit: function () {
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
                        presubmit: function () {
                            var qstring = getParameterByName;
                            var type = "problem";
                            if (qstring("cid")) {
                                type = "contest";
                            }
                            else if (qstring("tid")) {
                                type = "topic"
                            }
                            this.submitDisabled = true;
                            $(".ui.teal.progress").show();
                            $("#progess_text").text("提交");
                            $('#progress').progress({
                                percent: 0
                            });
                            $("#progress").progress('set active');
                            var postdata = {
                                id: qstring("id"),
                                cid: qstring("cid"),
                                tid: qstring("tid"),
                                pid: qstring("pid"),
                                input_text: $("#ipt").val(),
                                language: $("#language").val(),
                                source: window.editor.getValue(),
                                type: type,
                                csrf: "<?=$token?>"
                            };
                            window.postdata = postdata;
                            var that = this;
                            $.post("submit.php", {
                                json: JSON.stringify(postdata),
                                csrf: "<?=$token?>"
                            }, function (data) {
                                var running_id = parseInt(data);
                                if (isNaN(running_id)) {
                                    alert("提交被服务器拒绝\n");
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
                        test_run: function () {
                            this.hide_warning = true;
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
                            $("#out").html($("#hidden_sample_output").html());
                            $('.ui.standard.modal')
                                .modal({
                                    blurring: false,
                                    allowMultiple: true
                                })
                                .modal('show')
                            ;
                            if (window.handler_interval) window.clearInterval(handler_interval);
                            var loader = "<img width=18 src=image/loader.gif>";
                            var tb = window.document.getElementById('result');
                            if (editor.getValue().length < 10) return alert("too short!");
                            tb.innerHTML = loader;
                            var qstring = getParameterByName;
                            var type = "problem";
                            if (qstring("cid")) {
                                type = "contest";
                            }
                            else if (qstring("tid")) {
                                type = "topic"
                            }
                            if (typeof window.socket === "object") {
                                var postdata = {
                                    id: -Math.abs(parseInt(qstring("id"))) || -Math.abs(parseInt(that.original_id)),
                                    cid: -Math.abs(parseInt(qstring("cid"))) || null,
                                    tid: qstring("tid"),
                                    pid: qstring("pid"),
                                    input_text: window.problemsubmitter.$data.sampleinput,
                                    language: $("#language").val(),
                                    source: window.editor.getValue(),
                                    type: type,
                                    csrf: "<?=$token?>"

                                };
                                window.postdata = postdata;


                            }
                            console.log(window.postdata);
                            $.post("submit.php?ajax", {
                                json: JSON.stringify(window.postdata),
                                csrf: "<?=$token?>"
                            }, function (data) {
                                if (typeof window.socket == "object" && window.socket.connected) {
                                    window.socket.emit("submit", {submission_id: parseInt(data), val: window.postdata});
                                }
                                else {
                                    //TODO:wait for edit
                                    //fresh_result(data);
                                }
                            });
                            this.submitDisabled = true;
                            this.resume_time = 20;
                            window.handler_interval = setTimeout(that.resume, 1000);
                        }
                    },
                    mounted: function () {
                        var that = this;
                        $(".not-compile").removeClass("not-compile");
                        load_editor.call(window);
                        window.editor.getSession().setValue(this.source_code);
                        $('.ui.accordion')
                            .accordion({
                                'exclusive': false
                            });
                        $('.ui.dropdown.selection').dropdown({
                            on: 'hover'
                        });
                        resolve();
                        if (getParameterByName("sid")) {
                            $.get("/api/status/solution?sid=" + getParameterByName("sid"), function (data) {
                                that.reloadtemplate(null, data.data.language)
                            })
                        }
                    }
                });
                <?php if(isset($_SESSION["administrator"])) { ?>
                window.problemsubmitter = problemsubmitter;
                <?php } ?>
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
        if (isMobile.any()) {
            location.href = "problem.php?<?=$_SERVER['QUERY_STRING']?>";
        }
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
    <div class="ui standard modal scrolling hidden center align" style="height:300px;background-color: #ffffff">
        <div class="header">测试运行</div>
        <div class="content">
            <div class="ui two column grid" style="height:70%;margin:auto;text-align: center">
                <div class="column">
                    <?php echo $MSG_Input ?>:<textarea style="height:100%;resize: none;border-radius:10px" cols=40
                                                       rows=5
                                                       id="input_text"
                                                       class="sample_input" v-model="sampleinput"></textarea>
                </div>
                <div class="column">
                    <?php echo $MSG_Output ?>:
                    <textarea style="height:100%;resize: none;border-radius:10px" cols=40 rows=5 id="out" name="out"
                              class="sample_output" :placeholder="'SHOULD BE:'+sampleoutput">
                        <?php echo $view_sample_output ?></textarea>
                    <textarea style="display:none" id="hidden_sample_output" class="sample_output">
SHOULD BE:
                        <?php echo $view_sample_output ?>
</textarea>
                </div>
                <br>
                <div style="margin: auto;text-align:right;padding-right:50px">
                    <br>
                    <div id="result" class="ui blue button right" style="text-align: right">状态</div>
                    &nbsp;
                </div>
            </div>
        </div>
    </div>
    <div>
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
                <div class="ui vertical center aligned segment">
                    <h2 class="ui header" id="probid" v-text="title">
                    </h2>
                    <div class='ui labels'>
                        <li class='ui label red' id="tlimit"
                            v-text="time"><?php echo "$MSG_Time_Limit:$row->time_limit" ?></li>
                        <li class='ui label red' id="mlimit"
                            v-text="memory"><?php echo "$MSG_Memory_Limit: $row->memory_limit" ?></li>
                        <li class='ui label orange' id="spj" v-cloak v-show="spj">Special Judge</li>
                        <li class='ui label grey' id="totsub"
                            v-text="submit"><?php echo "$MSG_SUBMIT: $row->submit" ?></li>
                        <li class='ui label green' id="totac"
                            v-text="accepted"><?php echo "$MSG_SOVLED:$row->accepted" ?> </li>
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
                           :href="'/problem_edit.php'+location.search" target="_blank">Edit</a>
                        <?php
                        if (isset($_SESSION['administrator'])) {
                            require_once("include/set_get_key.php");
                            ?>
                            <a class='ui button purple'
                               :href="'admin/quixplorer/index.php?action=list&dir='+problem_id+'&order=name&srt=yes'">TestData</a>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <h2 class='ui header hidden'><?= $MSG_Description ?></h2>
                <div class='ui hidden' v-html="description"></div>
                <h2 class='ui header hidden'><?= $MSG_Input ?></h2>
                <div class='ui hidden' v-html="input"></div>
                <h2 class='ui header hidden'><?= $MSG_Output ?></h2>
                <div class='ui hidden' v-html="output"></div>
                <h2 class='ui header hidden'><?= $MSG_Sample_Input ?></h2>
                <pre class='ui bottom attached segment hidden sample_input' v-text='sampleinput'><span
                            class=sampledata><?= ($sinput) ?></span></pre>
                <h2 class='ui header hidden'><?= $MSG_Sample_Output ?></h2>
                <pre class='ui bottom attached segment hidden sample_output' v-text='sampleoutput'><span
                            class=sampledata><?= ($soutput) ?></span></pre>
                <h2 class='ui header hidden'><?= $MSG_HINT ?></h2>
                <div class='ui hidden' v-html="hint"></div>
                <h2 class='ui header hidden'><?= $MSG_Source ?></h2>
                <div class='ui hidden'><p v-text="source"></p>
                </div>
            </div>
        </div>
        <div v-show="single_page === false"
             style="max-width:1300px;position:relative;margin:auto;height: 540px;border-radius: 10px"
             id="total_control">
            <div class="padding ui container mainwindow"
                 style="height:100%;width: 35%;overflow-y: auto;float:left;-webkit-border-radius: ;-moz-border-radius: ;border-radius: 10px;">
                <div class="ui vertical center aligned segment">
                    <h2 class="ui header" id="probid" v-text="title">
                    </h2>
                    <div class='ui labels'>
                        <li class='ui label red' id="tlimit"
                            v-text="time"><?php echo "$MSG_Time_Limit:$row->time_limit" ?></li>
                        <li class='ui label red' id="mlimit"
                            v-text="memory"><?php echo "$MSG_Memory_Limit: $row->memory_limit" ?></li>
                        <li class='not-compile' :class="'ui label orange'" id="spj" v-cloak v-show="spj">Special Judge
                        </li>
                        <li class='ui label grey' id="totsub"
                            v-text="submit"><?php echo "$MSG_SUBMIT: $row->submit" ?></li>
                        <li class='ui label green' id="totac"
                            v-text="accepted"><?php echo "$MSG_SOVLED:$row->accepted" ?> </li>
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
                           :href="'/problem_edit.php'+location.search" target="_blank">Edit</a>
                        <?php
                        if (isset($_SESSION['administrator'])) {
                            require_once("include/set_get_key.php");
                            ?>

                            <a class='ui button purple'
                               :href="'admin/quixplorer/index.php?action=list&dir='+problem_id+'&order=name&srt=yes'">TestData</a>
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
                    <div class='content' id='problem_input' v-html="input">
                    </div>
                    <div class='title'><?= $MSG_Output ?><i class="dropdown icon"></i></div>
                    <div class='content' id='problem_output' v-html="output"></div>
                    <div class='title'><?= $MSG_Sample_Input ?><i class="dropdown icon"></i></div>
                    <div class='content'>
                            <pre class='ui bottom attached segment'><span id='problem_sample_input' class='sample_input'
                                                                          v-text='sampleinput'><?= ($sinput) ?></span></pre>
                    </div>
                    <div class='title'><?= $MSG_Sample_Output ?><i class="dropdown icon"></i></div>
                    <div class='content'>
                            <pre class='ui bottom attached segment'><span id='problem_sample_output'
                                                                          class='sample_output'
                                                                          v-text='sampleoutput'><?= ($soutput) ?></span></pre>
                    </div>
                    <div class='title'><?= $MSG_HINT ?><i class="dropdown icon"></i></div>
                    <div class='content' v-html="hint">
                    </div>
                    <?php
                    if ($pr_flag) {
                        ?>
                        <div class='title'><?= $MSG_Source ?><i class="dropdown icon"></i></div>
                        <div class='content'><p><a :href='"problemset.php?search="+source' id='problem_source'
                                                   v-text='source'></a></p></div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div style="width:65%;position:relative;float:left; border-radius: " id="right-side">
                <script src="template/semantic-ui/js/editor_config.js"></script>
                <textarea style="display:none" cols=40 rows=5 name="input_text"
                          id="ipt" class="sample_input"><?php echo $view_sample_input ?></textarea>
                <div id="modeBar" style="margin: 0;
        padding: 0;
        position: relative;
        height: 35px;
        color: black;width:100%;text-align:right" class="ui menu borderless">
                    <div class="item not-compile">
                        <select class="not-compile" v-cloak :class="'ui dropdown selection'" id="language"
                                name="language"
                                @change="reloadtemplate($event)" v-model="selected_language">
                            <option v-for="language in lang_list" :value="language.num">{{language.name}}</option>
                        </select>
                        <a
                                :class="'item'" class="not-compile" v-cloak id="clipbtn" data-clipboard-action="copy"
                                style="float:left;" v-if="prepend||append">复制代码</a>
                    </div>
                    <div class="right menu">
                        <div class="item">
                        </div>
                        <div class="item"><span class="item">字号:</span>
                            <div class="ui input"><input type="text" value=""
                                                         style="width:60px;text-align:center;height:30px"
                                                         id="fontsize" @keyup="resize($event)"></div>
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
                <div v-if="prepend" class="code"
                     style="width: 100%;padding:0px;line-height:1.2;text-align:left;margin-bottom:0px;"
                     :ace-mode="'ace/mode/'+language_template[selected_language]"
                     ace-theme="ace/theme/monokai"
                     id="prepend" v-text="prepend[selected_language]">
                </div>
                <div style="width:100%;height:460px" :style="{width:'100%',height:'460px',fontSize:fontSize+'px'}"
                     cols=180 rows=20
                     id="source"></div>
                <div v-if="append" id="append" class="code"
                     style="width: 100%; padding:0px; line-height:1.2;text-align:left;margin-bottom:0px;"
                     :ace-mode="'ace/mode/'+language_template[selected_language]"
                     ace-theme="ace/theme/monokai" v-text="append[selected_language]">
                </div>
                <div id="statusBar" style="margin: 0;
        padding: 0;
        position: relative;
        height: 30px;
        color: black;width:100%" class="ui menu borderless">
                    <div style="text-align:center;" class="item"><?php echo $OJ_NAME ?>&nbsp;&nbsp;</div>
                    <div class="ui right menu">
                        <div class="ui buttons">
                            <input id="Submit" class="ui button green " :disabled="submitDisabled" type=button
                                   value="<?php echo $MSG_SUBMIT ?>"
                                   @click="do_submit">
                            <div class="or"></div>
                            <input id="TestRun" class="ui button blue" @click="test_run" :disabled="submitDisabled"
                                   type=button value="<?php echo $MSG_TR ?>"
                            >&nbsp;<!--<span class="btn" id=result>状态</span>-->
                        </div>
                    </div>
                </div>
                <div class="ui teal progress" data-value="0" data-total="3" id="progress" style="display:none">
                    <div class="bar">
                        <div class="progress"></div>
                    </div>
                    <div class="label" id="progess_text"></div>
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
                    var judge_result = [<?php
                        foreach ($judge_result as $result) {
                            echo "'$result',";
                        }
                        ?>''];
                    var judge_style = [<?php
                        foreach ($judge_style as $style) {
                            echo "'$style',";
                        }
                        ?>''];


                    function print_result(solution_id) {
                        sid = solution_id;
                        $("#out").load("status-ajax.php?tr=1&solution_id=" + solution_id);
                    }


                    function fresh_result(solution_id) {
                        sid = solution_id;
                        var xmlhttp;
                        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                            xmlhttp = new XMLHttpRequest();
                        }
                        else {// code for IE6, IE5
                            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        xmlhttp.onreadystatechange = function () {
                            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                var tb = window.document.getElementById('result');
                                var r = xmlhttp.responseText;
                                var ra = r.split(",");
                                var loader = "<img width=18 src=image/loader.gif>";
                                var tag = "span";
                                if (ra[0] < 4) tag = "span disabled=true";
                                else tag = "a";
                                {
                                    if (ra[0] == 11) {
                                        tb.innerHTML = "<" + tag + " href='ceinfo.php?sid=" + solution_id + "' class='badge badge-info' target=_blank>" + judge_result[ra[0]] + "</" + tag + ">";
                                        tb.className = "ui button " + judge_color[ra[0]];
                                    }
                                    else {
                                        tb.innerHTML = "<" + tag + " href='reinfo.php?sid=" + solution_id + "' class='badge badge-info' target=_blank>" + judge_result[ra[0]] + "</" + tag + ">";
                                        tb.className = "ui button " + judge_color[ra[0]];
                                    }
                                }
                                if (ra[0] < 4) tb.innerHTML += loader;
                                tb.innerHTML += "Memory:" + ra[1] + "kb&nbsp;&nbsp;";
                                tb.innerHTML += "Time:" + ra[2] + "ms";
                                if (ra[0] < 4)
                                    window.setTimeout("fresh_result(" + solution_id + ")", 2000);
                                else {
                                    window.setTimeout("print_result(" + solution_id + ")", 2000);
                                    count = 1;
                                }
                            }
                        }
                        xmlhttp.open("GET", "status-ajax.php?solution_id=" + solution_id, true);
                        xmlhttp.send();
                    }

                    var count = 0;


                    function frush_result(runner_id) {
                        $.get("status-ajax.php?solution_id=" + runner_id, function (data) {
                            if (data == "<!-csrf check failed->") {
                                $("#progess_text").text("未通过安全检测，请刷新页面");
                                // setTimeout("frush_result(" + runner_id + ")", 250);
                                $('#progress').progress({
                                    percent: 100
                                });
                                return;
                            }
                            var res = data.split(',');
                            var status = parseInt(res[0]);

                            if (status == 0) {
                                $("#progess_text").text(judge_result[status]);
                                setTimeout("frush_result(" + runner_id + ")", 250);
                                $('#progress').progress({
                                    percent: 20
                                });
                            }
                            else if (status == 2) {
                                $("#progess_text").text(judge_result[status]);
                                setTimeout("frush_result(" + runner_id + ")", 250);
                                $('#progress').progress({
                                    percent: 40
                                });
                            }
                            else if (status == 3) {
                                $("#progess_text").text(judge_result[status] + " 已通过测试点:" + res[3]);
                                setTimeout("frush_result(" + runner_id + ")", 250);
                                $('#progress').progress({
                                    percent: 40
                                });
                            }
                            else if (status == 4) {
                                count = 0;
                                $("#progess_text").text(judge_result[status] + " 内存使用:" + res[1] + "KB 运行时间:" + res[2] + "ms");
                                $('#progress').progress({
                                    percent: 100
                                });
                                $("#progress").progress('set success');
                                <?php if(isset($cid) && $cid > 1000){ ?>
                                $.get("contest_problem_ajax.php?cid=<?=$cid?>", function (data) {
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
                                    $(".ui.massive.vertical.menu").html(str).fadeIn();
                                    // console.log(json);
                                })
                                <?php } ?>
                            }
                            else if (status == 5 || status == 6) {
                                count = 0;
                                $("#progess_text").text("在第" + (parseInt(res[3]) + 1) + "个测试点发生 " + judge_result[status]);
                                $('#progress').progress({
                                    percent: 100
                                });
                                $("#progress").progress('set error');
                            }
                            else {
                                count = 0;
                                $("#progess_text").text("在第" + (parseInt(res[3]) + 1) + "个测试点发生 " + judge_result[status]);
                                $('#progress').progress({
                                    percent: 100
                                });
                                $("#progress").progress('set warning');
                            }
                        })
                    }

                    console.timeEnd("function");
                </script>
                <div id="clipcp" style="display:none"></div>
            </div>
            <div id="next_problem">
                <div class="ui massive vertical menu" style="position:relative;float:left; display:none"></div>
            </div>
        </div>
    </div>
</div>
<?php include("template/$OJ_TEMPLATE/bottom.php"); ?>
<!-- /container -->
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

</body>
</html>
