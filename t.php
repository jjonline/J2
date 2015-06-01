<?php 
/**
 * 微语部分
 */
if(!defined('EMLOG_ROOT')) {exit('error!');} 
?>
<section class="container container_section">
    <div class="contentWrap">
        <div class="content">
        <div class="article_position"><i>您的位置：</i><a href="<?php echo BLOG_URL; ?>" title="<?php echo $blogname; ?>"><?php echo $blogname; ?></a> <small>&gt;</small> <a href="<?php echo BLOG_URL; ?>t">微语</a></div>
        <ul class="twiter">
            <?php
            foreach($tws as $val):
            //dump($val);
            $author = $user_cache[$val['author']]['name'];
            $avatar = empty($user_cache[$val['author']]['avatar']) ? 
                        BLOG_URL . 'admin/views/images/avatar.jpg' : 
                        BLOG_URL . $user_cache[$val['author']]['avatar'];
            if(empty($user_cache[$val['author']]['avatar'])) {
               $avatar = empty($user_cache[$val['author']]['mail'])?TEMPLATE_URL.'images/noAvator.jpg':J_getGravatar($user_cache[$val['author']]['mail']);
            }else {
               $avatar =  BLOG_URL . $user_cache[$val['author']]['avatar'];
            }
            $tid = (int)$val['id'];
            $img = empty($val['img']) ? "" : '<a title="查看图片" href="'.BLOG_URL.str_replace('thum-', '', $val['img']).'" target="_blank"><img src="'.BLOG_URL.$val['img'].'" alt="微语配图"/></a>';
            ?> 
            <li class="twiter_list">
                <img src="<?php echo $avatar; ?>" alt="<?php echo $author; ?>" class="twiter_avatar" />
                <p class="twiter_content"><?php echo $val['t'];?></p>
                <?php if(!empty($img)) {echo '<p class="twiter_img">'.$img.'</p>';}?>
                <p class="twiter_info"><span class="twiter_author"><?php echo $author; ?></span><time class="twiter_time"><i class="fa fa-clock-o"></i> <?php if(strtotime($val['date'])) {echo timeago(strtotime($val['date']));}else{echo $val['date'];}?></time><span class="twiter_reply_btn"><i class="fa fa-reply-all"></i> <a href="javascript:loadr('<?php echo DYNAMIC_BLOGURL; ?>?action=getr&tid=<?php echo $tid;?>','<?php echo $tid;?>');">回复(<span id="rn_<?php echo $tid;?>"><?php echo $val['replynum'];?></span>)</a></p>            
            <?php if ($istreply == 'y'):?>
            <div class="huifu" id="rp_<?php echo $tid;?>">
                <ul id="r_<?php echo $tid;?>" class="r twiter_chirldren"></ul>
                <p class="msg"><span id="rmsg_<?php echo $tid; ?>">回复微语：</span></p>
            	<div class="twiter_reply_ipt_area"><textarea id="rtext_<?php echo $tid; ?>" placeholder="说点什么吧~" title="说点什么吧~"></textarea></div>

                <p class="tinfo">
                    <font style="display:<?php if(ROLE == ROLE_ADMIN || ROLE == ROLE_WRITER){echo 'none';}?>">
                        <span class="twiter_reply_ipt_name">昵称：<input type="text" id="rname_<?php echo $tid; ?>" value="" /></span>
                        <span class="twiter_reply_ipt_code" style="display:<?php if($reply_code == 'n'){echo 'none';}?>">验证码：<input type="text" id="rcode_<?php echo $tid; ?>" value="" /><?php echo $rcode; ?></span>
                    </font>
                    <button class="button_p" type="button" onclick="reply('<?php echo DYNAMIC_BLOGURL; ?>index.php?action=reply',<?php echo $tid;?>);"><i class="fa fa-check-circle-o"></i> 提交回复</button>     
                </p>
            </div>
            <?php endif;?>
            
            </li>
            <?php endforeach;?>
        </ul>
        <?php if(!empty($pageurl)) { echo '<div class="page twiter_page">'.$pageurl.'</div>';} ?>
        </div><!--end content-->
    </div><!--end contentWrap-->
<?php
 include View::getView('side');
?>
</section>
<?php
 include View::getView('footer');
?>