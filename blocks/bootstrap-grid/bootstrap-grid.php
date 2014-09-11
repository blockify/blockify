<?php

$children = &$block->children;

echo $block->openTag('div', ['class' => 'row']);

for ($children->rewind(); $children->valid(); $children->next()) {
    $child = $children->current();


}

echo $block->closeTag();
