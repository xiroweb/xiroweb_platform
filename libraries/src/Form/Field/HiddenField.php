<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\Form\Field;

use Joomla\CMS\Form\FormField;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Form Field class for the Joomla Platform.
 * Provides a hidden field
 *
 * @link   https://html.spec.whatwg.org/multipage/input.html#hidden-state-(type=hidden)
 * @since  1.7.0
 */
class HiddenField extends FormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.7.0
     */
    protected $type = 'Hidden';

    /**
     * Name of the layout being used to render the field
     *
     * @var    string
     * @since  3.7
     */
    protected $layout = 'joomla.form.field.hidden';

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since   1.7.0
     */
    protected function getInput()
    {
        // Trim the trailing line in the layout file
        return rtrim($this->getRenderer($this->layout)->render($this->getLayoutData()), PHP_EOL);
    }

    /**
     * Method to get the data to be passed to the layout for rendering.
     *
     * @return  array
     *
     * @since 3.7
     */
    protected function getLayoutData()
    {
        return parent::getLayoutData();
    }
}
