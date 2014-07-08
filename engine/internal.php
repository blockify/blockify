<?php
/*!
 * Blockify (http://blockify.co)
 * Copyright 2014 Blockify
 * Licensed under GNU (https://github.com/62design/blockify/blob/master/LICENSE)
 */

namespace Blockify\Internal;

function get_json_file( $file )
{
    if( file_exists($file) ) {
        return json_decode( file_get_contents($file), true );
    }
    return false;
}

function try_get_contents( $filename, $type = null )
{
    $filename = BLOCKIFY_BLOCKS_PATH . '/' . $filename;

    if( ! file_exists($filename) )
        return false;

    return file_get_contents( $filename, $type );
}

function getEngineDataJSON( $key )
{
    return get_json_file( BLOCKIFY_ENGINE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . "{$key}.json" );
}


function is_array_assoc($arr)
{
    return array_keys($arr) !== range(0, count($arr) - 1);
}

function stringifyAttributes( $attributes )
{
    if( empty($attributes) ) {
        return '';
    }

    // Before we output, let's order the attributes according to Code Guide by @mdo
    // http://codeguide.co/#html-attribute-order
    $order = [
        "/^class$/",
        "/^id$/", "/^name$/",
        "/^data-/",
        "/^src$/", "/^for$/", "/^type$/", "/^href$/",
        "/^title$/", "/^alt$/",
        "/^aria-/", "/^role$/",
        "/^itemscope$/", "/^itemtype$/", "/^itemid$/", "/^itemref$/"
    ];
    uksort($attributes, function ($a, $b) use ($order) {
        $pos_a = array_search_preg($a, $order);
        $pos_b = array_search_preg($b, $order);
        $pos_a = $pos_a === false ? sizeof($order) : $pos_a;
        $pos_b = $pos_b === false ? sizeof($order) : $pos_b;
        return $pos_a - $pos_b;
    });

    $return = '';

    foreach( $attributes as $key => &$value ) {
        switch( true ) {
            case is_bool($value);
                if( $value === true ) {
                    $return .= " {$key}";
                }
                break;
            case empty($value):
                break;
            default:
                $value = stringify_space_seperated( $value );
                $return .= " {$key}=\"{$value}\"";
                break;
        }
    }

    return $return;
}

function arrayify( $data ) {
    switch(true) {

        case empty($data):
            return array();

        case !is_array($data):
            return array($data);

        default:
            return $data;

    }
}

function stringify_space_seperated( $data ) {
    switch(true) {

        case is_string($data):
            return trim($data);

        case is_array($data):
            return implode(' ', $data);

        default:
            return $data;

    }
}

function _array_replace_null_recursive($base, $new)
{
    foreach ($new as $key => $value)
    {
        if( array_key_exists($key, $base) && is_null($base[$key]) )
        {
            $base[$key] = $value;
        }
        else if( is_array($value) )
        {
            $value = _array_replace_null_recursive($base[$key], $value);
        }
    }
    return $base;
}

function array_replace_null_recursive($array, $array1)
{
    $args = func_get_args();

    $array = $args[0];
    if( ! is_array($array) )
    {
        return $array;
    }
    for( $i = 1, $argc = func_num_args(); $i < $argc; $i++ )
    {
        if( is_array($args[$i]) )
        {
            $array = _array_replace_null_recursive($array, $args[$i]);
        }
    }
    return $array;
}

function array_search_preg( $needle, $haystack ) {
    if( !is_array($haystack) ) {
        return false;
    }
    foreach( $haystack as $key => $value ) {
        if( preg_match($value, $needle) ) {
            return $key;
        }
    }
    return false;
}

function glob_recursive( $pattern, $flags = 0 )
{
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
    {
        $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}

function getBlockPath($name, $directory = \BLOCKIFY_BLOCKS_PATH)
{
    return $directory . DIRECTORY_SEPARATOR . $name;
}

function getBlockNames()
{
    if (!is_dir(BLOCKIFY_BLOCKS_PATH)) {
        return null;
    }

    $blocks_dir = @opendir(BLOCKIFY_BLOCKS_PATH);
    if (!$blocks_dir) {
        return null;
    }

    $blocks = array();

    while (($file = readdir($blocks_dir)) !== false) {
        if (substr($file, 0, 1) == '.') {
            continue;
        }
        $blocks[] = $file;
    }

    return $blocks;
}
