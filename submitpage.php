<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <?php
    $langmask=$OJ_LANGMASK;
    if(isset($cid)&&intval($cid)>0)
    {
    $result=$database->select("contest","langmask",["contest_id"=>$cid]);
    $langmask=intval($result[0]);
    }
    $tid = "";
    if (isset($_GET['tid']))
        $tid = intval($_GET['tid']);
    ?>
    <?php include("csrf.php"); ?>
    <title><?php echo $OJ_NAME ?></title>
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <script src="ace-builds/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
    <!--<script src="template/<?php echo $OJ_TEMPLATE ?>/js/flat-ui.js"></script>-->
    <script src="ace-builds/src-min-noconflict/ext-language_tools.js" type="text/javascript" charset="utf-8"></script>

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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
<div class="pusher">
    <!-- Main component for a primary marketing message or call to action -->
    <div class="padding ui container">
        <center>
            <?php
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
                $OJ_EDITE_AREA = false;
            }
            //if($OJ_EDITE_AREA){
            ?>
            <!--<script language="Javascript" type="text/javascript" src="edit_area/edit_area_full.js"></script>-->
            <!--<script language="Javascript" type="text/javascript">
            editAreaLoader.init({
            id: "source"
            ,start_highlight: true
            ,allow_resize: "both"
            ,allow_toggle: true
            ,word_wrap: true
            ,language: "en"
            ,syntax: "cpp"
            ,font_size: "12"
            ,syntax_selection_allow: "basic,c,cpp,java,pas,perl,php,python,ruby"
            ,toolbar: "search, go_to_line, fullscreen, |, undo, redo, |, select_font,syntax_selection,|, change_smooth_selection, highlight, reset_highlight, word_wrap, |, help"
            });
            </script>-->

            <script>
                console.time("function");
                function qsa(sel) {
                    return Array.apply(null, document.querySelectorAll(sel));
                }
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
            <form id=frmSolution action="submit.php" method="post"
                <?php if ($OJ_LANG == "cn") { ?>
                    onsubmit="return checksource(editor.getValue());"
                <?php } ?>
            >
                <?php if (!isset($_GET['cid']) && (!isset($_GET['tid']))) { ?>
                    Problem <span class=blue><b><?php echo $id ?></b></span>
                    <input id=problem_id type='hidden' value='<?php echo $id ?>' name="id"><br>
                <?php } else {
//$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
//if ($pid>25) $pid=25;
                    ?>
                    Problem <span class=blue><b><?php echo chr($pid + ord('A')) ?></b></span> of Contest <span
                            class=blue><b><?php echo $cid ?></b></span><br>
                    <input id="cid" type='hidden' value='<?php echo $cid ?>' name="cid">
                    <input id="tid" type='hidden' value='<?php echo $tid ?>' name="tid">
                    <input id="pid" type='hidden' value='<?php echo $pid ?>' name="pid">

                <?php } ?>
                <select class="ui dropdown selection" id="language" name="language"
                        onChange="reloadtemplate(this);">
                    <?php
                    $lang_count = count($language_ext);
                    /*if (isset($_GET['langmask']))
                        $langmask = $_GET['langmask'];
                    else
                        $langmask = $OJ_LANGMASK;
                        */
                    if (isset($_COOKIE['lastlang'])) $lastlang = $_COOKIE['lastlang'];
                    else $lastlang = 0;
                    $lang = (~((int)$langmask)) & ((1 << ($lang_count)) - 1);
                    $append_flag = 0;
                    $append_first_lang = -1;
                    for ($i = 0; $i < $lang_count; $i++) {
                        if (($lang & (1 << $i)) && (($OJ_APPENDCODE && (file_exists("$OJ_DATA/$id/prepend.$language_ext[$i]") || file_exists("$OJ_DATA/$id/append.$language_ext[$i]"))) || !$OJ_APPENDCODE)) {
                            echo "<option value=$i " . ($lastlang == $i ? "selected" : "") . ">
" . $language_name[$i] . "
</option>";
                            if ($append_first_lang == -1) $append_first_lang = $i;
                            $append_flag = 1;
                            if ($lastlang == 0) $lastlang == $i;
                        }
                    }
                    if (~$append_first_lang) {
                        $lastlang = $append_first_lang;
                    }
                    $prepend_file = "$OJ_DATA/$id/prepend.$language_ext[$lastlang]";
                    $append_file = "$OJ_DATA/$id/append.$language_ext[$lastlang]";
                    if ($append_flag == 0) {
                        for ($i = 0; $i < $lang_count; $i++) {
                            if ($lang & (1 << $i)) {
                                echo "<option value=$i " . ($lastlang == $i ? "selected" : "") . ">
" . $language_name[$i] . "
</option>";
                            }
                        }
                    }
                    ?>
                </select>
                <br>

                <!--<textarea style="width:80%" cols=180 rows=20 id="source" name="source"><?php //echo htmlentities($view_src,ENT_QUOTES,"UTF-8")?></textarea>-->
                <br>
                <div id="modeBar" style="margin: 0;
        padding: 0;
        position: relative;
        height: 35px;
        color: black;width:80%;text-align:right" class="ui menu borderless"><?php if ($OJ_APPENDCODE && $append_flag) { ?><a
                            href="javascript:void(0)" class="item" id="clipbtn" data-clipboard-action="copy"
                            data-clipboard-target="#clipcp" style="float:left;">复制代码</a><?php } ?>
                    <div class="right menu"><div class="item">
                    </div><div class="item"><span class="item">字号:</span>
                    <div class="ui input"><input type="text" value="" style="width:60px;text-align:center;height:30px"
                                                 id="fontsize" onkeyup="resize(this)"></div></div>
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
                        </select></div></div></div>
                <?php if ($OJ_APPENDCODE && $append_flag) { ?>
                    <div class="code" style="width: 80%;padding:0px;line-height:1.2;text-align:left;margin-bottom:0px;"
                         ace-mode="ace/mode/<?php echo $language_template[$lastlang] ?>" ace-theme="ace/theme/monokai"
                         id="prepend">
                        <?php

                        //echo "<script>console.log($prepend_file)</script>";
                        if (isset($OJ_APPENDCODE) && $OJ_APPENDCODE && file_exists($prepend_file)) {
                            echo htmlentities(file_get_contents($prepend_file));

                        }
                        ?>
                    </div>
                <?php } ?>
                <div style="width:80%;height:500px" cols=180 rows=20
                     id="source"><?php echo htmlentities($view_src, ENT_QUOTES, "UTF-8") ?></div>
                <textarea id="hide_source" style="display:none" name="source"></textarea>
                <?php if ($OJ_APPENDCODE && $append_flag) { ?>
                    <div id="append" class="code"
                         style="width: 80%; padding:0px; line-height:1.2;text-align:left;margin-bottom:0px;"
                         ace-mode="ace/mode/<?php echo $language_template[$lastlang] ?>" ace-theme="ace/theme/monokai">
                        <?php

                        if (isset($OJ_APPENDCODE) && $OJ_APPENDCODE && file_exists($append_file)) {
                            echo htmlentities(file_get_contents($append_file));
                        }
                        ?>
                    </div>
                <?php } ?>
                <div id="statusBar" style="margin: 0;
        padding: 0;
        position: relative;
        height: 30px;
        color: black;width:80%" class="ui menu borderless"><div style="text-align:center;" class="item"><?php echo $OJ_NAME ?>&nbsp;&nbsp;</div></div>
                <br>
                <?php echo $MSG_Input ?>:<textarea style="width:30%" cols=40 rows=5 id="input_text"
                                                   name="input_text"><?php echo $view_sample_input ?></textarea>
                <?php echo $MSG_Output ?>:
                <textarea style="width:30%" cols=10 rows=5 id="out" name="out">SHOULD BE:
