<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_stats_admin
 *
 * @copyright   (C) 2012 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

Factory::getApplication()->getLanguage()->load('tpl_xiroadmin',JPATH_BASE);
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('xiroadmin.mod_stats_admin', 'blocks/mod_stats.css', ['relative' => true, 'version' => 'auto'], []);
?>
<?php

// tim va tach array ra rieng
$array_get = array( 'file', 'users');
$list_users_articles = array();

foreach ($list as $key => $item) {
	if (in_array($item->icon, $array_get )) {
		$list_users_articles[$item->icon] = $list[$key];
		unset($list[$key]);
	}
}

$html = '';

if (!empty($list_users_articles['file'])) {
	$html .=  '<div class="block-stats-admin"><div class="small-box mod-stats-bg-1">';
	$html .= '<div class="inner"><h3>'. $list_users_articles['file']->data .'</h3><p>'. $list_users_articles['file']->title . '</p></div>';
	$html .= '<div class="icon"><i class="far fa-newspaper"></i></div>';
	$html .= '<a href="'. (isset($list_users_articles['file']->link) ? $list_users_articles['file']->link : '') . '" class="small-box-footer">';
	$html .= (isset($list_users_articles['file']->link) ? Text::_('TPL_XIROADMIN_MOD_STAST_ADMIN_MORELINK').' <i class="fa fa fa-arrow-circle-right"></i>' : '&nbsp;');
	$html .= '</a></div></div>';
}

if (!empty($list_users_articles['users'])) {
	$html .=  '<div class="block-stats-admin"><div class="small-box mod-stats-bg-2">';
	$html .= '<div class="inner"><h3>'. $list_users_articles['users']->data .'</h3><p>'. $list_users_articles['users']->title . '</p></div>';
	$html .= '<div class="icon"><i class="fa fa-user"></i></div>';
	$html .= '<a href="'. (isset($list_users_articles['users']->link) ? $list_users_articles['users']->link : '') . '" class="small-box-footer">';
	$html .= (isset($list_users_articles['users']->link) ? Text::_('TPL_XIROADMIN_MOD_STAST_ADMIN_MORELINK').' <i class="fa fa fa-arrow-circle-right"></i>' : '&nbsp;');
	$html .= '</a></div></div>';
}

	$html .=  '<div class="block-stats-admin"><div class="small-box mod-stats-bg-3">';
	$html .= '<div class="inner"><h3>'. Text::_('TPL_XIROADMIN_MOD_STAST_ADMIN_INFO').'</h3><p>';
		foreach ($list as $item) {
			$html .= $item->title . ': ' . $item->data . '; ';
		}
	$html .= '</p></div>';
	$html .= '<div class="icon"><i class="fas fa-info-circle"></i></div>';
	$html .= '<a href="index.php?option=com_admin&amp;view=sysinfo" class="small-box-footer">';
	$html .=  Text::_('TPL_XIROADMIN_MOD_STAST_ADMIN_MORELINK').' <i class="fa fa fa-arrow-circle-right"></i>';
	$html .= '</a></div></div>';


?>
<div class="row row-cols-1 row-cols-md-3 align-items-stretch">
	<?php echo $html; ?>
</div>
