<?php
/**
 * Securitycheck package
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of Securitycheck component
 */
class com_SecuritycheckInstallerScript
{
	
	/** @var array Obsolete files and folders to remove  */
	private $ObsoleteFilesAndFolders = array(
		'files'	=> array(
			// Outdated media files
			'media/com_securitycheck/images/blocked.jpg',
			'media/com_securitycheck/images/http.jpg',
			'media/com_securitycheck/images/no_read.jpg',
			'media/com_securitycheck/images/oval_blue_left.gif',
			'media/com_securitycheck/images/oval_blue_right.gif',
			'media/com_securitycheck/images/oval_green_left.gif',
			'media/com_securitycheck/images/oval_green_right.gif',
			'media/com_securitycheck/images/permitted.jpg',
			'media/com_securitycheck/images/read.jpg',
			'media/com_securitycheck/images/second_level.jpg',
			'media/com_securitycheck/images/session_protection.jpg',
			'media/com_securitycheck/images/task_running.gif',			
			'media/com_securitycheck/javascript/excanvas.js',
			'media/com_securitycheck/javascript/jquery.flot.min.js',
			'media/com_securitycheck/javascript/jquery.flot.pie.min.js',
			'media/com_securitycheck/javascript/jquery.flot.stack.js',
			'media/com_securitycheck/javascript/jquery.flot.resize.min.js',
			'media/com_securitycheck/javascript/bootstrap-tab.js',
			'media/com_securitycheck/javascript/charisma.js',
			'media/com_securitycheck/javascript/jquery.js',
			'media/com_securitycheck/javascript/bootstrap-modal.js',
			'media/com_securitycheck/javascript/jquery.percentageloader-0.1.js',
			'media/com_securitycheck/javascript/jquery.percentageloader-0.1_license.txt',			
			'media/com_securitycheck/stylesheets/BebasNeue-webfont.eot',
			'media/com_securitycheck/stylesheets/BebasNeue-webfont.svg',
			'media/com_securitycheck/stylesheets/BebasNeue-webfont.ttf',
			'media/com_securitycheck/stylesheets/BebasNeue-webfont.woff',
			'media/com_securitycheck/stylesheets/bootstrap.min.css',
			'media/com_securitycheck/stylesheets/jquery.percentageloader-0.1.css',
			'media/com_securitycheck/stylesheets/opa-icons.css',			
		),
		'folders'	=> array(
			// Removed views
			'administrator/components/com_securitycheck/views/initialize_data',
		)
	);
	
	/**
	 * Removes obsolete files and folders
	 *
	 * @param array $ObsoleteFilesAndFolders
	 */
	private function _removeObsoleteFilesAndFolders($ObsoleteFilesAndFolders)
	{
		// Remove files
		JLoader::import('joomla.filesystem.file');
		if(!empty($ObsoleteFilesAndFolders['files'])) foreach($ObsoleteFilesAndFolders['files'] as $file) {
			$f = JPATH_ROOT.'/'.$file;
			if(!file_exists($f)) continue;
			JFile::delete($f);
		}
		
		//Remove folders
		JLoader::import('joomla.filesystem.file');
		if(!empty($ObsoleteFilesAndFolders['folders'])) foreach($ObsoleteFilesAndFolders['folders'] as $folder) {
			$f = JPATH_ROOT.'/'.$folder;
			if(!JFolder::exists($f)) continue;
			JFolder::delete($f);
		}
	}
	
	/**
	 * Joomla! pre-flight event
	 * 
	 * @param string $type Installation type (install, update, discover_install)
	 * @param JInstaller $parent Parent object
	 */
	public function preflight($type, $parent)
	{
		// Only allow to install on PHP 5.3.0 or later
		if ( !version_compare(PHP_VERSION, '5.3.0', 'ge') ) {
			JFactory::getApplication()->enqueueMessage('Securitycheck Pro requires, at least, PHP 5.3.0', 'error');
			return false;
		} else if ( version_compare(JVERSION, '3.0.0', 'lt') ) {
			// Only allow to install on Joomla! 3.0.0 or later, but not in 2.5 branch
			JFactory::getApplication()->enqueueMessage("This version doesn't work in Joomla! 2.5 branch", 'error');
			return false;
		}
		
		// Check if the 'mb_strlen' function is enabled
		if ( !function_exists("mb_strlen") ) {
			JFactory::getApplication()->enqueueMessage("The 'mb_strlen' function is not installed in your host. Please, ask your hosting provider about how to install it", 'warning');
			return false;
		}
		
		$this->_removeObsoleteFilesAndFolders($this->ObsoleteFilesAndFolders);
	}
	
