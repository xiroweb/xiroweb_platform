<?php
/**
 * @copyright   (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

use Joomla\Utilities\ArrayHelper;

defined('_JEXEC') or die;

extract($displayData);

$id = empty($id) ? '' : ' id="' . $id . '"' ;

$html = '<button '. $id .' type="button" data-bs-target="#' . $selector . '" class="'. $class . '" data-bs-toggle="modal"' . '">';$html .= empty($icon) ? '' : '<i class="'. $icon . '"></i> ';
$html .= $title . '</button>';
echo $html;
