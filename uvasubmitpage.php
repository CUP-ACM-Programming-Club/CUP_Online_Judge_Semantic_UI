<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $OJ_NAME ?></title>
    <?php include("csrf.php"); ?>
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <![endif]-->
    <script>
        window.lastlang = parseInt("<?=$lastlang?>")||1;
    </script>
    <script>
        $.get("https://uhunt.onlinejudge.org/api/p/num/" + getParameterByName("pid"), function (result) {
            //  console.log(result);
            $spj = $("#spj");
            if (result['status'] == 2) {
                $spj.show();
            }
            else if (result['status'] == 0) {
                $spj.html("题目不可用");
                $spj.show();
            }
        });
        $.get("/api/problem/UVA/" + getParameterByName("pid"), function (data) {
            var result = data.problem;
            //result=JSON.parse(result);
            $title = $("#ptitle");
            $tle = $("#tlm");
            $spj = $("#spj");
            $smt = $("#smt");
            $acp = $("#acp");
            $tle.html($tle.html() + result['time_limit'] / 1000 + "&nbsp;s");
            $title.html($title.html() + result['title']);
            $smt.html($smt.html() + result['submit']);
            $acp.html($acp.html() + result['accepted']);
        });
        function flush_theme() {
            var highlight = ace.require("ace/ext/static_highlight")
            var dom = ace.require("ace/lib/dom")
            qsa(".code").forEach(function (codeEl) {
                highlight(codeEl, {
                    mode: codeEl.getAttribute("ace-mode"),
                    theme: codeEl.getAttribute("ace-theme"),
                    startLineNumber: 1,
                    showGutter: codeEl.getAttribute("ace-gutter"),
                    trim: true
                }, function (highlighted) {

                });
            });
        }
    </script>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
<div class="pusher padding">
    <div class="ui modal" style="height:350px;background-color: #ffffff">
        <div style="margin: auto;text-align: center;font-size: 18px;padding-top: 20px">Test Data</div>
        <br>
        <div style="height:70%;margin:auto;text-align: center">
            <?php echo $MSG_Input ?>:<textarea style="width:40%;height:100%;resize: none;border-radius:10px" cols=40
                                               rows=5 id="input_text"><?php echo $view_sample_input ?></textarea>
            <?php echo $MSG_Output ?>:
            <textarea style="width:40%;height:100%;resize: none;border-radius:10px" cols=10 rows=5 id="out" name="out">SHOULD BE:
                <?php echo $view_sample_output ?></textarea>
            <textarea style="display:none" id="hidden_sample_output">
SHOULD BE:
                <?php echo $view_sample_output ?>
