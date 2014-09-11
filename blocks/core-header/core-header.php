<?php

use \Blockify\Block;
use \Blockify\Element;
use \Blockify\VoidElement;

$block->openTag('header');

?>
<div class="container">
<?php

$brand;

if (!empty($block->model['image'])) {
    $brand = $block->model->createVoidElement('img', 'image', [
        'class' => 'brand-image',
        'alt' => $block->model['name']
    ]);
} else if (!empty($block->model['name'])) {
    $brand = $block->model['name'];
}

echo new Element('a', $brand, [
    'class' => 'brand',
    'href' => $block->model['url']
]);

echo $block->content();

?>
</div>
<?php

$block->closeTag();
