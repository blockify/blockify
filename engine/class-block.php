<?php

namespace Blockify;

class Block
{
    public $document;
    public $options;
    public $container;
    public $package;

    public function __construct($package, $document, $options, $container)
    {
        $this->document = $document;
        $this->options = $options;
        $this->container = $container;
        $this->package = $package;
    }

    public function open($tagName = 'section', $attributes = null)
    {
        // Add block name to class
        $attributes = array_merge_recursive((array)$attributes, ['class' => [$this->package->name]]);

        /*
        // Add developer attributes if in dev mode and using the current block
        if (BLOCKIFY_DEV) {
            $attributes['class'][] = 'blockify-dev-block';
            $attributes['data-block'] = json_encode( $target, JSON_HEX_APOS );
        }
        */

        // Open Item tag
        $this->document->open($tagName, $attributes);

        // Echo container if required
        if ($this->container === true) {
            echo "<div class=\"container\">\n";
        }
    }

    public function close()
    {
        // End container if required
        if ($this->container === true) {
            echo "</div>\n";
        }

        // Close Item tag
        $this->document->close();
    }
}
