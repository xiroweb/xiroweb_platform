<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var \Joomla\Component\Modules\Administrator\View\Module\HtmlView $this */

// A backward compatibility for bootstrap modals: hide toolbar
// @TODO: Remove in 6.0
$this->getDocument()->getWebAssetManager()->addInlineScript('
if (window.parent.Joomla.Modal) {
    const modal = window.parent.Joomla.Modal.getCurrent();
    const toolbar = document.querySelector(".component .subhead")
    if (modal && modal.nodeName !== "JOOMLA-DIALOG" && toolbar) {
        toolbar.style.display = "none";
        console.warn("Update your modal for Module editing to use Joomla Dialog");
    }
}
', ['name' => 'inline.module-modal-fix'], ['type' => 'module']);
?>
<div class="subhead noshadow mb-3">
    <?php echo $this->document->getToolbar('toolbar')->render(); ?>
</div>
<div class="container-popup">
    <?php $this->setLayout('edit'); ?>
    <?php echo $this->loadTemplate(); ?>
</div>
