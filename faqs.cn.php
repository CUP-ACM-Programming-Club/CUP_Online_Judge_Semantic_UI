<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $OJ_NAME?></title>
    <?php include("template/$OJ_TEMPLATE/css.php");?>
    <?php include("template/$OJ_TEMPLATE/js.php");?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css" media="screen">
    .editor { 
        height:300px;
        width:inherit;
    }
</style>
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="ui main text container pusher padding">

    <!-- Main component for a primary marketing message or call to action -->
    <div>
        <center>
            <h1 class="ui header block"><?php echo $OJ_NAME?> FAQ</h1>
        </center>
        <font color=green>Q</font>:这个在线评测平台使用什么样的编译器和编译选项?<br>
            <font color=red>A</font>:详见<a href="http://acm.cup.edu.cn/wiki/%E5%B8%B8%E8%A7%81%E9%97%AE%E9%A2%98/%E7%BC%96%E8%AF%91%E5%91%BD%E4%BB%A4" target="_blank">CUP Online Judge WIKI</a><br>
        <p>  编译器版本为（系统可能升级编译器版本，这里直供参考）:<br>
        <div class="ui raised segment">
            <font color=blue>
gcc 版本 8.1.0 (GCC)<br>
使用内建 specs。<br>
COLLECT_GCC=/usr/local/bin/gcc<br>
COLLECT_LTO_WRAPPER=/usr/local/libexec/gcc/x86_64-pc-linux-gnu/8.1.0/lto-wrapper<br>
目标：x86_64-pc-linux-gnu<br>
配置为：../configure --disable-multilib<br>
线程模型：posix<br>

            </font><br>
            <font color=blue>clang version 3.4.2 (tags/RELEASE_34/dot2-final)
                Target: x86_64-redhat-linux-gnu
            </font><br>
            <font color=blue>glibc 2.3.6</font><br>
            <font color=blue>openjdk version "1.8.0_171"<br>
OpenJDK Runtime Environment (build 1.8.0_171-b10)<br>
OpenJDK 64-Bit Server VM (build 25.171-b10, mixed mode)
<br>
Python 2.7.5
<br>
Python 3.6.2 (default, Oct 21 2017, 11:38:05) 
<br>
            </font></p>
            </div>
       <div class="ui section divider"></div>
        <div class="ui bottom attached warning message">
  <i class="warning icon"></i>
  Java程序必须使用Main作为类，main作为主函数提交，否则将无法通过编译
</div>
        <p><font color=green>Q</font>:程序怎样取得输入、进行输出?<br>
            <font color=red>A</font>:你的程序应该从标准输入 stdin('Standard Input')获取输入，并将结果输出到标准输出 stdout('Standard Output').例如,在C语言可以使用 'scanf' ，在C++可以使用'cin' 进行输入；在C使用 'printf' ，在C++使用'cout'进行输出.</p>
        <p>用户程序不允许直接读写文件, 如果这样做可能会判为运行时错误 "<font color=green>Runtime Error</font>"。<br>
            <br>
            下面是 1000题的参考答案</p>
        <p> C++:<br>
        </p>
        
            <div class="editor" id="editor">#include &lt;iostream&gt;
using namespace std;
int main(){
    int a,b;
    while(cin >> a >> b)
        cout << a+b << endl;
	return 0;
}
</div>

        C:<br>
        <div class="editor" id="editor1">#include &lt;stdio.h&gt;
int main(){
    int a,b;
    while(scanf("%d %d",&amp;a, &amp;b) != EOF)
        printf("%d\n",a+b);
	return 0;
}
</div>
        PASCAL:<br>
        <div class="editor" id="editor2">program p1001(Input,Output);
var
  a,b:Integer;
begin
   while not eof(Input) do
     begin
       Readln(a,b);
       Writeln(a+b);
     end;
end.
</div>
        <br>
        Java:<br>
        <div class="editor" id="editor3">import java.util.*;
public class Main{
	public static void main(String args[]){
		Scanner cin = new Scanner(System.in);
		int a, b;
		while (cin.hasNext()){
			a = cin.nextInt(); b = cin.nextInt();
			System.out.println(a + b);
		}
	}
}</div><br>Python 2:<br>
<div class="editor" id="editor4">import sys
for line in sys.stdin:
    a = line.split()
    print int(a[0]) + int(a[1])

</div>
<br>Python 3:<br>
<div class="editor" id="editor5">while True:
    try:
        line = input()
        print(sum(map(int,line.split())))
    except EOFError:
        break
