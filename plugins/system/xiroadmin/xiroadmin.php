<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  System.Xiroweb
 *
 * @copyright   Copyright (C) 2020 XiroWeb. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// use Joomla\CMS\Form\Form;
// use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Factory;
// use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\Registry\Registry;
use Joomla\CMS\Autoload\ClassLoader;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormHelper;

class plgSystemXiroadmin extends CMSPlugin
{

	protected $app;

	function __construct(&$subject, $config)
	{
		// call parent constructor
		parent::__construct($subject, $config);

	}

	public function onContentPrepareForm(Form $form, $data)
	{

		if (!$this->app->isClient('administrator')) {
			return;
		}

		$template = $this->app->getTemplate(true);
		if ($template->template !== 'xiroadmin') {
			return;
		}

		$name = $form->getName();

		// form for view com_module
		if (in_array($name, array('com_modules.modules.0.filter'))) {

			FormHelper::addFieldPrefix('Xiroweb\\Plugin\\System\\Xiroadmin\\Field');
			FormHelper::addFormPath(__DIR__ . '/helper/com_modules/forms');

			$form->loadFile('filter_modules_position',true);
		}

		if (!in_array($name, array('com_modules.module')))
		{
			return true;
		}

		return true;
	}

	public function onRenderModule(&$module, &$attribs) {
		if ($this->app->input->getBool('positionmodal') && $this->app->input->getBool('tp') && ComponentHelper::getParams('com_templates')->get('template_positions_display'))
		{
			$attribs['style'] = str_replace(' outline', '', $attribs['style']);
		}
	}


	public function onAfterRenderModule(&$module, &$attribs) {

		if ($this->app->input->getBool('positionmodal') && $this->app->input->getBool('tp') && ComponentHelper::getParams('com_templates')->get('template_positions_display'))
		{
			$style = 'outlinemodal';

			$params = new Registry($module->params);

			$displayData = array(
				'module'  => $module,
				'params'  => $params,
				'attribs' => $attribs,
			);

			$basePath = __DIR__ . '/layouts';

			if ($moduleContent = LayoutHelper::render('chromes.' . $style, $displayData, $basePath))
			{
				$module->content = $moduleContent;
			}

		}

	}

		/**
	 * Overide core
	 **/

	public function onAfterRoute() {

		if ($this->app->isClient('site'))
		{
			if ($this->app->input->getBool('positionmodal',0)) {
				$this->app->set('caching',0);
				$this->app->set('cache_handler', 'file');
				$this->app->allowCache(false);
			}
			return;
		}

		$input = $this->app->input;

		$option = $input->get('option', '');

		if ($option !== 'com_modules') {
			return;
		}


		if('com_modules' == $this->app->input->getCMD('option') && $this->app->isClient('administrator')) {

			/*
			 $loader = include JPATH_LIBRARIES . '/vendor/autoload.php';
			$classmap = array(
				'Joomla\\Component\\Modules\\Administrator\\Model\\ModulesModel' =>  __DIR__ . '/helper/com_modules/src/Model/ModulesModel.php',
				'Joomla\\Component\\Modules\\Administrator\\Controller\\ModulesController' =>  __DIR__ . '/helper/com_modules/src/Controller/ModulesController.php'
			);
			$loader->addClassMap($classmap);
			 */

			// attack to populateState
			$context = 'com_modules.modules.0.list';
			$cur_state = $this->app->getUserState($context, array());
			if (empty($cur_state['fullordering'])) {
				$cur_state['fullordering'] = 'a.ordering ASC';
			}
			$this->app->setUserState($context, $cur_state);

		}
	}

    
    public function onBeforeCompileHead()
    {
        if ($this->app->isClient('site'))
        {
            if ($this->app->input->getBool('positionmodal',0)) {
                $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
                $wr = $wa->getRegistry();
                $wr->addRegistryFile('media/plg_system_xiroadmin/joomla.asset.json');
                $wa->useScript('plg_system_xiroadmin.linkappendquerytp');
            }
        }

    }

}
