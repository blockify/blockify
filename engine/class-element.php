<?php

namespace Blockify;

class Element
{
    public $tagName;
    public $content;
    public $attributes;

    public function __construct($tagName, $content = '', $attributes = [])
    {
        $this->tagName = $tagName;
        $this->content = $content;
        $this->attributes = $attributes;
    }

    public function __toString()
    {
        $attributes = \Blockify\Internal\stringifyAttributes($this->attributes);
        return "<{$this->tagName}{$attributes}>{$this->content}</{$this->tagName}>\n";
    }
}
