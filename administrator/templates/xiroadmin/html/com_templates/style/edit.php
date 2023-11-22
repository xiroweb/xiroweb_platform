<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_templates
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Component\ComponentHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('form.validate');

$this->useCoreUI = true;

$user = Factory::getUser();
?>
<?php 
$client = (int) $this->item->client_id === 1 ? 'administrator' : 'site';
$selector_viewlayout = 'ModalSelectviewlayout-'.$this->item->id;
echo HTMLHelper::_(
	'bootstrap.renderModal',
	$selector_viewlayout,
	array(
		'url' 		  => Route::link($client, 'index.php?tp=1&templateStyle=' . (int) $this->item->id),
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

$selector_addmodule = 'ModalSelectaddmodule-'.$this->item->id;
echo HTMLHelper::_(
	'bootstrap.renderModal',
	$selector_addmodule,
	array(
		'url' 		  => Route::link('administrator', 'index.php?option=com_modules&tmpl=component&view=select&templateStyle=' . (int) $this->item->id . '&roottemplate=' . (string) $this->item->template),
		'title'       => Text::_('TPL_XIROADMIN_COM_TEMPLATES_TEMPLATE_EDIT_ADD_MODULE_MODAL_TITLE'),
		'backdrop'    => 'static',
		'height'      => '1000px',
		'width'       => '800px',
		'bodyHeight'  => 90,
		'modalWidth'  => 90,
		'footer'      => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'
				. Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>',
	)
);

$selector_modulemanager = 'ModalSelectmodulemanager-'.$this->item->id;
echo HTMLHelper::_(
	'bootstrap.renderModal',
	$selector_modulemanager,
	array(
		'url' 		  => Route::link('administrator', 'index.php?option=com_modules&tmpl=component&templateStyle=' . (int) $this->item->id . '&roottemplate=' . (string) $this->item->template),
		'title'       => Text::_('TPL_XIROADMIN_COM_TEMPLATES_TEMPLATE_EDIT_MANAGER_MODULE_MODAL_TITLE') . ' ' . $this->escape($this->item->title),
		'backdrop'    => 'static',
		'height'      => '1000px',
		'width'       => '800px',
		'bodyHeight'  => 90,
		'modalWidth'  => 90,
		'footer'      => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'
				. Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>',
	)
);
$selector_preview = 'ModalSelectpreview-'.$this->item->id;
echo HTMLHelper::_(
	'bootstrap.renderModal',
	$selector_preview,
	array(
		'url' 		  => Route::link($client, 'index.php?templateStyle=' . (int) $this->item->id),
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
?>
<form action="<?php echo Route::_('index.php?option=com_templates&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="style-form" aria-label="<?php echo Text::_('COM_TEMPLATES_STYLE_FORM_EDIT'); ?>" class="form-validate">

    <?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="text-start">
			<?php if ($this->item->client_id == '0') : ?>
				<?php
					
					echo LayoutHelper::render('xiroadmin.xiromodalbutton',
						array(
							'selector' => $selector_viewlayout,
							'class'	=> 'btn btn-xiroadmin btn-raised mb-2 btn-sm',
							'icon' => 'fa fa-fw fa-object-group',
							'title' => Text::_('TPL_XIROADMIN_COM_TEMPLATES_TEMPLATE_VIEW_LAYOUT')
						)
					);
				?>
				<?php
				if (ComponentHelper::getParams('com_templates')->get('template_positions_display')) {
			
					echo LayoutHelper::render('xiroadmin.xiromodalbutton',
						array(
							'selector' => $selector_addmodule,
							'class'	=> 'btn btn-xiroadmin btn-raised mb-2 btn-sm me-1',
							'icon' => 'far fa-calendar-plus',
							'title' => Text::_('TPL_XIROADMIN_COM_TEMPLATES_TEMPLATE_EDIT_ADD_MODULE_BTN')
						)
					);

					echo LayoutHelper::render('xiroadmin.xiromodalbutton',
						array(
							'selector' => $selector_modulemanager,
							'class'	=> 'btn btn-xiroadmin btn-raised mb-2 btn-sm',
							'icon' => 'fa fa-cubes',
							'title' => Text::_('TPL_XIROADMIN_COM_TEMPLATES_TEMPLATE_EDIT_MANAGER_MODULE_BTN')
						)
					);
				}
				
				?>
			<?php endif; ?>
			<?php
					
					echo LayoutHelper::render('xiroadmin.xiromodalbutton',
						array(
							'selector' => $selector_preview,
							'class'	=> 'btn btn-xiroadmin btn-raised mb-2 btn-sm',
							'icon' => 'fa fa-fw fa-object-group',
							'title' => Text::_('COM_TEMPLATES_TEMPLATE_PREVIEW')
						)
					);
				?>
		</div>

    <div class="main-card">
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'details', 'recall' => true, 'breakpoint' => 768]); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'details', Text::_('JDETAILS')); ?>

        <div class="row">
            <div class="col-lg-9">
                <h2>
                    <?php echo Text::_($this->item->template); ?>
                </h2>
                <div class="info-labels">
                    <span class="badge bg-secondary">
                        <?php echo $this->item->client_id == 0 ? Text::_('JSITE') : Text::_('JADMINISTRATOR'); ?>
                    </span>
                </div>
                <div>
                    <p><?php echo Text::_($this->item->xml->description); ?></p>
                    <?php
                    $this->fieldset = 'description';
                    $description = LayoutHelper::render('joomla.edit.fieldset', $this);
                    ?>
                    <?php if ($description) : ?>
                        <p class="readmore">
                            <a href="#" onclick="document.querySelector('#tab-description').click();">
                                <?php echo Text::_('JGLOBAL_SHOW_FULL_DESCRIPTION'); ?>
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
                <?php
                $this->fieldset = 'basic';
                $html = LayoutHelper::render('joomla.edit.fieldset', $this);
                echo $html ? '<hr>' . $html : '';
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                // Set main fields.
                $this->fields = array(
                    'home',
                    'client_id',
                    'template'
                );
                ?>
                <?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
                <?php echo $this->form->renderField('inheritable'); ?>
                <?php echo $this->form->renderField('parent'); ?>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php if ($description) : ?>
            <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'description', Text::_('JGLOBAL_FIELDSET_DESCRIPTION')); ?>
            <fieldset id="fieldset-description" class="options-form">
                <legend><?php echo Text::_('JGLOBAL_FIELDSET_DESCRIPTION'); ?></legend>
                <div>
                <?php echo $description; ?>
                </div>
            </fieldset>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php endif; ?>

        <?php
        $this->fieldsets = array();
        $this->ignore_fieldsets = array('basic', 'description');
        echo LayoutHelper::render('joomla.edit.params', $this);
        ?>

        <?php if ($user->authorise('core.edit', 'com_menus') && $this->item->client_id == 0 && $this->canDo->get('core.edit.state')) : ?>
            <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'assignment', Text::_('COM_TEMPLATES_MENUS_ASSIGNMENT')); ?>
            <fieldset id="fieldset-assignment" class="options-form">
                <legend><?php echo Text::_('COM_TEMPLATES_MENUS_ASSIGNMENT'); ?></legend>
                <div>
                <?php echo $this->loadTemplate('assignment'); ?>
                </div>
            </fieldset>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php endif; ?>

        <?php echo HTMLHelper::_('uitab.endTabSet'); ?>

        <input type="hidden" name="task" value="">
        <?php echo HTMLHelper::_('form.token'); ?>
    </div>
</form>
