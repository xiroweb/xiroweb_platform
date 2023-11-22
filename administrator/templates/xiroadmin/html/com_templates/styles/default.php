<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_templates
 *
 * @copyright   (C) 2008 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;

HTMLHelper::_('behavior.multiselect');

$user      = Factory::getUser();
$clientId = (int) $this->state->get('client_id', 0);
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
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
<div class="row">
	<div class="col-12 text-end">
		<a href="https://www.xiroweb.com/template" target="_blank" class="btn btn-warning mb-2"><i class="fa fa-fw fa-cloud-download"></i> <?php echo JText::_('TPL_XIROADMIN_COM_TEMPLATES_TEMPLATE_MORE_LINK'); ?></a>
	</div>
</div>
<form action="<?php echo Route::_('index.php?option=com_templates&view=styles'); ?>" method="post" name="adminForm" id="adminForm">
	<div class="row">
		<div class="col-12">
			<div id="j-main-container" class="j-main-container <?php echo $clientId ? 'supperadmin' : '' ?>">
				<?php if ($clientId) : ?>
					<div class="alert alert-error">
						<h4><?php echo Text::_('TPL_XIROADMIN_COM_TEMPLATES_SUPPERADMIN_CLIENT_TITLE'); ?></h4>
						<?php echo Text::_('TPL_XIROADMIN_COM_TEMPLATES_SUPPERADMIN_CLIENT_DESC'); ?>
					</div>
				<?php endif; ?>
				<?php if (!$template_positions_display) : ?>
					<div class="alert alert-danger xiroadmin-box-shadow-3">
						<h4><?php echo Text::_('TPL_XIROADMIN_NOTE_COM_TEMPLATES_POSTIONS_DISPLAY_NEED_TURN_ON_TITLE'); ?></h4>
						<?php echo Text::_('TPL_XIROADMIN_NOTE_COM_TEMPLATES_POSTIONS_DISPLAY_NEED_TURN_ON_DESC'); ?>
						<a class="btn btn-default btn-sm btn-raised" href="<?php echo $templates_config_link; ?>">
							<span class="icon-options icon-fw" aria-hidden="true"></span>
							<?php echo Text::_('JTOOLBAR_OPTIONS'); ?> <?php echo Text::_('TPL_XIROADMIN_COM_TEMPLATES'); ?>
						</a>
						<?php echo Text::_('TPL_XIROADMIN_COM_MODULES_NOTE_COM_TEMPLATES_POSTIONS_DISPLAY_NEED_TURN_ON_GUIDE'); ?>
					</div>
				<?php endif; ?>

				<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('selectorFieldName' => 'client_id'))); ?>
				<?php if ($this->total > 0) : ?>
					<div class="templates-list" id="styleList">
						<div class="hidden">
						<?php echo HTMLHelper::_('grid.checkall'); ?>
						</div>

						<div class="row row-cols-1 row-cols-md-3 align-items-md-stretch">
							<?php foreach ($this->items as $i => $item) :
								$canCreate = $user->authorise('core.create',     'com_templates');
								$canEdit   = $user->authorise('core.edit',       'com_templates');
								$canChange = $user->authorise('core.edit.state', 'com_templates');
							?>

							<div class="x-style d-flex">
								<div class="card card-xiro xiroadmin-transition flex-fill">
									<div class="button-check-theme">
										<?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->title); ?>
									</div>
									<?php echo LayoutHelper::render('xiroadmin.com_templates.thumb', array('template'=> $item->template, 'client_id' => $item->client_id   )); ?>
									<div class="card-body text-center">
										<h2><?php echo $this->escape($item->title); ?></h2>
										<p><?php echo ucfirst($this->escape($item->template)); ?></p>
										<?php if ($clientId === 0) : ?>
										<div class="small">
											<?php if ($item->home == '1') : ?>
												<?php echo Text::_('COM_TEMPLATES_STYLES_PAGES_ALL'); ?>
											<?php elseif ($item->home != '0' && $item->home != '1') : ?>
												<?php echo Text::sprintf('COM_TEMPLATES_STYLES_PAGES_ALL_LANGUAGE', $this->escape($item->language_title)); ?>
											<?php elseif ($item->assigned > 0) : ?>
												<?php echo Text::sprintf('COM_TEMPLATES_STYLES_PAGES_SELECTED', $this->escape($item->assigned)); ?>
											<?php else : ?>
												<?php echo Text::_('COM_TEMPLATES_STYLES_PAGES_NONE'); ?>
											<?php endif; ?>
										</div>
										<?php endif; ?>

										<div class="text-center action-area">
										<?php if ($item->home == '0' || $item->home == '1') : ?>
											<?php echo HTMLHelper::_('jgrid.isdefault', $item->home != '0', $i, 'styles.', $canChange && $item->home != '1'); ?>
										<?php elseif ($canChange):?>
											<a class="btn btn-default" href="<?php echo Route::_('index.php?option=com_templates&task=styles.unsetDefault&cid[]=' . $item->id . '&' . Session::getFormToken() . '=1'); ?>">
												<?php if ($item->image) : ?>
													<?php echo HTMLHelper::_('image', 'mod_languages/' . $item->image . '.gif', $item->language_title, array('title' => Text::sprintf('COM_TEMPLATES_GRID_UNSET_LANGUAGE', $item->language_title)), true); ?>
												<?php else : ?>
													<span class="label" title="<?php echo Text::sprintf('COM_TEMPLATES_GRID_UNSET_LANGUAGE', $item->language_title); ?>"><?php echo $item->home; ?></span>
												<?php endif; ?>
											</a>
										<?php else : ?>
											<?php if ($item->image) : ?>
												<?php echo HTMLHelper::_('image', 'mod_languages/' . $item->image . '.gif', $item->language_title, array('title' => $item->language_title), true); ?>
											<?php else : ?>
												<span class="label" title="<?php echo $item->language_title; ?>"><?php echo $item->home; ?></span>
											<?php endif; ?>
										<?php endif; ?>
									</div>

									</div>
									<div class="card-footer">

											<?php $client = (int) $item->client_id === 1 ? 'administrator' : 'site'; ?>
											<?php
											if (!(($item->client_id == '1') && ($item->home == '0'))) {

											// tat khi la administrator va khong la home
											// bat khi khong la admin va khong phai home
												$selector_viewsite = 'ModalSelectViewSite-'.$item->id;
												echo LayoutHelper::render('xiroadmin.xiromodalbutton',
													array(
														'selector' => $selector_viewsite,
														'class'	=> 'btn btn-xiroadmin btn-raised btn-sm p-1',
														'icon' => 'fa fa-eye',
														'title' => Text::_('COM_TEMPLATES_TEMPLATE_PREVIEW')
													)
												);
												echo HTMLHelper::_(
													'bootstrap.renderModal',
													$selector_viewsite,
													array(
														'url' 		  => Route::link($client, 'index.php?templateStyle=' . (int) $item->id),
														'title'       => Text::_('COM_TEMPLATES_TEMPLATE_PREVIEW'),
														'backdrop'    => 'static',
														'height'      => '1000px',
														'width'       => '800px',
														'bodyHeight'  => 90,
														'modalWidth'  => 90,
														'footer'      => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'
																. Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>',
													)
												);
											}
											?>


											<?php if ($this->preview) : ?>
												<?php
											if (!(($item->client_id == '1') && ($item->home == '0'))) {
												$selector_viewlayout = 'ModalSelectviewlayout-'.$item->id;
												echo LayoutHelper::render('xiroadmin.xiromodalbutton',
													array(
														'selector' => $selector_viewlayout,
														'class'	=> 'btn btn-xiroadmin btn-raised btn-sm p-1',
														'icon' => 'fa fa-fw fa-object-group',
														'title' => Text::_('TPL_XIROADMIN_COM_TEMPLATES_TEMPLATE_VIEW_LAYOUT')
													)
												);
												echo HTMLHelper::_(
													'bootstrap.renderModal',
													$selector_viewlayout,
													array(
														'url' 		  => Route::link($client, 'index.php?tp=1&templateStyle=' . (int) $item->id),
														'title'       => Text::_('TPL_XIROADMIN_COM_TEMPLATES_TEMPLATE_VIEW_LAYOUT'),
														'backdrop'    => 'static',
														'height'      => '1000px',
														'width'       => '800px',
														'bodyHeight'  => 90,
														'modalWidth'  => 90,
														'footer'      => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'
																. Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>',
													)
												);
											}
											?>
											<?php else : ?>
												<span class="icon-eye-slash" aria-labelledby="nopreview-<?php echo (int) $item->id; ?>" aria-hidden="true"></span>
												<div role="tooltip" id="nopreview-<?php echo (int) $item->id; ?>"><?php echo Text::_('COM_TEMPLATES_TEMPLATE_NO_PREVIEW'); ?></div>
											<?php endif; ?>



										<?php // button thu 3 ?>
										<?php if ($canEdit) : ?>
											<a class="btn btn-xiroadmin btn-raised btn-sm" href="<?php echo Route::_('index.php?option=com_templates&task=style.edit&id=' . (int) $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->title); ?>">
												<i class="fa fa-fw fa-edit"></i>
												<?php echo Text::_('TPL_XIROADMIN_COM_TEMPLATES_TEMPLATE_ACTION_CUSTOM'); ?>

											</a>
										<?php else : ?>
											<?php echo $this->escape($item->title); ?>
										<?php endif; ?>

									</div>


								</div>
							</div>
							<?php endforeach; ?>
						</div>
					</div>

					<?php // load the pagination. ?>
					<?php echo $this->pagination->getListFooter(); ?>

				<?php endif; ?>

				<input type="hidden" name="task" value="">
				<input type="hidden" name="boxchecked" value="0">
				<?php echo HTMLHelper::_('form.token'); ?>
			</div>
		</div>
	</div>
</form>
