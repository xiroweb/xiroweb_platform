<?php

/**
 * @subpackage  Templates.xirostart
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @copyright   (C) 2021 XiroWeb Ltd. <https://www.xiroweb.com>
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */
if ($this->params->get('hideGeneratorMeta',1)) {
    $this->setGenerator(null);
}

$app   = Factory::getApplication();
$input = $app->getInput();
$wa    = $this->getWebAssetManager();

// Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', 'favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon-pinned.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);

// Detecting Active Variables
$option   = $input->getCmd('option', '');
$view     = $input->getCmd('view', '');
$layout   = $input->getCmd('layout', '');
$task     = $input->getCmd('task', '');
$itemid   = $input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu     = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';

// Use a font scheme if set in the template style options
$paramsFontScheme = $this->params->get('useFontScheme', false);
$fontStyles       = '';

if ($paramsFontScheme) {
    if (stripos($paramsFontScheme, 'https://') === 0) {
        $this->getPreloadManager()->preconnect('https://fonts.googleapis.com/', ['crossorigin' => 'anonymous']);
        $this->getPreloadManager()->preconnect('https://fonts.gstatic.com/', ['crossorigin' => 'anonymous']);
        $this->getPreloadManager()->preload($paramsFontScheme, ['as' => 'style', 'crossorigin' => 'anonymous']);
        $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, [], ['media' => 'print', 'rel' => 'lazy-stylesheet', 'onload' => 'this.media=\'all\'', 'crossorigin' => 'anonymous']);

        if (preg_match_all('/family=([^?:]*):/i', $paramsFontScheme, $matches) > 0) {
            $fontStyles = '--template-font-family-body: "' . str_replace('+', ' ', $matches[1][0]) . '", sans-serif;
			--template-font-family-headings: "' . str_replace('+', ' ', isset($matches[1][1]) ? $matches[1][1] : $matches[1][0]) . '", sans-serif;
			--template-font-weight-normal: 400;
			--template-font-weight-headings: 700;';
        }
    } elseif ($paramsFontScheme === 'system') {
        $fontStylesBody    = $this->params->get('systemFontBody', '');
        $fontStylesHeading = $this->params->get('systemFontHeading', '');

        if ($fontStylesBody) {
            $fontStyles = '--template-font-family-body: ' . $fontStylesBody . ';
            --template-font-weight-normal: 400;';
        }
        if ($fontStylesHeading) {
            $fontStyles .= '--template-font-family-headings: ' . $fontStylesHeading . ';
            --template-font-weight-headings: 700;';
        }
    } else {
        $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, ['version' => 'auto'], ['media' => 'print', 'rel' => 'lazy-stylesheet', 'onload' => 'this.media=\'all\'']);
        $this->getPreloadManager()->preload($wa->getAsset('style', 'fontscheme.current')->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);
    }
}

if (!empty($fontStyles)){
    $wa->addInlineStyle(":root{
        $fontStyles
    }");
}

