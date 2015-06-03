<?php 
/**
 * 站点首页模板
 */
if(!defined('EMLOG_ROOT')) {exit('error!');} 
?>
<?php doAction('index_loglist_top'); ?>
<section class="container container_section"><!--begin log list-->
	<div class="contentWrap">
		<div class="content">
		<?php 
		if (!empty($logs)):
		if(isWebIndex() && empty($keyword)) {
			//首页显示
			echo topLog($Log_Model);//J2首页逻辑下的置顶 最多4条数据
			echo '<h2 class="article_wrap_title content_title">最近更新</h2>';
		}
		if(!empty($sort)) {
			//栏目页显示
			echo catagTop($Log_Model,$sort);
			$des = $sort['description']?$sort['description']:'这家伙很懒，还没填写该栏目的介绍呢~';
			echo '<div class="catag_wrap_title"><h2 class="catat_title">'.$sortName.'</h2><p>'.$des.'</p></div>';
		}
		if(!empty($record)) {
			//日期记录
			$year    = substr($record,0,4);
			$month   = ltrim(substr($record,4,2),'0');
			$day     = substr($record,6,2);
			$archive = $day?$year.'年'.$month.'月'.ltrim($day,'0').'日':$year.'年'.$month.'月';
			echo '<div class="catag_wrap_title"><h2 class="catat_title">日志归档</h2><p>'.$archive.'发布的文章</p></div>';
			//echo '<h2 class="article_wrap_title content_title">日志归档（'.$archive.'发布的文章）</h2>';
		}
		if(!empty($author_name)) {
			//作者日志显示
			echo '<div class="catag_wrap_title"><h2 class="catat_title">作者</h2><p>本站作者 <strong>'.$author_name.'</strong> 共计发布文章'.$lognum.'篇</p></div>';
		}
		if(!empty($keyword)) {
			//搜索
			echo '<div class="catag_wrap_title"><h2 class="catat_title">站内搜索</h2><p>本次搜索帮您找到有关 <strong>'.$keyword.'</strong> 的结果'.$lognum.'条</p></div>';
		}
		if(!empty($tag)) {
			//关键词
			echo '<div class="catag_wrap_title"><h2 class="catat_title">标签关键词</h2><p>关于 <strong>'.$tag.'</strong> 的文章共有'.$lognum.'条</p></div>';
		}
		$i = 0;//置顶最多显示4条 过滤列表中的重复项
		foreach($logs as $key=>$value):
		if(isWebIndex() && empty($keyword)) {
			if($value['top']=='y' && $i<4) {$i++;continue;}
		}else if(!empty($sort)){
			if($value['sortop']=='y' && $i<4) {$i++;continue;}
		}
		?>
			<article class="article_exp article_exp_<?php echo ($key+1);?>">
				<a href="<?php echo $value['log_url']; ?>" class="article_exp_img" target=_blank><img src="<?php echo getLogImageAtt($value['logid']);?>" alt="<?php echo $value['log_title']; ?>" title="<?php echo $value['log_title']; ?>"></a>
				<header class="article_exp_header">
					<h2><?php blog_title_sort($value['logid']); ?><a href="<?php echo $value['log_url']; ?>" title="<?php echo $value['log_title']; ?>" target=_blank><?php echo $value['log_title']; ?></a><?php if($value['top']=='y' || $value['sortop']=='y') {echo ' <i class="fa fa-arrow-up"></i>';}?></h2>
				</header>
				<p class="article_exp_meta">
					<time class="article_exp_time"><i class="fa fa-clock-o"></i> <?php echo timeago($value['date'],true);?></time>
					<span class="article_exp_pv"><i class="fa fa-eye"></i> 阅读(<?php echo $value['views']; ?>)</span>
					<a href="<?php echo $value['log_url']; ?>#comments" class="article_exp_cmt"><i class="fa fa-comments-o"></i> 评论(<?php echo $value['comnum']; ?>)</a>
					<?php editflg($value['logid'],$value['author']); ?>
				</p>
				<div class="article_exp_des"><?php echo preg_replace("/阅读全文&gt;&gt;/",'',strip_tags($value['log_description'])); ?></div>
			</article>
		<?php 
		endforeach;
		else:
		?>
			<h2>未找到</h2>
			<p>抱歉，没有符合您查询条件的结果。</p>
		<?php endif;?>
		<?php
		if(!empty($page_url)) {
			echo '<div class="page article_exp_page">'.$page_url.'</div>';
		}
		?>
	</div><!--end content-->
</div><!--end contentWrap-->
<?php
 include View::getView('side');
?>
</section>
<?php
 include View::getView('footer');
?>