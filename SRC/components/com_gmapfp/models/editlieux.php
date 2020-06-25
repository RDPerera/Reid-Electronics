<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3_15F
	* Creation date: Janvier 2014
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

class GMapFPsModelEditLieux extends JModelLegacy
{
	var $_list;
	var $_id = 0;
	var $_data;

	function __construct()
	{
		parent::__construct();
		$doc = JFactory::getDocument();

		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$array = $input->get('cid', '', 'ARRAY');
		if (!empty($array)) $this->setId((int)$array[0]);
		
		$array = $input->get('id', '', 'ARRAY');
		if (!empty($array)) $this->setId((int)$array[0]);
		
		//Insertion des entêtes GMapFP si non déjà fait.
		if (!defined( '_JOS_GMAPFP_CSS' ))
		{
			/** verifi que la fonction n'est défini qu'une faois */
			define( '_JOS_GMAPFP_CSS', 1 );
	
			$doc->addCustomTag( '<link rel="stylesheet" href="'.JURI::base().'components/com_gmapfp/views/gmapfp/gmapfp.css" type="text/css" />'); 
			$doc->addCustomTag( '<link rel="stylesheet" href="'.JURI::base().'components/com_gmapfp/views/gmapfp/gmapfp2.css" type="text/css" />'); 
		}
	}

	function _buildQuery()
	{
		$query = ' SELECT * '
			. ' FROM #__gmapfp_marqueurs where published = 1 '
		;

		return $query;
	}

	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}


	function getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__gmapfp '.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->nom = null;
			$this->_data->alias = null;
			$this->_data->adresse = null;
			$this->_data->adresse2 = null;
			$this->_data->codepostal = null;
			$this->_data->ville = null;
			$this->_data->departement = null;
			$this->_data->pay = null;
			$this->_data->tel = null;
			$this->_data->tel2 = null;
			$this->_data->fax = null;
			$this->_data->email = null;
			$this->_data->web = null;
			$this->_data->img = null;
			$this->_data->album = null;
			$this->_data->intro = null;
			$this->_data->message = null;
			$this->_data->horaires_prix = null;
			$this->_data->affichage = null;
			$this->_data->marqueur = null;
			$this->_data->link = null;
			$this->_data->icon = null;
			$this->_data->icon_label = null;
			$this->_data->glat = null;
			$this->_data->glng = null;
			$this->_data->gzoom = null;
			$this->_data->catid = null;
			$this->_data->userid = null;
			$this->_data->published = null;
			$this->_data->checked_out = null;
			$this->_data->metadesc = null;
			$this->_data->metakey = null;
			$this->_data->ordering = 0;			
		}
		
		if (JString::strlen($this->_data->message) > 1) {
			$this->_data->text = $this->_data->intro . "<hr id=\"system-readmore\" />" . $this->_data->message;
		} else {
			$this->_data->text = $this->_data->intro;
		}
		return $this->_data;
	}

	function getMarqueurs()
	{
		if (empty( $this->_list ))
		{
			$query = $this->_buildQuery();
			$this->_list = $this->_getList( $query );
		}

		return $this->_list;
	}

	function getUser($id)
	{
		$query = ' (SELECT userid '
			. ' FROM #__gmapfp '
			. ' WHERE id='.$id.')'
		;
		$query = ' SELECT name, email '
			. ' FROM #__users '
			. ' WHERE id='.$query
		;
        $this->_db->setQuery( $query );
        $user = $this->_db->loadObject();

		return $user;
	}

	function store($data)
	{
		$mainframe 	= JFactory::getApplication();
		$input		= $mainframe->input;
		$params 	= $mainframe->getParams('com_gmapfp');
		$row 		= $this->getTable('GMapFP', 'GMapFPTable');
    	$_layout 	= $input->get('layout', "", '', 'string');

		//$data = JRequest::get( 'post' );

		//traitement spécifique au formulaire de soumission
		if ($_layout=='soumission'){
			//forçage publication automatique
			if ($params->get('gmapfp_submit_validation_admin', 0)){
				$row->published = true;
			}
		}

		// Prepare the content for saving to the database
		$this->saveGMapfpPrep( $row );


		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		//Pour ajout, test si enregistrement existe déjà. => anti-rebond
		if ($row->id == 0){
			$query = ' SELECT id '
				. ' FROM #__gmapfp '
				. ' WHERE userid='.$row->userid
				. ' AND alias="'.$row->alias.'"';
			;
			$this->_db->setQuery( $query );
			$result = $this->_db->loadObject();
			if ($result) return true;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// attribut le n° 1 à ordering et décale les autres n°
		if (!$row->reorder()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		//envoie d'un message d'avertissement au contact désigné
		if (($params->get('gmapfp_submit_envoie_mail_admin', 0)) AND ($_layout=='soumission')){
			$sendmail = $this->sendmail($row->id);
		}

		return $row->id;
	}

	private function saveGMapFPPrep( &$row )
	{
		$mainframe 	= JFactory::getApplication();
		$input		= $mainframe->input;
		// Get submitted text from the request variables
		$text_horaires_prix = $input->get( 'text_horaires_prix', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$text_message		= $input->get( 'text_message', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$text_link			= $input->get( 'text_link', '', 'post', 'string', JREQUEST_ALLOWRAW );

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
			$user		=  JFactory::getUser();
			$row->userid = $user->get('id');
		}


		return true;
	}
	
	private function sendmail($id)
	{
		$mainframe = JFactory::getApplication(); 

		$user 		= JFactory::getUser();
		$user_property = $user->getProperties();
		if ($user_property['id']==0) {
			$user_property['username']='guest';
			$user_property['email']='???@???.??';
		}

		$config = $mainframe->getParams();
	    //$disable_https = $this->params->get('disable_https', false);
		$disable_https = true;

		if (!$disable_https) {
			$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		} else {
			$url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		}
		$mySubject = JText::_('GMAPFP_NEW_SUBMIT');

		$myMessage = JText::sprintf('GMAPFP_NEW_SUBMIT2', '', '')." \n";
		$myMessage.= 'http://'.$_SERVER['SERVER_NAME']."/administrator/index.php?option=com_gmapfp&controller=gmapfp&task=edit&cid=".$id."\n\n";
		$myMessage.= JText::_('GMAPFP_BY_USER')." ".$user_property['username'];;
		$myMessage.= "\n\n".JText::_('GMAPFP_MERCI_PUBLIER');
		//$myRecipient = $config->get('moderateur');

		$myRecipient = JFactory::getUser($config->get('moderateur', ''));
		if ($myRecipient) {
			$email_emetteur = $user_property['email'];
			$nom_emetteur	= $_SERVER['SERVER_NAME'];

			$myRecipient_property = $myRecipient->getProperties();
			$myRecipient = $myRecipient_property['email'];
			$mailSender = JFactory::getMailer();
			$mailSender->addRecipient($myRecipient);
			$mailSender->setSender($email_emetteur, $nom_emetteur);
			$mailSender->addReplyTo($user_property['email'], '' );
			$mailSender->setSubject($mySubject);
			$mailSender->setBody($myMessage);
			if (!$mailSender->Send()) {
				return false;
			} else {
				return true;
			}
		}
		return false;
	}

}
?>
