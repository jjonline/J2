<?php
/*@support tpl_options*/
!defined('EMLOG_ROOT') && exit('access deined!');
$options = array(
    'qq_wb' => array(
        'type' => 'text',
        'name' => '腾讯微博Url',
        'default' => 'http://t.qq.com/jjonline',
        'description' => '设置你的腾讯微薄地址',
    ),
    'sina_wb' => array(
        'type' => 'text',
        'name' => '新浪微博Url',
        'default' => 'http://weibo.com/511456119',
        'description' => '设置你的新浪微薄地址',
    ),
);