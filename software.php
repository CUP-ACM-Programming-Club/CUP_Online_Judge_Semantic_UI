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
<?php include("template/$OJ_TEMPLATE/nav.php") ?>
<div class="ui container padding">
    <div class="ui one column grid">
        <div class="column">
            <h2 class="ui header">
                <i class="settings icon"></i>
                <div class="content">
                    浏览器
                    <div class="sub header">下载最适合使用的浏览器</div>
                </div>
            </h2>
        </div>
    </div>
    <div class="ui two column grid">
        <div class="column">
            <div class="ui card">
                <a class="image chrome" href="software/63.0.3239.84_chrome_installer.exe">
                    <img src="/img/Chrome.png">
                </a>
                <div class="content">
                    <a class="header chrome" href="software/63.0.3239.84_chrome_installer.exe">Chrome 63</a>
                    <div class="meta">
                        <a class="chrome_content">适合Windows 7/8/8.1/10 系统使用</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="ui card">
                <a class="image" href="software/Firefox-ESR-full-latest.exe">
                    <img src="/img/firefox.jpg">
                </a>
                <div class="content">
                    <a class="header" href="software/Firefox-ESR-full-latest.exe">Firefox ESR (for Windows XP)</a>
                    <div class="meta">
                        <a>适合Windows XP 系统使用</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui section divider"></div>
    <div class="ui one column grid">
        <div class="column">
            <h2 class="ui header">
                <i class="plug icon"></i>
                <div class="content">
                    集成开发环境
                    <div class="sub header">使用更加舒适的IDE</div>
                </div>
            </h2>
        </div>
    </div>
    <div class="ui three column grid">
        <div class="column">
            <div class="ui card">
                <a class="image clion" href="software/CLion-2017.3.">
                    <img src="/img/clion.png">
                </a>
                <div class="content">
                    <a class="header clion" href="software/CLion-2017.3.">CLion 2017.3</a>
                    <div class="meta">
                        <a class="clion_content">适合Windows 7/8/8.1/10 系统使用</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="ui card">
                <a class="image" href="software/codeblocks-16.01mingw-setup.exe">
                    <img src="img/codeblocks.png">
                </a>
                <div class="content">
                    <a class="header" href="software/codeblocks-16.01mingw-setup.exe">Code::Blocks (For Windows)</a>
                    <div class="meta">
                        <a>轻量级跨平台IDE</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="ui card">
                <a class="image" href="https://www.jetbrains.com/idea/">
                    <img src="https://cdn.worldvectorlogo.com/logos/intellij-idea-1.svg">
                </a>
                <div class="content">
                    <a class="header" href="https://www.jetbrains.com/idea/">Intellij Idea</a>
                    <div class="meta">
                        <a>前往官网下载</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui section divider"></div>
    <div class="ui one column grid">
        <div class="column">
            <h2 class="ui header">
                <i class="terminal icon"></i>
                <div class="content">
                    编译环境
                    <div class="sub header">使用更加符合运行环境的编译套件</div>
                </div>
            </h2>
        </div>
    </div>
</div>
<?php include("template/$OJ_TEMPLATE/bottom.php") ?>
<script>
    var userAgent=window.navigator.userAgent;
    var $chrome_val=$(".header.chrome").html();
    var $clion_val=$(".header.clion").html();
    var $clion_suffix=$(".clion").attr("href");
    if(userAgent.indexOf('Windows NT')!=-1)
    {
        $(".clion").attr("href",$clion_suffix+"exe");
        if(userAgent.indexOf('64')!=-1)
        {
            $(".chrome").attr("href","software/63.0.3239.84_chrome_installer_x64.exe");
            $(".header.chrome").html($chrome_val+" For Windows X64");
        }
        else
        {
            $(".chrome").attr("href","software/63.0.3239.84_chrome_installer.exe");
            $(".header.chrome").html($chrome_val+" For Windows X86");
        }
    }
    else if(userAgent.indexOf('Mac')!=-1)
    {
        $(".chrome").attr("href","software/googlechrome.dmg");
        $(".chrome_content").html("适用于macOS 10.9及以上的系统");
        $(".header.chrome").html($chrome_val+" For macOS");
        $(".clion").attr("href",$clion_suffix+"dmg");
        $(".clion_content").html("适用于macOS 10.9.4及以上的系统")
    }
    else
    {
        $(".clion").attr("href",$clion_suffix+"tar.gz");
        $(".clion_content").html("适用于Linux 64位桌面为GNOME或KDE的系统")
    }
</script>
</body>
</html>