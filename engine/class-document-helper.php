<?php

namespace Blockify;

class DocumentHelper
{

    private static function walk(&$data)
    {
        if (!is_array($data)) {
            return $data;
        }

        global $blockify;
        $schema = $blockify->schema;

        $assoc = \Blockify\Internal\is_array_assoc($data);

        if ($assoc) {
            if (!array_key_exists('@type', $data) || empty($data['@type'])) {
                $data['@type'] = 'Thing';
            }

            if (array_key_exists($data['@type'], $schema['types'])) {
                if (!array_key_exists('@context', $data) || empty($data['@context'])) {
                    $data['@context'] = 'http://schema.org';
                }
            }

            $class = "\\Blockify\\Types\\{$data['@type']}";
            if (class_exists($class)) {
                return new $class( $data );
            }

        }

        array_walk($data, array('\Blockify\DocumentHelper', 'walk'));

        if ($assoc) {
            $data = new Item($data);
        }

        return $data;
    }

    public static function clean($data, $template)
    {
        if (empty($data)) {
            $data = \Blockify\Placeholder\create($template);
        }
        return DocumentHelper::walk($data);
    }

}
