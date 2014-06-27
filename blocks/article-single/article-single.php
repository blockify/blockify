<?php

$block->open('article');

echo '<figure>';
$block->document->tag('img', 'image', ['class' => 'img-responsive']);
echo '</figure>';

echo '<header>';
$block->document->tag('h1', 'name');
$block->document->tag('h4', 'description');
echo '</header>';

if (!empty($block->options['buttons'])) {
    block('blockify-buttons', $block->options['buttons'], false, false);
}

foreach (['articleBody', 'text'] as $key) {
    if (is_string($block->document[$key]) && !empty($block->document[$key])) {
            $block->document->tag('div', $key);
            $block->document['wordCount'] = str_word_count($block->document[$key]);
            $block->document->meta('wordCount');
            return true;
    }
}

$block->close();
