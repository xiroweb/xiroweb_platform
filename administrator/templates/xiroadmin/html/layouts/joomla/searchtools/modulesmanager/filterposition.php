<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;

$data = $displayData;

// Load the form filters
// $filters = $data['view']->filterForm->getGroup('filter');
$field = $data;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

?>
<?php $dataShowOn = ''; ?>
<div class="js-stools-field-filter"<?php echo $dataShowOn; ?>>
	<span class="visually-hidden"><?php echo $field->label; ?></span>
	<?php echo $field->input; ?>
</div>
