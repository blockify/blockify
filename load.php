<?php
/*!
 * Blockify (http://blockify.co)
 * Copyright 2014 Blockify
 * Licensed under GNU (https://github.com/62design/blockify/blob/master/LICENSE)
 */

define('BLOCKIFY_VERSION', 'v0.1.2 beta');

// Define Blockify defaults
function bl_define_default($key, $value)
{
    if (!defined($key)) {
        define($key, $value);
    }
}

bl_define_default('BLOCKIFY_PATH', dirname(__FILE__));
bl_define_default('BLOCKIFY_URL', '//127.0.0.1/blockify');
bl_define_default('BLOCKIFY_DEV', false);
bl_define_default('BLOCKIFY_BLOCKS_PATH', BLOCKIFY_PATH . DIRECTORY_SEPARATOR . 'blocks');
bl_define_default('BLOCKIFY_ENGINE_PATH', BLOCKIFY_PATH . DIRECTORY_SEPARATOR . 'engine');
bl_define_default('BLOCKIFY_BUILD_PATH', BLOCKIFY_PATH . DIRECTORY_SEPARATOR . 'build');
bl_define_default('BLOCKIFY_LIVERELOAD_URL', DIRECTORY_SEPARATOR . '/127.0.0.1:35729/livereload.js');

// Load Blockify engine
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'class-item.php');
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'class-model-helper.php');
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'class-block.php');
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'class-element.php');
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'class-void-element.php');
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'class-block-package.php');
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'internal.php');
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'class-blockify-manager.php');

// Init Blockify Class
$GLOBALS['blockify'] = new \Blockify\Manager;

// Load Blockify extras
//require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'blockify-tasks.php');

// Temp global funtcions:

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
        echo "<script src=\"" . BLOCKIFY_LIVERELOAD_URL . "\"></script>\n";
    }
}

function block()
{
    trigger_error('block() is no longer used, see Blockify Changelog', E_UESR_ERROR);
}
