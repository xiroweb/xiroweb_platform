<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 *
 * @copyright   (C) 2015 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

namespace Xiroweb\Plugin\System\Xiroadmin\Field;

\defined('_JEXEC') or die;

use Joomla\CMS\Form\Field\ListField;
use Joomla\Component\Modules\Administrator\Helper\ModulesHelper;
use Joomla\Component\Templates\Administrator\Helper\TemplatesHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;



/**
 * Modules Position field.
 *
 * @since  3.4.2
 */
class ModulesPositionXiroadminField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  3.4.2
	 */
	protected $type = 'ModulesPositionXiroadmin';

	/**
	 * Client name.
	 *
	 * @var    string
	 * @since  4.0.0
	 */
	protected $client;

	protected $layout = 'xiroadmin.form.field.modulesposition';

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to get the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   4.0.0
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'client':
				return $this->$name;
		}

		return parent::__get($name);
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to set the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	public function __set($name, $value)
	{
		switch ($name)
		{
			case 'client':
				$this->$name = (string) $value;
				break;

			default:
				parent::__set($name, $value);
		}
	}

	/**
	 * Method to attach a Form object to the field.
	 *
	 * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed              $value    The form field value to validate.
	 * @param   string             $group    The field name group control value. This acts as an array container for the field.
	 *                                       For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                       full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     FormField::setup()
	 * @since   4.0.0
	 */
	public function setup(\SimpleXMLElement $element, $value, $group = null)
	{
		$result = parent::setup($element, $value, $group);

		if ($result === true)
		{
			$this->client = $this->element['client'] ? (string) $this->element['client'] : 'site';
		}

		return $result;
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   3.4.2
	 */
	public function getOptions()
	{
		$clientId = $this->client === 'administrator' ? 1 : 0;
		$options  = ModulesHelper::getPositions($clientId);

		$app = Factory::getApplication();

		$roottemplate = $app->input->getCMD('roottemplate', '');

		if (!empty($roottemplate)) {

			$options = array();

			$positions = TemplatesHelper::getPositions($clientId, $roottemplate);

			foreach ($positions as $position)
			{
				if (!$position)
				{
					$options[] = HTMLHelper::_('select.option', '', Text::_('COM_MODULES_NONE'));
				}
				else
				{
					$options[] = HTMLHelper::_('select.option', $position, $position);
				}
			}

		}

		return array_merge(parent::getOptions(), $options);
	}
}
