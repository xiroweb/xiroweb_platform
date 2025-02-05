<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var \Joomla\Component\Content\Site\View\Form\HtmlView $this */
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('form.validate')
    ->useScript('com_content.form-edit');


$css = <<< CSS
    .form-style-m .control-group {
        margin: 0.3em 0 !important;
    }
    .form-style-m .form-control {
        padding: .2rem 1rem !important;
    }
    joomla-tab[view=tabs]>div[role=tablist] {
        white-space: normal !important;
    }
CSS;

$wa->addInlineStyle($css);

$this->tab_name = 'com-content-form';
$this->ignore_fieldsets = ['image-intro', 'image-full', 'jmetadata', 'item_associations'];
$this->useCoreUI = true;

// Create shortcut to parameters.
$params = $this->state->get('params');

// This checks if the editor config options have ever been saved. If they haven't they will fall back to the original settings
if (!$params->exists('show_publishing_options')) {
    $params->set('show_urls_images_frontend', '0');
}
?>
<div class="edit item-page">
    <?php if ($params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1>
            <?php echo $this->escape($params->get('page_heading')); ?>
        </h1>
    </div>
    <?php endif; ?>

    <form action="<?php echo Route::_('index.php?option=com_content&a_id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical">
        <fieldset>
            <?php echo HTMLHelper::_('uitab.startTabSet', $this->tab_name, ['active' => 'editor', 'recall' => true, 'breakpoint' => 768]); ?>

            <?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'editor', Text::_('COM_CONTENT_ARTICLE_CONTENT')); ?>
                <?php echo $this->form->renderField('title'); ?>

                <?php if (is_null($this->item->id)) : ?>
                    <?php echo $this->form->renderField('alias'); ?>
                <?php endif; ?>

                <div class="row">
                    <div class="col-6 col-md-3">
                        <?php echo $this->form->renderField('catid'); ?>
                    </div>
                    <div class="col-6 col-md-3">
                        <?php echo $this->form->renderField('transition'); ?>
                        <?php echo $this->form->renderField('state'); ?>
                    </div>
                    <div class="col-6 col-md-3">
                        <?php if ($this->item->params->get('access-change')) : ?>
                            <?php echo $this->form->renderField('featured'); ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-6 col-md-3">
                        <?php echo $this->form->renderField('access'); ?>
                    </div>
                </div>

                <?php if ($params->get('show_urls_images_frontend')) : ?>
                <div class="row form-style-m gx-4 align-items-stretch">
                    <div class="col-md-6">
                        <div class="border rounded-2 p-2 mb-1 border border-2 h-100">
                            <div class="row">
                                <div class="col-8 mx-auto col-sm-4">
                                    <?php echo $this->form->renderField('image_intro', 'images'); ?>
                                </div>
                                <div class="col-12 col-sm-8">
                                    <?php echo $this->form->renderField('image_intro_alt', 'images'); ?>
                                    <?php echo $this->form->renderField('image_intro_alt_empty', 'images'); ?>
                                    <?php echo $this->form->renderField('image_intro_caption', 'images'); ?>
                                    <?php echo $this->form->renderField('float_intro', 'images'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded-2 p-2 mb-1 border border-2 h-100">
                            <div class="row">
                            <div class="col-8 mx-auto col-sm-4">
                                    <?php echo $this->form->renderField('image_fulltext', 'images'); ?>
                                </div>
                                <div class="col-12 col-sm-8">
                                    <?php echo $this->form->renderField('image_fulltext_alt', 'images'); ?>
                                    <?php echo $this->form->renderField('image_fulltext_alt_empty', 'images'); ?>
                                    <?php echo $this->form->renderField('image_fulltext_caption', 'images'); ?>
                                    <?php echo $this->form->renderField('float_fulltext', 'images'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="clearfix">
                    <?php echo $this->form->renderField('articletext'); ?>
                </div>

                <?php if ($this->captchaEnabled) : ?>
                    <?php echo $this->form->renderField('captcha'); ?>
                <?php endif; ?>

                <?php echo $this->form->renderField('tags'); ?>

                <?php if ($params->get('show_publishing_options', 1) == 1) : ?>
                        <?php echo $this->form->renderField('metadesc'); ?>
                        <?php echo $this->form->renderField('metakey'); ?>
                <?php endif; ?>

            <?php echo HTMLHelper::_('uitab.endTab'); ?>

            <?php if ($params->get('show_urls_images_frontend')) : ?>
                <?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'images', Text::_('COM_CONTENT_IMAGES_AND_URLS')); ?>
                
                
                <?php echo $this->form->renderField('urla', 'urls'); ?>
                <?php echo $this->form->renderField('urlatext', 'urls'); ?>
                <div class="control-group">
                    <div class="controls">
                        <?php echo $this->form->getInput('targeta', 'urls'); ?>
                    </div>
                </div>
                <?php echo $this->form->renderField('urlb', 'urls'); ?>
                <?php echo $this->form->renderField('urlbtext', 'urls'); ?>
                <div class="control-group">
                    <div class="controls">
                        <?php echo $this->form->getInput('targetb', 'urls'); ?>
                    </div>
                </div>
                <?php echo $this->form->renderField('urlc', 'urls'); ?>
                <?php echo $this->form->renderField('urlctext', 'urls'); ?>
                <div class="control-group">
                    <div class="controls">
                        <?php echo $this->form->getInput('targetc', 'urls'); ?>
                    </div>
                </div>
                <?php echo HTMLHelper::_('uitab.endTab'); ?>
            <?php endif; ?>

            <?php echo LayoutHelper::render('joomla.edit.params', $this); ?>

            <?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'publishing', Text::_('COM_CONTENT_PUBLISHING')); ?>
                
                
                <?php echo $this->form->renderField('note'); ?>
                <?php if ($params->get('save_history', 0)) : ?>
                    <?php echo $this->form->renderField('version_note'); ?>
                <?php endif; ?>
                <?php if ($params->get('show_publishing_options', 1) == 1) : ?>
                    <?php echo $this->form->renderField('created_by_alias'); ?>
                <?php endif; ?>
                <?php if ($this->item->params->get('access-change')) : ?>
                    <?php if ($params->get('show_publishing_options', 1) == 1) : ?>
                        <?php echo $this->form->renderField('featured_up'); ?>
                        <?php echo $this->form->renderField('featured_down'); ?>
                        <?php echo $this->form->renderField('publish_up'); ?>
                        <?php echo $this->form->renderField('publish_down'); ?>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php if (is_null($this->item->id)) : ?>
                    <div class="control-group">
                        <div class="control-label">
                        </div>
                        <div class="controls">
                            <?php echo Text::_('COM_CONTENT_ORDERING'); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>

            <?php if (Multilanguage::isEnabled()) : ?>
                <?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'language', Text::_('JFIELD_LANGUAGE_LABEL')); ?>
                    <?php echo $this->form->renderField('language'); ?>
                <?php echo HTMLHelper::_('uitab.endTab'); ?>
            <?php else : ?>
                <?php echo $this->form->renderField('language'); ?>
            <?php endif; ?>

            

            <?php echo HTMLHelper::_('uitab.endTabSet'); ?>

            <input type="hidden" name="task" value="">
            <input type="hidden" name="return" value="<?php echo $this->return_page; ?>">
            <?php echo HTMLHelper::_('form.token'); ?>
        </fieldset>
        <div class="d-grid gap-2 d-sm-block mb-2">
            <button type="button" class="btn btn-primary" data-submit-task="article.apply">
                <span class="icon-check" aria-hidden="true"></span>
                <?php echo Text::_('JSAVE'); ?>
            </button>
            <button type="button" class="btn btn-primary" data-submit-task="article.save">
                <span class="icon-check" aria-hidden="true"></span>
                <?php echo Text::_('JSAVEANDCLOSE'); ?>
            </button>
            <?php if ($this->showSaveAsCopy) : ?>
                <button type="button" class="btn btn-primary" data-submit-task="article.save2copy">
                    <span class="icon-copy" aria-hidden="true"></span>
                    <?php echo Text::_('JSAVEASCOPY'); ?>
                </button>
            <?php endif; ?>
            <button type="button" class="btn btn-danger" data-submit-task="article.cancel">
                <span class="icon-times" aria-hidden="true"></span>
                <?php echo Text::_('JCANCEL'); ?>
            </button>
            <?php if ($params->get('save_history', 0) && $this->item->id) : ?>
                <?php echo $this->form->getInput('contenthistory'); ?>
            <?php endif; ?>
        </div>
    </form>
</div>
