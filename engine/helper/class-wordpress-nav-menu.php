<?php

namespace Blockify;

class WordPressNavMenu
{
    public $items = [];
    private $childQueue = [];

    public function __construct($menu, $args)
    {
        $wp_items = wp_get_nav_menu_items($menu, $args);

        if (!$wp_items) {
            $locations = get_nav_menu_locations();
            $wp_items = wp_get_nav_menu_items($locations[$menu], $args);
        }

        if (!$wp_items) {
            return false;
        }

        foreach ($wp_items as $item) {
            $item = WordPressNavMenu::parseItem($item);

            // Look for queued children
            foreach ($this->childQueue as $key => $child) {
                if ($child['@parent'] === $item['@id']) {
                    unset($this->childQueue[$key]);
                    $item['@list'][] = $child;
                }
            }

            // Look for parent
            if ($item['@parent'] == 0) {
                $this->items[] = $item;
            } else {
                $this->childQueue[$item['@id']] = $item;
                array_walk($this->items, [$this, 'addToParent'], $item);
            }
        }
    }

    private function addToParent(&$value, $key, $item)
    {
        if ($value['@id'] === $item['@parent']) {
            unset($this->childQueue[$item['@id']]);
            $value['@list'][] = $item;
        }
        if (array_key_exists('@list', $value)) {
            array_walk($value['@list'], [$this, 'addToParent'], $item);
        }
    }

    public static function parseItem($item)
    {
        $isButton = preg_grep("/[button|btn]/i", $item->classes);
        return [
            '@type' => 'Thing/' . ($isButton ? 'Button' : 'Link'),
            '@id' => $item->ID,
            '@parent' => (int) $item->menu_item_parent,
            '@list' => [],
            'name' => $item->title,
            'description' => $item->description,
            'url' => $item->url
        ];
    }
}
