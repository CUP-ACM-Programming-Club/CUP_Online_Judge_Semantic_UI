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
    <?php include("template/semantic-ui/css.php");?>
    <?php include("template/semantic-ui/js.php");?>

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
<?php include("template/semantic-ui/nav.php");?>
<div class="ui main container">
    <!-- Main component for a primary marketing message or call to action -->
<script type="text/x-template" id="markdown_content">
<div class="ui warning message">
<div class="header">提示</div>
若您对平台使用方法、OJ模式相关问题不够了解，建议使用Google、百度等搜索引擎搜索后，再阅读本<b>FAQ</b>中的内容(如<a href="https://zh.wikipedia.org/wiki/%E5%9C%A8%E7%BA%BF%E5%88%A4%E9%A2%98%E7%B3%BB%E7%BB%9F" target="_blank">Wikipedia-在线评测系统</a>)。
<br>平台开发相关信息，请访问<a href="about.php" target="_blank">关于</a><br>关于本平台使用的开源项目，请访问<a href="opensource.php" target="_blank">开放源代码声明</a><br>想要了解关于<b>ACM/ICPC竞赛</b>的资讯，请善用搜索引擎，并阅读<a href="icpc.php" target="_blank">什么是ACM/ICPC</a>
</div>
 
作者:[Ryan Lee(李昊元)](/userinfo.php?user=2016011253)
## 我如何能够提交我的代码?

1. 注册一个账号
2. 进入一个题目
3. 粘贴你的代码
4. 点击提交，查看返回结果
```cpp
   if(AC) goto 2;
   else {
       debug;
       goto 3;
   }
```
## 我的代码在什么环境上运行？

- CPU:Intel(R) Xeon(R) CPU E5-2609 0 @ 2.40GHz
- RAM:16G
- OS:CentOS 7
- GCC:8.2.0
- Clang:3.4.2
- Java:6/7/8/10(OpenJDK)
- JavaScript:NodeJS 10.10.0
- Python:CPython/PyPy
## 我的编译环境是什么？

| Compiler(Language) | Command                                                                                                        |
|:------------------:| -------------------------------------------------------------------------------------------------------------- |
| GCC(C/C++)         | `gcc/g++ -fmax-errors=10 -fno-asm -Wall -O2 -lm --static -std=c++${version} -DONLINE_JUDGE -o Main Main.cc `   |
| Clang(C/C++)       | `clang/clang++ Main.cc -o Main -ferror-limit=10 -fno-asm -Wall -lm --static -std=c++${version} -DONLINE_JUDGE` |
| Java               | `java -J${java_xms} -J${java_xmx} -encoding UTF-8 Main.java`                                                   |
| Python             | None                                                                                                           |
| JavaScript         | None                                                                                                           |
| fpc(Pascal)        | `fpc Main.pas -Cs32000000 -Sh -O2 -Co -Ct -Ci`                                                                 |



## 我如何从评测机获得输入，并将结果输出？

评测机仅接受使用`stdin`进行输入，并将结果输出到`stdout`中。所有文件操作均被禁止使用

## 评测机返回的结果代表什么意思?

| 评测结果                       | 含义                                           |
| -------------------------- | -------------------------------------------- |
| Waiting/等待                 | 等待评测队列对代码进行评测                                |
| Compiling/编译中              | 系统正在编译代码                                     |
| Running/运行并评判              | 系统正在运行程序，并进行评判                               |
| Accept/答案正确                | 代码通过所有的评测样例                                  |
| Presentation Error/格式错误    | 代码结果可以通过所有样例，但是没有符合题目要求的格式                   |
| Wrong Answer/答案错误          | 代码没有通过所有的评测样例                                |
| Time Limit Exceeded/时间超限   | 代码运行的时间超出了题目的要求，程序被提前强行终止                    |
| Memory Limit Exceeded/内存超限 | 代码运行的内存超出了题目的要求，程序被提前强行终止                    |
| Output Limit Exceeded/输出超限 | 代码运行结果超出正确输出(一般是超出正确输出长度两倍以上)或**超出评测机对输出文件的限制(256MB)**                |
| Runtime Error/运行错误         | 代码在运行过程中出现段错误/访问非法内存空间/非法调用系统操作/浮点数除零错误/系统错误 |
| Compile Error/编译错误         | 编译过程中发生错误，编译失败                               |
| Add to queue/已加入队列         | 代码已加入爬虫提交队列，等待向远程服务器发送提交请求                   |
| Server Refuse/提交被服务器拒绝     | 由于代码不合法/目标服务器状态非法等原因，代码没有成功提交至目标服务器          |
| System Error/系统错误          | 由于不可预料的原因，系统无法完成评测                           |

### 特别说明:格式错误(2018/09/24 新增)
> 什么是格式错误？

格式错误是你的答案和标准输出的答案一致，但是控制字符/换行字符/空格数量与标准答案不同的情况。
例如:
|期望输出|实际输出 |
|:-|:-|
|a&nbsp;&nbsp;=&nbsp;&nbsp;b(间隔两个空格)|a=b|
这种时候判题机会返回**格式错误**
以下情况也会被认为是格式错误:
|期望输出|实际输出 |
|:-|:-|
|* * \*<br>&nbsp;* *<br>&nbsp;&nbsp;\*|* * \*<br>&nbsp;* *&nbsp;<br>&nbsp;&nbsp;\*&nbsp;&nbsp;|
以上两个的区别是:期望输出的右边**直接是换行，没有空格**
但是，以下的输出不会被认为是格式错误，而是认为是**答案正确**
|期望输出|实际输出|
|-|-|
|Hello,world!\n|Hello,world!|
这是因为判题机会**自动忽略最后一行行尾的换行符、空格以及控制字符**，至于为什么会做这个处理，将在其他的文章中讨论。

