<?php
/*
 * @J2 附加函数库文件
 * @authors Jea杨 (JJonline@JJonline.Cn)
 * @date    2014-12-07
 * @date    2015-06-01 fixed
 * @version 1.1
 */
if(!defined('EMLOG_ROOT')) {exit('J2 Functions Requrire Emlog!');}
/**
 * @des 获取指定logid的附图 方法体内部自动实现查询、缓存 提升系统执行效率
 * @des 注意：本方法额外添加了一个logimageatt缓存字段 方法体外部不宜调用该字段
 * CACHE structure::
 *  array{
 *   log_id => array('url'=>log_att_image_url,'time'=>unixtimestamp);
  * }
 * @param $logid 日志id
 * @return string => log attachement image url(include blog_url)
 */
function getLogImageAtt($logid) {
	global $CACHE;
	/*缓存文件不存在，建立空值缓存文件先*/
	if(!is_file(EMLOG_ROOT . '/content/cache/logimageatt.php')) {
		$CACHE->cacheWrite(serialize(array(0=>array('url'=>TEMPLATE_URL.'images/noImg.png','time'=>time()))),'logimageatt');
	}
	$logImageAtt    =  $CACHE->readCache('logimageatt');
	if(!empty($logImageAtt) && !empty($logImageAtt[$logid])) {
		//指定时间之后重建缓存以处理可能要替换文章附图的情况 暂不实现
		return $logImageAtt[$logid]['url'];
	}
	//缓存中不存在 建立缓存并返回数据
	$Db        = MySql::getInstance();
	$query     = $Db->query("SELECT * FROM " . DB_PREFIX . "attachment WHERE `blogid`=".$logid." ORDER BY `aid` DESC");
	$_attcache = TEMPLATE_URL.'images/noImg.png';
	while($row = $Db->fetch_array($query)) {
		if($row['width'] == 220 && $row['height'] == 150) {
			$_attcache   = BLOG_URL.ltrim(ltrim($row['filepath'],'.'),'/');
			break;
		}
		//dump($row);
		/*兼容老系统产生的无宽度、高度的图片尺寸*/
		$suffix = strtolower(getFileSuffix($row['filepath']));
		if(in_array($suffix, array('jpg', 'png', 'jpeg', 'gif'))) {
			$fileAbsoluteDir = '.'.ltrim($row['filepath'],'.');//图片目录相对于入口文件的路径
			$size = @getimagesize($fileAbsoluteDir);//读取图片尺寸情况
			if(!empty($size) && $size[0]==220 && $size[1]==150) {
				$_attcache   = BLOG_URL.ltrim(ltrim($row['filepath'],'.'),'/');
				break;
			}
		}
	}
	//添加新单元数据
	$logImageAtt[$logid] = array('url'=>$_attcache,'time'=>time());
	$CACHE->cacheWrite(serialize($logImageAtt),'logimageatt');
	return $_attcache;
}

/**
 * @des 处理日志正文中的图片包裹标签  若被p标签包裹就要替换掉p标签
 * @param content 日志正文
 * @return string 
 */
function handleContent($content) {
	// $content     = preg_replace("/[\t\n\r]+/","",$content);//去除换行符、回车、制表符
	$pcre        = '/<p[^>]*>\s*(<img\s+src=".*?"\s+.*?\/*>)\s*<\/p>/i';
	$pcrecontent = preg_replace_callback($pcre,function ($m) {
		if(is_array($m)) {
			return '<div class="article_image">'.$m[1].'</div>';
		}

	},$content);
	if($pcrecontent) {return $pcrecontent;}
	return $content;
}

/**
 * @des 显示评论列表与否的判定方法
 * @param $comnum 评论内容体
 * @return string 
 */
function isShowComment($comnum) {
	return !!$comnum;
}

/**
 * @des 获取avator头像 解决被墙
 * @param email
 * @return string 
 */
function J_getGravatar($email, $s = 40, $d = 'mm', $g = 'g') {
	$hash   = md5($email);
	$avatar = "https://secure.gravatar.com/avatar/$hash?s=$s";
	return $avatar;
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}

/**
 * @des 多维数组按字段降序排列
 * @param $multiArr 降序数组
 * @param $ordeKey  排序字段
 * @return string 
 */
