<?php 
/**
 * 友链
 *
 * @package custom
 * @author Stapx Steve [林槐]
 * @version 1.0.0
 * @link https://stapxs.cn
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<?php $this->need('header.php'); ?>

<link rel="stylesheet" href="<?php $this->options->themeUrl('src/css/page.css'); ?>">

<div class="page-main" id="main" role="main">
    <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
        <div class="post-content article-main" itemprop="articleBody">
            <?php $this->content(); ?>
        </div>
    </article>
</div>

<script src="<?php $this->options->themeUrl('src/js/components/links.js')?>"></script>

<?php $this->need('footer.php'); ?>
