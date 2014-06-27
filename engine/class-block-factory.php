<?php

namespace Blockify;

class BlockFactory
{
    private $blockPackages = [];

    public function getPackage($basename)
    {
        if (array_key_exists($basename, $this->blockPackages)) {
            return $this->blockPackages[$basename];
        } else {
            $package = new Package($basename);
            $this->blockPackages[$basename] = &$package;
            return $package;
        }
    }

    public function build($basename, $document, $options, $container)
    {
        return $this->getPackage($basename)->spawn($document, $options, $container);
    }

    public function getAllPackages()
    {
        $packages = [];
        $blockNames = \Blockify\Internal\getBlockBasenames();
        foreach ($blockNames as $basename) {
            if (($package = $this->getPackage($basename)) != false) {
                $packages[] = $package;
            }
        }
        return $packages;
    }
}
