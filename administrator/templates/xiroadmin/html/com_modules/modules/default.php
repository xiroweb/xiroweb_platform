<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 *
 * @copyright   (C) 2008 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;

/** @var \Joomla\Component\Modules\Administrator\View\Modules\HtmlView $this */

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
// $wa->useScript('table.columns');
$wa->useScript('multiselect');

$app = Factory::getApplication();
$input = $app->input;
$tmpl = $input->get('tmpl', '', 'CMD');

$templateStyle = $input->getInt('templateStyle',0);
$roottemplate = $input->get('roottemplate', '', 'CMD');

	// send to module edit,
	// send data to xiroadmin/html/layouts/com_modules/joomla/form/field/modulespositionedit.php
	// because can't pass url parameter, value stop by $config['filter_fields']
	// so web just overide layout template
	$app->setUserState('com_modules.module.templatestyle', $templateStyle);
	$app->setUserState('com_modules.module.roottemplate', $roottemplate);


$clientId  = (int) $this->state->get('client_id', 0);
$user      = $this->getCurrentUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = ($listOrder == 'a.ordering');

if ($saveOrder && !empty($this->items))
{
	$saveOrderingUrl = 'index.php?option=com_modules&task=modules.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
	HTMLHelper::_('draggablelist.draggable');
}

$template_positions_display =ComponentHelper::getParams('com_templates')->get('template_positions_display');
if (!$template_positions_display) {
	$uri    = Uri::getInstance();
	$return_page = 'index.php' . $uri->toString(array('query'));
	$return_page = base64_encode($return_page);
	$templates_config_link = 'index.php?option=com_config&view=component&component=com_templates&path=';
	$templates_config_link .= '&return=' . $return_page;
	$templates_config_link .= '#templates';
}



