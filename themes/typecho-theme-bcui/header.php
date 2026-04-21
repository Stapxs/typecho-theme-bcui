<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php $this->archiveTitle([
            'category' => _t('分类 %s 下的文章'),
            'search'   => _t('包含关键字 %s 的文章'),
            'tag'      => _t('标签 %s 下的文章'),
            'author'   => _t('%s 发布的文章')
        ], '', ' - '); ?><?php $this->options->title(); ?></title>

    <!-- Border Card UI -->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('bcui/css/style.css')?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('bcui/css/color-light.css')?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('bcui/css/prism-light.css')?>">

    <!-- 通过自有函数输出HTML头部信息 -->
    <?php $this->header(); ?>
</head>

<script>
    window.__TITLE__ = <?= json_encode($this->options->title); ?>;

    is_auto_dark = ('<?php Typecho_Widget::widget('Widget_Options')->darkmode(); ?>' === 'auto')
    // const themeColor = '<?php Typecho_Widget::widget('Widget_Options')->color(); ?>' === '' ? '0' : '<?php Typecho_Widget::widget('Widget_Options')->color() ?>'
    // document.documentElement.style.setProperty('--color-main', 'var(--color-main-' + <?php Typecho_Widget::widget('Widget_Options')->color(); ?> + ')')
</script>

<header>
    <div id="header-view" class="header-view"></div>
</header>

<body>
