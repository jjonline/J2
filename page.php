<?php 
/*
 * @显示自建页面方法
 * @authors Ajing (JJonline@JJonline.Cn)
 * @date    2014-12-10
 * @version 1.2
 */
if(!defined('EMLOG_ROOT')) {exit('error!');} 
?>
<section class="container container_section">
	<div class="contentWrap">
		<div class="content">
			<div class="article_position"><i>您的位置：</i><a href="<?php echo BLOG_URL; ?>" title="<?php echo $blogname; ?>"><?php echo $blogname; ?></a> <small>&gt;</small> <?php echo $log_title; ?></div>
			<header class="article_header">
				<h1><a href="<?php echo Url::log($logid);?>"><?php echo $log_title;?></a></h1>
				<div class="article_meta">
					<span><i class="fa fa-user"></i> <?php echo blog_author($author); ?></span>
					<span><i class="fa fa-clock-o"></i> <?php echo timeago($date,true);?></span>
					<span><i class="fa fa-eye"></i> <?php echo $views; ?>次浏览</span>
				</div>
			</header>
			<article class="article_content">
				<?php echo $log_content; ?>
				<p>---</p>
				<?php echo blog_att($logid);?>
				<p style="text-indent:0;margin:10px 0 0 0;">转载请注明本文标题和链接：《<a href="<?php echo Url::log($logid);?>"><?php echo $log_title;?></a>》</p>
			</article>
			<footer class="article_footer">
				<?php if(blog_tag($logid)) { echo '<div class="article_tags">'.blog_tag($logid).'</div>'; } ?>
				<div class="article_share bdsharebuttonbox page_share">
					<strong>分享到：</strong>
					<a class="bds_qzone" data-cmd="qzone"></a>
					<a class="bds_tsina" data-cmd="tsina"></a>
					<a class="bds_weixin bdsm" data-cmd="weixin"></a>
					<a class="bds_tqq" data-cmd="tqq"></a>
					<a class="bds_sqq bdsm" data-cmd="sqq"></a>
					<a class="bds_renren" data-cmd="renren"></a>
					<a class="bds_douban" data-cmd="douban"></a>
					<span class="bds_count" data-cmd="count"></span>
				</div>
			</footer>
			<div class="article_post_comment" id="comment-place">
				<?php blog_comments_post($logid,$ckname,$ckmail,$ckurl,$verifyCode,$allow_remark); ?>
			</div>
			<?php 
			if(isShowComment($comnum)) {
				echo '<h3 class="comment-header">网友评论<b>（'.$comnum.'）</b></h3>';
				echo '<a name="comments"></a>';
				echo '<div class="article_comment_list">';
			}
			?>
			<?php blog_comments($comments,$comnum); ?>
			<?php
			if(isShowComment($comnum)) {
				echo '</div>';
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