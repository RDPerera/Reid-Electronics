<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controllerform' );

class EventgalleryControllerOrder extends JControllerForm
{
    protected $view_list = 'orders';

    /**
     * Method to save a record.
     *
     * @param   string  $key     The name of the primary key of the URL variable.
     * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
     *
     * @return  boolean  True if successful, false otherwise.
     *
     * @since   12.2
     */
    public function save($key = null, $urlVar = null)
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app   = JFactory::getApplication();
        $lang  = JFactory::getLanguage();
        /**
         * @var EventgalleryModelOrder $model
         * @var JTable $table
         */
        $model = $this->getModel();
        $table = $model->getTable();
        $data  = $app->input->post->get('jform', array(), 'array');
        $checkin = property_exists($table, 'checked_out');
        $context = "$this->option.edit.$this->context";
        $task = $this->getTask();

        // Determine the name of the primary key for the data.
        if (empty($key))
        {
            $key = $table->getKeyName();
        }

        // To avoid data collisions the urlVar may be different from the primary key.
        if (empty($urlVar))
        {
            $urlVar = $key;
        }

        $recordId = $app->input->getString($urlVar);

        // Populate the row id from the session.
        $data[$key] = $recordId;

        // The save2copy task needs to be handled slightly differently.
        if ($task == 'save2copy')
        {
            // Check-in the original row.
            if ($checkin && $model->checkin($data[$key]) === false)
            {
                // Check-in failed. Go back to the item and display a notice.
                $errorMsg = JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError());
                $this->setMessage($errorMsg, 'error');

                $this->setRedirect(
                    JRoute::_(
                        'index.php?option=' . $this->option . '&view=' . $this->view_item
                        . $this->getRedirectToItemAppend($recordId, $urlVar), false
                    )
                );

                return false;
            }