</div>

        <hr>
        <font color=green>Q</font>:为什么我的程序在自己的电脑上正常编译，而系统告诉我编译错误!<br>
        <font color=red>A</font>:GCC的编译标准与VC6有些不同，更加符合c/c++标准:<br>
        <ul>
            <li><font color=blue>main</font> 函数必须返回<font color=blue>int</font>, <font color=blue>void main</font> 的函数声明会报编译错误。<br>
            <li><font color=green>i</font> 在循环外失去定义 "<font color=blue>for</font>(<font color=blue>int</font> <font color=green>i</font>=0...){...}"<br>
            <li><font color=green>itoa</font> 不是ansi标准函数.<br>
            <li><font color=green>__int64</font> 不是ANSI标准定义，只能在VC使用, 但是可以使用<font color=blue>long long</font>声明64位整数。<br>如果用了__int64,试试提交前加一句#define __int64 long long
        </ul>
        <hr>
        <font color=green>Q</font>:系统返回信息都是什么意思?<br>
        <font color=red>A</font>:详见下述:<br>
        <p><font color=blue><?=$MSG_Pending?></font> : 系统忙，你的答案在排队等待. </p>
        <p><font color=blue><?=$MSG_Pending_Rejudging?></font>: 因为数据更新或其他原因，系统将重新判你的答案.</p>
        <p><font color=blue><?=$MSG_Compiling?></font> : 正在编译.<br>
        </p>
        <p><font color="blue"><?=$MSG_Running_Judging?></font>: 正在运行和判断.<br>
        </p>
        <p><font color=blue><?=$MSG_Accepted?></font> : 程序通过!<br>
            <br>
            <font color=blue><?=$MSG_Presentation_Error?></font> : 答案基本正确，但是格式不对。<br>
            <br>
            <font color=blue><?=$MSG_Wrong_Answer?></font> : 答案不对，仅仅通过样例数据的测试并不一定是正确答案，一定还有你没想到的地方.<br>
            <br>
            <font color=blue><?=$MSG_Time_Limit_Exceed?></font> : 运行超出时间限制，检查下是否有死循环，或者应该有更快的计算方法。<br>
            <br>
            <font color=blue><?=$MSG_Memory_Limit_Exceed?></font> : 超出内存限制，数据可能需要压缩，检查内存是否有泄露。<br>
            <br>
            <font color=blue><?=$MSG_Output_Limit_Exceed?></font>: 输出超过限制，你的输出比正确答案长了两倍.<br>
            <br>
            <font color=blue><?=$MSG_Runtime_Error?></font> : 运行时错误，非法的内存访问，数组越界，指针漂移，调用禁用的系统函数。请点击后获得详细输出。<br>
        </p>
        <p>  <font color=blue><?=$MSG_Compile_Error?></font> : 编译错误，请点击后获得编译器的详细输出。<br>
            <br>
        </p>
        <hr>
        <font color=green>Q</font>:如何参加在线比赛?<br>
        <font color=red>A</font>:<a href=registerpage.php>注册</a> 一个帐号，然后就可以练习，点击比赛列表Contests可以看到正在进行的比赛并参加。<br>
        <br>
        <hr>
        <center>
            <font color=green size="+2">其他问题请发邮件到<a href="mailto:gxlhybh@gmail.com"><?php //echo $OJ_NAME?>我的邮箱</a></font>
        </center>
        <hr>
        <center>
            <table width=100% border=0>
                <tr>
                    <td align=right width=65%>
                        <a href = "index.php"><font color=red><?php echo $OJ_NAME?></font></a>
                        <font color=red>2017/3/18</font></td>
                </tr>
            </table>
        </center>
    </div>

</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script language="javascript" type="text/javascript" src="include/jquery.flot.js"></script>
<script src="/ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/monokai");
    editor.getSession().setMode("ace/mode/c_cpp");
    editor.setShowPrintMargin(false);
    document.getElementById('editor').style.fontSize='15px';
    editor.setReadOnly(true);
    var editor1 = ace.edit("editor1");
    editor1.setTheme("ace/theme/monokai");
    editor1.getSession().setMode("ace/mode/c_cpp");
    document.getElementById('editor1').style.fontSize='15px';
    editor1.setReadOnly(true);
    var editor2 = ace.edit("editor2");
    editor2.setTheme("ace/theme/monokai");
    editor2.getSession().setMode("ace/mode/pascal");
    document.getElementById('editor2').style.fontSize='15px';
    editor2.setReadOnly(true);
    var editor3 = ace.edit("editor3");
    editor3.setTheme("ace/theme/monokai");
    editor3.getSession().setMode("ace/mode/java");
    document.getElementById('editor3').style.fontSize='15px';
    editor3.setReadOnly(true);
    var editor4 = ace.edit("editor4");
    editor4.setTheme("ace/theme/monokai");
    editor4.getSession().setMode("ace/mode/python");
    document.getElementById('editor4').style.fontSize='15px';
    editor4.setReadOnly(true);
    var editor5 = ace.edit("editor5");
    editor5.setTheme("ace/theme/monokai");
    editor5.getSession().setMode("ace/mode/python");
    document.getElementById('editor5').style.fontSize='15px';
    editor4.setReadOnly(true);
    editor5.setReadOnly(true);
    editor1.setShowPrintMargin(false);
    editor2.setShowPrintMargin(false);
    editor3.setShowPrintMargin(false);
    editor4.setShowPrintMargin(false);
    editor5.setShowPrintMargin(false);
</script>
<?php include("template/$OJ_TEMPLATE/bottom.php"); ?>
</body>
</html>