	/**
	 * Runs after install, update or discover_update
	 * @param string $type install, update or discover_update
	 * @param JInstaller $parent 
	 */
	function postflight( $type, $parent )
	{
	
		$existe_tabla = false;
		
		$db = JFactory::getDBO();
		$total_rows = $db->getTableList();
		
		if ( !(is_null($total_rows)) ) {
			foreach ($total_rows as $table_name) {
				if ( strstr($table_name,"securitycheck_logs") ) {
					$existe_tabla = true;
				}
			}
		}
		
		if ( !$existe_tabla ) {
			// Disable plugin
			$tableExtensions = $db->quoteName("#__extensions");
			$columnElement   = $db->quoteName("element");
			$columnType      = $db->quoteName("type");
			$columnEnabled   = $db->quoteName("enabled");
			$db->setQuery(
				"UPDATE 
					$tableExtensions
				SET
					$columnEnabled=0
				WHERE
					$columnElement='securitycheck'
				AND
					$columnType='plugin'"
			);
			$db->execute();
			JFactory::getApplication()->enqueueMessage("There has been an error when creating database tables. Securitycheck Web Firewall plugin has been disabled.", 'warning');
		}	
	}
	
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
		$installer = new JInstaller();		
		
		$manifest = $parent->getParent()->getManifest();
		$source = $parent->getParent()->getPath('source');
		
		// Install plugins
		foreach($manifest->plugins->plugin as $plugin) {
			$attributes = $plugin->attributes();
			$plg = $source . DIRECTORY_SEPARATOR . $attributes['folder'].DIRECTORY_SEPARATOR.$attributes['plugin'];
			$result = $installer->install($plg);
		}

		$db = JFactory::getDbo();
		$tableExtensions = $db->quoteName("#__extensions");
		$columnElement   = $db->quoteName("element");
		$columnType      = $db->quoteName("type");
		$columnEnabled   = $db->quoteName("enabled");
            
		// Enable plugin
		$db->setQuery(
			"UPDATE 
				$tableExtensions
			SET
				$columnEnabled=1
			WHERE
				$columnElement='securitycheck'
			AND
				$columnType='plugin'"
		);
		
		$db->execute();

		// Install message
		$this->install_message($result); 
	}
	
		
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent){
		
		$db = JFactory::getDbo();
		$columnName      = $db->quoteName("extension_id");
		$tableExtensions = $db->quoteName("#__extensions");
		$type 			 = $db->quoteName("type");
		$columnElement   = $db->quoteName("element");
		$columnType      = $db->quoteName("folder");
            
		// Desinstall plugin
		$db->setQuery(
			"SELECT 
				$columnName
			FROM
				$tableExtensions
			WHERE
				$type='plugin'
			AND
				$columnElement='securitycheck'
			AND
				$columnType='system'"
		
		);

	$id = $db->loadResult();

	if ($id) {
		$installer = new JInstaller();
		$result = $installer->uninstall('plugin',$id,1);		
	}
	
	// Uninstall message
		$this->uninstall_message($result);	
	}
	
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		$manifest = $parent->getParent()->getManifest();
		$source = $parent->getParent()->getPath('source');

		$installer = new JInstaller();
		
		// Install plugins
		foreach($manifest->plugins->plugin as $plugin) {
			$attributes = $plugin->attributes();
			$plg = $source . DIRECTORY_SEPARATOR . $attributes['folder'].DIRECTORY_SEPARATOR.$attributes['plugin'];
			$installer->install($plg);
		}			
		
	}
	
