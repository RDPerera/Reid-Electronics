<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


//the name of the class must be the name of your component + InstallerScript
//for example: com_contentInstallerScript for com_content.
class pkg_eventgallery_coreInstallerScript
{

        protected $minimumPHPVersion = '5.5.0';
        protected $minimumJoomlaVersion = '3.7.0';

        /**
         * method to run before an install/update/uninstall method
         *
         * @return void
         */
        function preflight($type, $parent)
        {

            if (!version_compare(PHP_VERSION, $this->minimumPHPVersion, 'ge'))
            {
                $msg = "<p>You need PHP $this->minimumPHPVersion or later to install this package</p>";
                JLog::add($msg, JLog::WARNING, 'jerror');

                return false;
            }

            if (!version_compare(JVERSION, $this->minimumJoomlaVersion, 'ge'))
            {
                $msg = "<p>You need Joomla! $this->minimumJoomlaVersion or later to install this component</p>";
                JLog::add($msg, JLog::WARNING, 'jerror');

                return false;
            }

        }

        /**
         * method to run after an install/update/uninstall method
         *
         * @return void
         */
        function postflight($type, $parent)
        {
	        $db = JFactory::getDbo();

            $query = $db->getQuery(true);
            $query->delete("#__extensions");
            $query->where('element = ' . $db->quote("pkg_eventgallery_light"));
            $db->setQuery($query);
            $db->execute();

            if (JFile::exists(JPATH_MANIFESTS.'/packages/pkg_eventgallery_light.xml')) {
                JFile::delete(JPATH_MANIFESTS.'/packages/pkg_eventgallery_light.xml');
            }

            //fix wrong attemt for making only the component uninstallable.
            $query = $db->getQuery(true);
            $query->update('#__extensions')
                ->set('protected=0')
                ->where('element='. $db->q('com_eventgallery'));
            $db->setQuery($query);
            $db->execute();

            // uninstall MooTools Plugin
            $query = $db->getQuery(true);
            $query->select('extension_id')->from('#__extensions')
                ->where('element='. $db->q('eventgallery_mootools_fix'));
            $db->setQuery($query);
            $mooToolsPluginid = $db->loadResult();

            if ($mooToolsPluginid > 0) {
                $installer = JInstaller::getInstance();
                $installer->setPackageUninstall(true);
                $installer->uninstall('plugin', $mooToolsPluginid);
            }

	        $plugins = Array(
	                Array('system', 'picasaupdater'),
                    Array('system', 'eventgallerycapabilitiesreport'),
	                Array('installer', 'eventgallery'),
	                Array('content', 'eventgallery_fields_category'),
	            	Array('content', 'eventgallery_multilangcontent')
	        );


	        foreach($plugins as $pluginData) {

	            // Let's get the information of the update plugin
	            $query = $db->getQuery(true)
	                ->select('*')
	                ->from($db->qn('#__extensions'))
	                ->where($db->qn('folder').' = '.$db->quote($pluginData[0]))
	                ->where($db->qn('element').' = '.$db->quote($pluginData[1]))
	                ->where($db->qn('type').' = '.$db->quote('plugin'));
	            $db->setQuery($query);
	            $plugin = $db->loadObject();

	            // If it's missing or enabledthere's nothing else to do
	            if (!is_object($plugin) || $plugin->enabled)
	            {
	                continue;
	            }


	            // Otherwise, try to enable it
	            $pluginObject = (object)array(
	                'extension_id'  => $plugin->extension_id,
	                'enabled'       => 1
	            );

	            try
	            {
	                $result = $db->updateObject('#__extensions', $pluginObject, 'extension_id');
	            }
	            catch (Exception $e)
	            {
	            }
	        }
	    }


}
