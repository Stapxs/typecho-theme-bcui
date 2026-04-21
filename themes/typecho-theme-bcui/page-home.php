<?php 
/**
 * 主页
 *
 * @package custom
 * @author Stapx Steve [林槐]
 * @version 1.0.0
 * @link https://stapxs.cn
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
    $this->need('header.php');
?>

<div id="main" role="main">

    <div id="home-view" class="home-view"></div>

    <style>
        .home-view {
            margin: 0 auto;
            width: 80%;
        }
    </style>

    <script>
        window.__IMAGE__ = "<?= Typecho_Widget::widget('Widget_Options')->topImage() ?>";
    </script>

    <?php if ($this->options->debug): ?>
    <script type="module" src="http://localhost:5173/src/main.js"></script>
    <?php else: ?>
    <script src="<?php $this->options->themeUrl('assets/index.js'); ?>"></script>
    <?php endif ?>
</div>


<?php $this->need('footer.php'); ?>
