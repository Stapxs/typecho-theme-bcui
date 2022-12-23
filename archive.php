<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<div id="main" role="main" style="margin-top: 100px">
    <?php $pageType = $this->parameter->type; ?>
    <div class="archive-title ss-card">
        <div class="bg-left"></div>
        <div class="title">
            <span><?php $this->archiveTitle([
                'category' => _t('分类 <span>%s</span> 下的文章'),
                'search'   => _t('包含关键字 %s 的文章'),
                'tag'      => _t('标签 %s 下的文章'),
                'author'   => _t('%s 发布的文章')
            ], '', ''); ?></span>
        </div>
        <div class="bg-right"></div>
    </div>
    <div>
    <?php if ($this->have()): ?>
        <?php $isLeft = true; ?>
        <?php while ($this->next()): ?>
            <div class="art-body<?php if(!$isLeft){echo ' right';}$isLeft=!$isLeft; ?>">
                <div>
                    <div style="background: url(<?php showThumbnail($this); ?>) center;"></div>
                </div>
                <div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 88.05 64"><defs><style>.cls-2A{fill:var(--color-main);}</style></defs><g id="P_2" data-name="P 2"><g id="P_2-2" data-name="P 2"><polygon class="cls-2A" points="0 0 88.05 0 42.19 33 88.05 64 0 64 0 0"></polygon></g></g></svg>
                    </div>
                    <a href="<?php $this->permalink() ?>"><?php $this->title() ?></a>
                    <div>
                        <span><?php $this->excerpt(300,'...'); ?></span>
                    </div>
                    <div>
                        <?php echo $this->author->gravatar(280, 'G', NULL, 'post-card-content-meta-authors-link-avatar') ?>
                        <div class="info">
                            <a><?php $this->author(); ?></a>
                            <span><?php $this->date(); ?></span>
                        </div>
                        <?php if ($pageType != 'category'): ?>
                        <div class="category">
                            <span><?php $this->category(','); ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M32 32H480c17.7 0 32 14.3 32 32V96c0 17.7-14.3 32-32 32H32C14.3 128 0 113.7 0 96V64C0 46.3 14.3 32 32 32zm0 128H480V416c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V160zm128 80c0 8.8 7.2 16 16 16H336c8.8 0 16-7.2 16-16s-7.2-16-16-16H176c-8.8 0-16 7.2-16 16z"/></svg>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <article class="post">
            <h2 class="post-title"><?php _e('没有找到内容'); ?></h2>
        </article>
    <?php endif; ?>
    </div>

    <?php $this->pageNav('&laquo; 前一页', '后一页 &raquo;'); ?>
</div><!-- end #main -->

<?php $this->need('footer.php'); ?>
