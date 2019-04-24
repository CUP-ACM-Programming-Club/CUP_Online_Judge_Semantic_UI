<script src="/js/browser-detect.umd.js"></script>
<script>
var result = browserDetect();
if(result.name === "ie" && result.versionNumber < 10 || (result.name == "chrome" && result.versionNumber < 50) || (result.os.indexOf("XP")!== -1)) {
        location.href = "notie.php"
}
</script>
<?php 
$PREFIX = "";
if($_SERVER["HTTP_HOST"] == "oj.cupacm.com") {
    $PREFIX = "https://static.cupacm.com";
}
?>
<script src="<?=$PREFIX?>/template/semantic-ui/js/jquery-3.3.1.min.js"></script>
<script src="<?=$PREFIX?>/semantic-ui/semantic.min.js?ver=1.0.6"></script>
<script src="<?=$PREFIX?>/js/markdown-it.js?ver=1.0.7"></script>
<!-- Place this tag in your head or just before your close body tag. -->
<script src="<?=$PREFIX?>/js/vue.min.js?ver=2.6.10"></script>
<script src="<?=$PREFIX?>/template/semantic-ui/js/base64.js?type=min&ver=1.0"></script>
<script src="<?=$PREFIX?>/template/semantic-ui/js/utils.js?ver=1.0.6"></script>
<script src="<?=$PREFIX?>/template/semantic-ui/js/anime.min.js"></script>
<script src="<?=$PREFIX?>/js/dayjs.min.js"></script>
<script src="<?=$PREFIX?>/js/lodash.min.js?ver=4.7.11"></script>
<script>
    $(document).ready(function(){
        $(function() {
                setTimeout(function() {
                $("body").prepend('<a href="#top" id="toTop" style="z-index:999"><i class="arrow alternate huge circle up icon"></i></a>');
            	$("#toTop").scrollToTop();
                }, 50);
            });
    })
</script>