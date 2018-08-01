<!DOCTYPE html>
<html lang="en">
    <?php
    
    $jsk_lang=[];
    $jsk_lang["c"]=0;
    $jsk_lang["c++"]=1;
    $jsk_lang["c++14"]=2;
    $jsk_lang["java"]=3;
    $jsk_lang["python"]=4;
    $jsk_lang["python3"]=5;
    $jsk_lang["ruby"]=6;
    $jsk_lang["blockly"]=7;
    $jsk_lang["octave"]=8;
    ?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $OJ_NAME ?></title>
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <?php include("template/$OJ_TEMPLATE/extra_css.php") ?>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <?php include("template/$OJ_TEMPLATE/extra_js.php") ?>
    <?php include("csrf.php"); ?>
    <script>
        window.lastlang = "<?=$code_lang?>"||0;
        window.lastlang = parseInt(window.lastlang);
        window.oj_signal="<?=$oj_signal?>";
    </script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <![endif]-->
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
<div>
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
        
        <div style="max-width:1300px;position:relative;margin:auto;height: 560px;border-radius: 10px"
             id="total_control" class="background">
            
            <div class="padding ui container" id="left-side"
                 style="height:100%;width: 35%;overflow-y: auto;float:left;-webkit-border-radius: ;-moz-border-radius: ;border-radius: 10px;">
                <title><?= $MSG_PROBLEM ?>&nbsp;<?= $problem_row->problem_id ?>. -- <?= $problem_row->title ?></title>
                <center>
                    <?php if ($pr_flag) { ?>
                        <h2><?= $problem_row->source ?> <?= $pid ?>: <?= $problem_row->title ?></h2>
                    <?php } else {
                        $PID = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                        echo "<h2>$MSG_PROBLEM $PID[$cpid]: $problem_row->title</h2>";
                    } ?>

                    <div class="ui labels">
                        <li class="ui label red"><?= $MSG_Time_Limit ?>:<?= $problem_row->time_limit ?> 秒</li>
                        <li class='ui label red'><?= $MSG_Memory_Limit ?>: <?= $problem_row->memory_limit ?> MB</li>
                        <li class='ui label yellow'><?= $MSG_SUBMIT ?>: <?= $problem_row->submit ?></li>
                        <li class='ui label green'><?= $MSG_Accepted ?>: <?= $problem_row->accepted ?></li>
                    </div>
                    <br>
                    <div class="ui buttons">
                        <a href='vjudgeproblemstatus.php?pid=<?= $problem_row->problem_id ?>&oj=<?= $oj_signal ?>'
                           class='ui button orange'><?= $MSG_STATUS ?></a>&nbsp;
                           <a href='<?=strtolower($oj_signal)?>problem.php?<?=$_SERVER['QUERY_STRING']?>' class="ui button blue">单屏显示</a>
                        <?php
                        //echo "[<a href='bbs.php?pid=".$problem_row->problem_id."$ucid'>$MSG_BBS</a>]";
                        if (isset($_SESSION['administrator'])) {
                            require_once("include/set_get_key.php");
                            ?>
                            <a class='ui button violet'
                               href="admin/problem_edit.php?id=<?php echo $id ?>&getkey=<?php echo $_SESSION['getkey'] ?>">Edit</a>
                            <a class='ui button purple'
                               href="admin/quixplorer/index.php?action=list&dir=<?php echo $problem_row->problem_id ?>&order=name&srt=yes">TestData</a>
                            <?php
                        }
                        echo "</div>";
                        echo "</center>";
                        ?>
                        
                        <?php
                        echo "<br>";
                        echo "<div class='ui styled accordion'>";
                        echo "<div class='title'>$MSG_Description";
                        ?>
                        
                        <?php
                        echo "<i class=\"dropdown icon\"></i></div><div class='content'>";
                        ?>
                        <div>
                        <?php
                        echo $problem_row->description . "</div></div>";
                        echo "<div class='title'>$MSG_Input<i class=\"dropdown icon\"></i></div><div class='content'><div>" . $problem_row->input . "</div></div>";
                        echo "<div class='title'>$MSG_Output<i class=\"dropdown icon\"></i></div><div class='content'><div>" . $problem_row->output . "</div></div>";
                       // $sinput = str_replace("<", "&lt;", $problem_row->sample_input);
                       // $sinput = str_replace(">", "&gt;", $sinput);
                       // $soutput = str_replace("<", "&lt;", $problem_row->sample_output);
                      //  $soutput = str_replace(">", "&gt;", $soutput);
                        $sinput=$problem_row->sample_input;
                        $soutput=$problem_row->sample_output;
                        if (strlen($sinput)) {
                            echo "<div class='title'>$MSG_Sample_Input<i class=\"dropdown icon\"></i></div><div class='content'>
<pre class='ui bottom attached segment'><span class=sampledata>" . ($sinput) . "</span></pre></div>";
                        }
                        if (strlen($soutput)) {
                            echo "<div class='title'>$MSG_Sample_Output<i class=\"dropdown icon\"></i></div><div class='content'>
<pre class='ui bottom attached segment'><span class=sampledata>" . ($soutput) . "</span></pre></div>";
                        }
                        ?>
                    </div>
            </div>
            <div style="width:65%;position:relative;float:left; border-radius: " id="right-side">
                <script src="template/<?php echo $OJ_TEMPLATE; ?>/js/editor_config.js?v=1.0"></script>
                <script>
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
                <script src="include/checksource.js"></script>
                <script>
                    if(window.oj_signal=="HDU")
                    {
                        language=["c_cpp","","c_cpp","c_cpp","","java"];
                    }
                    else{
                        language=["c_cpp","c_cpp","java","pascal","c_cpp","c_cpp","fortran"];
                    }
                </script>
                <?php
                $lang_count = count($language_ext);
                if (!(isset($langmask) && $langmask)) {
                    if (isset($_GET['langmask']))
                        $langmask = $_GET['langmask'];
                    else
                        $langmask = $OJ_LANGMASK;
                }
                if (isset($_COOKIE['lastlang'])) $lastlang = $_COOKIE['lastlang'];
                else $lastlang = 0;
                $lang = (~((int)$langmask)) & ((1 << ($lang_count)) - 1);
                $append_flag = 0;
                $append_first_lang = -1;
                ?>
                <form id=frmSolution action="submit_hdu.php" method="post">
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
                                <?php if ($oj_signal == "HDU") { ?>
                                    <option value="0" selected>G++</option>
                                    <option value="2">C++</option>
                                    <option value="3">GCC</option>
                                    <option value="5">JAVA</option>
                                <?php } else if ($oj_signal == "POJ") { ?>
                                    <option value=0 selected>G++</option>
                                    <option value=1>GCC</option>
                                    <option value=2>Java</option>
                                    <option value=3>Pascal</option>
                                    <option value=4>C++</option>
                                    <option value=5>C</option>
                                    <option value=6>Fortran</option>
                                    <?php } else if($oj_signal == "VIJOS" || $oj_signal == "BZOJ"){ ?>
                                    <option value=0 selected>C</option>
                                    <option value=1>C++</option>
                                    <option value=2>C#</option>
                                    <option value=3>Pascal</option>
                                    <option value=4>Java</option>
                                    <option value=5>Python 2</option>
                                    <option value=6>Python 3</option>
                                    <option value=7>PHP</option>
                                    <option value=8>Rust</option>
                                    <option value=9>Haskell</option>
                                    <option value=10>JavaScript</option>
                                    <option value=11>Go</option>
                                    <option value=12>Ruby</option>
                                <?php }else{
                                    $language_list=json_decode($database->select("vjudge_accept_language","accept_language",["source"=>"JSK","problem_id"=>$pid])[0]);
                                    foreach($language_list as $key=>$value){
                                ?>
                                <option value="<?=$jsk_lang[$key]?>"><?=strtoupper($key)?></option>
                                <?php } } ?>
                            </select><?php
                            if ($OJ_APPENDCODE && $append_flag) { ?><a
                                href="javascript:void(0)" class="item" id="clipbtn" data-clipboard-action="copy"
                                data-clipboard-target="#clipcp" style="float:left;">复制代码</a><?php } ?>
                        </div>
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
                            <input id="Submit" class="ui button green" type=button value="<?php echo $MSG_SUBMIT ?>"
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
                </form>
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
                    var count = 0;
                    function frush_result(runner_id) {
                        var send=false;
                        $.get("status-hdu-ajax.php?sid=" + runner_id, function (data) {
                            var res = data.split(',');
                            var status = parseInt(res[0]);
                            var rid = parseInt(res[3]);
                            if (status == 0) {
                                var add_text="";
                                if(send){
                                    add_text+="远程服务器判题";
                                }
                                $("#progess_text").text(judge_result[status]+add_text);
                                setTimeout("frush_result(" + runner_id + ")", 1000);
                                $('#progress').progress({
                                    percent: 20
                                });
                            }
                            else if (status == 14) {
                                send=true;
                                $("#progess_text").text(judge_result[status]+",正在向远程服务器推送");
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
                                count=-1;
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
                                count=-1;
                                $("#progess_text").text(judge_result[status]);
                                $('#progress').progress({
                                    percent: 100
                                });
                                $("#progress").progress('set error');
                            }
                            else if(status == 15) {
                                var innerH = judge_result[status] + " 由于您的代码问题/目标OJ的故障,该代码未能提交";
                                $("#progess_text").html(innerH);
                                $('#progress').progress({
                                    percent: 100
                                });
                                $("#progress").progress('set warning');
                            }
                            else {
                                var innerH = judge_result[status];
                                if (status == 11) {
                                    count=-1;
                                    innerH += "<br>";
                                    if ("<?=$oj_signal?>" == "HDU") {
                                        innerH += "<a href='http://acm.hdu.edu.cn/viewerror.php?rid=" + rid + "' target='_blank'>点此查看详情</a>";
                                    }
                                    else if ("<?=$oj_signal?>" == "POJ") {
                                        innerH += "<a href='http://poj.org/showcompileinfo?solution_id=" + rid + "' target='_blank'>点此查看详情</a>";
                                    }
                                }
                                $("#progess_text").html(innerH);
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
                            input_text: $("#ipt").val(),
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
//if(typeof(eAL) != "undefined"){ eAL.toggle("source");eAL.toggle("source");}
//var editor=ace.edit("source");
                        document.getElementById("hide_source").value = editor.getValue();
//alert(editor.getValue());
//alert(document.getElementById("hide_source"));
                        //document.getElementById("frmSolution").target = "_self";
                        var lang = document.getElementById("language").value;
                        //document.getElementById("frmSolution").submit();
                        ajax_post();
                        //document.getElementById("TestRun").disabled = true;
                        document.getElementById("Submit").disabled = true;
                        count=20;
                        resume();
                    }
                    var handler_interval;
                    function resume() {
                        --count;
                        var s = document.getElementById('Submit');
                        //var t = document.getElementById('TestRun');
                        if (count < 0) {
                            s.disabled = false;
                         //   t.disabled = false;
                            s.value = "<?php echo $MSG_SUBMIT?>";
                          //  t.value = "<?php echo $MSG_TR?>";
                            if (handler_interval) window.clearInterval(handler_interval);
                        } else {
                            s.value = "<?php echo $MSG_SUBMIT?>(" + count + ")";
                          //  t.value = "<?php echo $MSG_TR?>(" + count + ")";
                            window.setTimeout("resume();", 1000);
                        }
                    }
                    function reloadtemplate(lang) {
                        document.cookie = "lastlang=" + lang.value;
                        var langn = lang.value;
                        var file_n = language_ext[langn];
                        editor.getSession().setMode("ace/mode/" + language[langn]);
                        var highlight = ace.require("ace/ext/static_highlight")
                        var dom = ace.require("ace/lib/dom")
                        qsa(".code").forEach(function (codeEl) {
                            codeEl.innerHTML = "";
                            codeEl.setAttribute("ace-mode", "ace/mode/" + language[langn])
                        });
                        
                    }
                </script>
                <script>
                    <?php include("template/$OJ_TEMPLATE/js/editor_setting.php") ?>
                </script>
                <script>
                    $("#theme").on('change', function () {
                        var this_theme = this.value;
                        Cookies.set('theme', this.value, {expires: 60});
                        console.log("设置主题成功！主题为:" + this.value);
                        Cookies.set('theme-name', $(this).find("option:selected").text(), {expires: 60});
                        console.log("设置主题名称成功！主题名称为:" + $(this).find("option:selected").text());
                        editor.setTheme(this.value);
                        qsa(".code").forEach(function (codeEl) {
                            codeEl.setAttribute("ace-theme", this_theme);
                        });
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
                </script>
                <div id="clipcp" style="display:none"></div>
            </div>
        </div>
    </div>
</div>
<?php include("template/$OJ_TEMPLATE/bottom.php"); ?>
</body>
</html>
