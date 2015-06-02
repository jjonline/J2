<?php 
/*
 * @模板控制器方法集合
 * @authors Ajing (JJonline@JJonline.Cn)
 * @date    2014-12-07
 * @version $Id$
 */
if(!defined('EMLOG_ROOT')) {exit('error!');} 
require_once('functions.php');
?>
<?php
//widget：blogger
function widget_blogger($title){
	global $CACHE;
	$user_cache = $CACHE->readCache('user');
	$name = '<p class="J_bloger"><i>笔名</i>： '.$user_cache[1]['name'].'</p>';
	if(!empty($user_cache[1]['mail'])) {
		/**处理邮箱地址 防止自动爬虫抓取后发送垃圾邮件**/
		$mailArr = explode('@',$user_cache[1]['mail']);
		if(is_array($mailArr)) { 
			$name .='<p class="J_bloger"><i>联系</i>： '.$mailArr[0].'<script type="text/javascript">document.write("@")</script>'.$mailArr[1].'</p>';
		}
	}
	?>
	<div class="widget widget_blogger">
		<h3><?php echo $title; ?></h3>
		<div class="widget_blogger_content">
			<?php if (!empty($user_cache[1]['photo']['src'])): /*此处使用了后台上传的原图，最佳建议300px的正方形头像*/?>
			<div class="widget_bloger_img">
				<img src="<?php echo BLOG_URL.str_replace('thum-', '', $user_cache[1]['photo']['src']); ?>" alt="blogger" />
			</div>
			<?php endif;?>
			<?php echo $name; ?>
			<p class="J_bloger"><i>简介</i>： <?php echo $user_cache[1]['des']; ?></p>
		</div>
	</div>
<?php }?>
<?php
//widget：日历
function widget_calendar($title){ ?>
	<div class="widget widget_calendar">
		<h3><i class="fa fa-calendar"></i> <?php echo $title; ?></h3>
		<div class="J_calendar" id="calendar">
		</div>
		<script>sendinfo('<?php echo Calendar::url(); ?>','calendar');</script>
	</div>
<?php }?>
<?php
//widget：标签
function widget_tag($title){
	global $CACHE;
	$tag_cache = $CACHE->readCache('tags');
	$tag_cache = array_multi_sort($tag_cache,'usenum');
	?>
	<div class="widget widget_tag">
		<h3><?php echo $title; ?></h3>
		<div class="J_tags">
		<?php if(!$tag_cache) {echo '暂无关键词';} ?>
		<?php $count=0; foreach($tag_cache as $value): ?>
			<?php if($count>=40) {break;}  ?>
			<a href="<?php echo Url::tag($value['tagurl']); ?>" title="<?php echo $value['tagname']; ?>" rel="nofollow"><?php echo $value['tagname']; ?>(<?PHP echo $value['usenum'];?>)</a>
			<?php $count++; ?>
		<?php endforeach; ?>
		</div>
	</div>
<?php }?>
<?php
//widget：分类
function widget_sort($title){
	global $CACHE;
	$sort_cache = $CACHE->readCache('sort'); ?>
	<div class="widget widget_sort">
		<h3><?php echo $title; ?></h3>
		<ul class="widget_sort_parent">
			<?php
			foreach($sort_cache as $value):
				if ($value['pid'] != 0) continue;
			?>
			<li>
			<i class="fa fa-folder"></i> <a href="<?php echo Url::sort($value['sid']); ?>"><?php echo $value['sortname']; ?>(<?php echo $value['lognum'] ?>)</a>
				<?php if (!empty($value['children'])): ?>
					<ul class="widget_sort_chirdren">
						<?php
						$children = $value['children'];
						foreach ($children as $key):
							$value = $sort_cache[$key];
						?>
						<li>
						<i class="fa fa-folder-open"></i> <a href="<?php echo Url::sort($value['sid']); ?>"><?php echo $value['sortname']; ?>(<?php echo $value['lognum'] ?>)</a>
						</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php }?>