// Enable assets
$wa->usePreset('template.xirostart.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
    ->useStyle('template.active.language')
    ->useStyle('template.user')
    ->useScript('template.user');
$wa->useStyle('template.custom');
$wa->useScript('template.custom');

$customizing_colors = [];
if ($this->params->get('body_color_onoff', 0) ) {
    $customizing_colors[] = "--body-color: ";
    $customizing_colors[] = $this->params->get('body_color_box', '');
    $customizing_colors[] = ";";
}
if ($this->params->get('link_color_onoff', 0) ) {
    $customizing_colors[] = "--link-color: ";
    $customizing_colors[] = $this->params->get('link_color_box', '');
    $customizing_colors[] = ";";
}
if ($this->params->get('link_color_hover_onoff', 0) ) {
    $customizing_colors[] = "--link-hover-color: ";
    $customizing_colors[] = $this->params->get('link_color_hover_box', '');
    $customizing_colors[] = ";";
}
if ($this->params->get('primary_onoff', 0) ) {
    $color_primary = $this->params->get('primary_box', '');
    $customizing_colors[] = "--primary: " . $color_primary . ";";
    $customizing_colors[] = ".btn-primary {";
    $customizing_colors[] =  "--btn-bg: " . $color_primary . ";";
    $customizing_colors[] =  "--btn-border-color: " . $color_primary . ";";
    $customizing_colors[] =  "--btn-hover-bg: " . $color_primary . "c2;";
    $customizing_colors[] =  "--btn-hover-border-color: " . $color_primary . ";";
    $customizing_colors[] =  "--btn-active-bg: " . $color_primary . ";";
    $customizing_colors[] =  "--btn-active-border-color: " . $color_primary . ";";
    $customizing_colors[] =  "--btn-disabled-bg: " . $color_primary . ";";
    $customizing_colors[] =  "--btn-disabled-border-color: " . $color_primary . ";";
    $customizing_colors[] = "}";
}
$customizing_colors_str = implode("",$customizing_colors);
if (!empty($customizing_colors_str)) {
    $wa->addInlineStyle(":root, [data-bs-theme=\"light\"] {
        $customizing_colors_str
    }");
}

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.xirostart.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

// Logo file or site title param
if ($this->params->get('logoFile')) {
    $logo = HTMLHelper::_('image', Uri::root(false) . htmlspecialchars($this->params->get('logoFile'), ENT_QUOTES), $sitename, ['loading' => 'eager', 'decoding' => 'async'], false, 0);
} elseif ($this->params->get('siteTitle')) {
    $logo = '<span title="' . $sitename . '">' . htmlspecialchars($this->params->get('siteTitle'), ENT_COMPAT, 'UTF-8') . '</span>';
} else {
    $logo = '<span class="your-logo" title="' . Text::_('TPL_XIROSTART_YOUR_LOGO') . '">' . Text::_('TPL_XIROSTART_YOUR_LOGO') . '</span><span class="your-logo-description">'. Text::_('TPL_XIROSTART_YOUR_LOGO_DESCRIPTION') . '</span>';
}

// Container
$fluidcontainer = $this->params->get('fluidContainer') ? '-fluid' : '';

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

$stickyHeader = $this->params->get('stickyHeader') ? 'position-sticky sticky-top' : '';
if ($input->get('tp',0,'INT')) {
    // unset stickyHeader when preview position on
    $stickyHeader = '';
}

// Defer fontawesome for increased performance. Once the page is loaded javascript changes it to a stylesheet.
$wa->getAsset('style', 'fontawesome')->setAttribute('rel', 'lazy-stylesheet');

//  when setting template option hideModuleWhenEditmodule is yes, and page is edit module form, 
$hideModuleWhenEditmodule = $this->params->get('hideModuleWhenEditmodule', 1) && ($input->getCmd('option', '') == 'com_config');
// set FALSE
$hideModuleWhenEditmodule = !$hideModuleWhenEditmodule;

//  when setting template option hideModuleWhenEditLayout is yes,of com Content..., set FALSE
$hideModuleWhenEditLayout = $this->params->get('hideModuleWhenEditLayout', 0);
//  and page is edit content form 
$hideModuleWhenEditLayout = $hideModuleWhenEditLayout && ($input->getCmd('view', '') == 'form') && ($input->getCmd('layout', '') == 'edit');
// of com Content, com Contact...
$componentsOpt = ['com_content', 'com_contact'];
$hideModuleWhenEditLayout = $hideModuleWhenEditLayout && in_array($input->getCmd('option', ''), $componentsOpt);
// set FALSE
$hideModuleWhenEditLayout = ! $hideModuleWhenEditLayout;

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>

<body class="site <?php echo $option
    . ' view-' . $view
    . ($layout ? ' layout-' . $layout : ' no-layout')
    . ($task ? ' task-' . $task : ' no-task')
    . ($itemid ? ' itemid-' . $itemid : '')
    . ($pageclass ? ' ' . $pageclass : '')
    . ($this->direction == 'rtl' ? ' rtl' : '');
?>">
    <header class="header <?php echo $stickyHeader ? ' ' . $stickyHeader : ''; ?>">

        <?php if ($this->countModules('topbar')) : ?>
            <div class="container<?php echo $fluidcontainer; ?>">
                <jdoc:include type="modules" name="topbar" style="html5" />
            </div>
        <?php endif; ?>
        <?php if ($this->countModules('header-fullwidth')) : ?>
            <jdoc:include type="modules" name="header-fullwidth" style="html5" />
        <?php endif; ?>

        <?php if ($this->countModules('below-top')) : ?>
            <div class="container<?php echo $fluidcontainer; ?>">
                <jdoc:include type="modules" name="below-top" style="html5" />
            </div>
        <?php endif; ?>

        <?php if ($this->params->get('brand', 1) || $this->countModules('header-right', true)) : ?>
            <div class="container<?php echo $fluidcontainer; ?> header-brand">
                <div class="row">
                    <?php  if ($this->params->get('brand', 1)) : ?>
                    <div class="site-brand col">
                        <a class="<?php echo $this->params->get('logoFile') ? 'site-logo' : 'site-logo-text'; ?>" href="<?php echo $this->baseurl; ?>/">
                            <?php echo $logo; ?>
                        </a>
                        <?php if ($this->params->get('siteDescription')) : ?>
                            <div class="site-description"><?php echo htmlspecialchars($this->params->get('siteDescription')); ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($this->countModules('header-right', true)) : ?>
                        <div class="col-12 col-md-3 header-right">
                            <jdoc:include type="modules" name="header-right" style="html5" />
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('menu-xirostart', true) || $this->countModules('search', true)) : ?>
            <div class="container<?php echo $fluidcontainer; ?> sitemenu-header">
                <div class="row">
                    <?php if ($this->countModules('menu-xirostart', true)) : ?>
                        <div class="col">
                            <jdoc:include type="modules" name="menu-xirostart" style="none" />
                        </div>
                    <?php endif; ?>
                    <?php if ($this->countModules('search', true)) : ?>
                        <div class="col-12 col-md-3 block-search">
                            <jdoc:include type="modules" name="search" style="none" />
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('header-bottom-fullwidth')) : ?>
            <jdoc:include type="modules" name="header-bottom-fullwidth" style="html5" />
        <?php endif; ?>
    </header>
    
        <?php if ($this->countModules('banner', true) && $hideModuleWhenEditmodule && $hideModuleWhenEditLayout) : ?>
            <div class="container-fluid g-0">
                <jdoc:include type="modules" name="banner" style="html5" />
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('top-a', true) && $hideModuleWhenEditmodule && $hideModuleWhenEditLayout) : ?>
        <div class="container<?php echo $fluidcontainer; ?>">
            <jdoc:include type="modules" name="top-a" style="html5" />
        </div>
        <?php endif; ?>

        <?php if ($this->countModules('top-b', true) && $hideModuleWhenEditmodule && $hideModuleWhenEditLayout) : ?>
        <div class="container<?php echo $fluidcontainer; ?>">
            <jdoc:include type="modules" name="top-b" style="html5" />
        </div>
        <?php endif; ?>

        <?php if ($this->countModules('top-c-fullwidth', true) && $hideModuleWhenEditmodule && $hideModuleWhenEditLayout) : ?>
            <jdoc:include type="modules" name="top-c-fullwidth" style="html5" />
        <?php endif; ?>

        <?php if ($this->countModules('top-multi-columns', true) && $hideModuleWhenEditmodule && $hideModuleWhenEditLayout) : ?>
        <div class="container<?php echo $fluidcontainer; ?> py-2">
            <div class="row row-cols-md-<?php echo $this->countModules('top-multi-columns', true); ?> g-3">
                <jdoc:include type="modules" name="top-multi-columns" style="card" />
            </div>
        </div>
        <?php endif; ?>

        <?php $hasSideBar = $this->countModules('sidebar-left', true) ||  $this->countModules('sidebar-right', true); ?>

        <div class="container<?php echo $fluidcontainer; ?> my-3">
        <div class="row">
        <?php if ($this->countModules('sidebar-left', true)  && $hideModuleWhenEditmodule && $hideModuleWhenEditLayout) : ?>
            <div class="col-md-3">
                <jdoc:include type="modules" name="sidebar-left" style="card" />
            </div>
        <?php endif; ?>

        <div class="col component-area">
            <jdoc:include type="modules" name="breadcrumbs" style="none" />
            <?php if ($this->countModules('main-top', true)  && $hideModuleWhenEditmodule) : ?>
                <jdoc:include type="modules" name="main-top" style="html5" />
            <?php endif; ?>
            <jdoc:include type="message" />
            <main>
            <jdoc:include type="component" />
            </main>
            <jdoc:include type="modules" name="main-bottom" style="html5" />
        </div>

        <?php if ($this->countModules('sidebar-right', true)  && $hideModuleWhenEditmodule) : ?>
            <div class="col-md-3">
                <jdoc:include type="modules" name="sidebar-right" style="card" />
            </div>
        <?php endif; ?>
        </div>
        </div>

        <?php if ($this->countModules('bottom-a', true)) : ?>
        <div class="container<?php echo $fluidcontainer; ?>">
            <jdoc:include type="modules" name="bottom-a" style="html5" />
        </div>
        <?php endif; ?>
        
        <?php if ($this->countModules('bottom-b', true)) : ?>
        <div class="container<?php echo $fluidcontainer; ?> py-2">
            <div class="row row-cols-md-<?php echo $this->countModules('bottom-b', true); ?> g-3">
                <jdoc:include type="modules" name="bottom-b" style="card" />
            </div>
        </div>
        <?php endif; ?>

        <?php if ($this->countModules('bottom-c-fullwidth', true)) : ?>
            <jdoc:include type="modules" name="bottom-c-fullwidth" style="html5" />
        <?php endif; ?>
    
        <?php if ($this->countModules('bottom-multi-columns', true) && $hideModuleWhenEditmodule && $hideModuleWhenEditLayout) : ?>
        <div class="container<?php echo $fluidcontainer; ?> py-2">
            <div class="row row-cols-md-<?php echo $this->countModules('bottom-multi-columns', true); ?> g-3">
                <jdoc:include type="modules" name="bottom-multi-columns" style="card" />
            </div>
        </div>
        <?php endif; ?>

    <?php if ($this->countModules('footer', true) || $this->countModules('footer-bottom', true)) : ?>
    <footer class="footer">
        <div class="container<?php echo $fluidcontainer; ?> ">
            <?php if ($this->countModules('footer', true)) : ?>
            <div class="row row-cols-md-<?php echo $this->countModules('footer', true); ?> g-3">
                <jdoc:include type="modules" name="footer" style="html5" />
            </div>
            <?php endif; ?>
            <?php if ($this->countModules('footer-bottom', true)) : ?>
            <jdoc:include type="modules" name="footer-bottom" style="html5" />
            <?php endif; ?>
        </div>
    </footer>
    <?php endif; ?>
    <?php if ($this->params->get('backTop') == 1) : ?>
        <a href="#top" id="back-top" class="back-to-top-link" aria-label="<?php echo Text::_('TPL_XIROSTART_BACKTOTOP'); ?>">
            <span class="icon-arrow-up icon-fw" aria-hidden="true"></span>
        </a>
    <?php endif; ?>
    <jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
