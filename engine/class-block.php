<?php

namespace Blockify;

class Block
{
    public $package;
    public $children = [];
    public $childrenLocations = [];
    private $model;
    private $uri;

    public function __construct($name, $model = null)
    {
        global $blockify;

        // Find the package for the block
        $this->package = $blockify->getPackage($name);

        // Set model to cleaned input
        $this->model = ModelHelper::clean($model, $this->package->defaults);
    }

    public function appendChild($child, $location = null)
    {
        if ($location == null) {
            array_push($this->children, $child);
        }
        else {
            if (!array_key_exists($location, $this->childrenLocations)) {
                $this->childrenLocations[$location] = array();
            }
            array_push($this->childrenLocations[$location], $child);
        }
        return $this;
    }

    public function appendChildren($children, $location = null)
    {
      foreach($children as $child) {
        $this->appendChild($child, $location);
      }
      return $this;
    }

    public function appendTo($parent)
    {
        array_push($parent->children, $this);
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

    public function content($location = null, $default = false)
    {
        global $blockify;

        $contents = '';

        if ($location == null || $default) {
          foreach ($this->children as $child) {
              $contents .= $blockify->buildBlock($child);
          }
        }

        if ($location != null) {
            if (!array_key_exists($location, $this->childrenLocations)) {
                $this->childrenLocations[$location] = array();
            }
            foreach ($this->childrenLocations[$location] as $child) {
                $contents .= $blockify->buildBlock($child);
            }
        }

        return $contents;
    }

    public function openTag($tagName = 'section', $attributes = null)
    {
        // Add block name to class
        $attributes = array_merge_recursive(
            (array)$attributes,
            ['class' => ["block-{$this->package->name}"]]
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
