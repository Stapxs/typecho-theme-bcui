<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

function themeConfig($form)
{
    // 返回的列表第一个永远是当前启用的
    $theme_info = (\Widget\Themes\Rows::alloc()->to($themes)->next());
    echo '<link rel="stylesheet" href="https://stapxs.github.io/Border-Card-UI/css/style.css">
    <link rel="stylesheet" href="https://stapxs.github.io/Border-Card-UI/css/color-light.css">
    <style>
        .main-card {
            display: flex;
            flex-direction: row;
            align-items: stretch;
            overflow: hidden;
            margin-top: 20px;
        }
        .main-card > div.bg-left {
            width: calc(100px + 10%);
            background: var(--color-main);
            margin: -200px 0 -200px -100px;
            transform: rotate(30deg);
            border: 10px solid var(--color-card-2);
        }
        .main-card > div.bg-right {
            width: calc(100px + 10%);
            background: var(--color-main);
            margin: -200px -100px -200px 0;
            transform: rotate(30deg);
            border: 10px solid var(--color-card-2);
        }
        .info-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }
        .info-card > svg {
            height: 3rem;
            fill: var(--color-main);
        }
        .info-card > span {
            display: block;
        }
        .info-card > span.title {
            font-size: 1.1rem;
            color: var(--color-main);
            margin-top: 10px;
        }
        .info-card > span.title > span {
            font-size: 0.8rem;
            color: var(--color-font-2);
        }
        .info-card > a {
            color: var(--color-font-r);
            background: var(--color-main);
            padding: 3px 15px;
            border-radius: 5rem;
            margin-top: 5px;
            font-size: 0.8rem;
        }
        .info-copy {
            margin-bottom: 40px;
        }
        .info-copy > span {
            width: 100%;
            display: block;
            text-align: center;
            font-size: 0.8rem;
            margin-top: 5px;
            color: var(--color-font-2);
        }
    </style>
    <div class="ss-card main-card">
        <div class="bg-left"></div>
        <div class="info-card">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 32c12.5 0 24.1 6.4 30.7 17L503.4 394.4c5.6 8.9 8.6 19.2 8.6 29.7c0 30.9-25 55.9-55.9 55.9H55.9C25 480 0 455 0 424.1c0-10.5 3-20.8 8.6-29.7L225.2 49c6.6-10.6 18.3-17 30.8-17zm65 192L256 120.4 176.9 246.5 208 288l48-64h65z"/></svg>
            <span class="title">
            '.$theme_info["title"].'
                <span>for Typecho</span>
            </span>
            <span>'.$theme_info["description"].'</span>
            <a>'.$theme_info["version"].'</a>
        </div>
        <div class="bg-right"></div>
    </div>
    <div  class="info-copy"><span>Copyright © 2020 - '.date('Y').' '.$theme_info["author"].'</span></div>';

    $copyright = new Typecho_Widget_Helper_Form_Element_Text(
        'copyright',
        null,
        null,
        _t('copyright 信息'),
        _t('这种事情自己随意就好了')
    );
    $form->addInput($copyright);

    $filing = new Typecho_Widget_Helper_Form_Element_Text(
        'filing',
        null,
        null,
        _t('备案信息'),
        _t('如果有备案信息的话（小声），可以填写 html 内容。')
    );
    $form->addInput($filing);

    $darkmode = new Typecho_Widget_Helper_Form_Element_Radio(
        'darkmode',
        array('auto' => _t('跟随系统'), 'light' => _t('浅色模式'), 'dark' => _t('深色模式')),
        'auto',
        _t('颜色模式'),
        _t('默认将跟随系统颜色模式，也可以自己指定。'));
    $form->addInput($darkmode);

    $ana = new Typecho_Widget_Helper_Form_Element_Text(
        'ana',
        null,
        null,
        _t('顶栏语录'),
        _t('需要填写一个只返回纯文本的 API。也可以b不要啦 ……')
    );
    $form->addInput($ana);
}

/*
function themeFields($layout)
{
    $logoUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoUrl',
        null,
        null,
        _t('站点LOGO地址'),
        _t('在这里填入一个图片URL地址, 以在网站标题前加上一个LOGO')
    );
    $layout->addItem($logoUrl);
}
*/

// 这是个从 illi 主题抄过来的获取第一张图片的方法（x
function showThumbnail($widget)
{
    $cai = '';//这里可以添加图片后缀，例如七牛的缩略图裁剪规则，这里默认为空
    $attach = $widget->attachments(1)->attachment;
    $pattern = '/\<img.*?src\=\"(.*?)\"[^>]*>/i';
    $patternMD = '/\!\[.*?\]\((http(s)?:\/\/.*?(jpg|png))/i';
    $patternMDfoot = '/\[.*?\]:\s*(http(s)?:\/\/.*?(jpg|png))/i';
    if (preg_match_all($pattern, $widget->content, $thumbUrl)) {
        $ctu = $thumbUrl[1][0].$cai;
    }
    //如果是内联式markdown格式的图片
    else if (preg_match_all($patternMD, $widget->content, $thumbUrl)) {
        $ctu = $thumbUrl[1][0].$cai;
    }
    //如果是脚注式markdown格式的图片
    else if (preg_match_all($patternMDfoot, $widget->content, $thumbUrl)) {
        $ctu = $thumbUrl[1][0].$cai;
    }
    else if ($attach && $attach->isImage) {
            $ctu = $attach->url.$cai;
    }
    if(Typecho_Widget::widget('Widget_Options')->slimg &&
        'showoff'==Typecho_Widget::widget('Widget_Options')->slimg){
        if($widget->fields->thumb)
        {
            $ctu = $widget->fields->thumb;
        }
    }
    echo $ctu;
}

// 随机输出文章
function posts($widget)
{
  $db = Typecho_Db::get();
  $sql = $db->select()->from('table.contents')
    ->where('table.contents.status = ?', 'publish')
    ->where('table.contents.type = ?', $widget->type)
    ->where('table.contents.password IS NULL')
    ->order('table.contents.created', Typecho_Db::SORT_ASC);
  $data = $db->fetchALL($sql);
  $total = count($data);
  $nums = ounts(3, $total);
  $temp = array_rand($data, $nums);
  $content = array();
  foreach ($temp as $val) {
    $content[] = $data[$val];
  }
  if ($content) {
    $arr = array();
    foreach ($content as $key => $v) {
      $contents = $widget->filter($content[$key]);
      $arr[$key]['title'] = $contents['title'];
      $arr[$key]['url'] = $contents['permalink'];
      $arr[$key]['content'] = excerpt($contents['text']);
      $arr[$key]['category'] = $contents['categories'][0]['name'];
      $arr[$key]['timeStamp'] = $contents['date']->timeStamp;
    }
    return $arr;
  } else {
    return false;
  }
}

function ounts($sum, $total)
{
  if ($sum > $total) {
    $sum -= 1;
    return ounts($sum, $total);
  } else {
    return $sum;
  }
}

function excerpt($post_excerpt)
{
  $post_excerpt = strip_tags(htmlspecialchars_decode($post_excerpt));
  $post_excerpt = trim($post_excerpt);

  $patternArr = array('/\s/', '/ /');
  $replaceArr = array('', '');
  $post_excerpt = preg_replace($patternArr, $replaceArr, $post_excerpt);
  $value = mb_strcut($post_excerpt, 0, 700, 'utf-8');
  return $value;
}