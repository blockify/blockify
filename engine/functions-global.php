<?php

// Global header function for Printing CSS
function blockify_css()
{
    global $blockify;

    foreach ($blockify->resources['css'] as $css) {
        $css = BLOCKIFY_URL . '/' . $css;
        echo "<link rel=\"stylesheet\" href=\"{$css}\">\n";
    }
}

// Global footer function for printing JavaScript
function blockify_js()
{
    global $blockify;

    foreach ($blockify->resources['js'] as $js) {
        $js = BLOCKIFY_URL . '/' . $js;
        echo "<script src=\"{$js}\"></script>\n";
    }
    if (BLOCKIFY_DEV) {
        echo "<script src=\"" . BLOCKIFY_URL . "/engine/js/dev.js\"></script>\n";
        echo "<script src=\"" . BLOCKIFY_LIVERELOAD_URL . "\"></script>\n";
    }
}
