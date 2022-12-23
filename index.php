<?php
/**
 * 这是果糖博客主题的 Typecho 版本
 *
 * @package Fructose Lite
 * @author Stapx Steve [林槐]
 * @version 1.0
 * @link https://stapxs.cn
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit; $this->need('header.php'); ?>
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

        <!-- >>>> 文章列表主体 -->
        <div id="main-list" class="art-list">
        <?php $isLeft = true; ?>
        <?php while ($this->next()): ?>
            <?php
                // 取出所有的 tag 名，用来单独筛出短日记
                $tag_names = array();
                foreach (($this->tags) as $value) {
                    $tag_names[] = $value["name"];
                }
            ?>
            <?php if(in_array('日记', $tag_names)): ?>
            <div class="say-body">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 31.1c-141.4 0-255.1 93.12-255.1 208c0 49.62 21.35 94.98 56.97 130.7c-12.5 50.37-54.27 95.27-54.77 95.77c-2.25 2.25-2.875 5.734-1.5 8.734c1.249 3 4.021 4.766 7.271 4.766c66.25 0 115.1-31.76 140.6-51.39c32.63 12.25 69.02 19.39 107.4 19.39c141.4 0 255.1-93.13 255.1-207.1S397.4 31.1 256 31.1zM127.1 271.1c-17.75 0-32-14.25-32-31.1s14.25-32 32-32s32 14.25 32 32S145.7 271.1 127.1 271.1zM256 271.1c-17.75 0-31.1-14.25-31.1-31.1s14.25-32 31.1-32s31.1 14.25 31.1 32S273.8 271.1 256 271.1zM383.1 271.1c-17.75 0-32-14.25-32-31.1s14.25-32 32-32s32 14.25 32 32S401.7 271.1 383.1 271.1z"></path></svg>
                </div>
                <div>
                    <span><span>碎碎念：</span><?php $this->title() ?></span>
                    <span><?php $this->date(); ?></span>
                    <?php echo $this->author->gravatar(280, 'G', NULL, 'post-card-content-meta-authors-link-avatar') ?>
                </div>
            </div>
            <?php else: ?>
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
                        <div class="category">
                            <span><?php $this->category(','); ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M32 32H480c17.7 0 32 14.3 32 32V96c0 17.7-14.3 32-32 32H32C14.3 128 0 113.7 0 96V64C0 46.3 14.3 32 32 32zm0 128H480V416c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V160zm128 80c0 8.8 7.2 16 16 16H336c8.8 0 16-7.2 16-16s-7.2-16-16-16H176c-8.8 0-16 7.2-16 16z"/></svg>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif ?>
        <?php endwhile; ?>
        </div>
        <div class="controller">
            <button onclick="window.location.href = '<?=$site.$page.$prev?>'" <?php echo $hidden ?> class="ss-button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M137.4 406.6l-128-127.1C3.125 272.4 0 264.2 0 255.1s3.125-16.38 9.375-22.63l128-127.1c9.156-9.156 22.91-11.9 34.88-6.943S192 115.1 192 128v255.1c0 12.94-7.781 24.62-19.75 29.58S146.5 415.8 137.4 406.6z"></path></svg></button>
            <div><span>第 <?= $cpage ?> 页</span></div>
            <button onclick="window.location.href = '<?=$site.$page.$next?>'" <?php echo $hiddens ?> class="ss-button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M118.6 105.4l128 127.1C252.9 239.6 256 247.8 256 255.1s-3.125 16.38-9.375 22.63l-128 127.1c-9.156 9.156-22.91 11.9-34.88 6.943S64 396.9 64 383.1V128c0-12.94 7.781-24.62 19.75-29.58S109.5 96.23 118.6 105.4z"></path></svg></button>
        </div>
        <!-- 文章列表主体 <<<< -->
        <?php //$this->need('sidebar.php'); ?>

<?php $this->need('footer.php'); ?>
