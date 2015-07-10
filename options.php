<?php
/*@support tpl_options*/
!defined('EMLOG_ROOT') && exit('access deined!');
$options         =   array(
    'logo'      =>  array(
        'type'          => 'image',
        'name'          => '网站Logo图标',
        'values'        => array(
            TEMPLATE_URL . 'images/logo.png',
        ),
        'description'   =>  '请参考主题包中images目录下的logo.psd制作150px*52px大小的Logo图片上传替换即可',
    ),
    'qq_wb'      =>  array(
        'type'          =>  'text',
        'name'          =>  '腾讯微博',
        'default'       =>  'http://t.qq.com/jjonline',
        'description'   =>  '设置你的腾讯微薄地址',
    ),
    'sina_wb'   =>  array(
        'type'          =>  'text',
        'name'          =>  '新浪微博',
        'default'       =>  'http://weibo.com/511456119',
        'description'   =>  '设置你的新浪微薄地址',
    ),
    'corp_pos'  =>  array(
        'type'          =>  'radio',
        'name'          =>  '文章附图裁剪位置',
        'default'       =>  1,//0居中开始裁剪 1左上角开始裁剪 2右上角开始裁剪  3左下角开始裁剪 4右下角开始裁剪
        'values'        =>  array(
            0   =>  '居中开始裁剪',
            1   =>  '左上角开始裁剪',
            2   =>  '右上角开始裁剪',
            3   =>  '左下角开始裁剪',
            4   =>  '右下角开始裁剪',
        ),
        'description'   =>  '文章附图自动裁剪开始的位置，文章中的大图裁剪成220px*150px时的开始位置',
    ),
    'up_cache'  =>  array(
        'type'          =>  'radio',
        'name'          =>  '附图自动更新',
        'default'       =>  0,
        'values'        =>  array(
            0   =>  '不自动更新',
            1   =>  '自动更新',
        ),
        'description'   =>  '文章附图的缓存是否每隔12小时后自动检测并更新，启用会适当加重服务器负载',
    ),
    'open_rdm'  =>  array(
        'type'          =>  'radio',
        'name'          =>  '启用随机默认附图',
        'default'       =>  1,
        'values'        =>  array(
            0   =>  '不启用随机默认附图',
            1   =>  '启用随机默认附图',
        ),
        'description'   =>  '当文章中没有图片可以用于裁剪时是否启用随机的默认附图，自定义的默认待选附图请上传图片至：<br/>./content/templates/J2/images/randoms/ 目录下<br/>图片大小最佳为220px*150px，名称任意但不要使用中文。',
    ),
    'is_preg'           =>  array(
        'type'          =>  'radio',
        'name'          =>  '图片标签修正处理',
        'default'       =>  1,
        'values'        =>  array(
            0   =>  '不修正',
            1   =>  '修正',
        ),
        'description'   =>  '文章中的图片标签默认被p和a标签包裹，不利于自适应，修正处理后将去掉包裹的p和a标签<br/>若需保留点击图片在新页面打开的功能，请点选“不修正”',
    ),
);