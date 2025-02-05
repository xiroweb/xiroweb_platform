<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;

$attributes = [];

$attributes['class'] = '';

if ($item->deeper) {
    if ($item->level > 1) {
        $attributes['class'] .= 'dropdown-item dropdown-toggle';
        $attributes['data-bs-toggle'] = 'dropdown';
        $attributes['aria-expanded'] = 'false';
    } else {
        $attributes['class'] .= 'nav-link dropdown-toggle';
        $attributes['data-bs-toggle'] = 'dropdown';
        $attributes['aria-expanded'] = 'false';
    }
} else {
    if ($item->level > 1) {
        $attributes['class'] .= 'dropdown-item';
    } else {
        $attributes['class'] .= 'nav-link';
    }
}

if (in_array($item->id, $path)) {
    $attributes['class'] .= ' active';
} elseif ($item->type === 'alias') {
    $aliasToId = $itemParams->get('aliasoptions');

    if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
        $attributes['class'] .= ' active';
    } elseif (in_array($aliasToId, $path)) {
        $attributes['class'] .= ' active';
    }
}


if ($item->anchor_title) {
    $attributes['title'] = $item->anchor_title;
}

if ($item->anchor_css) {
    $attributes['class'] .= $item->anchor_css;
}

if ($item->anchor_rel) {
    $attributes['rel'] = $item->anchor_rel;
}

if ($item->id == $active_id) {
    $attributes['aria-current'] = 'location';

    if ($item->current) {
        $attributes['aria-current'] = 'page';
    }
}

$linktype = $item->title;

if ($item->menu_icon) {
    // The link is an icon
    if ($itemParams->get('menu_text', 1)) {
        // If the link text is to be displayed, the icon is added with aria-hidden
        $linktype = '<span class="p-2 ' . $item->menu_icon . '" aria-hidden="true"></span>' . $item->title;
    } else {
        // If the icon itself is the link, it needs a visually hidden text
        $linktype = '<span class="p-2 ' . $item->menu_icon . '" aria-hidden="true"></span><span class="visually-hidden">' . $item->title . '</span>';
    }
} elseif ($item->menu_image) {
    // The link is an image, maybe with its own class
    $image_attributes = [];

    if ($item->menu_image_css) {
        $image_attributes['class'] = $item->menu_image_css;
    }

    $linktype = HTMLHelper::_('image', $item->menu_image, $item->title, $image_attributes);

    if ($itemParams->get('menu_text', 1)) {
        $linktype .= '<span class="image-title">' . $item->title . '</span>';
    }
}

if ($item->browserNav == 1) {
    $attributes['target'] = '_blank';
} elseif ($item->browserNav == 2) {
    $options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes';

    $attributes['onclick'] = "window.open(this.href, 'targetWindow', '" . $options . "'); return false;";
}

echo HTMLHelper::_('link', OutputFilter::ampReplace(htmlspecialchars($item->flink, ENT_COMPAT, 'UTF-8', false)), $linktype, $attributes);