/**
	 * method to show the install message
	 *
	 * @return void
	 */
	function install_message($result){
?>
		<img src='../media/com_securitycheck/images/tick_48x48.png' style='float: left; margin: 5px;'>
		<h1><?php echo JText::_( 'COM_SECURITYCHECK_HEADER_INSTALL' ); ?></h1>
		<h2><?php echo JText::_( 'COM_SECURITYCHECK_WELCOME' ); ?></h2>
		<div class="securitycheck-bootstrap">
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="title" colspan="2"><?php echo JText::_( 'COM_SECURITYCHECK_EXTENSION' ); ?></th>
					<th width="30%"><?php echo JText::_( 'COM_SECURITYCHECK_STATUS' ); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3"></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td colspan="2">Securitycheck <?php echo JText::_( 'COM_SECURITYCHECK_COMPONENT' ); ?></td>
					<td>
						<?php 
							$span = "<span class=\"badge badge-success\">";								
						?>
						<?php echo $span . JText::_( 'COM_SECURITYCHECK_INSTALLED' ); ?>
						</span>
					</td>					
				</tr>
				<tr class="row0">
					<td class="key" colspan="2">Securitycheck <?php echo JText::_( 'COM_SECURITYCHECK_PLUGIN' ); ?></td>
				<?php 
					if ($result) { 
				?>
					<td>
						<?php 
							$span = "<span class=\"badge badge-success\">";								
						?>
						<?php echo $span . JText::_( 'COM_SECURITYCHECK_INSTALLED' ); ?>
						</span>
						<?php 
							$span = "<span class=\"badge badge-info\">";	
							$message = JText::_( 'COM_SECURITYCHECK_PLUGIN_ENABLED' );																					
						?>
						<?php echo $span . $message; ?>
					</td>
				<?php 
					} else {
				?>
						<td>
							<?php 
								$span = "<span class=\"badge badge-important\">";								
							?>
							<?php echo $span . JText::_( 'COM_SECURITYCHECK_NOT_INSTALLED' ); ?>
							</span>
						</td>
				<?php
					}
				?>
				</tr>				
			</tbody>
		</table>
		</div>
<?php
	}
	
	/**
	 * method to show the uninstall message
	 *
	 * @return void
	 */
	function uninstall_message($result){
?>
	<h1><?php echo JText::_( 'COM_SECURITYCHECK_HEADER_UNINSTALL' ); ?></h1>
	<h2><?php echo JText::_( 'COM_SECURITYCHECK_GOODBYE' ); ?></h2>
	<div class="securitycheck-bootstrap">
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="title" colspan="2"><?php echo JText::_( 'COM_SECURITYCHECK_EXTENSION' ); ?></th>
				<th width="30%"><?php echo JText::_( 'COM_SECURITYCHECK_STATUS' ); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3"></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td colspan="2">Securitycheck <?php echo JText::_( 'COM_SECURITYCHECK_COMPONENT' ); ?></td>
				<td>
					<?php 
						$span = "<span class=\"badge badge-success\">";								
					?>
					<?php echo $span . JText::_( 'COM_SECURITYCHECK_UNINSTALLED' ); ?>
				</td>
			</tr>
			<tr class="row0">
				<td class="key" colspan="2">Securitycheck <?php echo JText::_( 'COM_SECURITYCHECK_PLUGIN' ); ?> </td>
				<?php 
				if ($result) {
				?>
					<td>
						<?php 
							$span = "<span class=\"badge badge-success\">";								
						?>
						<?php echo $span . JText::_( 'COM_SECURITYCHECK_UNINSTALLED' ); ?>
					</td>
				<?php
				} else {
				?>
					<td>
						<?php 
							$span = "<span class=\"badge badge-important\">";								
						?>
						<?php echo $span . JText::_( 'COM_SECURITYCHECK_NOT_INSTALLED' ); ?>
						</span>
					</td>
				<?php
				}
				?>
			</tr>		
		</tbody>
	</table>
	</div>
<?php
	}
}
?>