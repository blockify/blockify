<?php
/*!
 * Blockify (http://blockify.co)
 * Copyright 2014 Blockify
 * Licensed under GNU (https://github.com/62design/blockify/blob/master/LICENSE)
 */

/*
// ACF 5
function include_field_types_blockify( $version ) {
    include_once( BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'acf' . DIRECTORY_SEPARATOR . 'acf-v5.php' );
}
add_action('acf/include_field_types', 'include_field_types_blockify');

// ACF 4
function register_fields_blockify() {
    include_once( BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'acf' . DIRECTORY_SEPARATOR . 'acf-v4.php' );
}
add_action('acf/register_fields', 'register_fields_blockify');
*/

// wp_header & wp_footer actions
add_action('wp_head', 'blockify_css', 1);
add_action('wp_footer', 'blockify_js');

// Nav Menu Items
function bwp_get_nav_menu_items($menu, $args = [])
{
    $menu = new \Blockify\WordPressNavMenu($menu, $args);
    return $menu->items;
}
