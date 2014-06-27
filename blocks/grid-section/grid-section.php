<?php

$grid = new BlockifyGridIterator($block->document['@list']);
$childBlocks = $block->options['childBlocks'];

$block->open();

$block->document->tag('h1', 'name');
$block->document->tag('h2', 'description');

while ($grid->valid()) {

    $current = $grid->current();

    echo '<div class="' . $grid->column() . '">';

    if (!empty($block->document['@type'])) {
        $type = $block->document['@type'];
        if (array_key_exists($type, $childBlocks)) {
            // Do we have a block specified in options?
            $block = $childBlocks[$type];
            if (is_array($block)) {
                block($childBlocks[$type]['block'], $current, $childBlocks[$type]['options'], false);
            } else {
                block($childBlocks[$type], $current, false, false);
            }
        } else {
            // Fall back to article-single
            block('article-single', $current, false, false);
        }
    }

    echo '</div>';

    $grid->next();
}

$block->close();
