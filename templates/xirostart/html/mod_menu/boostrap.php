<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Helper\ModuleHelper;

HTMLHelper::_('bootstrap.collapse');
HTMLHelper::_('bootstrap.dropdown');


$id = '';

if ($tagId = $params->get('tag_id', '')) {
    $id = 'id="' . $tagId . '"';
}

// The menu class is deprecated. Use mod-menu instead
?>
<nav <?php echo $id; ?> class="navbarxirostart navbar navbar-expand-md navbar-dark bg-dark" aria-label="<?php echo $module->title ; ?>">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars-<?php echo $module->id; ?>" aria-controls="navbars-<?php echo $module->id; ?>" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbars-<?php echo $module->id; ?>">
        <ul <?php echo $id; ?> class="navbar-nav me-auto mb-2 mb-md-0">
        <?php foreach ($list as $i => &$item) {
            $itemParams = $item->getParams();
            $class      = 'nav-item item-' . $item->id;

            if ($item->id == $default_id) {
                $class .= ' default';
            }

            if (in_array($item->id, $path)) {
                $class .= ' active';
            } elseif ($item->type === 'alias') {
                $aliasToId = $itemParams->get('aliasoptions');

                if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
                    $class .= ' active';
                } elseif (in_array($aliasToId, $path)) {
                    $class .= ' active';
                }
            }

            if ($item->type === 'separator') {
                $class .= ' divider';
            }

            if ($item->deeper) {
                $class .= ' deeper';
            }

            if ($item->parent) {
                $class .= ' parent dropdown';
            }

            echo '<li class="' . $class . '">';

            switch ($item->type) :
                case 'separator':
                case 'component':
                case 'heading':
                case 'url':
                    require ModuleHelper::getLayoutPath('mod_menu', 'boostrap_' . $item->type);
                    break;

                default:
                    require ModuleHelper::getLayoutPath('mod_menu', 'boostrap_url');
                    break;
            endswitch;

            // The next item is deeper.
            if ($item->deeper) {
                if ($item->level > 1 ) {
                    echo '<ul class="dropdown-menu dropdown-submenu">';
                } else {
                    echo '<ul class="dropdown-menu">';
                }
            } elseif ($item->shallower) {
                // The next item is shallower.
                echo '</li>';
                echo str_repeat('</ul></li>', $item->level_diff);
            } else {
                // The next item is on the same level.
                echo '</li>';
            }
        }
        ?></ul>
</div>
</nav>
