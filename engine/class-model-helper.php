<?php

namespace Blockify;

class ModelHelper
{

    private static function walk(&$data)
    {
        if (!is_array($data)) {
            return $data;
        }

        global $blockify;

        $assoc = \Blockify\Internal\is_array_assoc($data);

        array_walk($data, array('\Blockify\ModelHelper', 'walk'));

        if ($assoc) {
            $data = new Item($data);
        }

        return $data;
    }

    public static function clean($data, $defaults)
    {
        $arrayData = array_replace_recursive((array)$defaults, (array)$data);
        return ModelHelper::walk($arrayData);
    }

}