<?php
//widget：最新微语
function widget_twitter($title){
	global $CACHE; 
	$newtws_cache = $CACHE->readCache('newtw');
	$istwitter = Option::get('istwitter');
	?>
	<div class="widget widget_twitter">
		<h3><?php echo $title; ?></h3>
		<div class="widget_content">
			<?php foreach($newtws_cache as $value): ?>
			<?php $img = empty($value['img']) ? "" : ' <a title="查看图片" class="t_img" href="'.BLOG_URL.str_replace('thum-', '', $value['img']).'" target="_blank"><i class="widget_twitter_image fa fa-image"></i></a>';?>
			<p><i class="fa fa-bullhorn"></i> <?php echo $value['t']; ?><?php echo $img;?> <time><?php echo timeago($value['date']); ?></time></p>
			<?php endforeach; ?>
		</div>
	</div>
<?php }?>
<?php
//widget：最新评论
function widget_newcomm($title){
	global $CACHE; 
	$com_cache = $CACHE->readCache('comment');
	?>
	<div class="widget widget_newcomm">
		<h3><?php echo $title; ?></h3>
		<ul class="widget_newcomm_ul">
			<?php
			foreach($com_cache as $value):
			$url = Url::comment($value['gid'], $value['page'], $value['cid']);
			?>
			<li>
				<a href="<?php echo $url; ?>">
					<img src="<?php echo J_getGravatar($value['mail']); ?>" class="avator">
					<?php echo $value['name']; ?>
					<span class="muted">
						<?php echo timeago($value['date']); ?>说：
						<br/>
						<?php echo comment2emoji($value['content']);?>
					</span>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php }?>
<?php
//widget：最新文章
function widget_newlog($title){
	global $CACHE; 
	$newLogs_cache = $CACHE->readCache('newlog');
	?>
	<div class="widget widget_newlog widget_log_list">
		<h3><span><?php echo $title; ?></span></h3>
		<ul class="J_log_list">
			<?php foreach($newLogs_cache as $value): ?>
			<li><i class="fa fa-th-list"></i> <a href="<?php echo Url::log($value['gid']); ?>" title="<?php echo $value['title']; ?>"><?php echo $value['title']; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php }?>
<?php
//widget：热门文章
function widget_hotlog($title){
	$index_hotlognum = Option::get('index_hotlognum');
	$Log_Model = new Log_Model();
	$randLogs = $Log_Model->getHotLog($index_hotlognum);?>
	<div class="widget widget_hotlog widget_log_list">
		<h3><?php echo $title; ?></h3>
		<ul class="J_log_list">
			<?php foreach($randLogs as $value): ?>
			<li><i class="fa fa-list-ul"></i> <a href="<?php echo Url::log($value['gid']); ?>" title="<?php echo $value['title']; ?>"><?php echo $value['title']; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php }?>
<?php
//widget：随机文章
function widget_random_log($title){
	$index_randlognum = Option::get('index_randlognum');
	$Log_Model = new Log_Model();
	$randLogs = $Log_Model->getRandLog($index_randlognum);?>
	<div class="widget widget_random_log widget_log_list">
		<h3><span><?php echo $title; ?></span></h3>
		<ul class="J_log_list">
			<?php foreach($randLogs as $value): ?>
			<li><i class="fa fa-list"></i> <a href="<?php echo Url::log($value['gid']); ?>" title="<?php echo $value['title']; ?>"><?php echo $value['title']; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php }?>
