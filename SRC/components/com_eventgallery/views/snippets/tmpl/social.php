<?php // no direct access
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
/**
 * @var \de\svenbluege\joomla\eventgallery\ObjectWithConfiguration $this
 */
?>

<?php IF (!isset($GLOBALS['eventgallery_social_stuff_loaded']) && $this->config->getSocial()->doUseSocialSharingButton()):?>
<?php $GLOBALS['eventgallery_social_stuff_loaded'] = true; ?>

<?php ENDIF ?>
