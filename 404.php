<?php 
/**
 * 自定义404页面
 */
if(!defined('EMLOG_ROOT')) {exit('error!');}
?>
<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>404 Not Found.</title>
</head>
<style type="text/css">
body{font-family: 'Microsoft Yahei',"\5FAE\8F6F\96C5\9ED1";color:#555;background-color:#eeeeee;position:relative;}
.main {background-color:#FFFFFF;font-size:14px;color:#666666;width:648px;border-radius:10px;padding:10px 20px;list-style:none;border:#DFDFDF 1px solid;position:absolute;left:50%;margin:0 0 0 -345px;display:none;}
.image {margin:0 auto;text-align:center;}
.main p {line-height:25px;text-align:center;}
.time {color:#F60;font-weight:bold;}
a,a:visited {color:#00a2ca;text-decoration:none;}
a:hover {text-decoration:underline;}
</style>
</head>
<body>
<div class="main">
<div class="image"><img src="<?php echo TEMPLATE_URL; ?>images/404.jpg"></div>
<p>抱歉，你所请求的页面不存在！本页面将在<strong class="time">9</strong>秒后自动[<a href="<?php echo BLOG_URL; ?>">返回首页</a>]</p>
</div>
</body>
<script type='text/javascript' src='http://apps.bdimg.com/libs/jquery/1.9.1/jquery.min.js'></script>
<script type="text/javascript">
$(function () {
	var handle,t=9,h = $(window).height(),timer = $('.time');
	if(h>533) {
		$('.main').css('top',(h-533)*1/3+'px').show();
	}else {
		$('.main').show();
	}
	handle = setInterval(function () {
		t--;
		if(t==0) {clearInterval(handle);window.location.href="<?php echo BLOG_URL; ?>";return false;}
		timer.empty().text(t);
	},1000);
});
</script>
</html>