<?php
//widget：搜索
function widget_search($title){ ?>
	<div class="widget widget_search">
		<div class="widget_content">
			<form method="get" action="<?php echo BLOG_URL; ?>index.php" class="widget_search_form">
				<input name="keyword" class="widget_search_input" type="text" placeholder="输入关键字搜索" autocomplete="off"/>
				<button class="widget_search_btn" type="submit"><i class="fa fa-search"></i></button>
			</form>
		</div>
	</div>
<?php } ?>
<?php
//widget：归档
function widget_archive($title){
	global $CACHE; 
	$record_cache = $CACHE->readCache('record');
	?>
	<div class="widget widget_archive">
		<h3><?php echo $title; ?></h3>
		<div class="J_archive">
		<?php foreach($record_cache as $value): ?>
			<span><i class="fa fa-archive"></i> <a href="<?php echo Url::record($value['date']); ?>"><?php echo $value['record']; ?>(<?php echo $value['lognum']; ?>)</a></span>
		<?php endforeach; ?>
		</div>
	</div>
<?php } ?>
<?php
//widget：自定义组件
function widget_custom_text($title, $content){ ?>
	<div class="widget widget_custom">
		<h3><?php echo $title; ?></h3>
		<div class="widget_custom_content">
			<?php echo $content; ?>
		</div>
	</div>
<?php } ?>
<?php
//widget：链接
function widget_link($title){
	global $CACHE; 
	$link_cache = $CACHE->readCache('link');
    //if (!blog_tool_ishome()) return;#只在首页显示友链去掉双斜杠注释即可
	?>
	<div class="widget widget_link">
		<h3><?php echo $title; ?></h3>
		<div class="J_link">
		<?php foreach($link_cache as $value): ?>
			<span><i class="fa fa-link"></i> <a href="<?php echo $value['url']; ?>" title="<?php echo $value['des']; ?>" target="_blank"><?php echo $value['link']; ?></a></span>
		<?php endforeach; ?>
		</div>
	</div>
<?php }?>
<?php
/**
 * @des 文章导航处理方法
 * @param $logData 文章数据 查看博文是存在数据
 * @param $logs 文章数据数组 分类下或首页存在
 * @return string
 */
function blog_navi($logData=null,$logs=null){
	global $CACHE; 
	$navi_cache    = $CACHE->readCache('navi');/*后台设置的导航栏显示数据*/
	$navi_info     = getNowPageSortUrl($logData,$logs);
	// dump($navi_info);
	?>
	<ul class="nav">
	<?php
	foreach($navi_cache as $value):
        if ($value['pid'] != 0) {
            continue;
        }
        /*前台判定输出管理链接*/
		if($value['url'] == ROLE_ADMIN && (ROLE == ROLE_ADMIN || ROLE == ROLE_WRITER)):
			?>
			<li class="item common" id="admin">
				<a href="<?php echo BLOG_URL; ?>admin/">管理站点</a>
				<ul class="navChirld">
					<li class="itemChirld common"><a href="<?php echo BLOG_URL; ?>admin/twitter.php">发布碎语</a></li>
					<li class="itemChirld common"><a href="<?php echo BLOG_URL; ?>admin/write_log.php">发布文章</a></li>
					<li class="itemChirld common"><a href="<?php echo BLOG_URL; ?>admin/?action=logout">退出系统</a></li>
				</ul>
			</li>
			<?php 
			continue;
		endif;

		$newtab       = $value['newtab'] == 'y' ? 'target="_blank"' : '';
        $value['url'] = $value['isdefault'] == 'y' ? BLOG_URL . $value['url'] : trim($value['url'], '/');
        $current_tab  = $navi_info['parentSortUrl'] == $value['url'] ? 'current' : 'common';

		?>
		<li class="item <?php echo $current_tab;?>">
			<a href="<?php echo $value['url']; ?>" <?php echo $newtab;if(BLOG_URL.'admin'==$value['url']){echo ' id="admin"';}?>><?php echo $value['naviname']; ?></a>
			<?php if (!empty($value['children'])) :?>
            <ul class="navChirld">
                <?php foreach ($value['children'] as $row){
                		$chirdUrl     = Url::sort($row['sid']);
                		$current_tab  = $navi_info['chirldSortUrl'] == $chirdUrl ? 'currentChirld' : 'commonChirld';
                        echo '<li class="itemChirld '.$current_tab.'"><a href="'.$chirdUrl.'">'.$row['sortname'].'</a></li>';
                }?>
			</ul>
            <?php endif;?>

            <?php if (!empty($value['childnavi'])) :?>
            <ul class="navChirld">
                <?php foreach ($value['childnavi'] as $row){
                        $newtab = $row['newtab'] == 'y' ? 'target="_blank"' : '';
                        echo '<li class="itemChirld"><a href="' . $row['url'] . "\" $newtab >" . $row['naviname'].'</a></li>';
                }?>
			</ul>
            <?php endif;?>

		</li>
	<?php endforeach; ?>
	</ul>
<?php }?>
<?php
/**
 * @des 首页置顶数据 读取首页置顶的两条数据 用于首页常显
 * @param Log_Model 日志数据操作句柄
 * @return string 
 */
