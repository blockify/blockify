<?php

use \Blockify\Block;
use \Blockify\Element;

$block->openTag('section', ['class' => 'clearfix']);

$socialTypes = [
    'twitter',
    'facebook',
    'gplus',
    'linkedin'
];

echo '<div class="social-button-group">';
foreach ($socialTypes as $socialType) {
    if (isset($block->model[$socialType])) {

        $icon = new Block('core-icon', [
            'icon' => "social:{$socialType}"
        ]);
        echo new Element('a', $icon, [
            'class' => ['social-button', $socialType],
            'href' => $block->model[$socialType],
            'target' => '_blank'
        ]);
    }
}
echo '</div>';

$contactTypes = [
    'tel',
    'email'
];

echo '<ul class="contact-list">';
foreach ($contactTypes as $contactType) {
    if (isset($block->model[$contactType])) {

        $href;
        switch ($contactType) {
            case 'tel':
                $href = 'tel:' . preg_replace('/[^\d-\+]+/', '', $block->model[$contactType]);
                break;
            case 'email':
                $href = 'mailto:' . $block->model[$contactType];
                break;
            default:
                $href = $block->model[$contactType];
                break;
        }

        $link = new Element('a', $block->model[$contactType], [
            'href' => $href,
            'target' => '_blank'
        ]);
        echo new Element('li', $link, [
            'class' => ['contact-item', $contactType]
        ]);
    }
}
echo '</ul>';

$block->closeTag();
