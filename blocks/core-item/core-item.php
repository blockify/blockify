<?php

use \Blockify\Block;
use \Blockify\Element;

$block->openTag('li');

echo new Element('a', $block->model['name'], [
    'href' => $block->model['url']
]);

if ($block->children->count() > 0) {
    echo '<ul class="clearfix">';
    echo $block->content();
    echo '</ul>';
}

$block->closeTag();
