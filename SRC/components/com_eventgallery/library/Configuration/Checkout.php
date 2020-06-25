<?php

namespace Joomla\Component\Eventgallery\Site\Library\Configuration;

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class Checkout extends Configuration
{
    public function doShowVat() {
        return $this->get('show_vat', 0) == 1;
    }

    public function getCheckoutDisclaimer() {
        return $this->get('checkout_disclaimer', '');
    }

    public function getCurrencySymbol() {
        return $this->get('currency_symbol', 'EUR');
    }

    public function getCurrencyCode() {
        return $this->get('currency_code', 'EUR');
    }

    public function doUseTermsAndConditionsCheckbox() {
        return $this->get('use_terms_conditions_checkbox', 1) == 1;
    }

    public function doUseAddressFieldState() {
        return $this->get('use_address_field_state', 1) == 1;
    }

    public function doUseAddressFieldCountry() {
        return $this->get('use_address_field_country', 1) == 1;
    }

    public function getCheckoutPreselectedCountry() {
        return $this->get('checkout_preselected_country', '');
    }

    public function doEnableBusinessCustomerData() {
        return $this->get('enable_business_customer_data', 0) == 1;
    }

    public function doUseLoginForm() {
        return $this->get('use_login_form', 1) == 1;
    }

    public function doUseAddressDataFromUser() {
        return $this->get('use_address_data_from_user', 1) == 1;
    }

    public function getProtocolForIncomingRequests() {
        return $this->get('protocol_for_incoming_request', 'keep');
    }

    public function getCheckoutMerchantAddress() {
        return $this->get('checkout_merchantaddress', '');
    }

    public function getFooterDisclaimer() {
        return $this->get('footer_disclaimer', '');
    }

    public function getPrivacyPolicyLink() {
        return $this->get('pp_article_link', '');
    }

    public function getTermsAndConditionsLink() {
        return $this->get('tac_article_link', '');
    }

    public function getImpressLink() {
        return $this->get('impress_article_link', '');
    }

}