</textarea>
            <br>
            <div style="margin: auto;text-align:right;padding-right:50px">
                <br>
                <div id="result" class="btn btn-info right" style="text-align: right">状态</div>
                &nbsp;
            </div>
        </div>
    </div>
    <div class="ui basic modal confirms hidden">
        <div class="ui icon header">
            <i class="archive icon"></i>提示
        </div>
        <div class="content" id="confirm_box">
        </div>
        <div class="actions">
            <div class="ui red basic cancel inverted button">
                <i class="remove icon"></i>
                返回
            </div>
            <!--<div class="ui green ok inverted button">
                <i class="checkmark icon"></i>
                确认提交
            </div>-->
        </div>
    </div>
    <div class="padding ui container" <?php if (isset($_GET['sid'])){ ?>style="display:none"<?php } ?>>
        <center>
            <h2 class="ui header" id="ptitle"><?php if($pr_flag)echo "UVA ".$pid;else echo $PID[$cpid] ?>&nbsp;&nbsp;</h2>
            <div class="ui labels">
                <li class="ui label red" id="tlm">时限:</li>
                <li class="ui label orange" style="display:none" id="spj">Special Judge</li>
                <li class="ui label grey" id="smt">提交:</li>
                <li class="ui label green" id="acp">通过:</li>
            </div>
            <a href='vjudgeproblemstatus.php?pid=<?= $pid ?>&oj=<?= $oj_signal ?>'
               class='ui button orange'><?= $MSG_STATUS ?></a>
               <a href="https://www.udebug.com/UVa/<?=$pid?>" target="_blank" class="ui button blue">uDebug</a>
            <button class="ui green button" id="button-submit">提交代码</button>
            <?php if($_SESSION["editor"] || $_SESSION["administrator"]){ ?>
            <a class="ui button violet" href="problem_edit.php?id=<?=$pid?>&from=uva">Edit</a>
            <?php } ?>
        </center>
        <h3 class="ui top attached block header">PDF题面</h3>
        <div class="ui attached buttom segment">
            <a href="https://uva.onlinejudge.org/external/<?= intval($pid / 100) ?>/p<?= $pid ?>.pdf" target="_blank"
               download="<?= $pid ?>">若题面无法显示点此下载</a>
            <embed width="100%" height="700" name="plugin" id="plugin"
                   src="https://uva.onlinejudge.org/external/<?= intval($pid / 100) ?>/p<?= $pid ?>.pdf"
                   type="application/pdf" internalinstanceid="4">
        </div>
    </div>
    <div>
        <script src="ace-builds/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
        <script src="ace-builds/src-min-noconflict/ext-language_tools.js" type="text/javascript"
                charset="utf-8"></script>

        <script src="ace-builds/src-min-noconflict/ext-error_marker.js" type="text/javascript" charset="utf-8"></script>
        <script src="ace-builds/src-min-noconflict/ext-statusbar.js" type="text/javascript" charset="utf-8"></script>
        <script src="ace-builds/src-min-noconflict/ext-emmet.js" type="text/javascript" charset="utf-8"></script>

        <script src="template/<?php echo $OJ_TEMPLATE ?>/js/cookie.js"></script>
        <script src="ace-builds/src-min-noconflict/ext-static_highlight.js"></script>
        <style type="text/css">
            .code {
                width: 50%;
                white-space: pre-wrap;
                border: solid lightgrey 1px
            }
        </style>
        <!-- Main component for a primary marketing message or call to action -->
        <div
            style="max-width:1300px;position:relative;margin:auto;height: 560px;border-radius: 10px;<?php if (!isset($_GET['sid'])) { ?>display:none<?php } ?>"
            id="total_control" class="ui container">
            <div style="width:100%;position:relative;float:left; border-radius: " id="right-side">
                <script src="template/<?php echo $OJ_TEMPLATE; ?>/js/editor_config.js?ver=1.0"></script>
                <script src="include/checksource.js"></script>
                
                <div id="frmSolution">
                    <?php if (!isset($_GET['cid'])) { ?>
                        <input id=problem_id type='hidden' value='<?php echo $pid ?>' name="pid">
                    <?php } else {
                        ?>
                        <input id="cid" type='hidden' value='<?php echo $cid ?>' name="cid">
                        <input id="tid" type='hidden' value='<?php echo $tid ?>' name="tid">
                        <input id="pid" type='hidden' value='<?php echo $cpid ?>' name="pid">
                    <?php } ?>
                    <textarea style="display:none" cols=40 rows=5
                              name="input_text"><?php echo $view_sample_input ?></textarea>
                    <div id="modeBar" style="margin: 0;
        padding: 0;
        position: relative;
        height: 35px;
        color: black;width:100%;text-align:right" class="ui menu borderless">
                        <div class="item">
                            <select class="ui dropdown selection" id="language" name="language"
                                    onChange="reloadtemplate(this);">
                                <option value="1" selected>ANSI C 5.3.0</option>
                                <option value="2">JAVA OpenJDK 1.8.0</option>
                                <option value="3">C++ 5.3.0 GNU C++</option>
                                <option value="4">Pascal</option>
                                <option value="5">C++11 5.3.0 GNU C++</option>
                                <option value="6">Python 3</option>
                            </select>
                        </div>
                        <button class="ui green button" id="backproblem">返回题面</button>
                        <div class="right menu">
                            <div class="item">
                            </div>
                            
                            <div class="item"><span class="item">字号:</span>
                                <div class="ui input"><input type="text" value=""
                                                             style="width:60px;text-align:center;height:30px"
                                                             id="fontsize" onkeyup="resize(this)"></div>
                            </div>
                            <div class="item">
                                <span>主题:</span><select class="ui selection dropdown" id="theme" size="1">

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
                    <div style="width:100%;height:480px" cols=180 rows=20
                         id="source"><?php echo htmlentities($view_src, ENT_QUOTES, "UTF-8") ?></div>
                    <textarea id="hide_source" style="display:none" name="code"></textarea>
                    <div id="statusBar" style="margin: 0;
        padding: 0;
        position: relative;
        height: 30px;
        color: black;width:100%" class="ui menu borderless">
                        <div style="text-align:center;" class="item"><?php echo $OJ_NAME ?>&nbsp;&nbsp;</div>
                        <div class="ui right menu">
                            <input id="Submit" class="ui green button" type=button value="<?php echo $MSG_SUBMIT ?>"
                                   onclick="do_submit();">&nbsp;
                        </div>
                    </div>
                    <br>
                    <div class="ui teal progress" data-value="0" data-total="3" id="progress" style="display:none">
                        <div class="bar">
                            <div class="progress"></div>
                        </div>
                        <div class="label" id="progess_text"></div>
                    </div>
                </div>
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
// alert(r);
// alert(judge_result[r]);
                                var loader = "<img width=18 src=image/loader.gif>";
                                var tag = "span";
                                if (ra[0] < 4) tag = "span disabled=true";
                                else tag = "a";
                                {
                                    if (ra[0] == 11) {
                                        tb.innerHTML = "<" + tag + " href='ceinfo.php?sid=" + solution_id + "' class='badge badge-info' target=_blank>" + judge_result[ra[0]] + "</" + tag + ">";
                                        tb.className = "btn " + judge_style[ra[0]];
                                    }
                                    else {
                                        tb.innerHTML = "<" + tag + " href='reinfo.php?sid=" + solution_id + "' class='badge badge-info' target=_blank>" + judge_result[ra[0]] + "</" + tag + ">";
                                        tb.className = "btn " + judge_style[ra[0]];
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
                    function getSID() {
                        var ofrm1 = document.getElementById("testRun").document;
                        var ret = "0";
                        if (ofrm1 == undefined) {
                            ofrm1 = document.getElementById("testRun").contentWindow.document;
                            var ff = ofrm1;
                            ret = ff.innerHTML;
                        }
                        else {
                            var ie = document.frames["frame1"].document;
                            ret = ie.innerText;
                        }
                        return ret + "";
                    }
                    var count = 0;
                    function frush_result(runner_id) {
                        $.get("status-hdu-ajax.php?sid=" + runner_id, function (data) {
                            var res = data.split(',');
                            var status = parseInt(res[0]);

                            if (status == 0) {
                                $("#progess_text").text(judge_result[status]);
                                setTimeout("frush_result(" + runner_id + ")", 1000);
                                $('#progress').progress({
                                    percent: 20
                                });
                            }
                            else if (status == 14) {
                                $("#progess_text").text(judge_result[status]);
                                setTimeout("frush_result(" + runner_id + ")", 1000);
                                $('#progress').progress({
                                    percent: 40
                                });
                            }
                            else if (status == 2) {
                                $("#progess_text").text(judge_result[status]);
                                setTimeout("frush_result(" + runner_id + ")", 1000);
                                $('#progress').progress({
                                    percent: 60
                                });
                            }
                            else if (status == 3) {
                                $("#progess_text").text(judge_result[status]);
                                setTimeout("frush_result(" + runner_id + ")", 1000);
                                $('#progress').progress({
                                    percent: 60
                                });
                            }
                            else if (status == 4) {
                                $("#progess_text").text(judge_result[status] + " 内存使用:" + res[1] + "KB 运行时间:" + res[2] + "ms");
                                $('#progress').progress({
                                    percent: 100
                                });
                                $("#progress").progress('set success');
                                <?php if(isset($cid) && $cid > 1000){ ?>
                                $.get("contest_problem_ajax.php?cid=<?=$cid?>", function (data) {
                                    var json = JSON.parse(data);
                                    setTimeout(function () {
                                        $(".jumbotron").html("").animate({width: 0, borderRadius: 0, padding: 0});
                                    }, 500);
                                    var str = "<a class='item'><h3>剩下未完成的题目</h3></a>";
                                    if (json.length == 0) {
                                        str += "<a class='item'><h2>恭喜AK</h2>";
                                    }
                                    else {
                                        console.log(typeof json);
                                        json.sort(function (a, b) {
                                            if (a['num'] > b['num'])return 1;
                                            else if (a['num'] == b['num'])return 0;
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
                                $("#progess_text").text(judge_result[status]);
                                $('#progress').progress({
                                    percent: 100
                                });
                                $("#progress").progress('set error');
                            }
                            else {
                                $("#progess_text").text(judge_result[status]);
                                $('#progress').progress({
                                    percent: 100
                                });
                                $("#progress").progress('set warning');
                            }
                        })
                    }

                    function ajax_post() {
                        $(".ui.teal.progress").show();
                        $("#progess_text").text("提交");
                        $('#progress').progress({
                            percent: 0
                        });
                        $("#progress").progress('set active');
                        var postdata = {
                            id: $("#problem_id").val(),
                            cid: $("#cid").val(),
                            tid: $("#tid").val(),
                            pid: $("#pid").val(),
                            input_text: "",
                            language: $("#language").val(),
                            source: $("#hide_source").val(),
                            oj_name: "<?=$oj_signal?>",
                            csrf: "<?=$token?>"
                        };
                        console.log(postdata);
                        $.post("submit_hdu.php", {
                            json: JSON.stringify(postdata),
                            csrf: "<?=$token?>"
                        }, function (data) {
                            var running_id = parseInt(data);
                            frush_result(running_id);
                        });
                    }
                    function do_submit() {
                        if(window.timeout)
                        {
                            alert("提交过于频繁");
                        }
                        if (editor.getValue().length < 7) {
                            $('.ui.basic.confirms.modal')
                                .modal({
                                    offset: 400,
                                    onShow: function () {
                                        $("#confirm_box").html("<h2><center>代码过短</center></h2>");
                                    },onApprove: function () {
                                        
                                    }
                                })
                                .modal('show');
                                return;
                        }
//if(typeof(eAL) != "undefined"){ eAL.toggle("source");eAL.toggle("source");}
//var editor=ace.edit("source");
                        document.getElementById("hide_source").value = editor.getValue();
//alert(editor.getValue());
//alert(document.getElementById("hide_source"));
                        //document.getElementById("frmSolution").target = "_self";
                        var lang = document.getElementById("language").value;
                        //document.getElementById("frmSolution").submit();
                        ajax_post();
                        window.timeout=true;
                        setTimeout(function(){window.timeout=false},5000);
                    }
                    var handler_interval;
                    function do_test_run() {
                        if(window.timeout)
                        {
                            alert("提交过于频繁");
                        }
                        window.timeout=true;
                        setTimeout(function(){window.timeout=false},5000);
                        document.getElementById("out").innerHTML = document.getElementById("hidden_sample_output").innerHTML;
                        $('.ui.modal')
                            .modal('show')
                        ;
                        if (handler_interval) window.clearInterval(handler_interval);
                        var loader = "<img width=18 src=image/loader.gif>";
                        var tb = window.document.getElementById('result');
//if(typeof(eAL) != "undefined"){ eAL.toggle("source");eAL.toggle("source");}
//console.log(editor);
                        document.getElementById("hide_source").value = editor.getValue();
//console.log(editor.getValue().length);
                        if (editor.getValue().length < 10) return alert("too short!");
                        tb.innerHTML = loader;

                        var mark = "problem_id";
                        var problem_id = document.getElementById(mark);
                        problem_id.value = -problem_id.value;
                        document.getElementById("frmSolution").target = "testRun";
//console.log($("#frmSolution").serialize());
//document.getElementById("frmSolution").submit();
                        $.post("submit.php?ajax", $("#frmSolution").serialize(), function (data) {
                            <?php if(isset($_SESSION['administrator'])){ ?>
                            console.log("测试运行 发送信息:");
                            console.log($("#frmSolution").serialize());
                            <?php } ?>
                            fresh_result(data);
                        });
                        document.getElementById("TestRun").disabled = true;
                        document.getElementById("Submit").disabled = true;
                        problem_id.value = -problem_id.value;
                        count = 20;
                        handler_interval = window.setTimeout("resume();", 1000);
                    }
                    function resume() {
                        --count;
                        var s = document.getElementById('Submit');
                        var t = document.getElementById('TestRun');
                        if (count < 0) {
                            s.disabled = false;
                            t.disabled = false;
                            s.value = "<?php echo $MSG_SUBMIT?>";
                            t.value = "<?php echo $MSG_TR?>";
                            if (handler_interval) window.clearInterval(handler_interval);
                        } else {
                            s.value = "<?php echo $MSG_SUBMIT?>(" + count + ")";
                            t.value = "<?php echo $MSG_TR?>(" + count + ")";
                            window.setTimeout("resume();", 1000);
                        }
                    }
                    function reloadtemplate(lang) {
                        //const lang=Array("C","C++","Pascal","Java","Ruby","Bash","Python","PHP","Perl","C#","Obj-C","FreeBasic","Schema","Clang","Clang++","Lua","JavaScript","Go","Other Language");
                        var langn = lang.value;
                        editor.getSession().setMode("ace/mode/" + uva_language[langn]);
                        var highlight = ace.require("ace/ext/static_highlight")
                        var dom = ace.require("ace/lib/dom")
                        qsa(".code").forEach(function (codeEl) {
                            codeEl.innerHTML = "";
                            codeEl.setAttribute("ace-mode", "ace/mode/" + uva_language[langn])
                        });
                    }
                </script>
                <script>
                    ace.require("ace/ext/language_tools");
        var editor = ace.edit("source");
        var StatusBar = ace.require("ace/ext/statusbar").StatusBar;
        var statusBar = new StatusBar(editor, document.getElementById("statusBar"));
        editor.$blockScrolling = Infinity;
        if (Cookies.get('theme')) {
            editor.setTheme(Cookies.get('theme'));
            qsa(".code").forEach(function (codeEl) {
                codeEl.setAttribute("ace-theme", Cookies.get('theme'));
            });
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
        editor.getSession().setMode("ace/mode/"+uva_language[window.lastlang]);
        console.log(uva_language[window.lastlang]);
        if (Cookies.get('font-size')) {
            document.getElementById('source').style.fontSize = Cookies.get('font-size') + 'px';
            document.getElementById('fontsize').value = Cookies.get('font-size');
            console.log("从Cookie中获取字体大小成功");
        }
        else {
            document.getElementById('source').style.fontSize = '18px';
            console.log("未设置字体大小，使用默认字体大小");
        }
        var theme_n;
        if (Cookies.get('theme')) {
            theme_n = Cookies.get('theme');
        }
        else {
            theme_n = "ace/theme/monokai";
        }
        var arr = document.getElementsByTagName("option");
        var len = arr.length;
        for (var i = 0; i < len; i++) {
            if (arr[i].value == theme_n) {
                arr[i].selected = true;
                break;
            }
        }
                </script>
                <script>
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
                        Cookies.set('theme', this.value, {expires: 60});
                        console.log("设置主题成功！主题为:" + this.value);
                        Cookies.set('theme-name', $(this).find("option:selected").text(), {expires: 60});
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

                    function resize(obj) {
                        var size = obj.value;
                        if (!isNaN(size)) {
                            Cookies.set('font-size', size, {expires: 60});
                            document.getElementById('source').style.fontSize = size + "px";
                        }
                    }
                    function qsa(sel) {
                        return Array.apply(null, document.querySelectorAll(sel));
                    }
                </script>
                <script>
                    <?php if($OJ_APPENDCODE && $append_flag){ ?>
                    var obj = document.getElementById('clipbtn');
                    var clipboard = new Clipboard(obj, {
                        text: function (trigger) {
                            var mergetext = document.getElementById('prepend_hide').innerText;
                            mergetext += "\n/*请在下方编写你的代码*/\n";
                            if (editor.getValue().length !== 0) {
                                mergetext += editor.getValue();
                            }
                            else {
                                mergetext += "\n\n\n\n";
                            }
                            mergetext += "\n/*请在上方填写你的代码*/\n";
                            mergetext += document.getElementById('append_hide').innerText;
                            document.getElementById('clipcp').innerHTML = mergetext;
                            return mergetext;
                        },
                        target: function (trigger) {
                            return document.getElementById('clipcp');
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
                    <?php } ?>
                    console.timeEnd("function");
                    $("#button-submit").on("click", function (e) {
                        $(".padding.ui.container").toggle();
                        $("#total_control").toggle();
                    });
                    $("#backproblem").on("click", function (e) {
                        $(".padding.ui.container").toggle();
                        $("#total_control").toggle();
                    });
                    $("#language")[0].value=window.lastlang;
                </script>

                <div id="prepend_hide"
                     style="display:none"><?php if (isset($OJ_APPENDCODE) && $OJ_APPENDCODE && file_exists($prepend_file)) {
                        echo htmlentities(file_get_contents($prepend_file));

                    } ?></div>
                <div id="append_hide"
                     style="display:none"><?php if (isset($OJ_APPENDCODE) && $OJ_APPENDCODE && file_exists($append_file)) {
                        echo htmlentities(file_get_contents($append_file));

                    } ?></div>
                <div id="clipcp" style="display:none"></div>
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
