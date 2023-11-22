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
$image_intro_button_modal = 	'<button'
			. ' type="button"'
			. ' class="btn btn-secondary edit_attribute"'
			. ' data-bs-toggle="modal"'
			. ' data-bs-target="#ModalSelectImageIntroModal">'
			. '<span class="icon-edit" aria-hidden="true"></span> '
			. '</button>';

$this->form->removeField('image_intro', 'images');

$image_intro_modal = HTMLHelper::_(
				'bootstrap.renderModal',
				'ModalSelectImageIntroModal',
				array(
					'title'       => Text::_('TPL_XIROADMIN_COM_CONTENT_FIELD_IMAGE_INTRO_OPTIONS_MODAL_TITLE'),
					'backdrop'    => 'static',
					'height'      => '400px',
					'width'       => '800px',
					'bodyHeight'  => 70,
					'modalWidth'  => 80,
					'footer'      => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'
							. Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>',
				),
				$this->form->renderFieldset('image-intro')
			);


$image_full_label =  Text::_($this->form->getFieldsets()['image-full']->label);
$image_full_field =  $this->form->renderField('image_fulltext', 'images');
$image_full_button_modal = 	'<button'
			. ' type="button"'
			. ' class="btn btn-secondary edit_attribute"'
			. ' data-bs-toggle="modal"'
			. ' data-bs-target="#associationSelect' . '12' . 'Modal">'
			. '<span class="icon-edit" aria-hidden="true"></span> '
			. '</button>';

$this->form->removeField('image_fulltext', 'images');

$image_full_modal = HTMLHelper::_(
				'bootstrap.renderModal',
				'associationSelect' . '12' . 'Modal',
				array(
					'title'       => Text::_('TPL_XIROADMIN_COM_CONTENT_FIELD_IMAGE_FULTEXT_OPTIONS_MODAL_TITLE'),
					'backdrop'    => 'static',
					'height'      => '400px',
					'width'       => '800px',
					'bodyHeight'  => 70,
					'modalWidth'  => 80,
					'footer'      => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'
							. Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>',
				),
				$this->form->renderFieldset('image-full')
			);
?>

<div class="row">
	<div class="col">
		<div class="image-media-select">
			<?php echo  $image_intro_field . $image_intro_button_modal . $image_intro_modal ;?>
		</div>
	</div>
	<div class="col">
		<div class="image-media-select">
			<?php echo $image_full_field . $image_full_button_modal . $image_full_modal ;?>
		</div>
	</div>
</div>

