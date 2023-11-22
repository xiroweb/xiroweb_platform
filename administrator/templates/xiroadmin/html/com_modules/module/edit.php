<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;


HTMLHelper::_('behavior.combobox');

$hasContent = isset($this->item->xml->customContent);
$hasContentFieldName = 'content';

// For a later improvement
if ($hasContent)
{
	$hasContentFieldName = 'content';
}

// Get Params Fieldsets
$this->fieldsets = $this->form->getFieldsets('params');
$this->useCoreUI = true;

Text::script('JYES');
Text::script('JNO');
Text::script('JALL');
Text::script('JTRASHED');

$this->document->addScriptOptions('module-edit', ['itemId' => $this->item->id, 'state' => (int) $this->item->id == 0 ? 'Add' : 'Edit']);

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate')
	->useScript('com_modules.admin-module-edit');


$app = Factory::getApplication();
$input = $app->input;

// value stopped by modal
// $templateStyle = $input->getInt('templateStyle',0);
// $roottemplate = $input->get('roottemplate', '', 'CMD');

$templateStyle = $app->getUserState('com_modules.module.templatestyle', 0);
$roottemplate = $app->getUserState('com_modules.module.roottemplate', '');

// In case of modal
$isModal = $input->get('layout') === 'modal';
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';
$tmplurl = $input->get('tmpl', '', 'CMD');

// don't close modal when active save button
if ($templateStyle) {
	$wa->disableScript('com_modules.admin-module-edit');
	$wa->registerAndUseScript('com_modules.admin-module-edit-modal', 'com_modules/admin-module-edit-modal.js', ['relative' => true, 'version' => 'auto'], []);
}



$js = <<<JS
function openTabDescription() {
	document.querySelector('[aria-controls="description"]').click();
};
JS;
$wa->addInlineScript($js, [], []);
?>

