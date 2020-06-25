<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_41F
	* Creation date: Mai 2016
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();

class GMapFPsControllerGmapfpContact extends GMapFPsController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

	}

	function submit()
	{
		$app = JFactory::getApplication(); 
		$input = $app->input;
		
		$cids = $input->get( 'cid', array(0), 'post', 'array' );

		$Itemid    = JRequest::getInt('Itemid'); 
		$sent = false;
        $params = clone($app->getParams('com_gmapfp'));

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Initialize some variables
		$db			=  JFactory::getDBO();
		$SiteName	= $app->getCfg('sitename');

		$default	= JText::_('GMAPFP_EMAIL_RECU_DE').' '.$SiteName;
		$contactId	= $input->get( 'id',			0,			'post',	'int' );
		$name		= $input->get( 'nom',			'',			'post' );
		$email		= $input->get( 'email',		'',			'post' );
		$subject	= $input->get( 'subject',		$default,	'post' );
		$body		= $input->get( 'message',		'',			'post' );
		$emailCopy	= $input->get( 'email_copy', 	0,			'post',	'int' );
		$redirect	= $input->get( 'redirect', 	'',			'post' );
		 // load the contact details
		$model		= $this->getModel('gmapfp');
		$qOptions[]	= $contactId;
		$contact_array	= $model->getGMapFPList( $qOptions );
		$contact	= $contact_array[0];

		// Input validation
		if  (!$this->_validateInputs( $contact, $email, $subject, $body ) ) {
			JError::raiseWarning( 0, $this->getError() );
			return false;
		}

		//gestion du captcha
		$user   =  JFactory::getUser();
		if ($params->get('gmapfp_afficher_captcha')&&!$user->get('gid')) { 
			if(count($_POST)>0){
				if(isset($_SESSION['gmapfp-captcha-code'])) {
					if ($_SESSION['gmapfp-captcha-code'] == @$_POST['keystring']){
						//$captacha=true;
					}else{
						JError::raiseWarning(0, JText::_('GMAPFP_CAPTCHA'));
						$link = JRoute::_('index.php?option=com_gmapfp&view=gmapfpcontact&id='.$contact->slug.'&Itemid='.$Itemid, false);
						$this->setRedirect($link, "");
						return false;
						//$captacha=false;
					}
				}
			}
			unset($_SESSION['gmapfp-captcha-code']);
		};

		$MailFrom 	= $app->getCfg('mailfrom');
		$FromName 	= $app->getCfg('fromname');

		// Prepare email body
		$bodymail 	= JText::_('GMAPFP_EMAIL_RECU_DE').' '.$name."\n";
		$bodymail 	.= JText::_('GMAPFP_EMAIL_RECU_PAR').' '.JURI::base()."\n";
		$bodymail 	.= "\r\n\r\n".stripslashes($body);
			
		$mail = JFactory::getMailer();
		$mail->addRecipient( $contact->email );
		$mail->setSender( array( $email, $name ) );
		$mail->setSubject( $FromName.': '.$subject );
		$mail->setBody( $bodymail );

		$sent = $mail->Send();

		// check whether email copy function activated
		if ( $emailCopy and $sent)
		{
			$copyText 		= JText::_('GMAPFP_COPIE_DE').' '.$SiteName.' '.$contact->nom;
			$copyText 		.= "\r\n\r\n".$body;
			$copySubject 	= JText::_('GMAPFP_COPIE_DE')." ".$subject;

			$mail = JFactory::getMailer();

			$mail->addRecipient( $email );
			$mail->setSender( array( $MailFrom, $FromName ) );
			$mail->setSubject( $copySubject );
			$mail->setBody( $copyText );
			$sent = $mail->Send();
		}
		if ($sent) {
			$msg = JText::_( 'GMAPFP_EMAIL_MERCI');
		} else {
			$msg = JText::_( 'GMAPFP_SUBMIT_ERROR' );
		}

		$link = JRoute::_($redirect, false);
		$this->setRedirect($redirect, $msg);
	}

	/**
	 * Validation des entrées
	 */
	function _validateInputs( $contact, $email, $subject, $body )
	{
		$session = JFactory::getSession();

		// Get params and component configurations
//		$params		= new JParameter($contact->params);
//		$pparams	= &$app->getParams('com_contact');

		// check for session cookie
//		$sessionCheck 	= $pparams->get( 'validate_session', 1 );
		$sessionCheck 	= 1 ;
		$sessionName	= $session->getName();
		if  ( $sessionCheck ) {
			if ( !isset($_COOKIE[$sessionName]) ) {
				$this->setError( JText::_('ALERTNOTAUTH') );
				return false;
			}
		}

		// test to ensure that only one email address is entered
		$check = explode( '@', $email );
		if ( strpos( $email, ';' ) || strpos( $email, ',' ) || strpos( $email, ' ' ) || count( $check ) > 2 ) {
			$this->setError( JText::_( 'GMAPFP_EMAIL_VALID', true ) );
			return false;
		}

		return true;
	}

}
?>
