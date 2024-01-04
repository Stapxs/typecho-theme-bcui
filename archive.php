<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<?php
    // 获取分页信息
    $hiddens = '';
    $hidden = '';
    $page = '';
    if ($this->is('index')) {
      $page = "/index.php/page/";
    } elseif ($this->is('author')) {
      $page = $this->author->permalink;
    } elseif ($this->is('category')) {
      $url = $_SERVER['PHP_SELF'];
      if (empty($url)) {
        $url = $_SERVER['REQUEST_URI'];
      }
      $page = preg_replace("/\/\d+/u", '', $url);
    }
    $prev = $this->_currentPage - 1;
    $next = $this->_currentPage + 1;
    if ($this->_currentPage == 0 || $this->_currentPage == 1) {
      $hidden = 'hidden';
      $cpage = 1;
    } else {
      $cpage = $this->_currentPage;
    }
    if ($this->_currentPage == ceil($this->getTotal() / $this->parameter->pageSize)) {
      $hiddens = 'hidden';
    } elseif (ceil($this->getTotal() / $this->parameter->pageSize) == 1) {
      $hiddens = 'hidden';
      $hidden = 'hidden';
    }
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    $site = $http_type . $_SERVER['HTTP_HOST'];
?>

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
    
    <div class="controller">
        <button onclick="window.location.href = '<?=$site.$page.$prev?>'" <?php echo $hidden ?> class="ss-button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M137.4 406.6l-128-127.1C3.125 272.4 0 264.2 0 255.1s3.125-16.38 9.375-22.63l128-127.1c9.156-9.156 22.91-11.9 34.88-6.943S192 115.1 192 128v255.1c0 12.94-7.781 24.62-19.75 29.58S146.5 415.8 137.4 406.6z"></path></svg></button>
        <div><span>第 <?= $cpage ?> 页</span></div>
        <button onclick="window.location.href = '<?=$site.$page.$next?>'" <?php echo $hiddens ?> class="ss-button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M118.6 105.4l128 127.1C252.9 239.6 256 247.8 256 255.1s-3.125 16.38-9.375 22.63l-128 127.1c-9.156 9.156-22.91 11.9-34.88 6.943S64 396.9 64 383.1V128c0-12.94 7.781-24.62 19.75-29.58S109.5 96.23 118.6 105.4z"></path></svg></button>
    </div>
</div><!-- end #main -->

<?php $this->need('footer.php'); ?>
