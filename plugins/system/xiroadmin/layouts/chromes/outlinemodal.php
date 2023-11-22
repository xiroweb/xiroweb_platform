<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2019 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;

$app = Factory::getApplication();
if ($app->isClient('site'))
{
	// Session::checkToken('get') or die(Text::_('JINVALID_TOKEN'));
}

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wr = $wa->getRegistry();
$wr->addRegistryFile('media/plg_system_xiroadmin/joomla.asset.json');

$wa->registerAndUseStyle('layouts.chromes.outlinemodulmodal', 'plg_system_xiroadmin/outlinemodulemodal.css');

$module = $displayData['module'];

$wa->useScript('core');
$wa->useScript('plg_system_xiroadmin.admin-modules-modal');

$function  = $app->input->getCmd('function', 'jSelectModule');
$onclick   = $this->escape($function);

?>
	<?php
	$link = '#';
	$attribs = 'data-function="' . $this->escape($onclick) . '"'
		. ' data-id="' . $module->position . '"'
		. ' data-title="' . $this->escape($module->position) . '"'
		. ' data-cat-id=""'
		. ' data-language=""'
		. ' data-uri="' . $link . '"';
	?>
<div class="mod-preview">
	<div class="mod-preview-info">
		<div class="mod-preview-position">
			<a class="select-link" href="javascript:void(0)" <?php  echo $attribs; ?>>
				<?php echo $module->position; ?>
			</a>
		</div>
	</div>
	<div class="mod-preview-wrapper">
		<?php echo $module->content; ?>
	</div>
</div>
