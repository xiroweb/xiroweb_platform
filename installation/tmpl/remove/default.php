<?php
/**
 * @package    Joomla.Installation
 *
 * @copyright  (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

HTMLHelper::_('behavior.formvalidator');

/** @var \Joomla\CMS\Installation\View\Remove\HtmlView $this */
?>
<div id="installer-view" data-page-name="remove">

	<fieldset id="installCongrat" class="j-install-step active">
		<legend class="j-install-step-header">
			<span class="icon-trophy" aria-hidden="true"></span> <?php echo Text::_('INSTL_COMPLETE_CONGRAT'); ?>
		</legend>
		<div class="j-install-step-form" id="customInstallation">
			<div class="alert alert-success">
				<h2><?php echo Text::_('INSTL_COMPLETE_TITLE'); ?></h2>
			</div>
			<div class="mb-3 mt-4 hidden">
				<button class="btn btn-primary w-100" id="installAddFeatures">
					<?php echo Text::_('INSTL_COMPLETE_ADD_EXTRA_LANGUAGE'); ?> <span class="icon-chevron-right" aria-hidden="true"></span>
				</button>
			</div>
		</div>

		<?php if (count($this->installed_languages->administrator) > 1) : ?>
				<div id="defaultLanguage "
					class="j-install-step-form flex-column mt-5 border rounded hidden"
				>
		<?php else : ?>
				<div id="defaultLanguage"
					class="j-install-step-form flex-column mt-5 border rounded d-none hidden"
				>
		<?php endif; ?>
		<p><?php echo Text::_('INSTL_DEFAULTLANGUAGE_DESC'); ?></p>
		<table class="table table-sm">
			<thead>
			<tr>
				<th>
					<?php echo Text::_('INSTL_DEFAULTLANGUAGE_COLUMN_HEADER_SELECT'); ?>
				</th>
				<th>
					<?php echo Text::_('INSTL_DEFAULTLANGUAGE_COLUMN_HEADER_LANGUAGE'); ?>
				</th>
				<th>
					<?php echo Text::_('INSTL_DEFAULTLANGUAGE_COLUMN_HEADER_TAG'); ?>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($this->installed_languages->administrator as $i => $lang) : ?>
				<tr>
					<td>
						<input
							class="form-check-input"
							id="admin-language-cb<?php echo $i; ?>"
							type="radio"
							name="administratorlang"
							value="<?php echo $lang->language; ?>"
							<?php if ($lang->published) echo 'checked="checked"'; ?>
						/>
					</td>
					<td>
						<label for="admin-language-cb<?php echo $i; ?>">
							<?php echo $lang->name; ?>
						</label>
					</td>
					<td>
						<?php echo $lang->language; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<p><?php echo Text::_('INSTL_DEFAULTLANGUAGE_DESC_FRONTEND'); ?></p>
		<table class="table table-sm">
			<thead>
			<tr>
				<th>
					<?php echo Text::_('INSTL_DEFAULTLANGUAGE_COLUMN_HEADER_SELECT'); ?>
				</th>
				<th>
					<?php echo Text::_('INSTL_DEFAULTLANGUAGE_COLUMN_HEADER_LANGUAGE'); ?>
				</th>
				<th>
					<?php echo Text::_('INSTL_DEFAULTLANGUAGE_COLUMN_HEADER_TAG'); ?>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($this->installed_languages->frontend as $i => $lang) : ?>
				<tr>
					<td>
						<input
							class="form-check-input"
							id="site-language-cb<?php echo $i; ?>"
							type="radio"
							name="frontendlang"
							value="<?php echo $lang->language; ?>"
							<?php if ($lang->published) echo 'checked="checked"'; ?>
						/>
					</td>
					<td>
						<label for="site-language-cb<?php echo $i; ?>">
							<?php echo $lang->name; ?>
						</label>
					</td>
					<td>
						<?php echo $lang->language; ?>
					</td>
			</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<button id="defaultLanguagesButton" class="btn w-100 mt-4 btn-primary">
			<?php echo Text::_('INSTL_DEFAULTLANGUAGE_SET_DEFAULT_LANGUAGE'); ?> <span class="icon-chevron-right" aria-hidden="true"></span>
		</button>
		<?php echo HTMLHelper::_('form.token'); ?>
		</div>
	</fieldset>

		<div id="installRecommended" class="j-install-step active">
			<div class="j-install-step-form">
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="form-group j-install-last-step gap-2">
							<h3><?php echo Text::_('INSTL_COMPLETE_ADMINISTRATION_ABOUT_FRONTEND_BACKEND'); ?></h3>
							<p><?php echo Text::_('INSTL_COMPLETE_ADMINISTRATION_ABOUT_FRONTEND_BACKEND_DESC'); ?></p>

							<p class="mt-3"><?php echo Text::_('INSTL_COMPLETE_ADMINISTRATION_ABOUT_BACKEND'); ?> <span class="badge bg-secondary"><?php echo Uri::root(); ?>administrator/</span></p>
							<button type="button" class="complete-installation btn btn-primary"
							data-href="<?php echo Uri::root(); ?>administrator/" <?php if ($this->development): ?>data-development<?php endif; ?>>
								<span class="icon-lock" aria-hidden="true"></span> <?php echo Text::_('INSTL_COMPLETE_ADMIN_BTN'); ?>
							</button>

							<p class="mt-3"><?php echo Text::_('INSTL_COMPLETE_ADMINISTRATION_ABOUT_FRONTEND'); ?> <span class="badge bg-secondary"><?php echo Uri::root(); ?></span></p>
							<button type="button" class="complete-installation btn btn-primary
							data-href="<?php echo Uri::root(); ?>" <?php if ($this->development): ?>data-development<?php endif; ?>>
								<span class="icon-eye" aria-hidden="true"></span> <?php echo Text::_('INSTL_COMPLETE_SITE_BTN'); ?>
							</button>
							
						</div>
					</div>
					<div class="col-12 col-md-6">
						
			<?php $displayTable = false; ?>
			<?php foreach ($this->phpsettings as $setting) : ?>
				<?php if ($setting->state !== $setting->recommended) : ?>
					<?php $displayTable = true; ?>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php
			if ($displayTable) : ?>
				<table class="table table-sm">
					<caption>
						<?php echo Text::_('INSTL_PRECHECK_RECOMMENDED_SETTINGS_DESC'); ?>
					</caption>
					<thead>
						<tr>
							<th scope="col">
								<?php echo Text::_('INSTL_PRECHECK_DIRECTIVE'); ?>
							</th>
							<th scope="col">
								<?php echo Text::_('INSTL_PRECHECK_RECOMMENDED'); ?>
							</th>
							<th scope="col">
								<?php echo Text::_('INSTL_PRECHECK_ACTUAL'); ?>
							</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($this->phpsettings as $setting) : ?>
						<?php if ($setting->state !== $setting->recommended) : ?>
							<tr>
								<th scope="row">
									<?php echo $setting->label; ?>
								</th>
								<td>
									<span class="badge bg-success disabled">
										<?php echo Text::_($setting->recommended ? 'JON' : 'JOFF'); ?>
									</span>
								</td>
								<td>
									<span class="badge bg-<?php echo ($setting->state === $setting->recommended) ? 'success' : 'warning'; ?>">
										<?php echo Text::_($setting->state ? 'JON' : 'JOFF'); ?>
									</span>
								</td>
							</tr>
						<?php endif; ?>
					<?php endforeach; ?>
					</tbody>
				</table>

				<?php endif; ?>

				<?php if ($this->development) : ?>
					<div class="alert flex-column mb-1" id="removeInstallationTab">
						<span class="mb-1 font-weight-bold"><?php echo Text::_('INSTL_COMPLETE_REMOVE_INSTALLATION'); ?></span>
						<span class="mb-1 font-weight-bold"><?php echo Text::_('INSTL_SITE_DEVMODE_LABEL'); ?></span>
						<button class="btn btn-danger mb-1" id="removeInstallationFolder"><?php echo Text::sprintf('INSTL_COMPLETE_REMOVE_FOLDER', 'installation'); ?></button>
					</div>
				<?php endif; ?>
				<?php echo HTMLHelper::_('form.token'); ?>

				
				</div>
				</div>
			</div>
		</div>

		<fieldset id="installLanguages" class="j-install-step">
			<legend class="j-install-step-header">
				<span class="icon-comment-dots" aria-hidden="true"></span> <?php echo Text::_('INSTL_LANGUAGES'); ?>
			</legend>
			<div class="j-install-step-form">
			<?php if (!$this->items) : ?>
				<p><?php echo Text::_('INSTL_LANGUAGES_WARNING_NO_INTERNET'); ?></p>
				<p>
					<a href="#"
							class="btn btn-primary"
							onclick="return Joomla.goToPage('remove');">
						<span class="icon-arrow-left icon-white" aria-hidden="true"></span>
						<?php echo Text::_('INSTL_LANGUAGES_WARNING_BACK_BUTTON'); ?>
					</a>
				</p>
				<p><?php echo Text::_('INSTL_LANGUAGES_WARNING_NO_INTERNET2'); ?></p>
			<?php else : ?>
			<form action="index.php" method="post" id="languagesForm" class="form-validate">
				<p id="wait_installing" class="hidden">
					<?php echo Text::_('INSTL_LANGUAGES_MESSAGE_PLEASE_WAIT'); ?><br>
				<div id="wait_installing_spinner" class="spinner spinner-img hidden"></div>
				</p>
				<table class="table table-sm">
				<caption id="install_languages_desc"><?php echo Text::_('INSTL_LANGUAGES_DESC'); ?></caption>
					<thead>
					<tr>
						<th scope="col">
							<span class="visually-hidden"><?php echo Text::_('INSTL_LANGUAGES_COLUMN_HEADER_LANGUAGE_SELECT'); ?></span>
						</th>
						<th scope="col">
							<?php echo Text::_('INSTL_LANGUAGES_COLUMN_HEADER_LANGUAGE'); ?>
						</th>
						<th scope="col">
							<?php echo Text::_('INSTL_LANGUAGES_COLUMN_HEADER_LANGUAGE_TAG'); ?>
						</th>
						<th scope="col" class="text-center">
							<?php echo Text::_('INSTL_LANGUAGES_COLUMN_HEADER_VERSION'); ?>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php $version = new \Joomla\CMS\Version; ?>
					<?php $currentShortVersion = preg_replace('#^([0-9\.]+)(|.*)$#', '$1', $version->getShortVersion()); ?>
					<?php foreach ($this->items as $i => $language) : ?>
						<?php // Get language code and language image. ?>
						<?php preg_match('#^pkg_([a-z]{2,3}-[A-Z]{2})$#', $language->element, $element); ?>
						<?php $language->code = $element[1]; ?>
						<tr>
							<td>
								<input class="form-check-input" type="checkbox" id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $language->update_id; ?>">
							</td>
							<th scope="row">
								<label for="cb<?php echo $i; ?>"><?php echo $language->name; ?></label>
							</th>
							<td>
								<?php echo $language->code; ?>
							</td>
							<td class="text-center">
								<?php // Display a Note if language pack version is not equal to Joomla version ?>
								<?php if (substr($language->version, 0, 3) != $version::MAJOR_VERSION . '.' . $version::MINOR_VERSION || substr($language->version, 0, 5) != $currentShortVersion) : ?>
									<span class="badge bg-warning hasTooltip" title="<?php echo Text::_('JGLOBAL_LANGUAGE_VERSION_NOT_PLATFORM'); ?>"><?php echo $language->version; ?></span>
								<?php else : ?>
									<span class="badge bg-success"><?php echo $language->version; ?></span>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<?php echo HTMLHelper::_('form.token'); ?>
			<?php endif; ?>
				<div class="form-group d-grid gap-2">
					<button id="installLanguagesButton" class="btn btn-primary w-100">
						<?php echo Text::_('JNEXT'); ?>
					</button>
					<button id="skipLanguages" class="btn btn-secondary w-100">
					<?php echo Text::_('JSKIP'); ?>
					</button>
				</div>
			</form>
			</div>
		</fieldset>

		<fieldset id="installFinal" class="j-install-step">
			<legend class="j-install-step-header">
				<span class="icon-joomla" aria-hidden="true"></span> <?php echo Text::_('INSTL_COMPLETE_FINAL'); ?>
			</legend>
			<div class="j-install-step-form">
				<p><?php echo Text::_('INSTL_COMPLETE_FINAL_DESC'); ?></p>
			</div>
		</fieldset>


</div>
