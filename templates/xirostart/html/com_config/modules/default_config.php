<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_config
 *
 * @copyright   (C) 2014 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
?>
<div class="col-md-3 order-2 order-md-0">

    <div class="control-group">
        <div class="control-label">
            <?php echo $this->form->getLabel('position'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('position'); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <?php echo $this->form->getLabel('showtitle'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('showtitle'); ?>
        </div>
    </div>

    <hr>

    <?php if (Factory::getUser()->authorise('core.edit.state', 'com_modules.module.' . $this->item['id'])) : ?>
    <div class="control-group">
        <div class="control-label">
            <?php echo $this->form->getLabel('published'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('published'); ?>
        </div>
    </div>
    <?php endif ?>

    <div class="control-group">
        <div class="control-label">
            <?php echo $this->form->getLabel('publish_up'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('publish_up'); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <?php echo $this->form->getLabel('publish_down'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('publish_down'); ?>
        </div>
    </div>

    <div class="control-group">
        <div class="control-label">
            <?php echo $this->form->getLabel('access'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('access'); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <?php echo $this->form->getLabel('ordering'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('ordering'); ?>
        </div>
    </div>

    <?php if (Multilanguage::isEnabled()) : ?>
        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('language'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('language'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="control-group">
        <div class="control-label">
            <?php echo $this->form->getLabel('note'); ?>
        </div>
        <div class="controls">
            <?php echo $this->form->getInput('note'); ?>
        </div>
    </div>

</div>