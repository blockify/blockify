<?php

namespace Blockify;

final class Manager
{
    protected $blockStack = [];
    private $blockPackages = [];
    public $resources = [
        'css' => [],
        'js'  => []
    ];

    public function __construct()
    {
        $this->detectResources();
        $this->executeBlockFunctions();
    }

    private function detectResources()
    {
        $dir = BLOCKIFY_BLOCKS_PATH;
        $built = ( file_exists(BLOCKIFY_BUILD_PATH) && is_dir(BLOCKIFY_BUILD_PATH) );
        $glob_func = '\Blockify\Internal\glob_recursive';

        if ($built && !BLOCKIFY_DEV) {
            $dir = BLOCKIFY_BUILD_PATH;
            $glob_func = 'glob';
        }

        $resources = array(
            'css' => call_user_func($glob_func, $dir . DIRECTORY_SEPARATOR . '*.css'),
            'js'  => call_user_func($glob_func, $dir . DIRECTORY_SEPARATOR . '*.js')
        );

        if (is_array($resources)) {
            foreach ($resources as $type => $files) {
                foreach ($files as $file) {
                    $url = str_replace(
                        '\\',
                        '/',
                        str_replace(BLOCKIFY_PATH . DIRECTORY_SEPARATOR, '', $file)
                    );
                    array_push($this->resources[$type], $url . '?ver=' . filemtime($file));
                }
            }
        }
    }

    private function executeBlockFunctions()
    {
        $block_names = \Blockify\Internal\getBlockNames();

        foreach ($block_names as $name) {
            $block_dir = \Blockify\Internal\getBlockPath($name);
            $functions = $block_dir . DIRECTORY_SEPARATOR . 'functions.php';

            if (file_exists($functions)) {
                include $functions;
            }
        }
    }

    public function updateGlobals()
    {
        $blockStackSize = sizeof($this->blockStack);

        if ($blockStackSize == 0) {
            unset($GLOBALS['block']);
            return;
        }

        $block = $this->blockStack[$blockStackSize - 1];

        extract(
            array(
                'block' => $block
            ),
            EXTR_OVERWRITE | EXTR_REFS
        );

        $GLOBALS['block'] = $block;
    }

    public function buildBlock($obj)
    {
        switch (gettype($obj)) {
            case 'string':
                return $obj;
            case 'object':
                return $this->buildBlockObject($obj);
        }
        return false;
    }

    private function buildBlockObject($obj)
    {
        switch (get_class($obj)) {
            case 'Blockify\Block':
                $block =& $obj;

                array_push($this->blockStack, $block);

                // Setup Globals
                $this->updateGlobals();

                // Start Buffer
                // TODO: Implement block caching
                ob_start();

                // Execute Block
                require $block->package->getPHP();

                // Store buffer contents
                $contents = ob_get_clean();

                // Cleanup, pop the stack and update globals
                $pop = array_pop($this->blockStack);
                $this->updateGlobals();

                // Return contents
                return $contents;
            default:
            case 'Blockify\Element':
                return (string) $obj;
        }
    }

    public function getResourceData($name, $filename, $type = null)
    {
        if (strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
            $data = $this->try_get_contents($filename, $type);
            if ($data != false) {
                return $data;
            }

            return false;
        }

        $folders = explode('/', $name);
        if (!empty($folders)) {
            $data = $this->try_get_contents($folders[0] . '/' . $filename, $type);
            if ($data != false) {
                return $data;
            }
        }

        $data = $this->try_get_contents($filename, $type);

        if ($data !== false) {
            return $data;
        }

        return false;
    }

    public function getPackage($name)
    {
        if (array_key_exists($name, $this->blockPackages)) {
            return $this->blockPackages[$name];
        } else {
            $package = new Package($name);
            $this->blockPackages[$name] = &$package;
            return $package;
        }
    }

    public function getAllPackages()
    {
        $packages = [];
        $blockNames = \Blockify\Internal\getBlocknames();
        foreach ($blockNames as $name) {
            if (($package = $this->getPackage($name)) != false) {
                $packages[] = $package;
            }
        }
        return $packages;
    }
}
