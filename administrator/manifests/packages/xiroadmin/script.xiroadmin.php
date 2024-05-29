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
use \Joomla\CMS\Cache\Cache as CMSCache;
use \Joomla\Database\ParameterType;
use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;

class Pkg_XiroadminInstallerScript
{

	protected $packageName = 'xiroadmin';

	/**
	 * The minimum PHP version required to install this extension
	 *
	 * @var   string
	 */
	protected $minimumPHPVersion = '5.6.0';

	/**
	 * The minimum Joomla! version required to install this extension
	 *
	 * @var   string
	 */
	protected $minimumJoomlaVersion = '5.1.0';

	/**
	 * The maximum Joomla! version this extension can be installed on
	 *
	 * @var   string
	 */
	protected $maximumJoomlaVersion = '5.9.99';


	protected $extensionsToEnable = array(
		['plugin', 'xiroadmin', '0', 'system' ],
	);

	protected $positionModulesUninstall = array(
		['xiroadmin2', '1' ],
		['xiroadmin3', '1' ],
		['xiroadmin4', '1' ],
	);


	public function preflight($type, $parent)
	{
		// Check the minimum PHP version
		if (!version_compare(PHP_VERSION, $this->minimumPHPVersion, 'ge'))
		{
			$msg = "<p>You need PHP $this->minimumPHPVersion or later to install this package</p>";
			Log::add($msg, Log::WARNING, 'jerror');

			return false;
		}

		// Check the minimum Joomla! version
		if (!version_compare(JVERSION, $this->minimumJoomlaVersion, 'ge'))
		{
			$msg = "<p>You need Joomla! $this->minimumJoomlaVersion or later to install this component</p>";
			Log::add($msg, Log::WARNING, 'jerror');

			return false;
		}

		// Check the maximum Joomla! version
		if (!version_compare(JVERSION, $this->maximumJoomlaVersion, 'le'))
		{
			$msg = "<p>You need Joomla! $this->maximumJoomlaVersion or earlier to install this component</p>";
			Log::add($msg, Log::WARNING, 'jerror');

			return false;
		}

		if (($type == 'uninstall') && $this->isHomeTemplateXiroadmin())
		{
			// load language from tempates xiroadmin 
			$lang = CMSFactory::getApplication()->getLanguage();
			$source = JPATH_ADMINISTRATOR . "/templates/xiroadmin";
			$lang->load("tpl_xiroadmin.sys", $source);

				$templates_style_admin = 'index.php?option=com_templates&view=styles&client_id=1';
				$msg = Text::_('PKG_XIROADMIN_UNINSTALL_PREFLIGHT_CHECK_HOME_EXIST');
				$msg .= '<a class="btn btn-default btn-sm btn-raised" href="' . $templates_style_admin . '">';
				$msg .= '<span class="icon-options icon-fw" aria-hidden="true"></span>';
				$msg .= Text::_('PKG_XIROADMIN_UNINSTALL_PREFLIGHT_TITLE_LINK_COM_ADMINISTRATOR_TEMPLATES_STYLE');
				$msg .= '</a>';
				Log::add($msg, Log::WARNING, 'jerror');
	
				return false;

			
		}

		return true;
	}