function topLog($Log_Model){
	if(!isWebIndex()) {return '';}
	//读取首页置顶的两条数据 用于首页常显
	$logs        = $Log_Model->getLogsForHome('ORDER BY `top` DESC ,`date` DESC',1,4);
	if(empty($logs)) {return '';}
	$toplog      = '';
	foreach ($logs as $key => $value) { //logid top log_url log_title
		if($value['top']!='y') {continue;}
		$isLeft  = (($key)%2)<1?'article_top_left':'article_top_right';
		$toplog .= '<li class="'.$isLeft.'"><a href="'.$value['log_url'].'" title="'.$value['log_title'].'"><img src="'.getLogImageAtt($value['logid']).'" alt="'.$value['log_title'].'"><h2>'.$value['log_title'].'</h2><p>'.preg_replace("/阅读全文&gt;&gt;/",'',strip_tags($value['log_description'])).'</p></a></li>';
	};
	if(!empty($toplog)) {
		return '<h2 class="article_wrap_title top_title">置顶推荐</h2><div class="article_top_content"><ul class="article_top">'.$toplog.'</ul></div>';
	}
	return '';
}
?>
<?php
/**
 * @des 栏目页用于显示置顶的方法 常显
 * @param Log_Model 日志数据操作句柄
 * @param sort 栏目分类信息数组
 * @return string 
 */
