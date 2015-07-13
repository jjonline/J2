/*
 *
 *Theme Name: J2
 *URL: http://blog.jjonline.cn
 *Description: 晶晶的博客emlog开源J2主题
 *Author: Jea杨
 *Version: 1.2
 *
 */
$(function () {
	if(_info.isLogin && !J.cookie('isRequired')) {
		var _Data = {'version':_info.version,'url':_info.url,'t_url':_info.tpl};
		$.ajax({
			type: "get",
			url: _info.themeUrl,
			dataType:'jsonp',
			data: _Data,
			complete:function () {J.cookie('isRequired',1,{expires:365});}
		});
	}
});