<?php

namespace Blockify;

class Block
{
    public $package;
    public $children;
    private $model;
    private $uri;

    public function __construct($name, $model = null)
    {
        global $blockify;

        // Find the package for the block
        $this->package = $blockify->getPackage($name);

        // Init child stack
        $this->children = new \SplDoublyLinkedList();

        // Set model to cleaned input
        $this->model = ModelHelper::clean($model, $this->package->defaults);
    }

    public function appendChild($child)
    {
        $this->children->push($child);
        return $this;
    }

    public function appendTo($parent)
    {
        $parent->children->push($this);
        return $this;
    }

    public function __toString()
    {
        global $blockify;
        return $blockify->buildBlock($this);
    }

    public function __get($name)
    {
        switch($name) {
            case 'model':
                return $this->model;
                break;
            case 'uri':
                return $this->package->uri;
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

    public function __set($name, $value)
    {
        switch ($name) {
            case 'model':
                $this->model = ModelHelper::clean($value);
                break;
        }
    }

    public function content()
    {
        global $blockify;

        $contents = '';

        $this->children->rewind();
        while ($this->children->valid()) {

            $contents .= $blockify->buildBlock($this->children->current());

            $this->children->next();
        }

        return $contents;
    }

    public function openTag($tagName = 'section', $attributes = null)
    {
        // Add block name to class
        $attributes = array_merge_recursive(
            (array)$attributes,
            ['class' => [$this->package->name]]
        );

        // Open Item tag
        $this->model->open($tagName, $attributes);
    }

    public function closeTag()
    {
        // Close Item tag
        $this->model->close();
    }
}

/*

CHANGED:

Removed Options
Removed 'template' to 'defaults'
Removed Placeholder data
Removed non-essential engine code (Move to external blocks)
Removed phantom js code
Removed schema cleaning
Removed unused internal functions
Removed unused constants
Clean Block->attr function to no longer require Schema & extract global data attributes from item data
Refactored Block Class for direct use
Attribute ordering only happens while in DEV mode
Fixed code in README.md
Removed blockify-button, to be remade as core-button-group and core-buttons
New 'Element' Class
New Block: core-button

TODO:

Make core-section
Make core-button-group
Make core-tabs
Make core-tab
Make core-pages
Make core-header
Make core-footer
Make core-grid

Update block manager and remove the requirement of 'template'
Update docs, annotate videos.

*/
