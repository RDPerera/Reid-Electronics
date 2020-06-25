<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class EventgalleryControllerSingleimage extends EventgalleryController
{

    public function display($cachable = false, $urlparams = array())
    {
        $folder = $this->input->getString('folder', '');

        /**
         * @var EventgalleryLibraryFactoryFolder $folderFactory
         */
        $folderFactory = EventgalleryLibraryFactoryFolder::getInstance();
        $folder = $folderFactory->getFolder($folder);


        if ($folder == null) {
            throw new Exception(JText::_('COM_EVENTGALLERY_EVENT_NO_PUBLISHED_MESSAGE'), 404);
        }
        // we need to do this only if someone entered a password.
        // the views will protect themselfs
        $accessAllowed = $folder->isVisible();

        if (!$accessAllowed) {
            $cachable = false;
        }



        $viewLayout = $this->input->get('layout', 'default', 'string');

        if ('imagesetselection' == $viewLayout) {
            $cachable = false;
        }

        parent::display($cachable, $urlparams);
    }

    function saveReport($cachable = false, $urlparams = array()) {

        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();



        /**
         * @var JModelForm $model
         */
        $model = $this->getModel('Singleimage', 'EventgalleryModel');
        $form = $model->getMessageForm();
        $data = $this->input->post->getArray();
        $validationSuccessful = $model->validate($form, $data);

        if ($validationSuccessful) {

            $row = JTable::getInstance('message', 'EventgalleryTable', array());
            $row->bind($app->input->post->getArray());
            $result = $row->store();

            /**
             * @var Joomla\Registry\Registry $config
             * @var Joomla\Registry\Registry $params
             */
            $config = JFactory::getConfig();
            $mailer = JFactory::getMailer();
            $egConfig = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();

            $subject = $config->get('sitename') . ' // ' . JText::_('COM_EVENTGALLERY_MESSAGES_REPORT_MAIL_SUBJECT');
            $body = [];
            $body[] = "<b>" . JText::_('COM_EVENTGALLERY_MESSAGES_EMAIL_LABEL') . ": </b> {$row->email}<br>";
            $body[] = "<b>" . JText::_('COM_EVENTGALLERY_MESSAGES_MESSAGE_LABEL') . ": </b> {$row->message}<br>";
            $body[] = "<b>Folder: </b> {$row->folder}<br>";
            $body[] = "<b>File: </b> {$row->file}<br>";
            $body = implode("", $body);

            $mailer->setSubject(
                $subject
            );

            $mailer->isHtml(true);
            $mailer->Encoding = 'base64';
            $mailer->setBody($body);

            // Customer Mail
            $sender = array(
                $config->get('mailfrom'),
                $config->get('fromname'));

            $mailer->setSender($sender);

            $userids = JAccess::getUsersByGroup($egConfig->getGeneral()->getAdminUserGroupId());

            foreach ($userids as $userid) {
                $user = JUser::getInstance($userid);
                if ($user->sendEmail == 1) {
                    $mailadresse = JMailHelper::cleanAddress($user->email);
                    $mailer->addRecipient($mailadresse);
                }
            }

            $send = $mailer->Send();

            if ($result && $send) {
                $app->enqueueMessage(JText::_('COM_EVENTGALLERY_MESSAGES_REPORT_SAVE_SUCCESS'), 'info');
            } else {
                $app->enqueueMessage(JText::_('COM_EVENTGALLERY_MESSAGES_REPORT_SAVE_FAILED'), 'error');
            }

            $folder = $app->input->getString('folder');
            $file = $app->input->getString('file');
            $app->redirect(JRoute::_("index.php?option=com_eventgallery&view=singleimage&layout=report&folder=" . $folder . "&file=" . $file));
        }

        return $this->display($cachable, $urlparams);
    }

}
