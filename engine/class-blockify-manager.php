<?php

namespace Blockify;

final class Manager
{
    protected $blockStack;
    protected $tagStack;
    public $factory;
    public $resources = [
        'css' => [],
        'js'  => []
    ];

    public $schema;

    public function __construct()
    {
        $this->schema = \Blockify\Internal\getEngineDataJSON('schema');
        $this->blockStack = new \SplStack();
        $this->tagStack = new \SplStack();
        $this->factory = new BlockFactory();
        $this->detectResources();
        $this->executeBlockFunctions();
    }

    private function detectResources()
    {
        $built = ( file_exists(BLOCKIFY_BUILD_PATH) && is_dir(BLOCKIFY_BUILD_PATH) );
        $dir = $built ? BLOCKIFY_BUILD_PATH : BLOCKIFY_BLOCKS_PATH;

        $glob_func = '\Blockify\Internal\glob_recursive';

        if ($built) {
            if (BLOCKIFY_DEV) {
                $dir .= DIRECTORY_SEPARATOR . 'dev';
            } else {
                $glob_func = 'glob';
            }
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
        $block_names = \Blockify\Internal\getBlockBasenames();

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
        if ($this->blockStack->count() == 0) {
            unset($GLOBALS['block']);
            return;
        }

        $block = $this->blockStack->top();

        extract(
            array(
                'block' => $block
            ),
            EXTR_OVERWRITE | EXTR_REFS
        );

        $GLOBALS['block'] = $block;
    }

    public function currentEval()
    {
        $block = $this->blockStack->top();

        // Setup globals + execute current block
        $this->updateGlobals();
        require $block->package->getPHP();

        // Pop block from stack and update globals
        $pop = $this->blockStack->pop();
        $this->updateGlobals();

        // Return the executed block
        return $pop;
    }

    public function getResourceData($basename, $filename, $type = null)
    {
        if (strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
            $data = $this->try_get_contents($filename, $type);
            if ($data != false) {
                return $data;
            }

            return false;
        }

        $folders = explode('/', $basename);
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

    public function create($basename, $document = null, $options = null, $container = true)
    {
        $block = $this->factory->build($basename, $document, $options, $container);
        $this->blockStack->push($block);
        $this->created[] = $block;

        return $block;
    }

}
