<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
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
 * @var   array    $options         Options available for this field.
 * @var   string   $dataAttribute   Miscellaneous data attributes preprocessed for HTML output
 * @var   array    $dataAttributes  Miscellaneous data attribute for eg, data-*
 */

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

$app = Factory::getApplication();

$htmlmore  = '';

$roottemplate = $app->input->get('roottemplate', '', 'CMD');
if (ComponentHelper::getParams('com_templates')->get('template_positions_display')) {

	$modalId = 'Module_' . $id;

	// $wa->useScript('field.modal-fields');
	$wa->registerAndUseScript('field.modal-fields-select-list-all-button', 'system/fields/modal-fields-select-list-all-button.js', ['relative' => true, 'version' => 'auto'], []);

	$wa->addInlineScript("
	window.jSelectModule_" . $id . " = function (id, title, catid, object, url, language) {
		window.processModalSelect('Module', '" . $id . "', id, title, catid, object, url, language);
	}",
		[],
		['type' => 'module']
	);

	$modalTitle    = Text::_('TPL_XIROADMIN_COM_MODULE_SELECT_POSITON');

	$urlSelect = 'index.php?tp=1&positionmodal=1';
	$urlSelect .= '&amp;' . Session::getFormToken() . '=1';
	$urlSelect .= '&amp;function=jSelectModule_' . $id;

	// $templatestyle =  $app->input->get('templatestyle', 0, 'INT');
	$templatestyle = $app->getUserState('com_modules.module.templatestyle', 0);
		if ($templatestyle) {
			$urlSelect .= '&templateStyle=' . $templatestyle;
		}

	$urlSelect = Route::link('site',$urlSelect);


	$htmlmore .= '<button'
	. ' class="btn btn-warning btn-raised' . ($value ? ' hidden' : '') . '"'
	. ' id="' . $id . '_select"'
	. ' data-bs-toggle="modal"'
	. ' type="button"'
	. ' data-bs-target="#ModalSelect' . $modalId . '">'
	. '<span class="icon-file" aria-hidden="true"></span> ' . Text::_('JSELECT')
	. '</button>';

	$htmlmore .= '<button'
	. ' class="btn btn-danger btn-raised ' . ($value ? '' : ' hidden') . '"'
	. ' id="' . $id . '_clear"'
	. ' type="button"'
	. ' onclick="window.processModalParent(\'' . $id . '\'); return false;">'
	. '<span class="icon-times" aria-hidden="true"></span> ' . Text::_('JALL')
				. '</button>';

				$htmlmore .= HTMLHelper::_(
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

				}

$spanstart = '<span class="input-group xiroadmin-input-group-select-position">';
$spanend = '</span>';
//
$html = array();
$attr = '';

// Initialize the field attributes.
$attr .= !empty($class) ? ' class="form-select ' . $class . '"' : ' class="form-select"';
$attr .= !empty($size) ? ' size="' . $size . '"' : '';
$attr .= $multiple ? ' multiple' : '';
$attr .= $required ? ' required' : '';
$attr .= $autofocus ? ' autofocus' : '';
$attr .= $onchange ? ' onchange="' . $onchange . '"' : '';
$attr .= !empty($description) ? ' aria-describedby="' . $name . '-desc"' : '';
$attr .= $dataAttribute;

// To avoid user's confusion, readonly="readonly" should imply disabled="disabled".
if ($readonly || $disabled)
{
	$attr .= ' disabled="disabled"';
}

// Create a read-only list (no name) with hidden input(s) to store the value(s).
if ($readonly)
{
	$html[] = HTMLHelper::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $value, $id);

	// E.g. form field type tag sends $this->value as array
	if ($multiple && is_array($value))
	{
		if (!count($value))
		{
			$value[] = '';
		}

		foreach ($value as $val)
		{
			$html[] = '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars($val, ENT_COMPAT, 'UTF-8') . '">';
		}
	}
	else
	{
		$html[] = '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '">';
	}
}
else
// Create a regular list passing the arguments in an array.
{
	$html[] = '<input class="form-control" id="' . $id . '_name" type="hidden" value="' . $value . '"  size="35">';

    $html[] = '<span class="visually-hidden"><label id="filter_position_id-lbl" for="filter_position_id">
    Position</label></span>';
    
	$listoptions = array();
	$listoptions['option.key'] = 'value';
	$listoptions['option.text'] = 'text';
	$listoptions['list.select'] = $value;
	$listoptions['id'] = $id . '_id';
	$listoptions['list.translate'] = false;
	$listoptions['option.attr'] = 'optionattr';
	$listoptions['list.attr'] = trim($attr);
	$html[] = HTMLHelper::_('select.genericlist', $options, $name, $listoptions);
}

echo $spanstart . $htmlmore . implode($html) . $spanend;
