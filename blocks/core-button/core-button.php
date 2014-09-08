<?php

$block->openTag('a', [
  'class' => 'btn',
  'href' => $block->model['url']
]);

echo $block->model['name'];

$block->closeTag();
