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

    <link rel="stylesheet" href="<?php $this->options->themeUrl('src/bootstrap/css/bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('src/font-awesome/css/font-awesome.min.css')?>">

    <!-- Border Card UI -->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('src/Border-Card-UI/css/style.css')?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('src/Border-Card-UI/css/color-light.css')?>">

    <link rel="stylesheet" href="<?php $this->options->themeUrl('src/css/index.css')?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('src/css/article.css')?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('src/css/others.css')?>">

    <link rel="stylesheet" href="<?php $this->options->themeUrl('src/Border-Card-UI/css/prism-light.css')?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('src/css/color/append-light.css')?>">
    
    <script src="<?php $this->options->themeUrl('src/js/index.js')?>"></script>

    <!-- 通过自有函数输出HTML头部信息 -->
    <?php $this->header(); ?>
</head>

<script>
    is_auto_dark = ('<?php Typecho_Widget::widget('Widget_Options')->darkmode(); ?>' === 'auto');
    const themeColor = '<?php Typecho_Widget::widget('Widget_Options')->color(); ?>' === '' ? '0' : '<?php Typecho_Widget::widget('Widget_Options')->color(); ?>'
    document.documentElement.style.setProperty('--color-main', 'var(--color-main-' + <?php Typecho_Widget::widget('Widget_Options')->color(); ?> + ')');
</script>

<body class="line-numbers">

<nav id="nav" class="container-lg navbar navbar-expand-lg navbar-dark fixed-top nav-bar">
    <div id="main-bar" class="container-lg main-bar">
        <a class="navbar-brand" href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title() ?></a>
        <button class="navbar-toggler" id="navbarNavAltMarkupButton" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <svg style="background-image: none;color: var(--color-font);" class="navbar-toggler-icon" xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30'><path stroke='currentColor' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'></path></svg>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav mr-auto">
                <a class="nav-item nav-link <?php if ($this->is('index')): ?>active<?php endif; ?>"
                    href="<?php $this->options->siteUrl(); ?>"><?php _e('主页'); ?></a>
                <?php Widget\Metas\Category\Rows::alloc()->to($category); ?>
                <?php while ($category->next()): ?>
                    <a class="nav-item nav-link <?php if ($this->is('category', $category->slug)): ?>active<?php endif; ?>"
                        href="<?php $category->permalink(); ?>"><?php $category->name(); ?></a>
                <?php endwhile; ?>
                <?php \Widget\Contents\Page\Rows::alloc()->to($pages); ?>
                <?php while ($pages->next()): ?>
                    <a class="nav-item nav-link <?php if ($this->is('page', $pages->slug)): ?>active<?php endif; ?>"
                        href="<?php $pages->permalink(); ?>"><?php $pages->title(); ?></a>
                <?php endwhile; ?>
            </div>
            <form class="form-inline my-2 my-lg-0" id="search" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
                <input class="form-control mr-sm-2 bar-search" type="text" id="s" name="s" class="text" placeholder="搜索" aria-label="搜索">
                <div id="user-avatar" class="avatar" title="<?php $this->user->hasLogin() ? $this->user->screenName() : '登录'; ?>" onclick="jumpTo('<?php $this->options->adminUrl(); ?>', true)">
                <?php if($this->user->hasLogin()): ?>
                    <?php echo $this->user->gravatar(330, 'G', NULL, NULL) ?>
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>
                <?php endif; ?>
                    <span id="user-name" class="user-name"><?php if($this->user->hasLogin()) $this->user->screenName(); else echo '登录'; ?></span>
                </div>
            </form>
        </div>
    </div>
    <?php if ($this->is('post')): ?>
    <!-- 文章页面才会有的文章副标题栏 -->
    <div class="container-lg article-controller" id="article-bar">
        <a class="navbar-brand" href="#body">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M480 32H128C110.3 32 96 46.33 96 64v336C96 408.8 88.84 416 80 416S64 408.8 64 400V96H32C14.33 96 0 110.3 0 128v288c0 35.35 28.65 64 64 64h384c35.35 0 64-28.65 64-64V64C512 46.33 497.7 32 480 32zM272 416h-96C167.2 416 160 408.8 160 400C160 391.2 167.2 384 176 384h96c8.836 0 16 7.162 16 16C288 408.8 280.8 416 272 416zM272 320h-96C167.2 320 160 312.8 160 304C160 295.2 167.2 288 176 288h96C280.8 288 288 295.2 288 304C288 312.8 280.8 320 272 320zM432 416h-96c-8.836 0-16-7.164-16-16c0-8.838 7.164-16 16-16h96c8.836 0 16 7.162 16 16C448 408.8 440.8 416 432 416zM432 320h-96C327.2 320 320 312.8 320 304C320 295.2 327.2 288 336 288h96C440.8 288 448 295.2 448 304C448 312.8 440.8 320 432 320zM448 208C448 216.8 440.8 224 432 224h-256C167.2 224 160 216.8 160 208v-96C160 103.2 167.2 96 176 96h256C440.8 96 448 103.2 448 112V208z"/></svg>
            <?php $this->title() ?>
        </a>
        <div class="post-controller">
            <svg onclick="changeContent()" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M0 96C0 78.33 14.33 64 32 64H416C433.7 64 448 78.33 448 96C448 113.7 433.7 128 416 128H32C14.33 128 0 113.7 0 96zM64 256C64 238.3 78.33 224 96 224H480C497.7 224 512 238.3 512 256C512 273.7 497.7 288 480 288H96C78.33 288 64 273.7 64 256zM416 448H32C14.33 448 0 433.7 0 416C0 398.3 14.33 384 32 384H416C433.7 384 448 398.3 448 416C448 433.7 433.7 448 416 448z"/></svg>
            <svg onclick="document.documentElement.scrollTop = 0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M54.63 246.6L192 109.3l137.4 137.4C335.6 252.9 343.8 256 352 256s16.38-3.125 22.62-9.375c12.5-12.5 12.5-32.75 0-45.25l-160-160c-12.5-12.5-32.75-12.5-45.25 0l-160 160c-12.5 12.5-12.5 32.75 0 45.25S42.13 259.1 54.63 246.6zM214.6 233.4c-12.5-12.5-32.75-12.5-45.25 0l-160 160c-12.5 12.5-12.5 32.75 0 45.25s32.75 12.5 45.25 0L192 301.3l137.4 137.4C335.6 444.9 343.8 448 352 448s16.38-3.125 22.62-9.375c12.5-12.5 12.5-32.75 0-45.25L214.6 233.4z"/></svg>
        </div>
    </div>
    <div class="nav-controller" onmouseover="barController()">
        <div id="nav-controller"></div>
    </div>
    <?php endif; ?>
</nav>

<div class="body">
    <?php
        // 获取页面类型，以及页面类型的参考见： /var/Widget/Archive.php@L1606、L617
        // 主要是 is 不能用于判断 404 ……
    ?>
    <?php if (!$this->is('post') && !$this->is('category') &&
                $this->parameter->type != 404): ?>
    <div class="top-bar" style="background: url(<?php Typecho_Widget::widget('Widget_Options')->bg(); ?>) center;">
        <div></div>
        <div>
            <div class="top-bar-title">
                <p id="title">
                    <?php
                        if($this->is('page')) {
                            echo $this->title();
                        } else {
                            echo $this->options->title();
                        }
                    ?>
                    </p>
                <span id="ana" data-ana="<?php Typecho_Widget::widget('Widget_Options')->ana(); ?>"></span>
            </div>
        </div>
    </div>
    <?php endif ?>
    <div class="container-lg" style="padding: 0;">