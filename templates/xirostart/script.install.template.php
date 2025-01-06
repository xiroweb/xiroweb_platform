<?php
/**
 * @copyright   Copyright (C) 2020 XiroWeb. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die();

use \Joomla\CMS\Application\ApplicationHelper as CMSAppHelper;
use \Joomla\CMS\Factory as CMSFactory;
use \Joomla\CMS\Log\Log as Log;
use \Joomla\Database\ParameterType;
use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;

class xirostartInstallerScript
{

	protected $positionModulesUninstall = array(
		['menu-xirostart', '0','mod_menu' ],
	);

	
	public function install($parent)
	{
		$this->installModule();
		return true;
	}

	public function uninstall($parent)
	{
		$this->uninstallModules();
		return true;
	}

	public function uninstallModules(){

		foreach ($this->positionModulesUninstall as $pos)
		{
			$this->uninstallModule($pos[0], $pos[1], $pos[2]);
		}
	}

	public function uninstallModule($position, $client, $module)
	{
		
		$db   = CMSFactory::getDbo();

		$query = $db->getQuery(true);

		$query
			->select($db->quoteName('id'))
			->from($db->quoteName('#__modules'))
			->where($db->quoteName('position') . ' = :position')
			->where($db->quoteName('client_id') . ' = :client_id')
			->where($db->quoteName('module') . ' = :module')
			->bind(':position', $position, ParameterType::STRING)
			->bind(':client_id', $client, ParameterType::INTEGER)
			->bind(':module', $module, ParameterType::STRING);
		$db->setQuery($query);

		try
		{
			$modules = $db->loadColumn();
		}
		catch (\RuntimeException $e)
		{
			$modules = [];
		}

		if (\count($modules))
		{
			// Ensure the list is sane
			$modules = ArrayHelper::toInteger($modules);
			$modID = implode(',', $modules);

			// Wipe out any items assigned to menus
			$query = $db->getQuery(true)
				->delete($db->quoteName('#__modules_menu'))
				->where($db->quoteName('moduleid') . ' IN (' . $modID . ')');
			$db->setQuery($query);

			try
			{
				$db->execute();
			}
			catch (\RuntimeException $e)
			{
				Log::add(Text::sprintf('JLIB_INSTALLER_ERROR_MOD_UNINSTALL_EXCEPTION', $e->getMessage()), Log::WARNING, 'jerror');
				$retval = false;
			}

			// Wipe out any instances in the modules table
			/** @var \Joomla\CMS\Table\Module $module */
			$module = Table::getInstance('Module');

			foreach ($modules as $modInstanceId)
			{
				$module->load($modInstanceId);

				if (!$module->delete())
				{
					Log::add(Text::sprintf('JLIB_INSTALLER_ERROR_MOD_UNINSTALL_EXCEPTION', $module->getError()), Log::WARNING, 'jerror');
					$retval = false;
				}
			}
		}
		
	}



	public function installModule()
	{

		$app = CMSFactory::getApplication();

		$app->getLanguage()->load('com_modules');

		// Add Include Paths.
		/** @var \Joomla\Component\Modules\Administrator\Model\ModuleModel $model */
		$model = $app->bootComponent('com_modules')->getMVCFactory()
			->createModel('Module', 'Administrator', ['ignore_request' => true]);
			

		$modules = array(
			array(
				'title' =>  'Mainmenu',
                'note' =>  '',
                'content' =>  null,
                'ordering' =>  '1',
                'position' =>  'menu-xirostart',
                'checked_out' =>  null,
                'checked_out_time' =>  null,
                'publish_up' =>  null,
                'publish_down' =>  null,
                'published' =>  '1',
                'module' =>  'mod_menu',
                'access' =>  '1',
                'showtitle' =>  '0',
                'client_id' =>  '0',
                'language' =>  '*',
				'params'    => array('menutype' => 'mainmenu','base' => '','startLevel' => 1,'endLevel' => 0,'showAllChildren' => 1,'tag_id' => '','class_sfx' => '','window_open' => '','layout' => 'xirostart:boostrap','moduleclass_sfx' => '','cache' => 1,'cache_time' => 900,'cachemode' => 'itemid','module_tag' => 'div','bootstrap_size' => '0','header_tag' => 'h3','header_class' => '','style' => '0'),
			),
		);


		foreach ($modules as $module)
		{
		
			$module['id']         = 0;
			$module['asset_id']   = 0;
			$module['note']       = '';
			$module['published']  = 1;

			if (!isset($module['assignment']))
			{
				$module['assignment'] = 0;
			}

			if (!isset($module['content']))
			{
				$module['content'] = '';
			}

			if (!isset($module['access']))
			{
				$module['access'] = 1;
			}

			if (!isset($module['showtitle']))
			{
				$module['showtitle'] = 1;
			}

			if (!isset($module['client_id']))
			{
				$module['client_id'] = 1;
			}

			$model->save($module);

		}

		return;
	}

}
