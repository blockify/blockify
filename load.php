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
bl_define_default('BLOCKIFY_ENGINE_HELPER_PATH', BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'helper');
bl_define_default('BLOCKIFY_BUILD_PATH', BLOCKIFY_PATH . DIRECTORY_SEPARATOR . 'build');
bl_define_default('BLOCKIFY_LIVERELOAD_URL', DIRECTORY_SEPARATOR . '/127.0.0.1:35729/livereload.js');

// Load Blockify engine
// Core Files
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'class-item.php');
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'class-document-helper.php');
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'class-block.php');
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'class-block-package.php');
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'class-block-factory.php');
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'internal.php');
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'functions-global.php');
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'functions-placeholder.php');
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'class-blockify-manager.php');
// Helper Files
require(BLOCKIFY_ENGINE_HELPER_PATH . DIRECTORY_SEPARATOR . 'integration.php');
require(BLOCKIFY_ENGINE_HELPER_PATH . DIRECTORY_SEPARATOR . 'class-grid-iterator.php');

// Init Blockify Class
$GLOBALS['blockify'] = new \Blockify\Manager;

// Load Blockify extras
require(BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'blockify-tasks.php');


// Global block function
function block($name, $document = null, $options = null, $container = true)
{
    global $blockify;

    $blockify->create($name, $document, $options, $container);

    return $blockify->currentEval();
}
