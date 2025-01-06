<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_config
 *
 * @copyright   (C) 2014 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;

/** @var \Joomla\Component\Config\Site\View\Modules\HtmlView $this */
$fieldSets = $this->form->getFieldsets('params');
$editorText  = false;
$moduleXml   = JPATH_SITE . '/modules/' . $this->item['module'] . '/' . $this->item['module'] . '.xml';

if (is_file($moduleXml)) {
    $xml = simplexml_load_file($moduleXml);

    if (isset($xml->customContent)) {
        $editorText = true;
    }
}

echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'general0', 'recall' => true, 'breakpoint' => 768]);
$i = 0;

foreach ($fieldSets as $name => $fieldSet) :
    $label = !empty($fieldSet->label) ? $fieldSet->label : 'COM_MODULES_' . strtoupper($name) . '_FIELDSET_LABEL';
    $class = isset($fieldSet->class) && !empty($fieldSet->class) ? $fieldSet->class : '';


    if (isset($fieldSet->description) && trim($fieldSet->description)) :
        echo '<p class="tip">' . $this->escape(Text::_($fieldSet->description)) . '</p>';
    endif;
    ?>
    <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general'. $i, Text::_($label)); ?>

    <?php if ($i == 0) : ?>
        <div class="row">
            <?php echo $this->loadTemplate('config'); ?>
            <div class="col-md-9 order-first order-md-2">
                <?php if ($editorText) : ?>
                    <div class="mt-2" id="custom">
                        <?php echo $this->form->getInput('content'); ?>
                    </div>
                <?php endif; ?>
    <?php endif; ?>

    <?php foreach ($this->form->getFieldset($name) as $field) : ?>    
        <?php // If multi-language site, make menu-type selection read-only ?>
        <?php if (Multilanguage::isEnabled() && $this->item['module'] === 'mod_menu' && $field->getAttribute('name') === 'menutype') : ?>
            <?php $field->readonly = true; ?>
        <?php endif; ?>
        <?php echo $field->renderField(); ?>
    <?php endforeach; ?>

    <?php if ($i == 0) : ?>
        </div></div>
    <?php endif; ?>

    <?php echo HTMLHelper::_('uitab.endTab'); ?>

<?php 
$i++;
endforeach;
?>
<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

