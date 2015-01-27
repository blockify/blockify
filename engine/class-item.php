<?php

namespace Blockify;

class Item implements \ArrayAccess, \Countable, \IteratorAggregate
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

    public function count()
    {
        return count($this->data);
    }

    public function getIterator() {
        return new \ArrayIterator($this->data);
    }

    public function __get($name)
    {
        switch($name) {
            case 'data':
                return $this->data;
                break;
            default:
                $trace = debug_backtrace();
                  trigger_error(
                  'Undefined property via __get(): ' . $name .
                  ' in ' . $trace[0]['file'] .
                  ' on line ' . $trace[0]['line'],
                  E_USER_NOTICE
                );
                return null;
        break;
    }

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

    public function createElement($tagName, $key, $attributes = [])
    {
        if(!isset($this->data[$key]) || empty($this->data[$key])) {
            return '';
        }
        return new \Blockify\Element($tagName, $this->data[$key], $attributes);
    }

    public function createVoidElement($tagName, $key, $attributes = [])
    {
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
            (array)$attributes,
            \Blockify\Internal\extractAttributes($this->data)
        );

        return \Blockify\Internal\stringifyAttributes($attributes);
    }

    public function open($tagName = 'section', $attributes = array())
    {
        // Get attributes
        $attributes = $this->attr($attributes);

        // Echo start tag
        echo "<{$tagName}{$attributes}>\n";

        $this->tagName = $tagName;
    }

    public function close()
    {
        if (!is_null($this->tagName)) {
            echo "</{$this->tagName}>\n";
            $this->tagName = null;
        }
    }
}