function array_multi_sort($multiArr,$ordeKey) {
	if(!is_array($multiArr) || !is_string($ordeKey)) {return $multiArr;}
	$arrSort = array();  
	foreach($multiArr AS $uniqid => $row){  
		foreach($row AS $key=>$value){  
			$arrSort[$key][$uniqid] = $value;  
		}  
	}  
	array_multisort($arrSort[$ordeKey], SORT_DESC, $multiArr);  
	return $multiArr;
}

/**
 * @des 获取最新一条碎语
 * @param null
 * @return string 
 */
function newSignletwiter() {
	global $CACHE; 
	$Twiter = $CACHE->readCache('newtw');
	$TwiterStr = '暂无碎语';
	if($Twiter[0]['content']) {
		$TwiterStr = trim($Twiter[0]['content']);
	}
	return $TwiterStr ;
}

/**
 * @des 转换unix时间戳为个性化时间显示
 * @param $unixtime unix时间戳
 * @param $isfixtimezone 是否修正时区  boolean
 * @des $isfixtimezone：脑残emlog发文时间会将服务器时间相对于时区进行处理后存储
 * @return string
 */
function timeago($unixtime,$isfixtimezone=false) {
	if(!ctype_digit( (string) $unixtime)) { return $unixtime; }
	if($isfixtimezone) {
		$unixtime     -= (int)Option::get('timezone')*3600;
	}
	$etime = time() - $unixtime;
    if ($etime < 1) return '刚刚';     
    $interval = array (         
        12 * 30 * 24 * 60 * 60  =>  '年前 ('.date('Y-m-d', $unixtime).')',
        30 * 24 * 60 * 60       =>  '个月前 ('.date('m-d', $unixtime).')',
        7 * 24 * 60 * 60        =>  '周前 ('.date('m-d', $unixtime).')',
        24 * 60 * 60            =>  '天前',
        60 * 60                 =>  '小时前',
        60                      =>  '分钟前',
        1                       =>  '秒前'
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}

/**
 * @des 获取当前页面的导航url 用于导航定位
 * @param $logData 文章数据 查看博文是存在数据
 * @param $logs 文章数据数组 分类下或首页存在
 * @return array ['isSigle'=>boolean,'chirldSortUrl'=>string,'parentSortUrl'=>string]
 */
function getNowPageSortUrl($logData=null,$logs=null) {
	global $CACHE; 
	$logSort     = $CACHE->readCache('sort');//博客内文章分类信息数组 pid!=0则为子分类
	$chirldSort  = array();//子分类数组 key为子分类sortid
	foreach ($logSort as $key => $value) {
		if($value['pid']!=0) {
			$chirldSort[$value['sid']] = $value;
		}
	}
	//$urlModel    = Option::get('isurlrewrite');//获取博客Url模式
	$nowUrl      = BLOG_URL.trim(Dispatcher::setPath(), '/');//当前访问的url
	
	/*文章页*/
	if($logData) {
		$sortid  = $logData['sortid'];//文章分类id 未分类或单独页面会显示成-1
		$logid   = $logData['logid'];//文章id
		$isSigle = $sortid<0;//是否页面、无分类
		if($isSigle) {return array('isSigle'=>true,'chirldSortUrl'=>$nowUrl,'parentSortUrl'=>$nowUrl);}
		/*检测是否子分类下的文章*/
		$sortUrl = Url::sort($sortid);//文章分类url、可能是子分类
		if(array_key_exists($sortid,$chirldSort)) {
			return array(
				'isSigle'=>false,
				'chirldSortUrl'=>$sortUrl,
				'parentSortUrl'=>Url::sort($chirldSort[$sortid]['pid'])
			);
		}else {
			return array('isSigle'=>false,'chirldSortUrl'=>$sortUrl,'parentSortUrl'=>$sortUrl);
		}
	}
	/*碎语*/
	if(isTwiterPage()) {
		return array('isSigle'=>false,'chirldSortUrl'=>BLOG_URL.'t','parentSortUrl'=>BLOG_URL.'t');
	}
	/*列表页*/
	if($logs) {
		if(isWebIndex()) {
			//首页情况 直接返回博客url
			return array('isSigle'=>false,'chirldSortUrl'=>BLOG_URL,'parentSortUrl'=>BLOG_URL);
		}
		$sortid  = $logs[0]['sortid'];
		$sortUrl = Url::sort($sortid);
		$nowSort = preg_replace('/\/page\/\d+/','',preg_replace('/&page=\d+/','',$nowUrl));
		if(array_key_exists($sortid,$chirldSort) && $nowSort==$sortUrl) {
			return array(
				'isSigle'=>false,
				'chirldSortUrl'=>$sortUrl,
				'parentSortUrl'=>Url::sort($chirldSort[$sortid]['pid'])
			);
		}else {
			return array('isSigle'=>false,'chirldSortUrl'=>$nowSort,'parentSortUrl'=>$nowSort);
		}
	}
	//特例情况 存在分类 但没有数据的
	return array('isSigle'=>false,'chirldSortUrl'=>$nowUrl,'parentSortUrl'=>$nowUrl);
}

/**
 * @des 检测是否为碎语页面=>脑残emlog在碎语带有分页时碎语navi将失效
 * @param null
 * @return boolean
 */
function isTwiterPage() {
	$qString = trim(Dispatcher::setPath(), '/');
	return !!preg_match('/^t(\/?page=\d+)*/',$qString);
}

/**
 * @des 检测是否为网站首页（首页包括分页的情况也被认为是首页）
 * @param null
 * @return boolean
 */
function isWebIndex() {
	$qString = trim(Dispatcher::setPath(), '/');
	return !!(!$qString || preg_match('/^\?page=\d+/',$qString) || preg_match('/^page\/\d+/',$qString) || preg_match('/^\?keyword=.*/',$qString));
}

/**
 * @des 检测网站相关配置项并传递给前端js调用
 * @param null
 * @return array
 */
function isVerfy() {
	global $CACHE; 
	$Options = $CACHE->readCache('options');
	//dump($Options);
	$verfy = array('isOpenComment'=>'false','isCommentCode'=>'false','isCommentCheck'=>'false','isOpenTwitter'=>'false','isOpenTwitterReply'=>'false','isTwiterCode'=>'false','isTwiterCheck'=>'false');
	if($Options['iscomment']=='y') {//是否开启文章评论
		$verfy['isOpenComment'] = 'true';
	}
	if($Options['comment_code']=='y') {//文章评论是否需要输入验证码
		$verfy['isCommentCode'] = 'true';
	}
	if($Options['ischkcomment']=='y') {//文章是否审核评论后显示
		$verfy['isCommentCheck'] = 'true';
	}
	if($Options['istwitter']=='y') {//是否前台开启碎语
		$verfy['isOpenTwitter'] = 'true';
	}
	if($Options['istreply']=='y') {//是否前台开启碎语回复
		$verfy['isOpenTwitterReply'] = 'true';
	}
	if($Options['reply_code']=='y') { //碎语是否开启回复验证码
		$verfy['isTwiterCode'] = 'true';
	}
	if($Options['ischkreply']=='y') {//碎语护肤是否需要审核
		$verfy['isTwiterCheck'] = 'true';
	}	
	return $verfy;
}

/**
 * @des 判断用户是否登录
 * @param null
 * @return boolean
 */
function isUserLogin() {
	if(ROLE == 'admin' || ROLE == 'writer') {
		return true;
	}
	return false;
}

/**
 * @des emoji 标签处理评论并输出
 * @param $str 评论数据
 * @return string
 */
function comment2emoji($str) {
	$data = array(
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e056.png',
			'title'=>'可爱'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e057.png',
			'title'=>'开心'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e414.png',
			'title'=>'害羞'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e402.png',
			'title'=>'奸笑'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e106.png',
			'title'=>'色'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e417.png',
			'title'=>'亲亲'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e108.png',
			'title'=>'流汗'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e403.png',
			'title'=>'惆怅'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e058.png',
			'title'=>'伤心'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e40b.png',
			'title'=>'衰'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e411.png',
			'title'=>'大哭'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e410.png',
			'title'=>'混乱'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e107.png',
			'title'=>'恐怖'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e059.png',
			'title'=>'生气'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e408.png',
			'title'=>'瞌睡'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e10c.png',
			'title'=>'外星人'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e022.png',
			'title'=>'爱心'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e00e.png',
			'title'=>'强悍'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e421.png',
			'title'=>'鄙视'
		),
		array(
			'img'=>TEMPLATE_URL.'images/emoji/e011.png',
			'title'=>'胜利'
		)
	);
	foreach($data as $key=>$value) {
		$str = str_replace('['.$value['title'].']','<img src="'.$value['img'].'" title="'.$value['title'].'">',$str);
	}
	return $str;
}