### 特别说明:时间超限(2018/09/24 新增)
> 时间超限指的是程序在执行过程中使用的CPU时间超过了题目的要求，抑或**由于程序基本没有使用CPU时间,但是始终处于运行状态超过了一定时间**(这里一般是10倍CPU限制时间)判题机将会返回时间超限。

一般来说，时间超限可能因为程序的多项式时间超出题目要求所导致的。比如题目对于CPU时间限制为$1\text{sec}$,对应简单操作$3\times10^9$次。若输入规模$n$,程序多项式时间函数$f(x)$,则有$f(n) \leq 1\times3\times 10^9$(事实上这并不严谨，只是一个为了方便理解的类比)。若$f(x)$不能满足限制，则**大概率无法在指定时间内运行结束**(这里还有一些神奇的玄学或编译器的编译期优化，以及循环展开等问题造成的影响，不予讨论)

但是有一些特殊情况也会造成时间超限。例如**程序无法申请足够的内存，导致在不占用CPU时间的情况保持使能状态直到运行时间耗尽**。这时我们不难发现CPU时间并未跑满，而评测机却返回了时间超限。

## 我如何针对OJ编写程序？

以Problem 1000 A+B Problem 为例

### C

```c
#include <stdio.h>
int main()
{
  int a,b;
  scanf("%d%d",&a,&b);
  printf("%d\n");
}
```

### C++

```c++
#include <iostream>
using namespace std;
int main()
{
  int a,b;
  cin >> a >> b;
  cout << a + b << endl;
}
```

### Java

**注:Java程序必须以`Main`作为主类，否则将返回编译错误**

```java
import java.util.*
public class Main {
	public static void main(String[] args) throws Exception {
  	Scanner in = new Scanner(System.in);
    int a = in.nextInt();
    int b = in.nextInt();
    System.out.println(a+b);
  }
}
```

### Python 2

```python
print sum(map(int,raw_input().split()))
```

### Python 3

```python
print(sum(map(int,input().split()))
```

## 注意事项

#### C/C++

1. `main`函数的返回值必须为`int`,`void main()`等非标准的写法将不被允许
2. 对于64位整数，请使用`long long/unsigned long long`而不是`__int64`声明变量，并使用`%lld`或`%llu`输入输出
3. 由于众所周知的原因，`cin`和`cout`的速度慢于`scanf`以及`printf`。因此我们推荐使用后两者读写数据。(事实上`cin`以及`cout`并不一定比`scanf`和`printf`慢。详情见[感性对比评测机效率](discusscontext.php?id=8)以及[cin加速](http://www.hankcs.com/program/cpp/cin-tie-with-sync\_with\_stdio-acceleration-input-and-output.html))

#### Java

1. 提交的代码中只能存在一个public 类,该类名必须为`Main`,类`Main`必须设置为`public`
2. `Main`类中必须存在一个`static main`方法，并保证该方法返回`void`

#### 其他语言

其他语言(包括Java)的时限和内存限制一般是C/C++的两倍

## 我明明本地跑样例过了，为什么提交没有AC?
样例只是题目所有测试点中的一个例子，不代表你的代码能够通过所有测试点的测试。请认真思考后修改你的代码


</script>
            <h1 class="ui dividing header">FAQ(Version:2018/09/24)</h1>
            <div class="markdown target"></div>
            <!--
            <div class="ui info message">
  <i class="close icon"></i>
  <div class="header">
    该板块内容需要更新
  </div>
  <ul class="list">
    <li>由于系统迭代升级多次，条目内容需要补充完整</li>
    <li>以下内容仅供参考</li>
  </ul>
</div>
        <font color=green>Q</font>:这个在线评测平台使用什么样的编译器和编译选项?<br>
            <font color=red>A</font>:详见<a href="http://acm.cup.edu.cn/wiki/%E5%B8%B8%E8%A7%81%E9%97%AE%E9%A2%98/%E7%BC%96%E8%AF%91%E5%91%BD%E4%BB%A4" target="_blank">CUP Online Judge WIKI</a><br>
        <p>  编译器版本为（系统可能升级编译器版本，这里直供参考）:<br>
        <div class="ui raised segment">
            <font color=blue>
g++ (GCC) 8.2.0<br>
Copyright © 2018 Free Software Foundation, Inc.<br>
使用内建 specs。<br>
COLLECT_GCC=/usr/local/bin/g++<br>
COLLECT_LTO_WRAPPER=/usr/local/libexec/gcc/x86_64-pc-linux-gnu/8.2.0/lto-wrapper<br>
目标：x86_64-pc-linux-gnu<br>
配置为：../configure --enable-language=c,c++ --disable-multilib<br>
线程模型：posix<br>
gcc 版本 8.2.0 (GCC)<br>

            </font><br>
            <font color=blue>clang version 3.4.2 (tags/RELEASE_34/dot2-final)
                Target: x86_64-redhat-linux-gnu
            </font><br>
            <font color=blue>glibc 2.3.6</font><br>
            <font color=blue>openjdk version "10.0.1" 2018-04-17<br>
OpenJDK Runtime Environment (build 10.0.1+10)<br>
OpenJDK 64-Bit Server VM (build 10.0.1+10, mixed mode)
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
        -->

</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script language="javascript" type="text/javascript" src="include/jquery.flot.js"></script>
<script src="/ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
/*
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
    */
    var mdcontent = $("#markdown_content").html();
    $(".markdown.target").html(markdownIt.render(mdcontent));
</script>
<?php include("template/semantic-ui/bottom.php"); ?>
</body>
</html>
