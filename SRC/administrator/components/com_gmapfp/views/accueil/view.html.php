<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3.50F
	* Creation date: Octobre 2017
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );
jimport('joomla.html.pane');

class GMapFPsViewAccueil extends JViewLegacy
{
    public function display($cachable = false, $urlparams = false)
	{
		$mainframe = JFactory::getApplication(); 

		JToolBarHelper::title( JText::_( 'GMAPFP_ACCUEIL_MANAGER' ),'cpanel.png' );
		$this->sidebar = JHtmlSidebar::render();

		parent::display();
	}

	/**
	 * GMapFP c'est aussi
	 */
	function Infos_GMapFP(){
        $lang = JFactory::getLanguage(); 
        $tag_lang=(substr($lang->getTag(),0,2)); 

		if ($tag_lang=='fr'){
			$output = '<div style="padding: 5px;">';
			$output .= "Une Galerie d'image : <a target='_blank' href='http://joomla-slideshow.com/fr/'>http://joomla-slideshow.com/fr/</a><br/>";
			$output .= "Une playlist audio : <a target='_blank' href='http://joomla-playlist.com/fr/'>http://joomla-playlist.com/fr/</a><br/>";
			$output .= "Des cartes vectoriels : <a target='_blank' href='http://cartes-cliquables-joomla.gmapfp.org/fr/'>http://cartes-cliquables-joomla.gmapfp.org/fr/</a><br/>";
			$output .= "Un portail famille pour Joomla : <a target='_blank' href='http://www.portail-famille-joomla.fr/'>http://www.portail-famille-joomla.fr/</a><br/>";
			$output .= '</div>';
		} else {
			$output = '<div style="padding: 5px;">';
			$output .= "An image gallery : <a target='_blank' href='http://joomla-slideshow.com/en/'>http://joomla-slideshow.com/en/</a><br/>";
			$output .= "An audio playlist : <a target='_blank' href='http://joomla-playlist.com/en/'>http://joomla-playlist.com/en/</a><br/>";
			$output .= "Vector maps : <a target='_blank' href='http://cartes-cliquables-joomla.gmapfp.org/en/'>http://cartes-cliquables-joomla.gmapfp.org/en/</a><br/>";
			$output .= '</div>';
		}
		return $output;
	}
	
	/**
	 * Donation pour GMapFP
	 */
	function Infos_Donation(){

		$mainframe  = JFactory::getApplication(); 
		$lang		= JFactory::getLanguage();
		$langue		= $lang->getTag();
		$langue=str_replace('-','_',$langue);
		
		$template	= $mainframe->getTemplate();
		$tag_lang=(substr($lang->getTag(),3,2)); 
	
		$output = '<div style="padding: 5px;">';
		$output .= '<span style="font-size:120%;">'.JText::_('GMAPFP_EXPLICATION_DONATION');
		$output .= '<br /><span style="color:#0000FF; font-size:170%; font-weight:bold;">'.JText::_('GMAPFP_SOMME_DONT').'</span>';
		$output .= JText::_('GMAPFP_EXPLICATION2_DONATION').'</span>';
		$output .= '<br /><br /><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=f_pelletier%40yahoo%2ecom&currency_code=EUR&lc="'.$tag_lang.'&item_name=Donation%20for%20GMapFP%20services&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHostedGuest"><img src="https://www.paypal.com/'.$langue.'/i/btn/btn_donate_SM.gif">
                        </a><br /><br /><br />';
		$output .= '</div>';
		return $output;
	}

	/**
	 * News du site de GMapFP
	 */
	function rss_out($rssurl) {
	 	
		$cache_time	= 43200;
		$rssDoc = false;
		$output = "";

		try
		{
			$feed = new JFeedFactory;
			$rssDoc = $feed->getFeed($rssurl);
		}
		catch (Exception $e)
		{
			$output = JText::_('JLIB_UTIL_ERROR_LOADING_FEED_DATA');
		}

		if ( $rssDoc == false ) {
			$output = JText::_('Error: Feed not retrieved');
		} else {	
			// channel header and link
			
			$numItems = 5;
			for( $j = 0; $j < $numItems; $j++ ) {
				$item = $rssDoc[$j];
				$output .= '<h5 class="feed-link">';
				$output .= '<a href="' .$item->guid. '" target="_blank">' .$item->title. '</a></h5>';
				if($item->content) {
					$output .= '<div class="feed-item-description">'.$this->limitText($item->content, 250).'</div>';
				}
			}
		}	 	
	 	return $output;
	}

	function Infos_News() {
	 	
        $lang = JFactory::getLanguage(); 
        $tag_lang=(substr($lang->getTag(),0,2)); 

		//  get RSS parsed object
		if ($tag_lang=='fr'){
			$rssurl		= 'http://gmapfp.org/fr/news?format=feed&type=rss';
		}else{
			$rssurl		= 'http://gmapfp.org/en/news-of-gmapfp?format=feed&type=rss';
		};
		return $this->rss_out($rssurl);
	}

	function limitText($text, $wordcount)
	{
		if(!$wordcount) {
			return $text;
		}

		$texts = explode( ' ', $text );
		$count = count( $texts );

		if ( $count > $wordcount )
		{
			$text = '';
			for( $i=0; $i < $wordcount; $i++ ) {
				$text .= ' '. $texts[$i];
			}
			$text .= '...';
		}

		return $text;
	}
		 

}
