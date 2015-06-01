/*
 *
 *Theme Name: J2
 *URL: http://blog.jjonline.cn
 *Description: 晶晶的博客emlog开源J2主题
 *Author: Jea杨
 *Version: 1.0
 *
 */
 
// visiter count 通过拉取jsonp文件进行主题安装统计
// http://ajing.qiniudn.com/require.js
// callbackFunc Jvisiter()
// function Jvisiter(data) {alert(data.data);};
/**lazy load plugins**/
(function(a){a.fn.scrollLoading=function(b){var c={attr:"data-src",container:a(window),callback:a.noop};var d=a.extend({},c,b||{});d.cache=[];a(this).each(function(){var h=this.nodeName.toLowerCase(),g=a(this).attr(d.attr);var i={obj:a(this),tag:h,url:g};d.cache.push(i)});var f=function(g){if(a.isFunction(d.callback)){d.callback.call(g.get(0))}};var e=function(){var g=d.container.height();if(a(window).get(0)===window){contop=a(window).scrollTop()}else{contop=d.container.offset().top}a.each(d.cache,function(m,n){var p=n.obj,j=n.tag,k=n.url,l,h;if(p){l=p.offset().top-contop,l+p.height();if((l>=0&&l<g)||(h>0&&h<=g)){if(k){if(j==="img"){f(p.attr("src",k))}else{p.load(k,{},function(){f(p)})}}else{f(p)}n.obj=null}}})};e();d.container.bind("scroll",e)}})(jQuery);


$(function () {
	/**碎语ajax提交字段 post index.php?action=reply&randnum=0.30505152395926416
	r:的说法撒的
	rname:撒旦法
	rcode:xxxd
	tid:198
	//碎语拉取回复情况字段 get index.php?action=getr&tid=198&stamp=1418228338929&randnum=0.8888127119280398
	tid:198
	stamp:1418228338929
	randnum:0.8888127119280398
	**/
	
	/**lazy load**/
	$.each($('.avatar img'),function (i,n) {$(n).scrollLoading();});
	
	/**回到顶部按钮**/
	function scrollTop() {
		var scroller = $('.scrollTop');
		document.documentElement.scrollTop+document.body.scrollTop>200?scroller.fadeIn():scroller.fadeOut();
	};
	//init
	scrollTop();
	//event
	$(window).scroll(function(){
		scrollTop();
	});
	$('.scrollTop').click(function(){
		$('html,body').animate({scrollTop:0},300);
	});	
	
	/**百度分享**/
	if(_info.logid && $(window).width()>640) {
		window._bd_share_config = {
			common: {
				"bdText": $('title').text(),
				"bdMini": "2",
				"bdUrl": $('.article_header h1 a').attr('href')+'#J_share',
				"bdDesc": '很不错的文章，分享给大家！',
				"bdMiniList": false,
				"bdPic": $('.article_content img:first') ? $('.article-content img:first').attr('src') : '',
				"bdStyle": "0",
				"bdSize": "24"
			},
			share: [{
				bdCustomStyle: _info.tpl + 'static/share.css'
			}],
			image: {
				tag: 'bdshare',
				"viewList": ["qzone", "tsina", "weixin", "tqq", "sqq", "renren", "douban"],
				"viewText": " ",
				"viewSize": "16"
			}
			//snsKey:{'tsina':'4025051940','tqq':'8c48c8a299234eccb6ff686421db6ce2'}
		};
		with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
	}
	/**手持设备版显示导航栏**/
	if($(window).width()<=640) {
		var navBtn = $('.btn_nav'),nav = $('.nav');
		navBtn.click(function () {
			nav.toggle();
		});
	}
});