<?php echo $view_sample_output ?>
</textarea>
                <br>
                <input id="Submit" class="ui green button" type=button value="<?php echo $MSG_SUBMIT ?>"
                       onclick="do_submit();">
                <input id="TestRun" class="ui blue button" type=button value="<?php echo $MSG_TR ?>"
                       onclick=do_test_run();><!--<span class="btn" id=result>状态</span>-->
                <div id="result" class="ui grey button">状态</div>
            </form>
        </center>
        <br>
        <div class="ui teal progress" data-value="0" data-total="3" id="progress" style="display:none">
                    <div class="bar">
                        <div class="progress"></div>
                    </div>
                    <div class="label" id="progess_text"></div>
                </div>
    </div>

</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

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
    function wsfs_result(data){
                        var solution_id = data["solution_id"];
                        sid = solution_id;
                        var state = data["state"];
                        var time=data["time"];
                        var memory = data["memory"];
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
                        if (state >= 4){
                            window.setTimeout("print_result(" + solution_id + ")", 2000);
                            count = 1;
                        }
                    }
    function wsresult(data){
                        //var res = data.split(',');
                            var status = parseInt(data["state"]);
                            var pass_point=data["pass_point"];
                            var time=data["time"];
                            var memory=data["memory"];
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
                                $("#progess_text").text(judge_result[status] + " 已通过测试点:" + pass_point);
                               // setTimeout("frush_result(" + runner_id + ")", 250);
                                $('#progress').progress({
                                    percent: 40
                                });
                            }
                            else if (status == 4) {
                                count=0;
                                $("#progess_text").text(judge_result[status] + " 内存使用:" + memory + "KB 运行时间:" + time + "ms");
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
                                count=0;
                                $("#progess_text").text("在第" + pass_point + "个测试点发生 " + judge_result[status]);
                                $('#progress').progress({
                                    percent: 100
                                });
                                $("#progress").progress('set error');
                            }
                            else {
                                count=0;
                                $("#progess_text").text("在第" + pass_point + "个测试点发生 " + judge_result[status]);
                                $('#progress').progress({
                                    percent: 100
                                });
                                $("#progress").progress('set warning');
                            }
                    }
    function frush_result(runner_id) {
                    $.get("status-ajax.php?solution_id=" + runner_id, function (data) {
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
                            $("#progess_text").text(judge_result[status] + " 内存使用:" + res[1] + "KB 运行时间:" + res[2] + "ms");
                            $('#progress').progress({
                                percent: 100
                            });
                            $("#progress").progress('set success');
                        }
                        else if (status == 5 || status == 6) {
                            $("#progess_text").text("在第" + (parseInt(res[3])) + "个测试点发生 " + judge_result[status]);
                            $('#progress').progress({
                                percent: 100
                            });
                            $("#progress").progress('set error');
                        }
                        else {
                            $("#progess_text").text("在第" + (parseInt(res[3])) + "个测试点发生 " + judge_result[status]);
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
                    document.getElementById("TestRun").disabled = true;
                    document.getElementById("Submit").disabled = true;
                    var postdata = {
                        id: $("#problem_id").val(),
                        cid: $("#cid").val(),
                        tid: $("#tid").val(),
                        pid: $("#pid").val(),
                        input_text: $("#ipt").val(),
                        language: $("#language").val(),
                        source: $("#hide_source").val(),
                        type: '<?php if (isset($_GET['tid'])) echo "topic"; else if (isset($_GET['cid'])) echo "contest"; else echo "problem"; ?>',
                        csrf:"<?=$token?>"
                    };
                    $.post("submit.php", {json: JSON.stringify(postdata),csrf:"<?=$token?>"}, function (data) {
                        var running_id = parseInt(data);
                        if(isNaN(running_id))
                            {
                                alert("题目未开放");
                            }
                            else
                            {
                                if(typeof window.socket=="object"&&socket.connected)
                                {
                                    window.socket.emit("submit",{submission_id:running_id,val:window.postdata});
                                }
                                else
                                    frush_result(running_id);
                                count = 20;
                                handler_interval = window.setTimeout("resume();", 1000);
                            }
                    });
                }
    function do_submit() {
//if(typeof(eAL) != "undefined"){ eAL.toggle("source");eAL.toggle("source");}
//var editor=ace.edit("source");
                    document.getElementById("hide_source").value = editor.getValue();
//alert(editor.getValue());
//alert(document.getElementById("hide_source"));
                    var mark = "<?php
                        if (isset($_GET['cid']))
                            echo "cid";
                        else if (isset($_GET['tid']))
                            echo "tid";
                        else
                            echo "problem_id";
                        ?>";
                    var problem_id = document.getElementById(mark);
                    if (mark == 'problem_id')
                        problem_id.value = '<?php echo $id?>';
                    else if (mark == 'tid')
                        problem_id.value = '<?php echo $tid ?>';
                    else
                        problem_id.value = '<?php echo $cid ?>';
                    document.getElementById("frmSolution").target = "_self";
                    var lang = document.getElementById("language").value;
                    var submit_obj = {
                        submit_id:<?php if (isset($id) && $id) echo $id; else if (isset($cid) && $cid) echo $cid; else if (isset($tid) && $tid) echo $tid ?>,
                        user_id: "<?php echo $_SESSION['user_id'] ?>",
                        language: language[lang]
                    };
                    // var json_str = JSON.stringify({type: 'message', data: submit_obj});
                    //document.getElementById("frmSolution").submit();
<?php if ($OJ_LANG == "cn") {?>
                    var error_str = checksource(editor.getValue());
                    if (error_str.length > 0) {
                        $('.ui.basic.confirms.modal')
                            .modal({
                                allowMultiple:true,
                                closable: false,
                                offset:400,
                                onDeny: function () {
                                    
                                },
                                onShow:function(){
                                    $("#confirm_box").html(error_str);
                                },
                                onApprove: function () {
                                    ajax_post();
                                },
                                onHidden:function(){
                                   // $('.ui.basic.confirms.modal').removeClass('hidden');
                                }
                            })
                            .modal('show')
                        ;
<?php } ?>
                }
                else
                {
                    ajax_post();
                }
                }
    var handler_interval;
    function do_test_run() {
        if (handler_interval) window.clearInterval(handler_interval);
        var loader = "<img width=18 src=image/loader.gif>";
        var tb = window.document.getElementById('result');
//if(typeof(eAL) != "undefined"){ eAL.toggle("source");eAL.toggle("source");}
//console.log(editor);
        document.getElementById("hide_source").value = editor.getValue();
//console.log(editor.getValue().length);
        if (editor.getValue().length < 10) return alert("too short!");
        tb.innerHTML = loader;

        var mark = "<?php
            if (isset($cid) && intval($cid) != 0)
                echo "cid";
            else if (isset($id) && intval($id) != 0)
                echo "problem_id";
            else
                echo "tid";

            ?>";
        var problem_id = document.getElementById(mark);
        problem_id.value = -problem_id.value;
        document.getElementById("frmSolution").target = "testRun";
//console.log($("#frmSolution").serialize());
//document.getElementById("frmSolution").submit();
                        if(typeof window.socket==="object")
                        {
                            var postdata = {
                            id: $("#problem_id").val(),
                            cid: $("#cid").val(),
                            tid: $("#tid").val(),
                            pid: $("#pid").val(),
                            input_text: $("#ipt").val(),
                            language: $("#language").val(),
                            source: $("#hide_source").val(),
                            type: '<?php if (isset($_GET['tid'])) echo "topic"; else if (isset($_GET['cid'])) echo "contest"; else echo "problem"; ?>',
                            csrf: "<?=$token?>"
                            
                        };
                        window.postdata=postdata;
                        
                            
                        }
        $.post("submit.php?ajax", $("#frmSolution").serialize(), function (data) {
            <?php if(isset($_SESSION['administrator'])){ ?>
                            console.log("测试运行 发送信息:");
                            console.log($("#frmSolution").serialize());
                            <?php } ?>
                            if(typeof window.socket=="object"&&window.socket.connected)
                            {
                                window.socket.emit("submit",{submission_id:data,val:window.postdata});
                            }
                            else
                                fresh_result(data);
        });
        document.getElementById("TestRun").disabled = true;
        document.getElementById("Submit").disabled = true;
        problem_id.value = -problem_id.value;
        count = 20;
        handler_interval = window.setTimeout("resume();", 1000);
    }
    function resume() {
        count--;
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
    const language = Array("c_cpp", "c_cpp", "pascal", "java", "ruby", "bash", "python", "php", "perl", "csharp", "objectivec", "text", "scheme", "c_cpp", "c_cpp", "lua", "javascript", "go", "text");
    language_ext = Array("c", "cc", "pas", "java", "rb", "sh", "py", "php", "pl", "cs", "m", "bas", "scm", "c", "cc", "lua", "js", "go");
    function reloadtemplate(lang) {
        document.cookie = "lastlang=" + lang.value;
        //const lang=Array("C","C++","Pascal","Java","Ruby","Bash","Python","PHP","Perl","C#","Obj-C","FreeBasic","Schema","Clang","Clang++","Lua","JavaScript","Go","Other Language");
        var langn = lang.value;
        var file_n = language_ext[langn];
        editor.getSession().setMode("ace/mode/" + language[langn]);
        var highlight = ace.require("ace/ext/static_highlight")
        var dom = ace.require("ace/lib/dom")
        qsa(".code").forEach(function (codeEl) {
            codeEl.innerHTML = "";
            codeEl.setAttribute("ace-mode", "ace/mode/" + language[langn])
        });
        var prepend_file_context = "";
        var append_file_context = "";
        $.post("getfile.php?ajax",
            {
                file_name: file_n,
                id: "<?php echo $id?>"
            }, function (data) {
                prepend_file_context = data.toString().substring(data.toString().indexOf("prepend_file_start:") + "prepend_file_start:".length, data.toString().indexOf("prepend_file_end;"));
                append_file_context = data.toString().substring(data.toString().indexOf("append_file_start:") + "append_file_start:".length, data.toString().indexOf("append_file_end;"));
                if (prepend_file_context)
                    document.getElementById("prepend").innerHTML = prepend_file_context;
                if (append_file_context)
                    document.getElementById("append").innerHTML = append_file_context;
                setTimeout(function () {
                    flush_theme();
                }, 0);
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
        $(document).ready(function () {
            console.time("Ready AJAX");
            console.log("页面加载完毕，进行AJAX POST请求");
            var file_n = language_ext[document.getElementById('language').value];
            $.post("getfile.php?ajax",
                {
                    file_name: file_n,
                    id: "<?php echo $id?>"
                }, function (data) {
                    console.log("POST请求得到响应");
                    prepend_file_context = data.toString().substring(data.toString().indexOf("prepend_file_start:") + "prepend_file_start:".length, data.toString().indexOf("prepend_file_end;"));
                    append_file_context = data.toString().substring(data.toString().indexOf("append_file_start:") + "append_file_start:".length, data.toString().indexOf("append_file_end;"));
                    if (prepend_file_context)
                        document.getElementById("prepend").innerHTML = prepend_file_context;
                    if (append_file_context)
                        document.getElementById("append").innerHTML = append_file_context;
                    if (prepend_file_context)
                        document.getElementById("prepend_hide").innerHTML = prepend_file_context;
                    if (append_file_context)
                        document.getElementById("append_hide").innerHTML = append_file_context;
                    setTimeout(function () {
                        console.log("刷新前后置代码主题");
                        flush_theme();
                        console.timeEnd("Ready AJAX");
                    }, 0);
                });
            setTimeout(function () {
                console.log("刷新前后置代码主题");
                flush_theme();
            }, 0);
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
    editor.getSession().setMode("ace/mode/c_cpp");
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
    if (Cookies.get('font-size')) {
        document.getElementById('source').style.fontSize = Cookies.get('font-size') + 'px';
        document.getElementById('fontsize').value = Cookies.get('font-size');
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
    if (Cookies.get('theme')) {
        theme_n = Cookies.get('theme');
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
    /*
     var theme=document.getElementById('theme');*/
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
    //var htmlv="";
    //editor.setValue(htmlv);
</script>
<script>
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
            if (editor.getValue().length != 0) {
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
<div id="prepend_hide"
     style="display:none"><?php if (isset($OJ_APPENDCODE) && $OJ_APPENDCODE && file_exists($prepend_file)) {
        echo htmlentities(file_get_contents($prepend_file));

    } ?></div>
<div id="append_hide"
     style="display:none"><?php if (isset($OJ_APPENDCODE) && $OJ_APPENDCODE && file_exists($append_file)) {
        echo htmlentities(file_get_contents($append_file));

    } ?></div>
<div id="clipcp" style="display:none"></div>
<?php include("template/$OJ_TEMPLATE/bottom.php") ?>
</body>
</html>
