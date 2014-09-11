<?php

$block->openTag('section', [
  'class' => 'core-section'
]);

echo '<div class="container">';

echo $block->model->createElement('h1', 'name');
echo $block->model->createElement('p', 'description');

echo $block->content();

echo '</div>';

$block->closeTag();
