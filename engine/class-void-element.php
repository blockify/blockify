<?php

namespace Blockify;

class VoidElement
{
    public $tagName;
    public $attributes;

    public function __construct($tagName, $attributes = [])
    {
        $this->tagName = $tagName;
        $this->attributes = $attributes;
    }

    public function __toString()
    {
        $attributes = \Blockify\Internal\stringifyAttributes($this->attributes);
        return "<{$this->tagName}{$attributes}>\n";
    }
}