	public function isHomeTemplateXiroadmin() 
	{
		$template  = 'xiroadmin';

		$clientId = '1';
	
		$db   = CMSFactory::getDbo();

		$query = $db->getQuery(true);

		$query
			->select($db->quoteName('id'))
			->from($db->quoteName('#__template_styles'))
			->where($db->quoteName('template') . ' = :template')
			->where($db->quoteName('client_id') . ' = :client_id')
			->where($db->quoteName('home') . ' = ' . $db->quote('1'))
			->bind(':template', $template, ParameterType::STRING)
			->bind(':client_id', $clientId, ParameterType::INTEGER);
		$db->setQuery($query);

		try
		{
			$home = $db->loadColumn();
		}
		catch (\RuntimeException $e)
		{
			$home = [];
		}

		if (\count($home))
		{
			return true;
		} else {
			return false;
		}
	}
	
	
	public function install($parent)
	{
		$this->enableExtensions();

		$this->setHomeTemplate();

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
			$this->uninstallModule($pos[0], $pos[1]);
		}
	}

	public function uninstallModule($position, $client)
	{
		
		$db   = CMSFactory::getDbo();

		$query = $db->getQuery(true);

		$query
			->select($db->quoteName('id'))
			->from($db->quoteName('#__modules'))
			->where($db->quoteName('position') . ' = :position')
			->where($db->quoteName('client_id') . ' = :client_id')
			->bind(':position', $position, ParameterType::STRING)
			->bind(':client_id', $client, ParameterType::INTEGER);
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


	private function enableExtensions()
	{
		$db = CMSFactory::getDbo();

		foreach ($this->extensionsToEnable as $ext)
		{
			$this->enableExtension($ext[0], $ext[1], $ext[2], $ext[3]);
		}
	}


	private function enableExtension($type, $name, $client = 1, $group = null)
	{
		$db = CMSFactory::getDbo();

		$query = $db->getQuery(true)
			->update('#__extensions')
			->set($db->qn('enabled') . ' = ' . $db->q(1))
			->where('type = ' . $db->quote($type))
			->where('element = ' . $db->quote($name));

		switch ($type)
		{
			case 'plugin':
				// Plugins have a folder but not a client
				$query->where('folder = ' . $db->quote($group));
				break;

			case 'language':
			case 'module':
			case 'template':
	
				break;

			default:
			case 'library':
			case 'package':
			case 'component':
			
				break;
		}

		$db->setQuery($query);

		try
		{
			$db->execute();
		}
		catch (\Exception $e)
		{
		}
	}

	public function setHomeTemplate()
	{
		$element  = 'xiroadmin';

		$clientId = '1';

		$type = 'template';
	
		$db   = CMSFactory::getDbo();

		$query = $db->getQuery(true);

			// Reset the home fields for the client_id.
		$query = $db->getQuery(true)
			->update($db->quoteName('#__template_styles'))
			->set($db->quoteName('home') . ' = ' . $db->quote('0'))
			->where($db->quoteName('client_id') . ' = :clientid')
			->where($db->quoteName('home') . ' = ' . $db->quote('1'))
			->bind(':clientid', $clientId, ParameterType::INTEGER);
		$db->setQuery($query);
		$db->execute();


		// Set the new home style.
		$query = $db->getQuery(true)
			->update($db->quoteName('#__template_styles'))
			->set($db->quoteName('home') . ' = ' . $db->quote('1'))
			->where($db->quoteName('client_id') . ' = :clientid')
			->where($db->quoteName('template') . ' = :template')
			->bind(':clientid', $clientId, ParameterType::INTEGER)
			->bind(':template', $element, ParameterType::STRING);
		$db->setQuery($query);
		$db->execute();

		return true;

	}

	public function clearCache() {
		$conf         = CMSFactory::getConfig();
		$clearGroups  = ['_system', 'com_templates', 'com_plugins', 'com_modules'];
		$cacheClients = [0, 1];

		foreach ($clearGroups as $group)
		{
			foreach ($cacheClients as $client_id)
			{
				try
				{
					$options = [
						'defaultgroup' => $group,
						'cachebase'    => ($client_id) ? JPATH_ADMINISTRATOR . '/cache' : $conf->get('cache_path', JPATH_SITE . '/cache'),
					];

					/** @var JCache $cache */
					$cache = CMSCache::getInstance('callback', $options);
					$cache->clean();
				}
				catch (Exception $exception)
				{
					$options['result'] = false;
				}

				// Trigger the onContentCleanCache event.
				try
				{
					CMSFactory::getApplication()->triggerEvent('onContentCleanCache', $options);
				}
				catch (Exception $e)
				{
					// Suck it up
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
				// The main menu Blog
				'title'     => 'Statistics',
				'ordering'  => 1,
				'position'  => 'xiroadmin2',
				'module'    => 'mod_stats_admin',
				'showtitle' => 0,
				"published" => 1,
				'access' 	=> 3,
				'client_id' => 1,
				'language' => '*',
				'params'    => array(
					'serverinfo' => 1,'siteinfo' => 1,'layout' => 'xiroadmin:xiroadmin','moduleclass_sfx' => '','cache' => 1,'cache_time' => 900,'cachemode' => 'static','style' => '0','module_tag' => 'div','bootstrap_size' => '0','header_tag' => 'h3','header_class' => ''
				),
			),
			array(
				'title'     => 'Components',
				'ordering'  => 1,
				'position'  => 'xiroadmin4',
				'module'    => 'mod_menu',
				'showtitle' => 1,
				'access'    => 3,
				'client_id' => 1,
				"published" => 1,
				'language' => '*',
				'params'    => array(
					'menutype' => '*','preset' => 'xirocomponent','check' => 1,'shownew' => 0,'showhelp' => 0,'forum_url' => '','layout' => 'xiroadmin:appcpanel','moduleclass_sfx' => ' moduletablexiroadmin','style' => '0','module_tag' => 'div','bootstrap_size' => '0','header_tag' => 'h2','header_class' => ''
				),
			),
			array(
				'title'     => 'Recently Added Articles',
				'ordering'  => 2,
				'position'  => 'xiroadmin3',
				'module'    => 'mod_latest',
				'showtitle' => 1,
				'access'    => 3,
				'client_id' => 1,
				"published" => 1,
				'language' => '*',
				'params'    => array(
					'count' => 5,'ordering' => 'c_dsc','catid' => '','user_id' => '0','layout' => '_:default','moduleclass_sfx' => ' xirotable card','automatic_title' => 0,'style' => '0','module_tag' => 'div','bootstrap_size' => '12','header_tag' => 'h2','header_class' => ''
				),
			),
			array(
				'title'     => 'Popular Articles',
				'ordering'  => 1,
				'position'  => 'xiroadmin3',
				'module'    => 'mod_popular',
				'showtitle' => 1,
				'access'    => 3,
				'client_id' => 1,
				"published" => 1,
				'language' => '*',
				'params'    => array(
					'count' => 5,'catid' => '','user_id' => '0','layout' => '_:default','moduleclass_sfx' => ' xirotable card','automatic_title' => 0,'style' => '0','module_tag' => 'div','bootstrap_size' => '12','header_tag' => 'h2','header_class' => ''
				),
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
				$module['access'] = 3;
			}

			if (!isset($module['showtitle']))
			{
				$module['showtitle'] = 1;
			}

			if (!isset($module['client_id']))
			{
				$module['client_id'] = 0;
			}

			$model->save($module);

		}

		return;

	}

	public function checkAndRemoveFolderFile() {

		$dryRun = false;
		
		$status = [
			'files_exist'     => [],
			'folders_exist'   => [],
			'files_deleted'   => [],
			'folders_deleted' => [],
			'files_errors'    => [],
			'folders_errors'  => [],
			'folders_checked' => [],
			'files_checked'   => [],
		];

		$files = array(
			'/administrator/templates/xiroadmin/template_preview.png',
			'/administrator/templates/xiroadmin/template_thumbnail.png',

		);
		$folders = array(
			// From 3.10 to 4.1
			'/administrator/templates/xiroadmin/css',
			'/administrator/templates/xiroadmin/images',
			'/administrator/templates/xiroadmin/js',
			'/administrator/templates/xiroadmin/less',
			'/administrator/templates/xiroadmin/scss',

		);

		$status['files_checked'] = $files;
		$status['folders_checked'] = $folders;

		foreach ($files as $file)
		{
			if ($fileExists = File::exists(JPATH_ROOT . $file))
			{
				$status['files_exist'][] = $file;

				if ($dryRun === false)
				{
					if (File::delete(JPATH_ROOT . $file))
					{
						$status['files_deleted'][] = $file;
					}
					else
					{
						$status['files_errors'][] = Text::sprintf('FILES_JOOMLA_ERROR_FILE_FOLDER', $file);
					}
				}
			}
		}


		foreach ($folders as $folder)
		{
			if ($folderExists = Folder::exists(JPATH_ROOT . $folder))
			{
				$status['folders_exist'][] = $folder;

				if ($dryRun === false)
				{
					if (Folder::delete(JPATH_ROOT . $folder))
					{
						$status['folders_deleted'][] = $folder;
					}
					else
					{
						$status['folders_errors'][] = Text::sprintf('FILES_JOOMLA_ERROR_FILE_FOLDER', $folder);
					}
				}
			}
		}


	}

	public function setInheritableTemplate()
	{
		$element  = 'xiroadmin';

		$clientId = '1';

		$type = 'template';
	
		$db   = CMSFactory::getDbo();

		$query = $db->getQuery(true);

		// Set the new home style.
		$query = $db->getQuery(true)
			->update($db->quoteName('#__template_styles'))
			->set($db->quoteName('inheritable') . ' = ' . $db->quote('1'))
			->where($db->quoteName('client_id') . ' = :clientid')
			->where($db->quoteName('template') . ' = :template')
			->bind(':clientid', $clientId, ParameterType::INTEGER)
			->bind(':template', $element, ParameterType::STRING);
		$db->setQuery($query);
		$db->execute();

		return true;

	}

	public function postflight($route, $adapter)	{

		$this->clearCache();

		// load language from tempates xiroadmin 
		$lang = CMSFactory::getApplication()->getLanguage();
		$source = JPATH_ADMINISTRATOR . "/templates/xiroadmin";
		$lang->load("tpl_xiroadmin.sys", $source);

		// ext mod_ stop, uninstall
		$db			=	JFactory::getDbo();
		$query		=	$db->getQuery( true );

		$query->select( $db->quoteName( array( 'extension_id' ) ) )
				->from( $db->quoteName( '#__extensions' ) )
				->where( $db->quoteName( 'type' ) . ' = ' . $db->quote( 'module' ) )
				->where( $db->quoteName( 'element' ) . ' = ' . $db->quote( 'mod_' ) );
		$db->setQuery( $query );
		$module_id	=	(int)$db->loadResult();
		
		if ( $module_id ) {
			$installer  =   JInstaller::getInstance();
		
			$module     =   JTable::getInstance( 'Extension' );
			$module->load( $module_id );
		
			if ( $module->type == 'module' ) {
				$installer->uninstall( $module->type, $module_id );
			}
		}

		// remove file and folder

		$this->checkAndRemoveFolderFile();

		// inheritable support childtemplate
		if ($route == 'update')
		{
			$this->setInheritableTemplate();
		}


		?>
		<style>
						
			@media (min-width : 768px) and (max-width : 1120px) {
				.row-fluid [class*="span"] {
					margin-left : 10px;
				}
			}

			@media (max-width : 767px) {
				#system-message-container.j-toggle-main, #j-main-container.j-toggle-main, #system-debug.j-toggle-main {
					float : none;
				}
			}

			/* End isis backend template fix */
			#j-main-container > .span12 > strong {
				font-weight : normal;
			}

			.template-intro {
				margin-bottom : 30px;
			}

			.template-intro *,
			.template-intro *::before,
			.template-intro *::after {
				box-sizing : border-box;
			}

			.template-intro-container {
				margin    : 0 auto;
				max-width : 85%;
			}

			.template-intro-container .btn {
				font-size     : 90%;
				font-weight   : bold;
				margin-bottom : 10px;
			}

			.intro-header {
				text-align : center;
				padding    : 60px 60px;
				background-color: rgba(28,206,234,0.82);
			    background: linear-gradient(-45deg, rgba(147,26,222,0.83) 0%, rgba(28,206,234,0.82) 100%);

			}

			.intro-header-title {
				font-weight   : normal;
				color         : rgb(255, 255, 255);
			}

			.intro-header-title small {
				color : rgba(255, 255, 255, 0.5);
			}

			.intro-header-screens {
				position : relative;
				bottom   : -8px;
			}

			.intro-xirodemoter, .intro-content-highlights, .intro-content-features {
				padding : 60px 20px;
			}

			.intro-content-highlights {
				background-color : #f5f5f5;
			}

			.intro-quote {
				max-width  : 70%;
				text-align : center;
				font-style : italic;
				opacity    : 0.75;
				margin     : 0 auto 50px auto;
			}

			.intro-section-title {
				font-weight    : normal;
				text-align     : center;
				padding-bottom : 20px;
				margin-bottom  : 40px;
				border-bottom  : 1px solid rgba(0, 0, 0, 0.15);
				font-size      : 40px;
				line-height    : 1;
			}

			.thumbnails .row-fluid .span4 {
				margin-bottom : 40px;
			}

			.thumbnails .row-fluid .span12 {
				margin-bottom : 40px;
				width         : 97%;
			}

			.thumbnail {
				padding          : 30px;
				border           : 1px solid rgba(0, 0, 0, 0.15);
				background-color : rgba(255, 255, 255, 0.35);
				box-shadow       : none;
			}

			.thumbnail h4 {
				line-height : 1.3;
				font-size   : 18px;
				margin      : 25px 0 0 0;
				text-align  : center;
			}

			.intro-feature .btn,
			.thumbnail .btn {
				margin-top : 20px;
			}

			.intro-feature h4 {
				margin-bottom : 25px;
			}

			.intro-feature + .intro-feature {
				margin-top : 40px;
			}

			.intro-xirodemoter {
				text-align : center;
				background : #202935;
				color      : #ffffff;
			}

			.version-history {
				margin          : 0 auto 2rem auto;
				padding         : 0;
				list-style-type : none;
				color           : #ffffff;
			}

			.version-history > li {
				margin      : 0 0 0.5em 0;
				padding     : 0 0 0 4em;
				font-weight : normal;
			}

			.version-new,
			.version-fixed,
			.version-upgraded {
				float                 : left;
				font-size             : 0.8em;
				margin-left           : -4.9em;
				width                 : 4.5em;
				color                 : white;
				text-align            : center;
				font-weight           : bold;
				text-transform        : uppercase;
				-webkit-border-radius : 4px;
				-moz-border-radius    : 4px;
				border-radius         : 4px;
			}

			.version-new {
				background : #7dc35b;
			}

			.version-fixed {
				background : #e9a130;
			}

			.version-upgraded {
				background : #61b3de;
			}

			.install-ok {
				background : #7dc35b;
				color      : #ffffff;
				padding    : 3px;
			}

			.install-not-ok {
				background : #E9452F;
				color      : #ffffff;
				padding    : 3px;
			}

			#installer-left {
				border : 1px solid #e0e0e0;
				float  : left;
				margin : 10px;
			}

			#installer-right {
				float : left;
			}

			.tpl-button {
				display               : inline-block;
				background            : #459300;
				border                : 1px solid #459300 !important;
				padding               : 2px;
				color                 : #fff !important;
				cursor                : pointer;
				margin                : 0;
				-webkit-border-radius : 5px;
				-moz-border-radius    : 5px;
				border-radius         : 5px;
				text-decoration       : none !important;
			}

			.tpl-button:hover {
				text-decoration : underline !important;
			}

			.big-warning {
				background : #FAF0DB;
				border     : solid 1px #EBC46F;
				padding    : 5px;
			}

			.big-warning b {
				color : red;
			}

		</style>


		<div class="template-intro">
			<div class="intro-header">
				<div class="template-intro-container">
					<div class="row-fluid">
					<div class="span12">
						<h1 class="intro-header-title">
							<?php echo Text::_('PKG_XIROADMIN_TEMPLATE_ADMINISTRATOR_HEADER_TITLE'); ?>
						</h1>
						</div>
					</div>
					
				</div>
			</div>
			<div class="intro-content">
				<section class="intro-content-highlights">
					<div class="template-intro-container">
						<div class="row-fluid">
							<div class="intro-quote lead">
								<?php echo Text::_('PKG_XIROADMIN_TEMPLATE_ADMINISTRATOR_INSTALL_DESC'); ?>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12">
								<h3 class="intro-section-title"><strong>XiroWeb</strong> </h3>
							</div>
						</div>
					</div>
				</section>
			</div>
			<div class="intro-xirodemoter">
				<h3 class="text-center"><?php echo Text::_('PKG_XIROADMIN_TEMPLATE_ADMINISTRATOR_INSTALL_DESC2'); ?></h3>
				<ul class="version-history">
					<li class="text-center"><?php echo Text::_('PKG_XIROADMIN_TEMPLATE_ADMINISTRATOR_INSTALL_DESC3'); ?></li>
				</ul>
				<div class="template-intro-container">
					<a class="btn btn-success btn-large" target="_blank" href="https://www.xiroweb.com/xiroadmin-administrator-template-joomla"><?php echo Text::_('PKG_XIROADMIN_TEMPLATE_GUIDE'); ?></a>
				</div>
			</div>
		</div>
		<?php
	}
}
