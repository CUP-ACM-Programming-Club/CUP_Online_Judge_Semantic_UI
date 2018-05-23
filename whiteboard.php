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
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php");
include("csrf.php");
?>
<div class="container">
    <!-- Main component for a primary marketing message or call to action -->
    <div class="ui container padding">
        <div class="ui grid">
            <div class="row">
                <div class="column">
                    <div id="modeBar" style="margin: 0;
        padding: 0;
        position: relative;
        height: 35px;
        color: black;width:100%;text-align:right" class="ui menu borderless">
                        <div class="item">
                            <select class="ui dropdown selection" id="language" name="language"
                                    onChange="reloadtemplate(this);">
                            </select>
                        </div>
                        <div class="right menu">
                            <div class="item">
                            </div>
                            <div class="item"><span class="item">字号:</span>
                                <div class="ui input"><input type="text" value=""
                                                             style="width:60px;text-align:center;height:30px"
                                                             id="fontsize" onkeyup="resize(this)" value="18"></div>
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
                                        <option value="ace/theme/monokai" selected>Monokai</option>
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
                    <div style="width:100%;height:650px" cols=180 rows=20
                         id="source"></div>
                    <textarea id="hide_source" style="display:none" name="source"></textarea>
                    <div id="statusBar" style="margin: 0;
        padding: 0;
        position: relative;
        height: 30px;
        color: black;width:100%" class="ui menu borderless">
                        <div style="text-align:center;" class="item"><?php echo $OJ_NAME ?>&nbsp;&nbsp;</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- /container -->
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->


<div style="display:none">
    <?php include("template/$OJ_TEMPLATE/bottom.php") ?>
</div>
<script src="ace-builds/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="ace-builds/src-min-noconflict/ext-language_tools.js" type="text/javascript"
        charset="utf-8"></script>

<script src="ace-builds/src-min-noconflict/ext-error_marker.js" type="text/javascript" charset="utf-8"></script>
<script src="ace-builds/src-min-noconflict/ext-statusbar.js?ver=1.0" type="text/javascript" charset="utf-8"></script>
<script src="ace-builds/src-min-noconflict/ext-emmet.js" type="text/javascript" charset="utf-8"></script>
<script src="template/<?php echo $OJ_TEMPLATE; ?>/js/editor_config.js"></script>
<script>
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
    }

    function resize(obj) {
        var size = obj.value;
        if (!isNaN(size)) {
            document.getElementById('source').style.fontSize = size + "px";
        }
    }

    ace.require("ace/ext/language_tools");
    var editor = ace.edit("source");
    var StatusBar = ace.require("ace/ext/statusbar").StatusBar;
    var statusBar = new StatusBar(editor, $("#statusBar")[0]);
    editor.getSession().setMode("ace/mode/c_cpp");
    document.getElementById('source').style.fontSize = "18" + "px";
    editor.setTheme("ace/theme/monokai");
    $("#theme").on('change', function () {
        editor.setTheme(this.value);
        setTimeout(function () {
            $("#total_control").height($("#right-side").height());
        }, 0);
    });
    <?php if(isset($_SESSION['administrator'])||isset($_SESSION['contest_manager'])){ ?>
    $(document).keyup(function () {
        var val = editor.getSession().getValue();
        if (val && val.length > 0) {
            var obj = {
                request: "text",
                content: val
            }
            window.socket.emit("whiteboard", obj);
        }
    });
    <?php } ?>
    window.waitforsocket = setInterval(function () {
        if (window.socket) {
            window.socket.on("whiteboard", function (data) {
                var from = data["from"];
                var type = data["type"];
                if (type=="content"&&from != "<?=$_SESSION["user_id"]?>")
                {
                    var content = data["content"];
                    editor.getSession().setValue(content);
                }
            });
            window.socket.emit("whiteboard", {
                request: "register"
            });
            clearInterval(window.waitforsocket);
        }
    }, 50);

</script>
</body>
</html>
