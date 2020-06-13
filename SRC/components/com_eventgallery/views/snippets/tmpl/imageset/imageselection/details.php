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
$cartMgr = EventgalleryLibraryManagerCart::getInstance();
$cart = $cartMgr->getCart();

/**
 * @var EventgalleryLibraryImagetypeset $imagetypeset
 */
$imagetypeset = $this->imageset;
$imagetypegroups = $imagetypeset->getImageTypeGroups(true);
$file = $this->file;
if ($this->imageset == null) {
    return;
}

?>

<script>
    function scrollIfNeeded(element, container) {
        var scrollTop = null;

        if (element.offsetTop <= container.scrollTop) {
            scrollTop = element.offsetTop;
        } else {
            const offsetBottom = element.offsetTop + element.offsetHeight;
            const scrollBottom = container.scrollTop + container.offsetHeight;
            if (offsetBottom > scrollBottom) {
               scrollTop = offsetBottom - container.offsetHeight;
            }
        }

        if (null != scrollTop) {
            if (container.scrollTo) {
                container.scrollTo({
                    'behavior': 'smooth',
                    'left': 0,
                    'top': scrollTop
                });
            } else {
                container.scrollTop = scrollTop;
            }
        }
    }
</script>



<div class="imageset">
    <div class="preview-image">
        <?php echo $file->getThumbImgTag(150, 150, "eg-preview-image",true, null, false, false);?>
    </div>

    <div class="imageset-details">
        <?PHP IF (strlen($this->imageset->getDescription())>0):?>
            <div class="imageset-description"><?php echo $this->imageset->getDescription(); ?></div>
        <?PHP ELSE: ?>
            <div class="imageset-description"><?php echo JText::_('COM_EVENTGALLERY_IMAGESET_PRICES') ?></div>
        <?PHP ENDIF; ?>

        <div class="imagetypegroups">
            <?php FOREACH($imagetypegroups as $imagetypes): ?>
                <?php /**
                * @var EventgalleryLibraryImagetypegroup $imagetypegroup
                */
                $imagetypegroup = $imagetypes[0]->getImageTypeGroup();
                IF ($imagetypegroup != null):?>
                    <a href="#" onclick="event.preventDefault();  scrollIfNeeded(document.getElementById('<?php echo 'imagegroup-'.$imagetypegroup->getId()?>'), document.getElementById('pricelist'))" class="imagetypegroup"><?php echo $imagetypegroup->getDisplayName();?></a>
                <?php ENDIF ?>
            <?php ENDFOREACH?>
        </div>
    </div>
    <div class="pricelist" id="pricelist">
        <table>

        <?php FOREACH($imagetypegroups as $imagetypes): ?>
        <?php
            /**
             * @var EventgalleryLibraryImagetypegroup $imagetypegroup
             */
            $imagetypegroup = $imagetypes[0]->getImageTypeGroup();?>
            <?php IF ($imagetypegroup != null):?>
                <tbody id="<?php echo 'imagegroup-'.$imagetypegroup->getId()?>">
                <tr class="group">
                    <td colspan="4">
                    <div class="imagetypegroup-name"><?php echo $imagetypegroup->getDisplayName()?></div>
                    <div class="imagetypegroup-description"><?php echo $imagetypegroup->getDescription()?></div>
                    </td>
                </tr>
            <?php ELSE:?>
                <tbody>
            <?php ENDIF ?>

            <?php FOREACH($imagetypes as $imageType): /** @var EventgalleryLibraryImagetype $imageType */?>
                <tr class="pricelist-line">
                    <td class="displayname">
                        <?php echo $imageType->getDisplayName(); ?>
                    </td>
                    <td class="pricedisplay">
                        <?php IF( count($imageType->getScalePrices()) == 0):?>
                            <span class="price"><?php echo $imageType->getPrice(); ?> <strong>*</strong></span>
                        <?php ELSE: ?>
                            <?php $this->showstar=true; $this->imagetype = $imageType; echo $this->loadSnippet('imageset/scaleprice/default'); ?>
                        <?php ENDIF; ?>
                        <?php IF ($imageType->getFreeQuantity() > 0 ):?><br><?php echo JText::sprintf('COM_EVENTGALLERY_IMAGETYPE_FREEQUANTITY_LABEL', $imageType->getFreeQuantity())?><?php ENDIF;?>
                    </td>
                    <td class="description"><?php echo $imageType->getDescription(); ?></td>
                    <td class="quantityselection">
                        <div class="input-append pull-right">
                            <button class='btn eventgallery-qtyminus' id="quantityminus_<?php echo $imageType->getId(); ?>" field='quantity_<?php echo $imageType->getId(); ?>'>-</button>
                            <?php
                            $lineitem = $cart->getLineItemByFileAndType($this->file->getFolderName(), $this->file->getFileName(), $imageType->getId());
                            if ($lineitem == null) {
                                $currentQuantity = 0;
                            } else {
                                $currentQuantity = $lineitem->getQuantity();
                            }
                            ?>
                            <input   type='text'
                                     data-id="<?php echo "folder=" . urlencode($this->file->getFolderName()) . "&file=" . urlencode($this->file->getFileName()) . "&imagetypeid=" . $imageType->getId() ?>"
                                     data-maxorderquantity="<?php echo $imageType->getMaxOrderQuantity(); ?>"
                                     name='quantity_<?php echo $imageType->getId(); ?>'
                                     value='<?php echo $currentQuantity?>'
                                     class='qty eventgallery-cartquantity' />
                            <button class='btn eventgallery-qtyplus' id="quantityplus_<?php echo $imageType->getId(); ?>" field='quantity_<?php echo $imageType->getId(); ?>'>+</button>
                        </div>
                    </td>
                </tr>

            <?php ENDFOREACH ?>
            </tbody>
        <?php ENDFOREACH ?>
        </table>
    </div>
</div>
<?php IF ($this->config->getCheckout()->doShowVat()):?>
    <p>
        <small><strong>*</strong> <?php echo JText::_('COM_EVENTGALLERY_PRODUCT_VAT_HINT') ?></small>
    </p>
<?php ENDIF; ?>

