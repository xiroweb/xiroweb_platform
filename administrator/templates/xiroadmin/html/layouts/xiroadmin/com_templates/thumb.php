<?php
/**
 * @package     XiroWeb.Xiroadmin
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2020 XiroWeb, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;


$template = $displayData['template'];
$clientId = $displayData['client_id'];

$client = ApplicationHelper::getClientInfo($clientId);
$basePath = $client->path . '/templates/' . $template;
$thumb = $basePath . '/template_thumbnail.png';
$preview = $basePath . '/template_preview.png';
$html = '';

$basePathMedia =    'media/templates/' . $client->name . '/' . $template;
$thumbMedia = $basePathMedia . '/images/template_thumbnail.png';
$previewMedia = $basePathMedia . '/images/template_preview.png';

if (file_exists($thumb))
{

    $clientPath = ($clientId == 0) ? '' : 'administrator/';
    $thumb = $clientPath . 'templates/' . $template . '/template_thumbnail.png';
    $html = HTMLHelper::_('image', $thumb, Text::_('COM_TEMPLATES_PREVIEW'), array('class' => 'w-100'));

    if (file_exists($preview))
    {
        $preview = $clientPath . 'templates/' . $template . '/template_preview.png';
        $html = HTMLHelper::_('image', $preview , Text::_('COM_TEMPLATES_PREVIEW'), array('class' => 'w-100'));
    }
} 

if (!(file_exists($thumb)) && (file_exists(JPATH_ROOT . '/' . $thumbMedia)))
{
    $html = HTMLHelper::_('image', $thumbMedia, Text::_('COM_TEMPLATES_PREVIEW'), array('class' => 'w-100'));

    if (file_exists($preview))
    {  
        $html = HTMLHelper::_('image', $previewMedia , Text::_('COM_TEMPLATES_PREVIEW'), array('class' => 'w-100'));
    }
}

echo $html;
