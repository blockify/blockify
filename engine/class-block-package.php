<?php

namespace Blockify;


class Package
{
    public $name;
    public $basename;

    public $screenshot;
    public $options = [];
    public $template = [];

    private $php;
    public function getPHP()
    {
        return $this->php;
    }

    private $functions;
    public function getFunctions()
    {
        return $this->functions;
    }

    public function __construct($basename)
    {
        if (is_array($basename)) {
            $basename = implode('-', $basename);
        }
        if (!is_string($basename)) {
            throw new \Exception('Invalid Block Basename');
        }

        $this->basename = $basename;

        $path = \Blockify\Internal\getBlockPath($basename);

        if (!file_exists($path)) {
            throw new \Exception("Block path not found: '{$path}'");
        }

        $files = [
            'php' => "{$basename}.php|index.php",
            'json' => "{$basename}.json|block.json",
            'functions' => 'functions.php',
            'screenshot' => 'screenshot.png'
        ];

        foreach ($files as $key => &$value) {
            foreach (explode('|', $value) as $file) {

                // Absolute file path for each possibility
                $file = $path . DIRECTORY_SEPARATOR . $file;
                if (file_exists($file)) {

                    // Our file exists so let's handle based on key
                    switch ($key) {
                        case 'json':
                            $value = json_decode(file_get_contents($file), true);
                            if ($value == null) {
                                throw new \Exception("Invalid block JSON file: '$file'");
                                return false;
                            }
                            break;
                        default:
                            $value = $file;
                            break;
                    }

                    // Don't try other possibilities for this file key
                    break;

                } else {
                    // File does not exist
                    $value = null;
                }

            }
        }

        if ($files['json'] == null) {
            throw new \Exception("You are missing a JSON file for your block: '{$basename}.json' or 'block.json' in '$block_dir'");
        }

        if ($files['php'] == null) {
            throw new \Exception("You are missing a PHP file for your block: '{$name}.php' or 'index.php' in '$block_dir'");
        }

        // We have a valid block

        $this->php = $files['php'];
        $this->functions = $files['functions'];
        $this->screenshot = $files['screenshot'];

        if (array_key_exists('template', $files['json']) && is_array($files['json']['template'])) {
            $this->template = $files['json']['template'];
        }
        if (array_key_exists('options', $files['json']) && is_array($files['json']['options'])) {
            $this->options = $files['json']['options'];
        }
    }

    public function spawn($document, $options, $container)
    {
        return new Block(
            $this,
            DocumentHelper::clean($document, $this->template),
            empty($options) ? $this->options : $options,
            $container
        );
    }
}
