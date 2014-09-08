<?php

use \Blockify\Block;

$block->openTag();

$block->model->tag('img', 'image');

echo 'It\'s Alive!';

$block->content();

$block->closeTag();
?>
