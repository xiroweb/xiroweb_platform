<?php
/**
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;


$image_intro_label =  Text::_($this->form->getFieldsets()['image-intro']->label);
$image_intro_field =  $this->form->renderField('image_intro', 'images');

$this->form->removeField('image_intro', 'images');

$image_full_label =  Text::_($this->form->getFieldsets()['image-full']->label);
$image_full_field =  $this->form->renderField('image_fulltext', 'images');

$this->form->removeField('image_fulltext', 'images');

?>

<div class="row">
	<div class="col">
		<div class="image-media-select">
			<?php echo  $image_intro_field;?>
                <?php echo HTMLHelper::_('bootstrap.startAccordion', 'imageIntroOptions'); ?>
                <?php echo HTMLHelper::_('bootstrap.addSlide', 'imageIntroOptions', Text::_('COM_CONTENT_FIELD_INTRO_LABEL'), 'collapseimageintro', 'accordion-header-button-bg-x'); ?>
                    <?php echo  $this->form->renderFieldset('image-intro'); ?>
                <?php echo HTMLHelper::_('bootstrap.endSlide'); ?>
                <?php echo HTMLHelper::_('bootstrap.endAccordion'); ?>


		</div>
	</div>
	<div class="col">
		<div class="image-media-select">
			<?php echo $image_full_field ;?>
            <?php echo HTMLHelper::_('bootstrap.startAccordion', 'imageFullOptions'); ?>
                <?php echo HTMLHelper::_('bootstrap.addSlide', 'imageFullOptions', Text::_('COM_CONTENT_FIELD_FULL_LABEL'), 'collapseimagefull', 'accordion-header-button-bg-x'); ?>
                    <?php echo $this->form->renderFieldset('image-full'); ?>
                <?php echo HTMLHelper::_('bootstrap.endSlide'); ?>
                <?php echo HTMLHelper::_('bootstrap.endAccordion'); ?>
		</div>
	</div>
</div>

