<?php

namespace Blockify;

class BlockFactory
{
    private $blockPackages = [];

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

    public function build($name, $document, $options, $container)
    {
        return $this->getPackage($name)->spawn($document, $options, $container);
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
