<?php
    $block->open('header', ['class'=>'clearfix']);
?>
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#header-simple-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
<?php


$headerSimpleLogo = function ($block)
{
    empty($block->document['image']) ?
        $block->document->tag('span', 'name') :
        $block->document->tag('img', 'image', ['alt' => $block->document['name']]);
};

if (empty($block->document['url'])) {
    ?><span class="logo"><?php
        $headerSimpleLogo($block);
    ?></span><?php
}
else $block->document->each('url', function ($prop, $value) use ($block, $headerSimpleLogo) {
    ?><a class="logo" href="<?= $value; ?>" itemprop="<?= $prop; ?>"><?php
        $headerSimpleLogo($block);
    ?></a><?php
});

$block->document->tag('div', 'description', ['class' => 'description hidden-xs']);

?>
    <nav class="collapse navbar-collapse" id="header-simple-collapse" itemscope itemtype="http://schema.org/SiteNavigationElement">
        <ul>
<?php
        $block->document->each('@list', function ($prop, $item) use ($block) {
            $item->open('li');
                $isButton = $item['@type'] === 'Thing/Button';
                $class = $isButton ? 'btn-default' : 'btn-link';
                ?><a class="btn <?= $class; ?>" href="<?= $item['url']; ?>" itemprop="url"><?= $item['name']; ?></a><?php
            $item->close();
        });
?>
        </ul>
    </nav>
<?php
    $block->close();
?>
