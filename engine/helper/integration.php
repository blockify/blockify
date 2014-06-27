<?php

if (!function_exists('is_wordpress')) {
    function is_wordpress()
    {
        return array_key_exists('wp_version', $GLOBALS);
    }
}

if (is_wordpress()) {
    require(BLOCKIFY_ENGINE_HELPER_PATH . DIRECTORY_SEPARATOR . 'class-wordpress-nav-menu.php');
    require(BLOCKIFY_ENGINE_HELPER_PATH . DIRECTORY_SEPARATOR . 'wordpress.php');
}
