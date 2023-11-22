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

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Component\ComponentHelper;


extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string   $autocomplete    Autocomplete attribute for the field.
 * @var   boolean  $autofocus       Is autofocus enabled?
 * @var   string   $class           Classes for the input.
 * @var   string   $description     Description of the field.
 * @var   boolean  $disabled        Is this field disabled?
 * @var   string   $group           Group the field belongs to. <fields> section in form XML.
 * @var   boolean  $hidden          Is this field hidden in the form?
 * @var   string   $hint            Placeholder for the field.
 * @var   string   $id              DOM id of the field.
 * @var   string   $label           Label of the field.
 * @var   string   $labelclass      Classes to apply to the label.
 * @var   boolean  $multiple        Does this field support multiple values?
 * @var   string   $name            Name of the input field.
 * @var   string   $onchange        Onchange attribute for the field.
 * @var   string   $onclick         Onclick attribute for the field.
 * @var   string   $pattern         Pattern (Reg Ex) of value of the form field.
 * @var   boolean  $readonly        Is this field read only?
 * @var   boolean  $repeat          Allows extensions to duplicate elements.
 * @var   boolean  $required        Is this field required?
 * @var   integer  $size            Size attribute of the input.
 * @var   boolean  $spellcheck      Spellcheck state for the form field.
 * @var   string   $validate        Validation rules to apply.
 * @var   string   $value           Value attribute of the field.
 * @var   array    $checkedOptions  Options that will be set as checked.
 * @var   boolean  $hasValue        Has this field a value assigned?
 * @var   array    $options         Options available for this field.
 * @var   array    $inputType       Options available for this field.
 * @var   string   $accept          File types that are accepted.
 * @var   array    $positions       Array of the positions
 */

if (ComponentHelper::getParams('com_templates')->get('template_positions_display') && !$client) {


	$app = Factory::getApplication();

	Text::script('JGLOBAL_SELECT_NO_RESULTS_MATCH');
	Text::script('JGLOBAL_SELECT_PRESS_TO_SELECT');

	$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

	$modalId = 'Module_' . $id;

	$wa->useScript('field.modal-fields');

	$wa->addInlineScript("
	window.jSelectModule_" . $id . " = function (id, title, catid, object, url, language) {
		window.processModalSelect('Module', '" . $id . "', id, title, catid, object, url, language);
	}",
		[],
		['type' => 'module']
	);

	Text::script('JGLOBAL_ASSOCIATIONS_PROPAGATE_FAILED');
	$modalTitle    = Text::_('TPL_XIROADMIN_COM_MODULE_SELECT_POSITON');

	$urlSelect = 'index.php?tp=1&positionmodal=1';
	$urlSelect .= '&amp;' . Session::getFormToken() . '=1';
	$urlSelect .= '&amp;function=jSelectModule_' . $id;

	$templatestyle = $app->getUserState('com_modules.module.templatestyle', 0);
		if ($templatestyle) {
			$urlSelect .= '&templateStyle=' . $templatestyle;
		}

	$urlSelect = Route::link('site',$urlSelect);


	$html  = '';

	$html .= '<span class="input-group">';
	$html .= '<input class="form-control" id="' . $id . '_name" type="hidden" value="' . $value . '"  size="35">';
	$html .= '<input size="35" class="form-control" type="text" id="' . $id . '_id" ' . $class . ' data-required="' . (int) $required . '" name="' . $name
			. '" data-text="' . htmlspecialchars(Text::_('TPL_XIROADMIN_COM_MODULE_SELECT_POSITON'), ENT_COMPAT, 'UTF-8') . '" value="' . $value . '">';

	$html .= '<button'
				. ' class="btn btn-warning btn-raised' . ($value ? ' hidden' : '') . '"'
				. ' id="' . $id . '_select"'
				. ' data-bs-toggle="modal"'
				. ' type="button"'
				. ' data-bs-target="#ModalSelect' . $modalId . '">'
				. '<span class="icon-file" aria-hidden="true"></span> ' . Text::_('JSELECT')
				. '</button>';

	$html .= '<button'
				. ' class="btn btn-danger btn-raised ' . ($value ? '' : ' hidden') . '"'
				. ' id="' . $id . '_clear"'
				. ' type="button"'
				. ' onclick="window.processModalParent(\'' . $id . '\'); return false;">'
				. '<span class="icon-times" aria-hidden="true"></span> ' . Text::_('JCLEAR')
				. '</button>';
	$html .= '</span>';

	$html .= HTMLHelper::_(
		'bootstrap.renderModal',
		'ModalSelect' . $modalId,
		array(
			'title'       => $modalTitle,
			'url'         => $urlSelect,
			'height'      => '400px',
			'width'       => '800px',
			'bodyHeight'  => 80,
			'modalWidth'  => 90,
			'footer'      => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'
								. Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>',
		)
	);

	echo $html;

} else {

	$attributes = array(
		'class="' . $class . '"',
		' allow-custom',
		' search-placeholder="' . $this->escape(Text::_('JGLOBAL_TYPE_OR_SELECT_SOME_OPTIONS')) . '" ',
	);

	$selectAttr = array(
		$disabled ? 'disabled' : '',
		$readonly ? 'readonly' : '',
		strlen($hint) ? 'placeholder="' . $this->escape($hint) . '"' : '',
		$onchange ? ' onchange="' . $onchange . '"' : '',
		$autofocus ? ' autofocus' : '',
	);

	if ($required)
	{
		$selectAttr[] = ' required class="required"';
		$attributes[] = ' required';
	}

	Text::script('JGLOBAL_SELECT_NO_RESULTS_MATCH');
	Text::script('JGLOBAL_SELECT_PRESS_TO_SELECT');

	Factory::getDocument()->getWebAssetManager()
		->usePreset('choicesjs')
		->useScript('webcomponent.field-fancy-select');

	?>
	<joomla-field-fancy-select <?php echo implode(' ', $attributes); ?>><?php
		echo HTMLHelper::_('select.groupedlist', $positions, $name, array(
				'id'          => $id,
				'list.select' => $value,
				'list.attr'   => implode(' ', $selectAttr),
			)
		);
	?></joomla-field-fancy-select>
<?php
}

