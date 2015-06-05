/*
 *
 *Theme Name: J2
 *URL: http://blog.jjonline.cn
 *Description: 晶晶的博客emlog开源J2主题
 *Author: Jea杨
 *Version: 1.1
 *
 */
/**image lazy load plugin**/
(function(a){a.fn.scrollLoading=function(b){var c={attr:"data-src",container:a(window),callback:a.noop};var d=a.extend({},c,b||{});d.cache=[];a(this).each(function(){var h=this.nodeName.toLowerCase(),g=a(this).attr(d.attr);var i={obj:a(this),tag:h,url:g};d.cache.push(i)});var f=function(g){if(a.isFunction(d.callback)){d.callback.call(g.get(0))}};var e=function(){var g=d.container.height();if(a(window).get(0)===window){contop=a(window).scrollTop()}else{contop=d.container.offset().top}a.each(d.cache,function(m,n){var p=n.obj,j=n.tag,k=n.url,l,h;if(p){l=p.offset().top-contop,l+p.height();if((l>=0&&l<g)||(h>0&&h<=g)){if(k){if(j==="img"){f(p.attr("src",k))}else{p.load(k,{},function(){f(p)})}}else{f(p)}n.obj=null}}})};e();d.container.bind("scroll",e)}})(jQuery);

/**Base Func**/
$(function () {
    /**ajax comment submit**/  
    if(_info && _info.logid && _info.isOpenComment) {   
        var face = [{ "img": "images\/emoji\/e056.png", "title": "[\u53ef\u7231]" }, { "img": "images\/emoji\/e057.png", "title": "[\u5f00\u5fc3]" }, { "img": "images\/emoji\/e414.png", "title": "[\u5bb3\u7f9e]" }, { "img": "images\/emoji\/e402.png", "title": "[\u5978\u7b11]" }, { "img": "images\/emoji\/e106.png", "title": "[\u8272]" }, { "img": "images\/emoji\/e417.png", "title": "[\u4eb2\u4eb2]" }, { "img": "images\/emoji\/e108.png", "title": "[\u6d41\u6c57]" }, { "img": "images\/emoji\/e403.png", "title": "[\u60c6\u6005]" }, { "img": "images\/emoji\/e058.png", "title": "[\u4f24\u5fc3]" }, { "img": "images\/emoji\/e40b.png", "title": "[\u8870]" }, { "img": "images\/emoji\/e411.png", "title": "[\u5927\u54ed]" }, { "img": "images\/emoji\/e410.png", "title": "[\u6df7\u4e71]" }, { "img": "images\/emoji\/e107.png", "title": "[\u6050\u6016]" }, { "img": "images\/emoji\/e059.png", "title": "[\u751f\u6c14]" }, { "img": "images\/emoji\/e408.png", "title": "[\u778c\u7761]" }, { "img": "images\/emoji\/e10c.png", "title": "[\u5916\u661f\u4eba]" }, { "img": "images\/emoji\/e022.png", "title": "[\u7231\u5fc3]" }, { "img": "images\/emoji\/e00e.png", "title": "[\u5f3a\u608d]" }, { "img": "images\/emoji\/e421.png", "title": "[\u9119\u89c6]" }, { "img": "images\/emoji\/e011.png", "title": "[\u80dc\u5229]" }];
        var _PostNameNode    = $('#comname'),//comname
            _PostMailNode    = $('#commail'),//commail
            _PostUrlNode     = $('#comurl'),//comurl
            _PostCodeNode    = $('.comment_verfiy_code'),//imgcode
            _PostGidNode     = $('#comment-gid'),//gid
            _PostPidNode     = $('#comment-pid'),//pid
            _PostCommentNode = $('#comment'),//comment        
            _PostFormNode    = $('#commentform'),
            _PostInfoNode    = $('.comment_info'),
            _PostSubBtnNode  = $('#comment_submit');
        //handle conmment emoji
        function HandlComment(str) {
            var ct = str,
                content = '';
            $.each(face, function(i, n) {
                var regxe = new RegExp('\\' + n.title.toString(), 'gm');
                if (i == 0) {
                    content = ct.replace(regxe, '<img src="' + _info.tpl + n.img + '">');
                } else {
                    content = content.replace(regxe, '<img src="' + _info.tpl + n.img + '">');
                }
            });
            return content;
        };
        /**control btn status**/
        function DisabledBtn(isDisabled,time) {
            if(isDisabled) {
                _PostSubBtnNode.addClass('subDisabled').attr('disabled',true).fadeTo('slow',0.3);
            }else {
                _PostSubBtnNode.removeClass('subDisabled').attr('disabled',false).fadeTo('slow',1);
            }
            if(time) {	//定时取消禁用状态
                setTimeout(function () {
                    _PostSubBtnNode.removeClass('subDisabled').attr('disabled',false).fadeTo('slow',1);
                },time*1000);
            }
        };
        _PostCommentNode.focus(function () {
            _PostInfoNode.empty().append('Ctrl+Enter快速提交').css('color','#555');
        });
        /**handle status ajax return**/
        /**
         * @param retStr ajax返回html
         * @return array['status':boolean,'info':text]
         */
        function handleCommentReturn(retStr) {
            var ret = J.trimAll(J.trimEnter(retStr));
            //console.log(ret);
            var isPcre = ret.match(/<divclass=\"main\"><p>[^<]+<\/p>/);
            if(isPcre) {
                //评论成功 需要审核
                var text = isPcre[0].match(/[\u4E00-\u9FA5]+，[\u4E00-\u9FA5]+/gi);
                if(text) {
                    return {'status':true,'info':'success'};
                }
                var text = isPcre[0].match(/[\u4E00-\u9FA5]+：[\u4E00-\u9FA5]+/gi);
                if(text) {
                    return {'status':false,'info':text[0]};
                }
                return true;
            }else {
                return {'status':true,'info':'评论成功'};
            }
            //意外情况
            return false;
        }
        /**submit**/
        _PostFormNode.submit(function () {
            var _postData = {
                    'gid':_PostGidNode.val(),
                    'pid':_PostPidNode.val(),
                    'comname':_PostNameNode.val(),
                    'commail':_PostMailNode.val(),
                    'comurl':_PostUrlNode.val(),
                    'imgcode':_PostCodeNode.val(),
                    'comment':_PostCommentNode.val()
                };
                _Url     = _PostFormNode.attr('action'),
                _Url     = _Url?_Url:_info.url+'index.php?action=addcom';
            //效验内容
            if(_postData.comment.length==0) {
                _PostInfoNode.empty().append('请输入评论').css('color','#CC0033');
                //_PostCommentNode.focus();                
                return false;
            }
            //前端显示1024个字符
            if(_postData.comment.length>1024) {
                _PostInfoNode.empty().append('评论字数过多').css('color','#CC0033');
                //_PostCommentNode.focus();                
                return false;
            }
            //未登录状态下的各种效验
            if(!_info.isLogin) {
                //验证码如果有
                if(_info.isCommentCode && _postData.imgcode.length<=1) {
                    _PostInfoNode.empty().append('验证码格式错误').css('color','#CC0033');
                    _PostCodeNode.focus();                
                    return false;
                }
                //昵称
                if(_postData.comname.length==0) {
                    _PostInfoNode.empty().append('昵称格式错误').css('color','#CC0033');
                    _PostNameNode.focus();                
                    return false;
                }
                //邮箱
                if(!J.isMail(_postData.commail)) {
                    _PostInfoNode.empty().append('邮箱格式错误').css('color','#CC0033');
                    _PostMailNode.focus();                
                    return false;
                }
                //url 如果有
                if(_postData.comurl.length>0 && !J.isUrl(_postData.comurl)) {
                    _PostInfoNode.empty().append('网址格式错误').css('color','#CC0033');
                    _PostUrlNode.focus();                
                    return false;
                }
            }
            DisabledBtn(true);
            _PostInfoNode.empty().append('正在提交...').css('color','#555');
            $.ajax({
                type: "POST",
                url: _Url,
                dataType:'html',
                data: _postData,
                success: function (msg) {
                    var result = handleCommentReturn(msg),_tips;
                    if(result.status) {
                        _PostCommentNode.val('');
                        //验证码处理
                        if(_info.isCommentCode) {
                            _PostCodeNode.val('');
                            var _imgNode = $('.comment_verfiy_container img'),
                                _src     = _imgNode.attr('src');
                            if(!_imgNode.attr('data-src')) {
                                _imgNode.attr('data-src',_src);
                            }
                            var _src = _imgNode.attr('data-src')+'?_rmd='+new Date().getTime();
                            _imgNode.attr('src',_src);                         
                        }
                        if(!_info.isCommentCheck) {
                            var _nickName = _postData.comname?_postData.comname:'管理员',
                                _tips     =  '评论成功';
                            if(_postData.pid>0) {
                                //子评论
                                var _newNode = [
                                    '<div class="comment comment-children" style="background:#FBFCE7;">',
                                    '<div class="avatar"><img src="'+_info.tpl+'images/noAvator.jpg"></div>',
                                    '<div class="comment-info">',
                                        '<div class="comment-content">'+HandlComment(_postData.comment)+'</div>',
                                        '<div class="comment-meata">',
                                            '<span class="comment-poster">'+_nickName+' </span>',
                                            '<span class="comment-time">刚刚</span>',
                                        '</div>',
                                    '</div>',
                                    '</div>'
                                ].join('');
                                $('#comment-'+_postData.pid).append(_newNode);
                            }else {
                                //全新评论 检测是否为第一条评论 产生评论容器
                                var _newNode  = [
                                    '<div class="comment dpt_line" style="background:#FBFCE7;">',
                                        '<div class="avatar"><img src="'+_info.tpl+'images/noAvator.jpg"></div>',
                                        '<div class="comment-info">',
                                            '<div class="comment-content">'+HandlComment(_postData.comment)+'</div>',
                                            '<div class="comment-meata"><span class="comment-poster">'+_nickName+' </span> <span class="comment-time">刚刚</span></div>',
                                        '</div>',
                                    '</div>'
                                ].join('');
                                if($('.article_comment_list').text()=='') {
                                    $('#comment-place').after('<h3 class="comment-header">网友评论<b>（1）</b></h3><div class="article_comment_list">'+_newNode+'</div>');
                                }else {
                                    $('.article_comment_list').prepend(_newNode);
                                }                                
                            }
                        }else {
                            _tips   =  '评论成功，管理员审核通过后方可显示';
                        }                        
                        _PostInfoNode.empty().append(_tips).css('color','#99CC33');
                    }else {
                        _PostInfoNode.empty().append(result.info).css('color','#CC0033'); 
                    }
                    DisabledBtn(false);
                    return false; 
                },
                error:function () {
                    _PostInfoNode.empty().append('网络异常，请刷新页面后再试').css('color','#CC0033');
                    DisabledBtn(false);
                    return false;
                }
            });
            // prevent default event
            return false;
        });
        //bind Ctrl+Enter submit
        _PostCommentNode.keypress(function(event){
            if(event.ctrlKey && event.keyCode == 13 || event.which == 10) {
                if(_PostSubBtnNode.hasClass('subDisabled')){return false;}else{$(this).submit();}
            }
        });
        //face
        var _FaceBtnNode = $('.comment_face_btn'),_FaceInsertNode = $('.form_textarea');
        _FaceBtnNode.click(function () {
            if($(this).hasClass('readyState')) {
                $('#Face').slideToggle();
            }else {
                $(this).addClass('readyState')
                var _FaceString = ['<div id="Face" class="faceContainer"><p>'];
                $.each(face,function(i,n) {
                    var _value = '<a href="javascript:;" title="'+n.title+'" data-title="'+n.title+'"><img src="'+_info.tpl + n.img+'"></a>';
                    _FaceString.push(_value);
                });
                _FaceString.push('</p></div>');
                _FaceString    = _FaceString.join('');
                _FaceInsertNode.after(_FaceString);
                //bind event
                $('#Face a').bind({
                    'click':function () {
                        var _FaceString = $(this).attr('data-title'),
                            obj         = $("#comment").get(0);
                        if (document.selection) {
                            obj.focus();
                            var sel = document.selection.createRange();
                            sel.text = _FaceString;
                        } else if (typeof obj.selectionStart === 'number' && typeof obj.selectionEnd === 'number') {
                            obj.focus();
                            var startPos = obj.selectionStart;
                            var endPos = obj.selectionEnd;
                            var tmpStr = obj.value;
                            obj.value = tmpStr.substring(0, startPos) + _FaceString + tmpStr.substring(endPos, tmpStr.length);
                        } else {
                            obj.value += _FaceString;
                        }
                        $('#Face').slideToggle();
                    }
                });
            }
        });
    };
    /**twiter ajax**/
    if(_info && _info.isOpenTwitter && _info.isPageTwiter && _info.isLogin) {
        var _tokenNode       = $('#token'),//token
            _twiterNode      = $('#addTwiter'),//t
            _PostSubTBtnNode = $('.addTwiterBtn'),//Tbtn
            _infoNode        = $('.addTwiterInfo'),
            _twiterForm      = $('.addTwiterForm');
        _twiterNode.focus(function () {
            _infoNode.empty().append('Ctrl+Enter快速提交').css('color','#555');
        });
        /**control btn status**/
        function DisabledTBtn(isDisabled,time) {
            if(isDisabled) {
                _PostSubTBtnNode.addClass('subDisabled').attr('disabled',true).fadeTo('slow',0.3);
            }else {
                _PostSubTBtnNode.removeClass('subDisabled').attr('disabled',false).fadeTo('slow',1);
            }
            if(time) {	//定时取消禁用状态
                setTimeout(function () {
                    _PostSubTBtnNode.removeClass('subDisabled').attr('disabled',false).fadeTo('slow',1);
                },time*1000);
            }
        };
        /**handle status ajax return**/
        /**
         * @param retStr ajax返回html
         * @return boolean
         */
        function handleTwiterReturn(retStr) {
            var ret = J.trimAll(J.trimEnter(retStr));
            if(/<spanclass=\"actived\">/.test(ret)) {
                return true;
            }
            if(/<spanclass=\"error\">/.test(ret)) {
                return false;
            }
            //意外情况
            return false;
        }
        _twiterForm.submit(function () {
            var _Data   = {'t':$.trim(_twiterNode.val()),'token':_tokenNode.val()},
                _Url    = _twiterForm.attr('action'),
                _Url    = _Url?_Url:_info.url+'admin/twitter.php?action=post';
            if(_Data.t.length==0) {
                _infoNode.empty().append('请输入碎语').css('color','#CC0033');
                //_twiterNode.blur();
                return false;
            }
            if(_Data.t.length>140) {
                _infoNode.empty().append('碎语不得多于140字').css('color','#CC0033');
                //_twiterNode.blur();
                return false;
            }
            DisabledTBtn(true);
            _infoNode.empty().append('正在提交...').css('color','#555');
            $.ajax({
                type: "POST",
                url: _Url,
                dataType:'html',
                data: _Data,
                success: function (msg) {
                    if(handleTwiterReturn(msg)) {
                        _twiterNode.val('');
                        var _newNode = [
                            '<li class="twiter_list" style="background:#FBFCE7;">',
                                '<img src="'+$('.twiter_list img').attr('src')+'" alt="'+$('.twiter_list img').attr('alt')+'" class="twiter_avatar" />',
                                '<p class="twiter_content">'+_Data.t+'</p>',
                                '<p class="twiter_info"><span class="twiter_author">'+$('.twiter_list img').attr('alt')+'</span><span class="twiter_time"><i class="fa fa-clock-o"></i> 刚刚</span></p>',
                            '</li>'
                        ].join('');
                        $('.twiter').prepend(_newNode);
                        _infoNode.empty().append('碎语发布成功').css('color','#99CC33');
                    }else {
                        _infoNode.empty().append('碎语发布失败，请刷新页面后再试').css('color','#CC0033'); 
                    }
                    DisabledTBtn(false);
                    return false;                   
                },
                error:function (XMLHttpRequest,textStatus,errorThrown) {
                    _infoNode.empty().append('网络异常，请刷新页面后再试').css('color','#CC0033');
                    DisabledTBtn(false);
                    return false;
                }
            });
            // prevent default event
            return false;
        });
        //bind Ctrl+Enter submit
        _twiterNode.keypress(function(event){
            if(event.ctrlKey && event.keyCode == 13 || event.which == 10) {
                if(_PostSubTBtnNode.hasClass('subDisabled')){return false;}else{$(this).submit();}
            }
        });
    }
    /**change vcode**/
    $('.comment_verfiy_container img').click(function () {
        var src = $(this).attr('src');
        if(!$(this).attr('data-src')) {
            $(this).attr('data-src',src);
        }
        var _src = $(this).attr('data-src')+'?_rmd='+new Date().getTime();
        $(this).attr('src',_src);
    });
    $('.twiter_reply_ipt_code img').click(function () {
        var src = $(this).attr('src');
        if(!$(this).attr('data-src')) {
            $(this).attr('data-src',src);
        }
        var _src = $(this).attr('data-src')+'&_rmd='+new Date().getTime();
        $(this).attr('src',_src);
    });
	/**lazy load**/
	$.each($('.avatar img'),function (i,n) {$(n).scrollLoading();});	
    
	/**back to top**/
	function scrollTop() {
		var scroller  = $('.scrollTop'),
            windoInfo = J.windowSize(),
            _right    = (windoInfo.width-1320)/2-42;
            if(_right>0) {
                scroller.css('right',_right+'px');
            }else {
                scroller.css('right','20px');
            }
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
	
	/**baidu share**/
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
		};
		with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
	}
	/**手持设备版显示导航栏**/
    var navBtn = $('.btn_nav'),nav = $('.nav');
    navBtn.click(function () {
        nav.toggle();
    });

});