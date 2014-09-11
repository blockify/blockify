<?php

use \Blockify\Block;
use \Blockify\Element;

$block->openTag('nav', ['class' => 'clearfix']);

echo '<ul class="clearfix">';
echo $block->content();
echo '</ul>';

$block->closeTag();
