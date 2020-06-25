<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_25F
	* Creation date: Février 2015
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

if (@$row->adresse and !$config->get('gmapfpadresse1_view', 0))     {$adr   = addslashes(trim($row->adresse))."<br />";}else{$adr='';};
if (@$row->adresse2 and !$config->get('gmapfpadresse2_view', 0))    {$adr2  = addslashes(trim($row->adresse2))."<br />";}else{$adr2='';};
if (@$row->codepostal and !$config->get('gmapfp_cp_view', 0))  {$cp    = addslashes(trim($row->codepostal))."<br />";}else{$cp='';};
if (@$row->ville and !$config->get('gmapfp_ville_view', 0))       {$ville = addslashes(trim($row->ville))."<br />";}else{$ville='';};
if (@$row->departement and !$config->get('gmapfp_departement_view', 0)) {$dep   = addslashes(trim($row->departement))."<br />";}else{$dep='';};
if (@$row->pay and !$config->get('gmapfp_pays_view', 0))         {$pays  = addslashes(trim($row->pay))."<br />";}else{$pays='';};

if ($config->get('gmapfp_zipcode_ville', 1)) {
	$adresse = $adr.$adr2.$cp.$ville.$dep.$pays;
}else{
	$adresse = $adr.$adr2.$ville.$dep.$pays.$cp;
};

$bubble = ($this->_num_marqueurs+1000).", \"<div class='gmapfp_marqueur' style='width:".$config->get('gmapfp_width_bulle_GMapFP', 400)."px; min-height:".$config->get('gmapfp_min_height_bulle_GMapFP', 150)."px; max-height:".$config->get('gmapfp_max_height_bulle_GMapFP', 350)."px; overflow-y:auto;'><span class='titre'>".$nom."</span><br /><br />".$image."<p class='adresse'>".$adresse."</p>".$plus_detail."</div>\",\"";

return $bubble;
?>