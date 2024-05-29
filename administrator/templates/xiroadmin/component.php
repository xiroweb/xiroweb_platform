<?php
/**
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

/** @var \Joomla\CMS\Document\HtmlDocument $this */

$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();

// Get the hue value
preg_match('#^hsla?\(([0-9]+)[\D]+([0-9]+)[\D]+([0-9]+)[\D]+([0-9](?:.\d+)?)?\)$#i', $this->params->get('hue', 'hsl(214, 63%, 20%)'), $matches);

$linkColor = $this->params->get('link-color', '#2a69b8');
list($r, $g, $b) = sscanf($linkColor, "#%02x%02x%02x");

$linkColorDark = $this->params->get('link-color-dark', '#7fa5d4');
list($rd, $gd, $bd) = sscanf($linkColorDark, "#%02x%02x%02x");
// Enable assets
$wa->usePreset('template.xiroadmin.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
	->useStyle('template.active.language')
	->useStyle('template.xiroadmin')
	->useStyle('template.user')
	->addInlineStyle(':root {
		--hue: ' . $matches[1] . ';
        --template-bg-light: ' . $this->params->get('bg-light', '#f0f4fb') . ';
		--template-text-dark: ' . $this->params->get('text-dark', '#495057') . ';
		--template-text-light: ' . $this->params->get('text-light', '#ffffff') . ';
        --link-color: ' . $linkColor . ';
		--link-color-rgb: ' . $r . ',' . $g . ',' . $b . ';
		--template-link-color: ' . $this->params->get('link-color', '#3071a9') . ';
        --template-special-color: ' . $this->params->get('special-color', '#001B4C') . ';
	}');
$wa->addInlineStyle('[data-bs-theme=dark]{
    --link-color: ' . $linkColorDark . ';
    --template-link-color: ' . $linkColorDark . ';
    --link-color-rgb: ' . $rd . ',' . $gd . ',' . $bd . ';
}');

$wa->useScript('xiroadmin.colormodes');

// No template.js for modals
$wa->disableScript('template.xiroadmin');

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.xiroadmin.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

// Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon-pinned.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);

?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
</head>
<body class="contentpane component">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>
