<?php // no direct access
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');

if ($this->imageset == null) {
    return;
}

?>

<div class="alert alert-success"><?php echo JText::_('COM_EVENTGALLERY_PRODUCT_BUY_IMAGES_CONFIRM_ADDING_SINGLE_IMAGE') ?> </div>
<style>
    .imageset .quantityselection,
    .imageset .group,
    .imageset .imagetypegroups,
    .imageset .imageset-description {
        display:none;
    }
</style>
<?php echo $this->loadSnippet('imageset/imageselection/details'); ?>
<script type="text/javascript">
    jQuery('.eventgallery-qtyplus').click();
</script>

