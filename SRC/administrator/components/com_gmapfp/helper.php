<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3/0
	* Creation date: Mars 2013
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/
defined('_JEXEC') or die;

class GMapFPsHelper
{
	function saveGMapFPPrep( &$row )
	{
		// Get submitted text from the request variables
		$text_horaires_prix = JRequest::getVar( 'text_horaires_prix', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$text_message		= JRequest::getVar( 'text_message', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$text_link			= JRequest::getVar( 'text_link', '', 'post', 'string', JREQUEST_ALLOWRAW );

		// Clean text for xhtml transitional compliance
		$text_horaires_prix		= str_replace( '<br>', '<br />', $text_horaires_prix );
		$text_message		= str_replace( '<br>', '<br />', $text_message );
		$text_link		= str_replace( '\\', '/', $text_link );

		// Search for the {readmore} tag and split the text up accordingly.
		$pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
		$tagPos	= preg_match($pattern, $text_message);

		if ( $tagPos == 0 )
		{
			$row->intro	= $text_message;
		} else
		{
			list($row->intro, $row->message) = preg_split($pattern, $text_message, 2);
		}

		$row->horaires_prix	= $text_horaires_prix;
		$row->link	= $text_link;

		//sauvegardel'id de l'utilisateur
		if (!$row->userid) {
			$user		= & JFactory::getUser();
			$row->userid = $user->get('id');
		}


		return true;
	}

	function saveGMapFPPerso( &$row )
	{
		// Get submitted text from the request variables
		$text_intro_carte		= JRequest::getVar( 'text_intro_carte'		, '', 'post', 'string', JREQUEST_ALLOWRAW );
		$text_conclusion_carte	= JRequest::getVar( 'text_conclusion_carte'	, '', 'post', 'string', JREQUEST_ALLOWRAW );
		$text_intro_detail		= JRequest::getVar( 'text_intro_detail'		, '', 'post', 'string', JREQUEST_ALLOWRAW );
		$text_conclusion_detail	= JRequest::getVar( 'text_conclusion_detail', '', 'post', 'string', JREQUEST_ALLOWRAW );

		// Clean text for xhtml transitional compliance
		$text_intro_carte		= str_replace( '<br>', '<br />', $text_intro_carte );
		$text_conclusion_carte	= str_replace( '<br>', '<br />', $text_conclusion_carte );
		$text_intro_detail		= str_replace( '<br>', '<br />', $text_intro_detail );
		$text_conclusion_detail	= str_replace( '<br>', '<br />', $text_conclusion_detail );

		// Search for the {readmore} tag and split the text up accordingly.

		$row->intro_carte		= $text_intro_carte;
		$row->conclusion_carte	= $text_conclusion_carte;
		$row->intro_detail		= $text_intro_detail;
		$row->conclusion_detail	= $text_conclusion_detail;

		return true;
	}

}