            // Reset the ID and then treat the request as for Apply.
            $data[$key] = 0;
            $task = 'apply';
        }

        // Access check.
        if (!$this->allowSave($data, $key))
        {
            $this->setMessage(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'), 'error');

            $this->setRedirect(
                JRoute::_(
                    'index.php?option=' . $this->option . '&view=' . $this->view_list
                    . $this->getRedirectToListAppend(), false
                )
            );

            return false;
        }

        // Validate the posted data.
        // Sometimes the form needs some posted data, such as for plugins and modules.
        $form = $model->getForm($data, false);

        if (!$form)
        {
            $app->enqueueMessage($model->getError(), 'error');

            return false;
        }

        // Test whether the data is valid.
        $validData = $model->validate($form, $data);

        // Check for validation errors.
        if ($validData === false)
        {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
            {
                if ($errors[$i] instanceof Exception)
                {
                    /**
                     * @var Exception $exception
                     */
                    $exception = $errors[$i];
                    $app->enqueueMessage($exception->getMessage(), 'warning');
                }
                else
                {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState($context . '.data', $data);

            // Redirect back to the edit screen.
            $this->setRedirect(
                JRoute::_(
                    'index.php?option=' . $this->option . '&view=' . $this->view_item
                    . $this->getRedirectToItemAppend($recordId, $urlVar), false
                )
            );

            return false;
        }

        if (!isset($validData['metadata']['tags']))
        {
            $validData['metadata']['tags'] = null;
        }

        // Attempt to save the data.
        if (!$model->save($validData))
        {
            // Save the data in the session.
            $app->setUserState($context . '.data', $validData);

            // Redirect back to the edit screen.
            $errorMsg = JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError());
            $this->setMessage($errorMsg, 'error');

            $this->setRedirect(
                JRoute::_(
                    'index.php?option=' . $this->option . '&view=' . $this->view_item
                    . $this->getRedirectToItemAppend($recordId, $urlVar), false
                )
            );

            return false;
        }

        // Save succeeded, so check-in the record.
        if ($checkin && $model->checkin($validData[$key]) === false)
        {
            // Save the data in the session.
            $app->setUserState($context . '.data', $validData);

            // Check-in failed, so go back to the record and display a notice.
            $errorMsg = JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError());
            $this->setMessage($errorMsg, 'error');

            $this->setRedirect(
                JRoute::_(
                    'index.php?option=' . $this->option . '&view=' . $this->view_item
                    . $this->getRedirectToItemAppend($recordId, $urlVar), false
                )
            );

            return false;
        }

        $this->setMessage(
            JText::_(
                ($lang->hasKey($this->text_prefix . ($recordId == 0 && $app->isClient('site') ? '_SUBMIT' : '') . '_SAVE_SUCCESS')
                    ? $this->text_prefix
                    : 'JLIB_APPLICATION') . ($recordId == 0 && $app->isClient('site') ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
            )
        );

        // Redirect the user and adjust session state based on the chosen task.
        switch ($task)
        {
            case 'apply':
                // Set the record data in the session.

                $recordId = $model->getState($this->context . '.id');
                /** @noinspection PhpParamsInspection
                 * we need to have a string here. This incompatibility is by intention!
                 */
                $this->holdEditId($context, $recordId);
                $app->setUserState($context . '.data', null);
                /** @noinspection PhpParamsInspection */
                $model->checkout($recordId);

                // Redirect back to the edit screen.
                /** @noinspection PhpParamsInspection */
                $this->setRedirect(
                    JRoute::_(
                        'index.php?option=' . $this->option . '&view=' . $this->view_item
                        . $this->getRedirectToItemAppend($recordId, $urlVar), false
                    )
                );
                break;

            case 'save2new':
                // Clear the record id and data from the session.
                $this->releaseEditId($context, $recordId);
                $app->setUserState($context . '.data', null);

                // Redirect back to the edit screen.
                $this->setRedirect(
                    JRoute::_(
                        'index.php?option=' . $this->option . '&view=' . $this->view_item
                        . $this->getRedirectToItemAppend(null, $urlVar), false
                    )
                );
                break;

            default:
                // Clear the record id and data from the session.
                $this->releaseEditId($context, $recordId);
                $app->setUserState($context . '.data', null);

                // Redirect to the list screen.
                $this->setRedirect(
                    JRoute::_(
                        'index.php?option=' . $this->option . '&view=' . $this->view_list
                        . $this->getRedirectToListAppend(), false
                    )
                );
                break;
        }

        // Invoke the postSave method to allow for the child class to access the model.
        $this->postSaveHook($model, $validData);

        return true;
    }

    public function edit($key = null, $urlVar = null)
    {
        $app   = JFactory::getApplication();
        /**
         * @var EventgalleryModelOrder $model
         * @var JTable $table
         */
        $model = $this->getModel();
        $table = $model->getTable();
        $cid   = $app->input->post->get('cid', array(), 'array');
        $context = "$this->option.edit.$this->context";

        // Determine the name of the primary key for the data.
        if (empty($key))
        {
            $key = $table->getKeyName();
        }

        // To avoid data collisions the urlVar may be different from the primary key.
        if (empty($urlVar))
        {
            $urlVar = $key;
        }

        // Get the previous record id (if any) and the current record id.
        $recordId =  (count($cid) ? $cid[0] : $app->input->getString($urlVar));
        $checkin = property_exists($table, 'checked_out');

        // Access check.
        if (!$this->allowEdit(array($key => $recordId), $key))
        {
            $this->setMessage(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'), 'error');

            $this->setRedirect(
                JRoute::_(
                    'index.php?option=' . $this->option . '&view=' . $this->view_list
                    . $this->getRedirectToListAppend(), false
                )
            );

            return false;
        }

        // Attempt to check-out the new record for editing and redirect.
        if ($checkin && !$model->checkout($recordId))
        {
            // Check-out failed, display a notice but allow the user to see the record.
            $errorMsg = JText::sprintf('JLIB_APPLICATION_ERROR_CHECKOUT_FAILED', $model->getError());
            $this->setMessage($errorMsg, 'error');

            $this->setRedirect(
                JRoute::_(
                    'index.php?option=' . $this->option . '&view=' . $this->view_item
                    . $this->getRedirectToItemAppend($recordId, $urlVar), false
                )
            );

            return false;
        }
        else
        {
            // Check-out succeeded, push the new record id into the session.
            $this->holdEditId($context, $recordId);
            $app->setUserState($context . '.data', null);

            $this->setRedirect(
                JRoute::_(
                    'index.php?option=' . $this->option . '&view=' . $this->view_item
                    . $this->getRedirectToItemAppend($recordId, $urlVar), false
                )
            );

            return true;
        }
    }

    public function resendmail(/** @noinspection PhpUnusedParameterInspection */$key = null, $urlVar = null) {

        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $app = JFactory::getApplication();

        // Load the front end language
        $language = JFactory::getLanguage();
        $language->load('com_eventgallery' , JPATH_SITE.DIRECTORY_SEPARATOR.'components/com_eventgallery', $language->getTag(), true);
        $language->load('com_eventgallery' , JPATH_SITE.DIRECTORY_SEPARATOR.'language'.DIRECTORY_SEPARATOR.'overrides', $language->getTag(), true, false);


        $orderid   = $app->input->post->get('id');

        /**
         * @var EventgalleryLibraryManagerOrder $orderMgr
         */
        $orderMgr = EventgalleryLibraryManagerOrder::getInstance();
        $order = $orderMgr->getOrderById($orderid);

        $config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();

        /**
         * @var EventgalleryLibraryManagerEmailtemplate $emailtemplateMgr
         */
        $emailtemplateMgr = EventgalleryLibraryManagerEmailtemplate::getInstance();

        $data = Array();

        $disclaimerObject = new EventgalleryLibraryDatabaseLocalizablestring($config->getCheckout()->getCheckoutDisclaimer());
        $disclaimer = strlen($disclaimerObject->get())>0?$disclaimerObject->get():JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_CONFIRMATION_DISCLAIMER');

        $data['disclaimer'] = $disclaimer;
        $data['order'] = $emailtemplateMgr->createOrderData($order, $config->getCheckout()->doShowVat());

        $data = json_decode(json_encode($data), FALSE);


        $to = Array($order->getEMail(), $order->getBillingAddress()==null? "": $order->getBillingAddress()->getFirstName().' '.$order->getBillingAddress()->getLastName());
        $send =  $emailtemplateMgr->sendMail('new_order', $order->getLanguage(), true, $data, $to, true);


        if ($send !== true) {
            $this->setMessage(JText::_('COM_EVENTGALLERY_ORDER_RESEND_MAIL_FAILED'));
        } else {
            $this->setMessage(JText::_('COM_EVENTGALLERY_ORDER_RESEND_MAIL_SUCCESS'));
        }

        $this->setRedirect(
            JRoute::_(
                'index.php?option=' . $this->option . '&view=' . $this->view_item
                . $this->getRedirectToItemAppend($orderid, 'id'), false
            )
        );

        return true;
    }

    /**
     * Function that allows child controller access to model data after the data has been saved.
     *
     * @param EventgalleryModelEvent|EventgalleryModelOrder $model The data model object.
     * @param   array $validData The validated data.
     *
     * @return    void
     * @since    1.6
     */
    protected function postSaveHook(\JModelLegacy $model, $validData = array())
    {
        $app = JFactory::getApplication();

        if ($app->input->getCmd('tmpl') != component) {
            return;
        }

        if ($this->task == 'apply')
        {
            $this->setRedirect(JRoute::_('index.php?option=com_eventgallery&view=order&layout=edit&tmpl=component&format=raw&id='.$this->input->getString('id') . $this->getRedirectToListAppend(), false));
        }

        if ($this->task == 'save')
        {
            $this->setRedirect(JRoute::_('index.php?option=com_eventgallery&view=order&layout=content&tmpl=component&format=raw&id='.$this->input->getString('id') . $this->getRedirectToListAppend(), false));
        }
    }

    public function download($key = null, $urlVar = null) {

        $str_orderid = $this->input->getString('id', null);


        /**
         * @var EventgalleryLibraryFactoryOrder $orderFactory
         */
        $orderFactory = EventgalleryLibraryFactoryOrder::getInstance();
        $order = $orderFactory->getOrderById($str_orderid);
        if ($order == null) {
            throw new Exception("Invalid Request.");
        }


        $zip = new ZipArchive();
        $config   = JFactory::getConfig();

        $tmpZipFilename = tempnam($config->get('tmp_path'), 'eg');


        if ($zip->open($tmpZipFilename, ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$tmpZipFilename>\n");
        }

        foreach($order->getLineItems() as $lineitem) {


            /**
             * @var EventgalleryLibraryImagelineitem $lineitem
             */

                $file = $lineitem->getFile();

                $context = stream_context_create(
                    array(
                        'http' => array(
                            'follow_location' => true
                        )
                    )
                );

                $download_file = $file->getOriginalFile();

                if ($download_file !== false) {

                    $filenameSegmenets = [
                        $order->getDocumentNumber(),
                        $lineitem->getQuantity(),
                        'x',
                        $lineitem->getImageType()->getName(),
                        $file->getFolderName(),
                        $file->getFileName()
                    ];

                    $fileuri = implode('_', $filenameSegmenets);

                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mimeType = $finfo->buffer($download_file);
                    if ($mimeType == 'image/jpeg') {
                        $fileuri .= '.jpg';
                    }

                    $zip->addFromString($fileuri, $download_file);
                }
                unset($download_file);



        }

        $zip->close();

        $size = filesize($tmpZipFilename);
        $fp   = fopen($tmpZipFilename, "rb");
        if (!($size && $fp)) {
            echo "Can't read zip file";
            return $this->endExecution();
        }

        ob_clean();
        ob_end_flush();

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . $order->getDocumentNumber(). '.zip');
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".$size);

        fpassthru($fp);
        fclose($fp);
        unlink($tmpZipFilename);
        return $this->endExecution();


    }

    private function endExecution() {
        die();
    }
}