<form action="<?php echo Route::_('index.php?option=com_modules&layout=' . $layout . $tmpl . '&client_id=' . $this->form->getValue('client_id') . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="module-form" aria-label="<?php echo Text::_('COM_MODULES_FORM_TITLE_' . ((int) $this->item->id === 0 ? 'NEW' : 'EDIT'), true); ?>" class="form-validate">

	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="main-card">
		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'general', 'recall' => true, 'breakpoint' => 768]); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', Text::_('COM_MODULES_MODULE')); ?>

		<div class="row">
			<div class="col-md-9 order-2">
				<?php if ($this->item->xml) : ?>
					<?php if ($this->item->xml->description) : ?>
						<h2>
							<?php
							if ($this->item->xml)
							{
								echo ($text = (string) $this->item->xml->name) ? Text::_($text) : $this->item->module;
							}
							else
							{
								echo Text::_('COM_MODULES_ERR_XML');
							}
							?>
						</h2>
						<div class="info-labels">
							<span class="badge bg-secondary">
								<?php echo $this->item->client_id == 0 ? Text::_('JSITE') : Text::_('JADMINISTRATOR'); ?>
							</span>
						</div>
						<div>
							<?php
							$this->fieldset    = 'description';
							$short_description = Text::_($this->item->xml->description);
							$long_description  = LayoutHelper::render('joomla.edit.fieldset', $this);

							if (!$long_description)
							{
								$truncated = HTMLHelper::_('string.truncate', $short_description, 550, true, false);

								if (strlen($truncated) > 500)
								{
									$long_description  = $short_description;
									$short_description = HTMLHelper::_('string.truncate', $truncated, 250);

									if ($short_description == $long_description)
									{
										$long_description = '';
									}
								}
							}
							?>
							<p><?php echo $short_description; ?></p>
							<?php if ($long_description) : ?>
								<p class="readmore">
									<a href="#" onclick="openTabDescription();">
										<?php echo Text::_('JGLOBAL_SHOW_FULL_DESCRIPTION'); ?>
									</a>
								</p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				<?php else : ?>
					<div class="alert alert-danger">
						<span class="icon-exclamation-triangle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('ERROR'); ?></span>
						<?php echo Text::_('COM_MODULES_ERR_XML'); ?>
					</div>
				<?php endif; ?>
				<?php
				if ($hasContent)
				{
					echo $this->form->getInput($hasContentFieldName);
				}
				$this->fieldset = 'basic';
				$html = LayoutHelper::render('joomla.edit.fieldset', $this);
				echo $html ? '<hr>' . $html : '';
				?>
				<?php if ($this->item->client_id == 0) : ?>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-12">
					<div class="xiroadmin-card mb-4">
						<div class="text-center">
							<span><i class="menu-menus fa fa-sitemap fa-3x"></i></span>
						</div>
							<div class="form-vertical">
							<?php echo $this->loadTemplate('assignment'); ?>
							</div>
					</div>
					</div>
				</div>
				<?php endif; ?>

			</div>
			<div class="col-md-3 order-1">
				<div class="xiroadmin-card mb-4">
					<div class="text-center">
							<span>
								<img width="100" src="<?php echo  JUri::root() . '/media/templates/administrator/xiroadmin/images/web-design.png'; ?>" />
							</span>
						</div>

					<?php
					// Set main fields.
					$this->fields = array(
						'position',
					);

					?>
					<?php if ($this->item->client_id == 0) : ?>
						<?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
					<?php else : ?>
						<?php echo LayoutHelper::render('joomla.edit.admin_modules', $this); ?>
					<?php endif; ?>

				</div>

				<?php
				// Set main fields.
				$this->fields = array(
					'showtitle',
					'published',
					'publish_up',
					'publish_down',
					'access',
					'ordering',
					'language',
					'note'
				);

				?>
				<?php if ($this->item->client_id == 0) : ?>
					<?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
				<?php else : ?>
					<?php echo LayoutHelper::render('joomla.edit.admin_modules', $this); ?>
				<?php endif; ?>

			</div>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php if (isset($long_description) && $long_description != '') : ?>
			<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'description', Text::_('JGLOBAL_FIELDSET_DESCRIPTION')); ?>
				<div class="card">
					<div class="card-body">
						<?php echo $long_description; ?>
					</div>
				</div>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>
		<?php endif; ?>

		<?php
		$this->fieldsets        = array();
		$this->ignore_fieldsets = array('basic', 'description');
		echo LayoutHelper::render('joomla.edit.params', $this);
		?>

		<?php if ($this->canDo->get('core.admin')) : ?>
			<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'permissions', Text::_('COM_MODULES_FIELDSET_RULES')); ?>
			<fieldset id="fieldset-permissions" class="options-form">
				<legend><?php echo Text::_('COM_MODULES_FIELDSET_RULES'); ?></legend>
				<div>
				<?php echo $this->form->getInput('rules'); ?>
				</div>
			</fieldset>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>
		<?php endif; ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

		<input type="hidden" name="task" value="">
		<?php if ($templateStyle && empty($input->get('return', '', 'BASE64'))) {

			//  $urlreutrn = 'index.php?option=com_templates&view=styles';
			$urlreutrn = Uri::base() . 'index.php?option=com_modules&tmpl=component';
			$urlreutrn .= ($roottemplate ? '&roottemplate=' . $roottemplate : '');
			$urlreutrn .= ($templateStyle ? '&templateStyle=' . $templateStyle : '');

			$urlreutrn  = base64_encode(Route::_($urlreutrn));
			$input->set('return', $urlreutrn);
		}
		?>
		<input type="hidden" name="return" value="<?php echo $input->get('return', null, 'BASE64'); ?>">
		<?php echo HTMLHelper::_('form.token'); ?>
		<?php echo $this->form->getInput('module'); ?>
		<?php echo $this->form->getInput('client_id'); ?>
	</div>
</form>
