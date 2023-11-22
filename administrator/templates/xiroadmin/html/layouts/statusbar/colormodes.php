<?php
/**
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

// Load the Bootstrap Dropdown
HTMLHelper::_('bootstrap.dropdown', '.dropdown-toggle');

Factory::getApplication()->getLanguage()->load('tpl_xiroadmin', JPATH_ADMINISTRATOR);

?>
<div class="header-item-content dropdown header-colormodes">
    <button class="dropdown-toggle d-flex align-items-center ps-0 py-0" data-bs-toggle="dropdown"  id="bd-theme" type="button"
        title="<?php echo Text::_('TPL_XIROADMIN_STATUS_COLORMODES_TITLE'); ?>"
        aria-label="Toggle theme (auto)">
        <span class="header-item-icon">
            <span class="fa fa-moon" aria-hidden="true" id="icon-colormode-active"></span>
        </span>
        <span class="icon-angle-down" aria-hidden="true"></span>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
    <ul class="list-unstyled m-0" aria-labelledby="bd-theme-text">
        <li>
            <button type="button" class="dropdown-item" data-bs-theme-value="light" aria-pressed="false">
                <span class="fa fa-sun" aria-hidden="true"></span>
                <?php echo Text::_('TPL_XIROADMIN_STATUS_COLORMODES_LIGHT'); ?>
            </button>
        </li>
        <li>
            <button type="button" class="dropdown-item" data-bs-theme-value="dark" aria-pressed="false">
            <span class="fa fa-moon" aria-hidden="true"></span>
                <?php echo Text::_('TPL_XIROADMIN_STATUS_COLORMODES_DARK'); ?>
            </button>
        </li>
        <li>
            <button type="button" class="dropdown-item" data-bs-theme-value="auto" aria-pressed="true">
                <span class="fa-solid fa-circle-half-stroke" aria-hidden="true"></span>
                <?php echo Text::_('TPL_XIROADMIN_STATUS_COLORMODES_AUTO'); ?>
            </button>
        </li>
        </ul>

    </div>
</div>
