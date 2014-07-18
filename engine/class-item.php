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

    public function tag($tagName, $keys, $attributes = null)
    {
        $self = &$this;
        $callback = function ($itemprop, $content) use ($tagName, $attributes, $self) {

            // Default to empty array if no attributes provided
            if (is_null($attributes) || !is_array($attributes)) {
                $attributes = [];
            }
            $attributes['itemprop'] = $itemprop;
            $shortTag = false;

            // Handle specific tags
            switch (strtolower($tagName)) {
                case 'img':
                    if (strpos($content, 'holder.js') !== false) {
                        $attributes['data-src'] = $content;
                    } else {
                        $attributes['src'] = $content;
                    }
                    $shortTag = true;
                    break;
                case 'a':
                    $attributes['href'] = $self['url'];
                    break;
            }

            $attributes = \Blockify\Internal\stringifyAttributes($attributes);

            if ($shortTag) {
                echo "<{$tagName}{$attributes}>\n";
            } else {
                echo "<{$tagName}{$attributes}>{$content}</{$tagName}>\n";
            }

        };

        return $this->each($keys, $callback);
    }

    public function attr($attributes = null)
    {
        // Default to empty array if no attributes provided
        if (is_null($attributes) || !is_array($attributes)) {
            $attributes = [];
        }

        // Setup all required attributes
        foreach (['class' => [], 'itemscope' => true, 'itemtype' => []] as $attribute => $value) {
            if (!array_key_exists($attribute, $attributes)) {
                $attributes[$attribute] = $value;
            }
        }

        foreach ($attributes as &$attribute) {
            if (!is_bool($attribute) && !is_array($attribute)) {
                $attribute = [$attribute];
            }
        }

        // Do we have a specified type?
        if (!empty($this->data['@type'])) {
            $itemtype = $this->data['@context'];
            if ($itemtype == 'http://schema.org' || strpos($itemtype, $this->data['@type']) === false) {
                $itemtype .= "/{$this->data['@type']}";
            }
            $attributes['itemtype'] = $itemtype;

            // Let's also look for a url
            if (!empty($this->data['url'])) {
                $attributes['itemid'] = $this->data['url'];
            }
        }

        // Do we have a local ID?
        if (!empty($this->data['@id']) && strpos($this->data['@id'], '#') === 0) {
            $attributes['id'] = substr($this->data['@id'], 1);
        }

        return \Blockify\Internal\stringifyAttributes($attributes);
    }

    public function open($tagName = 'section', $attributes = null)
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
