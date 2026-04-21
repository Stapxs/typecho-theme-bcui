<?php
/**
 * Border Card UI Theme for Typecho
 *
 * @package Border Card UI Theme
 * @author Stapx Steve
 * @version 1.0
 * @link https://github.com/Stapxs/typecho-theme-bcui
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
    $this->need('header.php');
?>

<div class="col-mb-12 col-8" id="main" role="main">
    <?php
        $posts = [];
        while ($this->next()) {
            $posts[] = [
                'cid' => $this->cid,
                'title' => $this->title,
                'description' => $this->text,
                'permalink' => $this->permalink,
                'author' => [
                    'name' => $this->author->screenName,
                    'url' => $this->author->permalink
                ],
                'date' => $this->date,
                'categories' => $this->categories,
                'tags' => $this->tags,
                'commentsNum' => $this->commentsNum
            ];
        };
    ?>

    <div id="list-view" class="list-view"></div>

    <style>
        .list-view {
            margin: 0 auto;
            width: 80%;
        }
    </style>

    <script>
        window.__POSTS__ = <?= json_encode($posts); ?>;
    </script>

    <?php if ($this->options->debug): ?>
    <script type="module" src="http://localhost:5173/src/main.js"></script>
    <?php else: ?>
    <script src="<?php $this->options->themeUrl('assets/index.js'); ?>"></script>
    <?php endif ?>

    <?php $this->pageNav('&laquo; 前一页', '后一页 &raquo;'); ?>
</div>


<?php $this->need('footer.php'); ?>
