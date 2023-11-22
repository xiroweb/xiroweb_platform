<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_title
 *
 * @copyright   (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

defined('_JEXEC') or die;
?>
<?php if (!empty($title)) : ?>
<div class="d-flex align-items-center flex-fill">
	<div class="container-title flex-fill">
		<?php echo $title; ?>
	</div>
</div>
<?php endif; ?>