?>
<form action="<?php echo Route::_('index.php?option=com_modules&view=modules&client_id=' . $clientId) . ($tmpl ? '&tmpl=' . $tmpl : ''). ($roottemplate ? '&roottemplate=' . $roottemplate : '') . ($templateStyle ? '&templateStyle=' . $templateStyle : ''); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container" class="j-main-container">
		<?php if ($clientId) : ?>
			<div class="alert alert-warning">
				<h4><?php echo Text::_('TPL_XIROADMIN_COM_TEMPLATES_SUPPERADMIN_CLIENT_TITLE'); ?></h4>
				<?php echo Text::_('TPL_XIROADMIN_COM_TEMPLATES_SUPPERADMIN_CLIENT_DESC'); ?>
			</div>
		<?php endif; ?>
		<?php if ((!$template_positions_display) && (!$templateStyle)) : ?>
				<div class="row">
					<div class="col-12">
						<div class="alert alert-danger xiroadmin-box-shadow-3">
							<h4><?php echo Text::_('TPL_XIROADMIN_COM_MODULES_NOTE_COM_TEMPLATES_POSTIONS_DISPLAY_NEED_TURN_ON_TITLE'); ?></h4>
							<?php echo Text::_('TPL_XIROADMIN_COM_MODULES_NOTE_COM_TEMPLATES_POSTIONS_DISPLAY_NEED_TURN_ON_DESC'); ?>
							<a class="btn btn-default btn-sm btn-raised" href="<?php echo $templates_config_link; ?>">
								<span class="icon-options icon-fw" aria-hidden="true"></span>
								<?php echo Text::_('JTOOLBAR_OPTIONS'); ?> <?php echo Text::_('TPL_XIROADMIN_COM_TEMPLATES'); ?>
							</a>
							<?php echo Text::_('TPL_XIROADMIN_COM_MODULES_NOTE_COM_TEMPLATES_POSTIONS_DISPLAY_NEED_TURN_ON_GUIDE'); ?>
						</div>
					</div>
				</div>
		<?php endif; ?>
		<?php echo LayoutHelper::render('joomla.searchtools.modulesmanager', array('view' => $this)); ?>
		<?php if ($this->total > 0) : ?>

			<ul class="toolbar-action-list <?php echo $templateStyle ? 'hidden' : '' ;  ?>">
				<li class="nowrap">
					<div class="btn btn-raised btn-default">
						<?php echo HTMLHelper::_('grid.checkall'); ?> <?php echo JText::_('TPL_XIROADMIN_COM_MODULES_SELECT_ALL'); ?>
					</div>
				</li>
			</ul>

			<table class="table" id="moduleList">
				<caption class="visually-hidden">
					<?php echo Text::_('COM_MODULES_TABLE_CAPTION'); ?>,
							<span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
							<span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
				</caption>

				<tbody <?php if ($saveOrder) :?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="false"<?php endif; ?>>
				<?php foreach ($this->items as $i => $item) :
					$ordering   = ($listOrder == 'a.ordering');
					$canCreate  = $user->authorise('core.create',     'com_modules');
					$canEdit    = $user->authorise('core.edit',		  'com_modules.module.' . $item->id);
					$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $user->get('id')|| is_null($item->checked_out);
					$canChange  = $user->authorise('core.edit.state', 'com_modules.module.' . $item->id) && $canCheckin;
				?>
					<tr class="row<?php echo $i % 2; ?> published-<?php echo $item->published; ?>" data-draggable-group="<?php echo $item->position ?: 'none'; ?>">

						<td class="text-center d-none d-md-table-cell order">
							<?php
							$iconClass = '';
							if (!$canChange)
							{
								$iconClass = ' inactive';
							}
							elseif (!$saveOrder)
							{
								$iconClass = ' inactive" title="' . Text::_('JORDERINGDISABLED');
							}
							?>
							<span class="sortable-handler<?php echo $iconClass; ?>">
								<span class="icon-ellipsis-v"></span>
							</span>
							<?php if ($canChange && $saveOrder) : ?>
								<input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order hidden">
							<?php endif; ?>
						</td>

						<th scope="row" class="has-context title-type-module">
							<?php echo $item->name; ?>:

								<?php if ($item->checked_out) : ?>
									<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'modules.', $canCheckin); ?>
								<?php endif; ?>
								<?php if ($canEdit) : ?>
									<a href="<?php echo Route::_('index.php?option=com_modules&task=module.edit&id=' . (int) $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->title); ?>">
										<?php echo $this->escape($item->title); ?></a>
								<?php else : ?>
									<?php echo $this->escape($item->title); ?>
								<?php endif; ?>

								<?php if (!empty($item->note)) : ?>
									<div class="small">
										<?php echo Text::sprintf('JGLOBAL_LIST_NOTE', $this->escape($item->note)); ?>
									</div>
								<?php endif; ?>

						</th>
						<td class="d-none d-md-table-cell">
							<?php if ($item->position) : ?>
								<span class="badge bg-xiroadmin">
									<?php echo $item->position; ?>
								</span>
							<?php else : ?>
								<span class="badge bg-secondary">
									<?php echo Text::_('JNONE'); ?>
								</span>
							<?php endif; ?>
						</td>

						<td class="modules-list-info">
							<div class="text-start">
								<?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->title); ?>
							</div>

							<?php if ($clientId === 0) : ?>
							<div class="small">
								<i class="menu-menus fa fa-sitemap"></i>
								<?php echo $item->pages; ?>
							</div>
							<?php endif; ?>
							<div class="small">
								<i class="menu-levels fa fa-user-secret"></i>
								<?php echo $this->escape($item->access_level); ?>
							</div>
							<?php if (($clientId === 0) && (Multilanguage::isEnabled())) : ?>
							<div class="small">
								<i class="menu-language fa fa-language"></i>
								<?php echo LayoutHelper::render('joomla.content.language', $item); ?>
							</div>
							<?php elseif ($clientId === 1 && ModuleHelper::isAdminMultilang()) : ?>
								<div class="small">
									<i class="menu-language fa fa-language"></i>
									<?php if ($item->language == ''):?>
										<?php echo Text::_('JUNDEFINED'); ?>
									<?php elseif ($item->language == '*'):?>
										<?php echo Text::alt('JALL', 'language'); ?>
									<?php else:?>
										<?php echo $this->escape($item->language); ?>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<div class="small text-end">
								ID: <?php echo (int) $item->id; ?>
							</div>
							<div class="text-end float-end">
								<?php // Check if extension is enabled ?>
								<?php if ($item->enabled > 0) : ?>
									<?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'modules.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
								<?php else : ?>
									<?php // Extension is not enabled, show a message that indicates this. ?>
									<span class="tbody-icon" title="<?php echo Text::sprintf('COM_MODULES_MSG_MANAGE_EXTENSION_DISABLED', $this->escape($item->name)); ?>">
										<span class="icon-minus-circle" aria-hidden="true"></span>
									</span>
								<?php endif; ?>
							</div>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<?php // load the pagination. ?>
			<?php echo $this->pagination->getListFooter(); ?>

		<?php endif; ?>

		<?php // Load the batch processing form. ?>
		<?php if ($user->authorise('core.create', 'com_modules')
			&& $user->authorise('core.edit', 'com_modules')
			&& $user->authorise('core.edit.state', 'com_modules')) : ?>
			<template id="joomla-dialog-batch"><?php echo $this->loadTemplate('batch_body'); ?></template>
		<?php endif; ?>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="boxchecked" value="0">
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>
