<?php

namespace Blockify;


class Package
{
    public $name;
    public $version;
    public $description;

    public $icon;
    public $image;
    public $options = [];
    public $template = [];

    public $json = [];

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

    public function __construct($name)
    {
        if (is_array($name)) {
            $name = implode('-', $name);
        }
        if (!is_string($name)) {
            throw new \Exception('Invalid block name');
        }

        $this->name = $name;
        $this->version = '0.0.0';

        $path = \Blockify\Internal\getBlockPath($name);

        if (!file_exists($path)) {
            throw new \Exception("Block path not found: '{$path}'");
        }

        $files = [
            'php' => "{$name}.php|index.php",
            'json' => "block.json",
            'functions' => 'functions.php',
            'icon' => 'icon.png',
            'image' => 'image.png'
        ];

        foreach ($files as $key => &$value) {
            foreach (explode('|', $value) as $filename) {

                $file = $path . DIRECTORY_SEPARATOR . $filename;

                if ($key == 'json') {
                    if (!is_array($value)) {
                        $value = [];
                    }

                    $dotFile = str_replace($filename, '.' . $filename, $file);

                    foreach ([$dotFile, $file] as $jsonFile) {
                        if (file_exists($jsonFile)) {
                            $decoded = json_decode(file_get_contents($jsonFile), true);
                            if ($decoded == null) {
                                throw new \Exception("Invalid block JSON file: '$jsonFile'");
                                return false;
                            }
                            $value = array_replace_recursive($value, $decoded);
                        }
                    }

                    if (empty($value)) {
                        throw new \Exception("No valid block JSON founde: '$file'");
                        return false;
                    }
                } else {

                    if (file_exists($file)) {
                        $value = $file;
                        break;
                    } else {
                        $value = null;
                    }

                }
            }
        }

        if ($files['json'] == null) {
            throw new \Exception("You are missing a JSON file for your block: 'block.json' in '$path'");
        }

        if (empty($files['json'])) {
            throw new \Exception("You are missing a JSON data for your block: 'block.json' in '$path'");
        }

        if ($files['php'] == null) {
            throw new \Exception("You are missing a PHP file for your block: '{$name}.php' or 'index.php' in '$path'");
        }

        // We have a valid block

        $this->php = $files['php'];
        $this->functions = $files['functions'];
        $this->icon = $files['icon'];
        $this->image = $files['image'];
        $this->json = $files['json'];
        if(array_key_exists('version', $this->json)) {
            $this->version = $this->json['version'];
        }
        if(array_key_exists('description', $this->json)) {
            $this->description = $this->json['description'];
        }

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
