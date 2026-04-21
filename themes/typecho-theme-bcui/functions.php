<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

function themeConfig($form)
{
    $logoUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'topImage',
        null,
        null,
        _t('顶栏图片'),
        _t('你可以在此处填写顶栏图片的链接')
    );

    $form->addInput($logoUrl);

    $sidebarBlock = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'sidebarBlock',
        [
            'ShowRecentPosts'    => _t('显示最新文章'),
            'ShowRecentComments' => _t('显示最近回复'),
            'ShowCategory'       => _t('显示分类'),
            'ShowArchive'        => _t('显示归档'),
            'ShowOther'          => _t('显示其它杂项')
        ],
        ['ShowRecentPosts', 'ShowRecentComments', 'ShowCategory', 'ShowArchive', 'ShowOther'],
        _t('侧边栏显示')
    );

    $form->addInput($sidebarBlock->multiMode());

    $debug = new \Typecho\Widget\Helper\Form\Element\Radio(
        'debug',
        [
            '0' => _t('关闭'),
            '1' => _t('开启')
        ],
        '0',
        _t('调试模式'),
        _t('开启调试开发模式')
    );

    $form->addInput($debug);
}

/*
function themeFields($layout)
{
    $logoUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoUrl',
        null,
        null,
        _t('站点LOGO地址'),
        _t('在这里填入一个图片URL地址, 以在网站标题前加上一个LOGO')
    );
    $layout->addItem($logoUrl);
}
*/
