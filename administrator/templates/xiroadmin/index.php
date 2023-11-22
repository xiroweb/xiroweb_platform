<?php
/**
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/** @var \Joomla\CMS\Document\HtmlDocument $this */

$app   = Factory::getApplication();
$input = $app->input;
$wa    = $this->getWebAssetManager();

// Detecting Active Variables
$option       = $input->get('option', '');
$view         = $input->get('view', '');
$layout       = $input->get('layout', 'default');
$task         = $input->get('task', 'display');
$cpanel       = $option === 'com_cpanel' || ($option === 'com_admin' && $view === 'help');
$hiddenMenu   = $app->input->get('hidemainmenu');
$sidebarState = $input->cookie->get('xiroadminSidebarState', '');

// Getting user accessibility settings
$a11y_mono      = (bool) $app->getIdentity()->getParam('a11y_mono', '');
$a11y_contrast  = (bool) $app->getIdentity()->getParam('a11y_contrast', '');
$a11y_highlight = (bool) $app->getIdentity()->getParam('a11y_highlight', '');
$a11y_font      = (bool) $app->getIdentity()->getParam('a11y_font', '');

// Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon-pinned.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);

// Template params
$logoBrandLarge  = $this->params->get('logoBrandLarge')
	? Uri::root() . htmlspecialchars($this->params->get('logoBrandLarge'), ENT_QUOTES)
	: Uri::root() . 'media/templates/administrator/xiroadmin/images/logos/logo.png';
$logoBrandSmall = $this->params->get('logoBrandSmall')
	? Uri::root() . htmlspecialchars($this->params->get('logoBrandSmall'), ENT_QUOTES)
	: Uri::root() . 'media/templates/administrator/xiroadmin/images/logos/icon-xiroweb.png';

$logoBrandLargeAlt = empty($this->params->get('logoBrandLargeAlt')) && empty($this->params->get('emptyLogoBrandLargeAlt'))
	? 'alt=""'
	: 'alt="' . htmlspecialchars($this->params->get('logoBrandLargeAlt'), ENT_COMPAT, 'UTF-8') . '"';
$logoBrandSmallAlt = empty($this->params->get('logoBrandSmallAlt')) && empty($this->params->get('emptyLogoBrandSmallAlt'))
	? 'alt=""'
	: 'alt="' . htmlspecialchars($this->params->get('logoBrandSmallAlt'), ENT_COMPAT, 'UTF-8') . '"';

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

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.xiroadmin.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

// ui integration 
if ($this->params->get('virtuemart-ui',1)) {
	$wa->useStyle('template.uiintegration.virtuemart');
}

// Set some meta data
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

$monochrome = (bool) $this->params->get('monochrome');

