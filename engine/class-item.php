<?php

namespace Blockify;

class Item implements \ArrayAccess
{
    private $data = array();
    private $tagName = null;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    // Blockify Functions

    public function each($keys, $callback)
    {
        $keys = \Blockify\Internal\arrayify($keys);

        foreach ($keys as $itemprop) {
            if (is_string($itemprop) && array_key_exists($itemprop, $this->data)) {

                $array = \Blockify\Internal\arrayify($this->data[$itemprop]);

                foreach ($array as $key => $content) {
                    $callback($itemprop, $content);
                }
            }
        }
    }

    public function map($keys, $callback)
    {
        $keys = \Blockify\Internal\arrayify($keys);
        $parameters = [];

        foreach ($keys as $itemprop) {
            if (is_string($itemprop) && array_key_exists($itemprop, $this->data)) {
                $parameters[$itemprop] = \Blockify\Internal\arrayify($this->data[$itemprop]);
            }
        }

        $callbackHelper = function () use ($callback, $keys) {
            $args = func_get_args();
            $data = [];
            for ($i = 0; $i < sizeof($keys); $i++) {
                $data[$keys[$i]] = $args[$i];
            }
            $callback($data);
        };

        call_user_func_array('array_map', array_merge([$callbackHelper], array_values($parameters)));
    }

    public function meta($keys)
    {
        $callback = function ($itemprop, $content) {
            echo "<meta itemprop=\"{$itemprop}\" content=\"{$content}\">\n";
        };
        return $this->each($keys, $callback);
    }

    public function createElement($tagName, $key, $attributes = [], $schema = false)
    {
        if ($schema) {
            $attributes['itemprop'] = $key;
        }
        return new \Blockify\Element($tagName, $this->data[$key], $attributes);
    }

    public function createVoidElement($tagName, $key, $attributes = [], $schema = false)
    {
        if ($schema) {
            $attributes['itemprop'] = $key;
        }
        switch ($tagName) {
            case 'area':
            case 'link':
                $attributes['href'] = $this->data[$key];
                break;
            case 'base':
                $attributes['target'] = $this->data[$key];
                break;
            case 'img':
            case 'embed':
            case 'source':
            case 'track':
                $attributes['src'] = $this->data[$key];
                break;
            default:
                $attributes['data-' . $key] = $this->data[$key];
                break;
        }
        return new \Blockify\VoidElement($tagName, $attributes);
    }

    public function attr($attributes = null)
    {
        $attributes = array_merge_recursive(
            \Blockify\Internal\extractAttributes($this->data),
            (array)$attributes
        );

        return \Blockify\Internal\stringifyAttributes($attributes);
    }

    public function open($tagName = 'section', $attributes = array())
    {
        // Get attributes
        $attributes = $this->attr($attributes);

        // Echo start tag
        switch (true) {
            case ($tagName == 'area'):
            case ($tagName == 'base'):
            case ($tagName == 'br'):
            case ($tagName == 'col'):
            case ($tagName == 'embed'):
            case ($tagName == 'img'):
            case ($tagName == 'input'):
            case ($tagName == 'keygen'):
            case ($tagName == 'link'):
            case ($tagName == 'meta'):
            case ($tagName == 'param'):
            case ($tagName == 'source'):
            case ($tagName == 'track'):
            case ($tagName == 'wbr'):
                echo "<{$tagName}{$attributes} />\n";
                break;
            default:
                echo "<{$tagName}{$attributes}>\n";
                $this->tagName = $tagName;
                break;
        }
    }

    public function close()
    {
        if (!is_null($this->tagName)) {
            echo "</{$this->tagName}>\n";
            $this->tagName = null;
        }
    }
}
