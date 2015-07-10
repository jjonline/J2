<?php
/*
Template Name:J2
Description:晶晶的博客开源扁平化自适应模板<a href="http://blog.jjonline.cn/theme/J2.html" target=_blank>勾贰</a>
Version:1.2 Beta
Author:Jea杨
Author Url:http://blog.jjonline.cn
Sidebar Amount:1
*/
if(!defined('EMLOG_ROOT')) {exit('error!');}
require_once View::getView('module');
?>
<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta http-equiv="Cache-Control" content="no-siteapp">
<meta http-equiv="Cache-Control" content="no-transform">
<title><?php echo $site_title; ?></title>
<meta name="ThemeAuthor" content="Jea杨(http://blog.jjonline.cn)">
<meta name="keywords" content="<?php echo $site_key; ?>" />
<meta name="description" content="<?php echo $site_description; ?>" />
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="<?php echo BLOG_URL; ?>xmlrpc.php?rsd" />
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="<?php echo BLOG_URL; ?>wlwmanifest.xml" />
<link rel="alternate" type="application/rss+xml" title="RSS"  href="<?php echo BLOG_URL; ?>rss.php" />
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
<link rel="apple-touch-icon-precomposed" href="<?php echo TEMPLATE_URL; ?>images/logo_icon.png">
<script type='text/javascript' src='http://apps.bdimg.com/libs/jquery/1.9.1/jquery.min.js'></script>
<link href="http://apps.bdimg.com/libs/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" media='all' />
<link href="http://apps.bdimg.com/libs/fontawesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" media='all' />
<link href="<?php echo TEMPLATE_URL; ?>static/view.css" rel="stylesheet" type="text/css" media='all' />
<script src="<?php echo BLOG_URL; ?>admin/editor/plugins/code/prettify.js" type="text/javascript"></script>
<script src="<?php echo BLOG_URL; ?>include/lib/js/common_tpl.js" type="text/javascript"></script>
<!--[if lt IE 9]><script src="http://apps.bdimg.com/libs/html5shiv/r29/html5.min.js"></script><![endif]-->
<?php doAction('index_head'); ?>
</head>
<body>
<header class="header">
	<div class="container top">
	    <h1 class="logo"><a href="<?php echo BLOG_URL; ?>" title="<?php echo $blogname; ?>"><?php echo $blogname; ?></a></h1>
		<?php blog_navi($logData,$logs);?>
		<div class="mini_nav_btn"><button class="btn_nav"><i class="fa fa-navicon"></i></button></div>
		<div class="search">
			<form class="searchForm" action="<?php echo BLOG_URL; ?>index.php">
				<input class="searchInput" type="text" name="keyword" placeholder="输入关键字搜索" autocomplete="off">
				<button class="searchBtn" type="submit"><i class="fa fa-search"></i></button>
			</form>
		</div>
	</div>
</header>
<div class="container">
	<div class="pull-right snsPanel"><a href="http://weibo.com/511456119" target=_blank class="sns ico-sns-sina">新浪微薄</a><a href="http://t.qq.com/jjonline" target=_blank class="sns ico-sns-qq">腾讯微薄</a></div>
	<div class="newTwiter"><p><strong>最新碎语：</strong><?php echo newSignletwiter(); ?></p></div>
</div>