<?php
/**
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

$modulePosition = $displayData['modules'];

$renderer   = Factory::getDocument()->loadRenderer('module');
$modules    = ModuleHelper::getModules($modulePosition);
$moduleHtml = [];
$moduleCollapsedHtml = [];

foreach ($modules as $key => $mod)
{
	$out = $renderer->render($mod);

	if ($out !== '')
	{
		if (strpos($out, 'data-bs-toggle="modal"') !== false)
		{
			$dom = new \DOMDocument;
			$dom->loadHTML($out);
			$els = $dom->getElementsByTagName('a');

			$moduleCollapsedHtml[] = $dom->saveHTML($els[0]); //$els[0]->nodeValue;
		}
		else
		{
			$moduleCollapsedHtml[] = $out;
		}

		$moduleHtml[] = $out;
	}
}
?>
<?php 
$colormodes = LayoutHelper::render('statusbar.colormodes', []);
$moduleCollapsedHtml[] = $colormodes;
$moduleHtml[] = $colormodes;
?>
<div class="header-items d-flex ms-auto">
	<?php
		foreach ($moduleHtml as $mod)
		{
			echo '<div class="header-item">' . $mod . '</div>';
		}
	?>
	<div class="header-more d-none" id="header-more-items" >
		<button class="header-more-btn dropdown-toggle" type="button" title="<?php echo Text::_('TPL_XIROADMIN_MORE_ELEMENTS'); ?>" data-bs-toggle="dropdown" aria-expanded="false">
			<div class="header-item-icon"><span class="icon-ellipsis-h" aria-hidden="true"></span></div>
			<div class="visually-hidden"><?php echo Text::_('TPL_XIROADMIN_MORE_ELEMENTS'); ?></div>
		</button>
		<div class="header-dd-items dropdown-menu">
			<?php
			foreach ($moduleCollapsedHtml as $key => $mod)
			{
				echo '<div class="header-dd-item dropdown-item" data-item="' . $key . '">' . $mod . '</div>';
			}
			?>
		</div>
	</div>
</div>