function catagTop($Log_Model,$sort) {
	$sortid = $sort['sid'];
	if ($sort['pid'] != 0 || empty($sort['children'])) {
		$sqlSegment   = "and sortid=$sortid";
	} else {
		$sortids      = array_merge(array($sortid), $sort['children']);
		$sqlSegment   = "and sortid in (" . implode(',', $sortids) . ")";
	}
	$sqlSegment      .=  " order by sortop desc, date desc";
	$logs = $Log_Model->getLogsForHome($sqlSegment, 1, 4);

	if(empty($logs)) {return '';}
	$toplog      = '';
	foreach ($logs as $key => $value) { //logid top log_url log_title
		if($value['sortop']!='y') {continue;}
		$isLeft  = (($key)%2)<1?'article_top_left':'article_top_right';
		$toplog .= '<li class="'.$isLeft.'"><a href="'.$value['log_url'].'" title="'.$value['log_title'].'"><img src="'.getLogImageAtt($value['logid']).'" alt="'.$value['log_title'].'"><h2>'.$value['log_title'].'</h2><p>'.preg_replace("/阅读全文&gt;&gt;/",'',strip_tags($value['log_description'])).'</p></a></li>';
	};
	if(!empty($toplog)) {
		return '<h2 class="article_wrap_title top_title">置顶文章</h2><div class="article_top_content"><ul class="article_top">'.$toplog.'</ul></div>';
	}
	return '';
}
?>
<?php
//blog：编辑
function editflg($logid,$author){
	$editflg = ROLE == ROLE_ADMIN || $author == UID ? '<span><i class="fa fa-edit"></i> <a href="'.BLOG_URL.'admin/write_log.php?action=edit&gid='.$logid.'" target="_blank">编辑</a></span>' : '';
	echo $editflg;
}
?>
<?php
//blog：分类
function blog_sort($blogid){
	global $CACHE; 
	$log_cache_sort = $CACHE->readCache('logsort');
	if(!empty($log_cache_sort[$blogid])) {
		echo '<a href="'.Url::sort($log_cache_sort[$blogid]['id']).'">'.$log_cache_sort[$blogid]['name'].'</a>';
	}else {
		echo '<a href="'.BLOG_URL.'">未分类</a>';
	}
}
?>
<?php
//blog：列表标题分类
function blog_title_sort($blogid){
	global $CACHE; 
	$log_cache_sort = $CACHE->readCache('logsort');
	if(!empty($log_cache_sort[$blogid])) {
		echo '<a href="'.Url::sort($log_cache_sort[$blogid]['id']).'" class="article_triggle">'.$log_cache_sort[$blogid]['name'].'<i></i></a>';
	}else {
		echo '<a href="'.BLOG_URL.'" class="article_triggle">未分类<i></i></a>';
	}
}
?>
<?php
//blog：文章标签
function blog_tag($blogid){
	global $CACHE;
	$log_cache_tags = $CACHE->readCache('logtags');
	if (!empty($log_cache_tags[$blogid])){
		$tag = '继续浏览有关 ';
		foreach ($log_cache_tags[$blogid] as $key=>$value){
			$tag .= ' <a href="'.Url::tag($value['tagurl']).'" class="article_tag article_tag'.$key.'" rel="nofollow">'.$value['tagname'].'</a>';
		}
		return $tag.' 的文章';
	}
	return '';
}
?>
<?php
//blog：文章作者
function blog_author($uid){
	global $CACHE;
	$user_cache = $CACHE->readCache('user');
	$author = $user_cache[$uid]['name'];
	$mail = $user_cache[$uid]['mail'];
	$des = $user_cache[$uid]['des'];
	$title = !empty($mail) || !empty($des) ? "title=\"$des $mail\"" : '';
	echo '<a href="'.Url::author($uid)."\" $title>$author</a>";
}
?>
<?php
//blog：相邻文章
function neighbor_log($neighborLog){
	extract($neighborLog);?>
	<?php if($prevLog):?>
		<span class="article_prev_log">上一篇 <a href="<?php echo Url::log($prevLog['gid']) ?>"><?php echo $prevLog['title'];?></a></span>
	<?php endif;?>
	<?php if($nextLog):?>
		<span class="article_next_log"><a href="<?php echo Url::log($nextLog['gid']) ?>"><?php echo $nextLog['title'];?></a> 下一篇</span>
	<?php endif;?>
<?php }?>
<?php
//blog：评论列表
function blog_comments($comments,$comnum){
    extract($comments);
    if($commentStacks): ?>
	<?php endif; ?>
	<?php
	$isGravatar 	   = Option::get('isgravatar');
	foreach($commentStacks as $cid):
    $comment 		   = $comments[$cid];
	$isNofollow   	   = $comment['url'] && $comment['url'] != BLOG_URL ? 'rel="nofollow"':'';
	$comment['poster'] = $comment['url'] ? '<a href="'.$comment['url'].'" target="_blank" '.$isNofollow.'>'.$comment['poster'].'</a>' : $comment['poster'];
	?>
	<div class="comment dpt_line" id="comment-<?php echo $comment['cid']; ?>">
		<a name="<?php echo $comment['cid']; ?>"></a>
		<?php
			if($isGravatar == 'y') {
				echo '<div class="avatar"><img src="'.TEMPLATE_URL.'images/0.gif" data-src="'.J_getGravatar($comment['mail']).'" /></div>';
			}else {
				echo '<div class="avatar"><img src="'.TEMPLATE_URL.'images/noAvator.jpg" /></div>';
			}
		?>
		<div class="comment-info">
			<div class="comment-content"><?php echo comment2emoji($comment['content']); ?></div>
			<div class="comment-meata"><span class="comment-poster"><?php echo $comment['poster']; ?> </span> <span class="comment-time"><?php if(strtotime($comment['date'])) { echo timeago(strtotime($comment['date']));}else {echo str_replace(' ','',$comment['date']);} ?></span> <a href="#comment-<?php echo $comment['cid']; ?>" onclick="commentReply(<?php echo $comment['cid']; ?>,this)" class="comment-reply-btn">回复</a></div>
		</div>
		<?php blog_comments_children($comments, $comment['children']); ?>
	</div>
	<?php endforeach; ?>
    <div class="page comment-page">
	    <?php echo $commentPageUrl;?>
    </div>
<?php }?>
<?php
//blog：子评论列表
function blog_comments_children($comments, $children){
	$isGravatar = Option::get('isgravatar');
	foreach($children as $child):
	$comment 		   = $comments[$child];
	$isNofollow   	   = $comment['url'] && $comment['url'] != BLOG_URL ? 'rel="nofollow"':'';
	$comment['poster'] = $comment['url'] ? '<a href="'.$comment['url'].'" target="_blank" '.$isNofollow.'>'.$comment['poster'].'</a>' : $comment['poster'];
	?>
	<div class="comment comment-children" id="comment-<?php echo $comment['cid']; ?>">
		<a name="<?php echo $comment['cid']; ?>"></a>
			<?php
				if($isGravatar == 'y') {
					echo '<div class="avatar"><img src="'.TEMPLATE_URL.'images/0.gif" data-src="'.J_getGravatar($comment['mail']).'" /></div>';
				}else {
					echo '<div class="avatar"><img src="'.TEMPLATE_URL.'images/noAvator.jpg" /></div>';
				}
			?>
		<div class="comment-info">
			<div class="comment-content"><?php echo comment2emoji($comment['content']); ?></div>
			<div class="comment-meata">
				<span class="comment-poster"><?php echo $comment['poster']; ?></span> 
				<span class="comment-time"><?php if(strtotime($comment['date'])) { echo timeago(strtotime($comment['date']));}else {echo str_replace(' ','',$comment['date']);} ?></span>
				<?php if($comment['level']<3){ echo '<a href="#comment-'.$comment['cid'].'" onclick="commentReply('.$comment['cid'].',this)" class="comment-reply-btn">回复</a>';}?>
			</div>
		</div>
		<?php blog_comments_children($comments, $comment['children']);?>
	</div>
	<?php endforeach; ?>
<?php }?>
<?php
//blog：发表评论表单
function blog_comments_post($logid,$ckname,$ckmail,$ckurl,$verifyCode,$allow_remark){
	if($allow_remark == 'y'): ?>
	<div class="comment_post_wrap comment_post" id="comment-post">
		<h3 class="comment-header"><span class="cancel-reply" id="cancel-reply" style="display:none;"><a href="javascript:void(0);" onclick="cancelReply()">取消回复</a></span>发表评论<a name="respond"></a></h3>
		<form method="post" name="commentform" action="<?php echo BLOG_URL; ?>index.php?action=addcom" id="commentform">
			<input type="hidden" name="gid" id="comment-gid" value="<?php echo $logid; ?>" />
			<input type="hidden" name="pid" id="comment-pid" value="0"/>
			<div class="form-group form_textarea">
				<div class="comment_textare"><textarea name="comment" id="comment" placeholder="说点什么吧~" title="说点什么吧~"></textarea></div>
				<div class="form-group submit_container">
					<div class="comment_tools">
						<?php 
							if(empty($ckmail)) {
								echo '<span class="comment_avator"><img src="'.TEMPLATE_URL.'images/noAvator.jpg" title="路人甲"><em>路人甲</em></span>';
							}else{
								echo '<span class="comment_avator"><img src="'.J_getGravatar($ckmail).'" title="'.$ckname.'"><em>'.$ckname.'</em></span>';
							}
						?>
						<span class="comment_face_btn"><i class="fa fa-smile-o"></i> 表情</span>
						<div class="comment_submit_wrap">
							<?php if(!empty($verifyCode)) {echo '<span class="comment_verfiy_container"><img src="'.BLOG_URL.'include/lib/checkcode.php" class="c_code" alt="看不清楚？点图切换" title="看不清楚？点图切换"><input type="text" name="imgcode" class="comment_verfiy_code" placeholder="输入验证码" autocomplete="off" title="看不清楚？点图切换"></span>';}; ?>
							<span class="comment_info">Ctrl+Enter快速提交</span>
							<button type="submit" name="submit" id="comment_submit" class="sub_btn"><i class="fa fa-check-circle-o"></i> 提交评论</button>
						</div>
					</div>
				</div>
			</div>
			<?php if(ROLE == ROLE_VISITOR): ?>
			<div class="comment_user_info">
				<div class="form-group">
					<input type="text" id="comname" name="comname" value="<?php echo $ckname; ?>" placeholder="昵称">
					<label for="comname">昵称（必填）</label>
				</div>
				<div class="form-group">
					<input type="text" id="commail" name="commail" value="<?php echo $ckmail; ?>" placeholder="邮箱">
					<label for="commail">邮箱（必填）</label>
				</div>
				<div class="form-group">
					<input type="text" id="comurl" name="comurl" value="<?php echo $ckurl; ?>" placeholder="网址">
					<label for="comurl">网址（选填）</label>
				</div>
			</div>
			<?php endif; ?>
		</form>
	</div>
	<?php endif; ?>
<?php }?>
<?php
//blog-tool:判断是否是首页
function blog_tool_ishome(){
    if (BLOG_URL . trim(Dispatcher::setPath(), '/') == BLOG_URL){
        return true;
    } else {
        return false;
    }
}
?>
<?php
/**
 * @des 获取日志附件 友好显示附件下载情况 
 * @param logid
 * @return string 
 */
