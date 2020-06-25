<?php 
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3.32F
	* Creation date: Août 2015
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die('Restricted access'); 
$doc 	= JFactory::getDocument();
$doc->addCustomTag( '<link rel="stylesheet" href="components/com_gmapfp/views/gmapfp/gmapfp_print.css" media="print" type="text/css" />'); 
$doc->addCustomTag( '<meta name="ROBOTS" content="NOINDEX,NOFOLLOW"/>'); 
$config = JComponentHelper::getParams('com_gmapfp'); 
$printer=JURI::base().'components/com_gmapfp/images/printer.png';
?>
<a class="gmnoprint" href="javascript:window.print()" title="<?php echo JText::_('GMAPFP_IMPRIMER'); ?>"><img src="<?php echo $printer; ?>" /></a>
<div  id="gmapfp_print" class="gmapfp">
<div>
	<?php echo $this->map; ?>
</div >
<?php
foreach ($this->lieux as $lieu) {	?>
    	<h1><?php echo $lieu->nom; ?></h1><br />
        <h4>(<?php echo $lieu->title; ?>)</h4><br />
		<div>
			<?php if ($lieu->img!=null) { $image=JURI::base().$config->get('gmapfp_chemin_img').$lieu->img;?> <img src=<?php echo $image; ?> /> <?php }; ?>
        </div>
        <table>
            <tr>
                <td class="gmapfp_print_taille2">
            <?php if ($lieu->adresse!=null) {?><label><?php echo JText::_('GMAPFP_ADRESSE');?></label><span><?php echo $lieu->adresse;?></span><br /> <?php };?> 
            <?php if ($lieu->adresse2!=null) {?><label><?php echo JText::_('GMAPFP_ADRESSE');?></label><span><?php echo $lieu->adresse2;?></span><br /> <?php };?> 
            <?php if ($lieu->codepostal!=null) {?><label><?php echo JText::_('GMAPFP_CODEPOSTAL');?></label><span><?php echo $lieu->codepostal;?></span><br /> <?php };?> 
            <?php if ($lieu->ville!=null) {?><label><?php echo JText::_('GMAPFP_VILLE'); ?></label><span><?php  echo $lieu->ville;?></span><br /> <?php };?> 
            <?php if ($lieu->departement!=null) {?><label><?php echo JText::_('GMAPFP_DEPARTEMENT');?></label><span><?php echo $lieu->departement;?></span><br /> <?php };?> 
            <?php if ($lieu->pay!=null) {?><label><?php echo JText::_('GMAPFP_PAYS');?></label><span><?php echo $lieu->pay;?></span><br /> <?php };?> 
                </td>
                <td>
            <?php if ($lieu->tel!=null) {?><label><?php echo JText::_('GMAPFP_TEL');?></label><span><?php echo $lieu->tel;?></span><br /> <?php };?> 
            <?php if ($lieu->tel2!=null) {?><label><?php echo JText::_('GMAPFP_TEL');?></label><span><?php echo $lieu->tel2;?></span><br /> <?php };?> 
            <?php if ($lieu->fax!=null) {?><label><?php echo JText::_('GMAPFP_FAX');?></label><span><?php echo $lieu->fax;?></span><br /> <?php };?> 
            <?php if ($lieu->email!=null) {?><label><?php echo JText::_('GMAPFP_EMAIL');?></label><span><?php echo $lieu->email;?></span><br /> <?php };?> 
            <?php if ($lieu->web!=null) {?><label><?php echo JText::_('GMAPFP_SITE_WEB');?> </label><span><?php echo $lieu->web;?></span> <br /> <?php };?> <br />
                </td>
			</tr>
		</table>
        <br />
        <span><?php echo $lieu->intro; echo $lieu->message; ?></span><br /><br />
        <?php if ($lieu->horaires_prix!=null) {?><label><?php echo JText::_('GMAPFP_HORAIRES_PRIX');?> </label><br /><span><?php echo $lieu->horaires_prix;?></span><br /> <?php };
}; ?>
<div style="page-break-before: always;"></div>
<div\>
