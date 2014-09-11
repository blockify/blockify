<?php

namespace Blockify;


class Package
{
    public $name;
    public $version;
    public $description;
    public $path;
    private $uri;

    public $icon;
    public $image;

    public $defaults;

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
            trigger_error('Invalid block name', E_USER_ERROR);
        }

        $this->name = $name;
        $this->version = '0.0.0';

        $this->path = \Blockify\Internal\getBlockPath($name);

        if (!file_exists($this->path)) {
            trigger_error("Block path not found: '{$this->path}'", E_USER_ERROR);
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

                $file = $this->path . DIRECTORY_SEPARATOR . $filename;

                if ($key == 'json') {
                    if (!is_array($value)) {
                        $value = [];
                    }

                    $dotFile = str_replace($filename, '.' . $filename, $file);

                    foreach ([$dotFile, $file] as $jsonFile) {
                        if (file_exists($jsonFile)) {
                            $decoded = json_decode(file_get_contents($jsonFile), true);
                            if ($decoded == null) {
                                trigger_error("Invalid block JSON file: '$jsonFile'", E_USER_ERROR);
                                return false;
                            }
                            $value = array_replace_recursive($value, $decoded);
                        }
                    }

                    if (empty($value)) {
                        trigger_error("No valid block JSON founde: '$file'", E_USER_ERROR);
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
            trigger_error("You are missing a JSON file for your block: 'block.json' in '$this->path'", E_USER_ERROR);
        }

        if (empty($files['json'])) {
            trigger_error("You are missing a JSON data for your block: 'block.json' in '$this->path'", E_USER_ERROR);
        }

        if ($files['php'] == null) {
            trigger_error("You are missing a PHP file for your block: '{$name}.php' or 'index.php' in '$this->path'", E_USER_ERROR);
        }

        // We have a valid block

        $this->php = $files['php'];
        $this->functions = $files['functions'];
        $this->icon = $files['icon'];
        $this->image = $files['image'];
        $this->json = $files['json'];
        if (array_key_exists('version', $this->json)) {
            $this->version = $this->json['version'];
        }
        if (array_key_exists('description', $this->json)) {
            $this->description = $this->json['description'];
        }
        if (array_key_exists('defaults', $this->json) && is_array($this->json['defaults'])) {
            $this->defaults = $this->json['defaults'];
        }
    }

    public function __get($name)
    {
        switch($name) {
            case 'uri':
                if (empty($this->uri)) {
                    $this->uri = BLOCKIFY_URL . '/' .
                        str_replace(
                            '\\',
                            '/',
                            str_replace(
                                BLOCKIFY_PATH . DIRECTORY_SEPARATOR,
                                '',
                                $this->path
                            )
                        );
                }
                return $this->uri;
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
}
