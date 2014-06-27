<?php
$block->open();

$block->document->each('@list', function ($itemprop, $button) {
    $button->tag('a', 'name', ['class' => ['btn btn-default']]);
});

$block->close();
