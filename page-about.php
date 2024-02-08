<?php 
/**
 * 关于
 *
 * @package custom
 * @author Stapx Steve [林槐]
 * @version 1.0.0
 * @link https://stapxs.cn
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<?php $this->need('header.php'); ?>

<link rel="stylesheet" href="<?php $this->options->themeUrl('src/css/page.css'); ?>">
<link rel="stylesheet" href="<?php $this->options->themeUrl('src/css/components/about.css'); ?>">

<div class="page-main" id="main" role="main">
    <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
        <div class="post-content article-main" id="about-content" itemprop="articleBody">
            <div id="about-content-main">
            <?php
                // 如果你想自己写这儿的内容，你只要知道：JSON 中对应字段名的内容会被填入到这儿对应 id 的元素内就行了
                // 你可以通过完整的写成 "about_github_card": { "value": "<div>card</div>", "type": "HTML" } 来输出 HTML
            ?>
                <!-- 关于 -->
                <div class="ab-card">
                    <div class="ss-card ab-base">
                        <div id='ab-gravatar'>{ab-gravatar}</div>
                        <p id='ab-name'>{ab-name}</p>
                        <p id='ab-mail'>{ab-mail}</p>
                        <span id='ab-context'>{ab-context}</span>
                        <hr>
                        <div id='ab-gpg'>{ab-gpg}</div>
                    </div>
                    <div class="ss-card says">
                        <header id='ab-hello-title'>{ab-hello-title}</header>
                        <div id='ab-hello'>{ab-hello}</div>
                    </div>
                </div>
            </div>

        </div>
    </article>
</div>

<script> let content = `<?php $this->content(); ?>` </script>
<script src="<?php $this->options->themeUrl('src/js/components/about.js')?>"></script>

<?php $this->need('footer.php'); ?>
