<?php

use \Blockify\VoidElement;

$iconParts = explode(':', $block->model['icon']);
$iconGroup = array_shift($iconParts);
$iconName = array_pop($iconParts);
$iconType = 'png';

if (!$iconGroup) {
    $iconGroup = 'core';
}

if (!$iconName) {
    $iconName = 'error';
}

echo new VoidElement('img', [
    'class' => $block->package->name,
    'src' => "{$block->uri}/{$iconGroup}/{$iconName}.{$iconType}"
]);
