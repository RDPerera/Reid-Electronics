<?php // no direct access

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * @var \de\svenbluege\joomla\eventgallery\ObjectWithConfiguration $this
 */
$showLoginForm = $this->config->getCheckout()->doUseLoginForm();

?>

    <?php IF ($showLoginForm && JFactory::getUser()->guest): ?>

        <form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="form-inline well">
            <h3>Login</h3>
            <p><?php echo JText::_('COM_EVENTGALLERY_CHECKOUT_FORM_REGISTERED_DESC');?></p>
            <?php foreach ($this->loginform->getFieldset('credentials') as $field) : ?>
                <?php if (!$field->hidden) : ?>
                    <?php echo $field->input; ?>
                <?php endif; ?>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-default"><?php echo JText::_('JLOGIN'); ?></button>

            <input type="hidden" name="return" value="<?php echo base64_encode($this->config->getLegacy('login_redirect_url', JRoute::_(JUri::base().'index.php?option=com_eventgallery&view=checkout&layout=change'))); ?>" />
            <?php echo JHtml::_('form.token'); ?>
            <ul class="nav nav-pills">
                <li><a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a></li>
                <li><a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>"><?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?></a></li>
                <?php
                $usersConfig = JComponentHelper::getParams('com_users');
                if ($usersConfig->get('allowUserRegistration')) : ?>
                    <li><a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>"><?php echo JText::_('COM_USERS_LOGIN_REGISTER'); ?></a></li>
                <?php endif; ?>
            </ul>
        </form>

    <?php ENDIF ?>