function blog_att($logid) {
	return '';//此方法已废除
	global $CACHE;
	$logatts = $CACHE->readCache('logatts');
	if(empty($logatts)) {
		return '';
	}
	if(empty($logatts[$logid])) {
		return '';
	}
	$attArr   = $logatts[$logid];//文章所附带附件的数组信息 可能有多个附件
	$fileType   = array(
		'rar'   =>	'fa fa-file-zip-o',
		'zip'   =>	'fa fa-file-zip-o',
		'gif'   =>	'fa fa-file-image-o',
		'jpg'   =>	'fa fa-file-image-o',
		'jpeg'  =>	'fa fa-file-image-o',
		'png'   =>	'fa fa-file-image-o',
		'txt'   =>	'fa fa-file-text-o',
		'pdf'   =>	'fa fa-file-pdf-o',
		'docx'  =>	'fa fa-file-word-o',
		'doc'   =>	'fa fa-file-word-o',
		'xls'   =>	'fa fa-file-excel-o',
		'xlsx'  =>	'fa fa-file-excel-o',
		'ppt'   =>	'fa fa-file-powerpoint-o',
		'pptx'  =>	'fa fa-file-powerpoint-o'
	);
	$logAttstr  = '<div class="article_att"><h3>附件下载：</h3><ol>';
	foreach ($attArr as $key => $value) {
		$fileSuffix     = getFileSuffix($value['url']);
		$logAttstr     .= '<li><a href="'.BLOG_URL.$value['url'].'" target=_blank>';
		if($fileType[$fileSuffix]) {
			$logAttstr .= '<i class="'.$fileType[$fileSuffix].'"></i> ';
		}else {
			$logAttstr .= '<i class="fa fa-file"></i> ';
		}
		$logAttstr 	   .= $value['filename'].'</a> （文件体积： '.$value['size'].'）</li>';
	}
	return $logAttstr  .= '</ol></div>';
}
?>