<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2011 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\HTML\Helpers;

use Joomla\CMS\Date\Date as DateHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Extended Utility class for handling date display.
 *
 * @since  2.5
 */
abstract class Date
{
    /**
     * Function to convert a static time into a relative measurement
     *
     * @param   string  $date    The date to convert
     * @param   string  $unit    The optional unit of measurement to return
     *                           if the value of the diff is greater than one
     * @param   string  $time    An optional time to compare to, defaults to now
     * @param   string  $format  An optional format for the HTMLHelper::date output
     *
     * @return  string  The converted time string
     *
     * @since   2.5
     */
    public static function relative($date, $unit = null, $time = null, $format = null)
    {
        if ($time === null) {
            // Get now
            $time = new DateHelper('now');
        }

        // Get the difference in seconds between now and the time
        $diff = strtotime($time) - strtotime($date);

        // Less than a minute
        if ($diff < 60) {
            return Text::_('JLIB_HTML_DATE_RELATIVE_LESSTHANAMINUTE');
        }

        // Round to minutes
        $diff = round($diff / 60);

        // 1 to 59 minutes
        if ($diff < 60 || $unit === 'minute') {
            return Text::plural('JLIB_HTML_DATE_RELATIVE_MINUTES', $diff);
        }

        // Round to hours
        $diff = round($diff / 60);

        // 1 to 23 hours
        if ($diff < 24 || $unit === 'hour') {
            return Text::plural('JLIB_HTML_DATE_RELATIVE_HOURS', $diff);
        }

        // Round to days
        $diff = round($diff / 24);

        // 1 to 6 days
        if ($diff < 7 || $unit === 'day') {
            return Text::plural('JLIB_HTML_DATE_RELATIVE_DAYS', $diff);
        }

        // Round to weeks
        $diff = round($diff / 7);

        // 1 to 4 weeks
        if ($diff <= 4 || $unit === 'week') {
            return Text::plural('JLIB_HTML_DATE_RELATIVE_WEEKS', $diff);
        }

        // Over a month, return the absolute time
        return HTMLHelper::_('date', $date, $format);
    }
}
