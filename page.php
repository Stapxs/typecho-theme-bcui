<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<?php // 既然作为一个单页模板，就得够干净 …… 不需要有评论啥的 XD ?>

<link rel="stylesheet" href="<?php $this->options->themeUrl('src/css/page.css'); ?>">

<div class="page-main" id="main" role="main">
    <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
        <div class="post-content article-main" itemprop="articleBody">
            <?php $this->content(); ?>
        </div>
    </article>
    <?php // $this->need('comments.php'); ?>
</div><!-- end #main-->

<?php $this->need('footer.php'); ?>
