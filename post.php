<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

    <link rel="stylesheet" href="<?php $this->options->themeUrl('src/css/article/view_card.css')?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('src/js/viewerjs/viewer.css')?>">

    <script defer src="<?php $this->options->themeUrl('src/js/katex/katex.min.js'); ?>"></script>
    <script defer src="<?php $this->options->themeUrl('src/js/viewerjs/viewer.js'); ?>"></script>
    <script defer src="<?php $this->options->themeUrl('src/js/katex/auto-render.min.js'); ?>"
            onload="renderMathInElement(document.getElementById('article-main'),
            {delimiters:[{left: '$$', right: '$$', display: true}, {left: '$', right: '$', display: false}]});"></script>

    <script src="<?php $this->options->themeUrl('src/js/util/link_view.js')?>"></script>
    <script src="<?php $this->options->themeUrl('src/js/article.js')?>"></script>
    
    <div id='imageView' class='image-view'></div>

    <!-- 文章主体 -->
    <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
        <div class="main-card">
            <div class="article-info">
                <div>
                    <p itemprop="name headline">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M480 32H128C110.3 32 96 46.33 96 64v336C96 408.8 88.84 416 80 416S64 408.8 64 400V96H32C14.33 96 0 110.3 0 128v288c0 35.35 28.65 64 64 64h384c35.35 0 64-28.65 64-64V64C512 46.33 497.7 32 480 32zM272 416h-96C167.2 416 160 408.8 160 400C160 391.2 167.2 384 176 384h96c8.836 0 16 7.162 16 16C288 408.8 280.8 416 272 416zM272 320h-96C167.2 320 160 312.8 160 304C160 295.2 167.2 288 176 288h96C280.8 288 288 295.2 288 304C288 312.8 280.8 320 272 320zM432 416h-96c-8.836 0-16-7.164-16-16c0-8.838 7.164-16 16-16h96c8.836 0 16 7.162 16 16C448 408.8 440.8 416 432 416zM432 320h-96C327.2 320 320 312.8 320 304C320 295.2 327.2 288 336 288h96C440.8 288 448 295.2 448 304C448 312.8 440.8 320 432 320zM448 208C448 216.8 440.8 224 432 224h-256C167.2 224 160 216.8 160 208v-96C160 103.2 167.2 96 176 96h256C440.8 96 448 103.2 448 112V208z"/></svg>
                        <?php $this->title() ?>
                    </p>
                    <div>
                        <div>
                            <a><?php $this->author(); ?></a>
                            <span>
                                <time datetime="<?php $this->date('c'); ?>" itemprop="datePublished"><?php $this->date(); ?></time>
                            </span>
                        </div>
                        <?php echo $this->author->gravatar(280, 'G', NULL, 'post-card-content-meta-authors-link-avatar') ?>
                    </div>
                </div>
            </div>
        </div>
        <div itemprop="keywords" class="tags comment-tags">
            <div>
                <?php $this->tags('</div><div>', true, '<script>document.getElementsByClassName("tags comment-tags")[0].style.display = "none"</script>'); ?>
            </div>
        </div>
        <div class="main">
            <div id="article-main" class="post-content article-main" itemprop="articleBody">
            <?php
                $content = $this->content;
                // 为 H 标签添加 id 方便目录跳转
                $content = preg_replace(
                    '/<(h[1-6])>(.+?)<\/h[1-6]>/m',
                    '<${1} id="toc-${2}">${2}</${1}>',
                    $content
                );
                // 为 img 标签增加 id 方便定位图片预览器
                function addIdToImg($matches) {
                    global $imgCounter;
                    if(!$imgCounter) {
                        $imgCounter = 0;
                    }
                    $imgTag = $matches[0];
                    $id = 'img-' . $imgCounter;
                    $imgCounter++;
                    $imgTagWithId = preg_replace('/<img(.*?)>/i', '<img$1 id="'.$id.'" onclick="viewImage(\''.$id.'\')">', $imgTag);
                    return $imgTagWithId;
                }
                $imgCounter = 1;
                $content = preg_replace_callback('/<img(.*?)>/i', 'addIdToImg', $content);
                echo $content;
             ?>
            </div>
            <div id="main-right" class="main-right" style="width: 0px">
                <div class="content hidden" id="content">
                    <div onclick="showContent()">
                        <span>目录</span>
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160zm352-160l-160 160c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L301.3 256 438.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0z"/></svg>
                    </div>
                    <div class="content-progress content-progress-small" id="content-progress-small" style="margin-top:-10px;margin-bottom:10px;"></div>
                </div>
            </div>
        </div>
    </article>
    <!-- 结束信息板 -->
    <div class="end-info" id="end-info">
        <span>作者：<?php $this->author(); ?></span>
        <span id="link_address">链接：<?php $this->permalink() ?></span>
        <span>版权：本博客所有文章除特别声明外，均采用 CC BY-NC-SA 4.0 许可协议。转载请注明出处！</span>
    </div>
    <!-- 推荐 -->
    <div class="more-card">
        <div id="more-sort-card" style="display: flex;">
            <span>推荐</span>
            <span>∞</span>
            <?php $rad_post = posts($this) ?>
            <?php if ($rad_post != false): ?>
                <?php foreach (posts($this) as $index=>$v) { ?>
                <?php if($index != 0): ?><hr><?php endif; ?>
                <a href="<?= $v['url'] ?>"><?= $v['title'] ?></a>
                <?php } ?>
            <?php endif; ?>
            <div class="fov">
                <span>&lt;&nbsp;&nbsp;<?php $this->thePrev('%s', '<a>没有了</a>'); ?></span>
                <div></div>
                <span><?php $this->theNext('%s', '<a>没有了</a>'); ?>&nbsp;&nbsp;&gt;</span>
            </div>
        </div>
        <div class="art-body" style="margin: 0;height: auto;">
            <div style="display: none"></div>
            <div style="margin-left: 0;margin-right: 0">
                <div>
                    <span style="line-height: 40px;margin-left: 30px;color: var(--color-font-r);">看看别的</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 88.05 64"><defs><style>.cls-2A {fill: var(--color-main);}</style></defs><g id="P_2" data-name="P 2"><g id="P_2-2" data-name="P 2"><polygon class="cls-2A" points="0 0 88.05 0 42.19 33 88.05 64 0 64 0 0"></polygon></g></g></svg>
                </div>
                <a href="<?php echo $rad_post[0]['url'] ?>"><?php echo $rad_post[0]['title'] ?></a>
                <div>
                    <span><?php echo $rad_post[0]['content'] ?></span>
                </div>
            </div>
        </div>
    </div>
    <!-- 评论 -->
    <?php $this->need('comments.php'); ?>

    <script src="<?php $this->options->themeUrl('src/js/prism.js')?>"></script>

<?php $this->need('footer.php'); ?>