$colordefaultbyxiroadmin = (bool) $this->params->get('colordefaultbyxiroadmin');
if ($colordefaultbyxiroadmin) {
    // More colorful twist (by Xiroadmin)
    $wa->addInlineStyle('.mod-stats-bg-1 {background-color: #00c0ef !important;}
    .mod-stats-bg-2 {background-color: #00a65a !important;}.mod-stats-bg-3 {background-color: #f39c12 !important;}
    .header {border-top-color: #147cd0;}.header .logo{background-color: #147cd0;}joomla-tab {--xiroadmin-tab-active-color: #2196f3;}.btn-xiroadmin{background-color:#147cd0;color:#f8f9fa}.btn-xiroadmin:hover{background-color:#2196f3;color:#f8f9fa}table#moduleList tbody tr.published-1 {border-color: #4fc3f7;}.bg-xiroadmin {background-color: #00c0ef!important;}');
}
Text::script('TPL_XIROADMIN_MORE_ELEMENTS');

// @see administrator/templates/xiroadmin/html/layouts/status.php
$statusModules = LayoutHelper::render('status', ['modules' => 'status']);
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>"<?php echo $a11y_font ? ' class="a11y_font"' : ''; ?>>
<head>
	<jdoc:include type="metas" />
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
</head>

<body class="admin <?php echo $option . ' view-' . $view . ' layout-' . $layout . ($task ? ' task-' . $task : '') . ($monochrome || $a11y_mono ? ' monochrome' : '') . ($a11y_contrast ? ' a11y_contrast' : '') . ($a11y_highlight ? ' a11y_highlight' : ''); ?>">
<noscript>
	<div class="alert alert-danger" role="alert">
		<?php echo Text::_('JGLOBAL_WARNJAVASCRIPT'); ?>
	</div>
</noscript>

<jdoc:include type="modules" name="customtop" style="none" />

<?php // Header ?>
<header id="header" class="header">
	<div class="header-inside ">
		<div class="header-title d-flex flex-fill">
			<div class="d-flex align-items-center">
				<?php // No home link in edit mode (so users can not jump out) and control panel (for a11y reasons) ?>
				<?php if ($hiddenMenu || $cpanel) : ?>
					<a class="logo <?php echo $sidebarState === 'closed' ? 'small' : ''; ?>" href="<?php echo Route::_('index.php'); ?>">
					<img src="<?php echo $logoBrandLarge; ?>" <?php echo $logoBrandLargeAlt; ?>>
					<img class="logo-collapsed" src="<?php echo $logoBrandSmall; ?>" <?php echo $logoBrandSmallAlt; ?>>
					</a>
				<?php else : ?>
					<a class="logo <?php echo $sidebarState === 'closed' ? 'small' : ''; ?>" href="<?php echo Route::_('index.php'); ?>">
						<img src="<?php echo $logoBrandLarge; ?>" alt="<?php echo Text::_('TPL_XIROADMIN_BACK_TO_CONTROL_PANEL'); ?>">
						<img class="logo-collapsed" src="<?php echo $logoBrandSmall; ?>" alt="<?php echo Text::_('TPL_XIROADMIN_BACK_TO_CONTROL_PANEL'); ?>">
					</a>
				<?php endif; ?>
			</div>
			<jdoc:include type="modules" name="title" />
		</div>
		<?php echo $statusModules; ?>
	</div>
</header>

<?php // Wrapper ?>
<div id="wrapper" class="d-flex wrapper<?php echo $hiddenMenu ? '0' : ''; ?> <?php echo $sidebarState; ?>">
	<?php // Sidebar ?>
	<?php if (!$hiddenMenu) : ?>
	<?php HTMLHelper::_('bootstrap.collapse', '.toggler-burger'); ?>
		<button class="navbar-toggler toggler-burger collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-wrapper" aria-controls="sidebar-wrapper" aria-expanded="false" aria-label="<?php echo Text::_('JTOGGLE_SIDEBAR_MENU'); ?>">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div id="sidebar-wrapper" class="sidebar-wrapper sidebar-menu" <?php echo $hiddenMenu ? 'data-hidden="' . $hiddenMenu . '"' : ''; ?>>
			<div id="sidebarmenu" class="sidebar-sticky">
				<div class="sidebar-toggle item item-level-1">
					<a id="menu-collapse" href="#" aria-label="<?php echo Text::_('JTOGGLE_SIDEBAR_MENU'); ?>">
						<span id="menu-collapse-icon" class="<?php echo $sidebarState === 'closed' ? 'icon-toggle-on' : 'icon-toggle-off'; ?> icon-fw" aria-hidden="true"></span>
						<span class="sidebar-item-title"><?php echo Text::_('JTOGGLE_SIDEBAR_MENU'); ?></span>
					</a>
				</div>
				<jdoc:include type="modules" name="menu" style="none" />
			</div>
		</div>
	<?php endif; ?>

	<?php // container-fluid ?>
	<div class="container-fluid container-main">
		<?php if (!$cpanel) : ?>
			<?php // Subheader ?>
			<?php HTMLHelper::_('bootstrap.collapse', '.toggler-toolbar'); ?>
			<button class="navbar-toggler toggler-toolbar toggler-burger collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subhead-container" aria-controls="subhead-container" aria-expanded="false" aria-label="<?php echo Text::_('TPL_XIROADMIN_TOOLBAR'); ?>">
				<span class="toggler-toolbar-icon"></span>
			</button>
			<div id="subhead-container" class="subhead mb-3">
				<div class="row">
					<div class="col-md-12">
						<jdoc:include type="modules" name="toolbar" style="none" />
					</div>
				</div>
			</div>
		<?php endif; ?>
		<section id="content" class="content">
			<?php // Begin Content ?>
			<jdoc:include type="modules" name="top" style="html5" />
			<div class="row">
				<div class="col-md-12">
					<main>
						<jdoc:include type="message" />
						<jdoc:include type="component" />
					</main>
				</div>
				<?php if ($this->countModules('bottom')) : ?>
					<jdoc:include type="modules" name="bottom" style="html5" />
				<?php endif; ?>
			</div>
			<?php // End Content ?>
		</section>
	</div>
</div>
<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
