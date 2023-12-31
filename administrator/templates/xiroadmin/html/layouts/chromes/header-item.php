<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.Atum
 *
 * @copyright   (C) 2008 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 *
 * Module chrome for rendering the module in a submenu
 */

defined('_JEXEC') or die;

$module = $displayData['module'];

if ((string) $module->content === '')
{
	return;
}

?>
<div class="header-item d-flex">
	<?php echo $module->content; ?>
</div>
