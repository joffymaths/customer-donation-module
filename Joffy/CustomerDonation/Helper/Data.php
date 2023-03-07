<?php

namespace Joffy\CustomerDonation\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * Donation module  config path
     */
    const CONFIG_DONATION_IS_ENABLED = 'donation_settings/general/enabled';
    const CONFIG_DONATION_LABEL = 'donation_settings/general/name';

    /**
     * @return mixed
     */
    public function isModuleEnabled()
    {

        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $isEnabled = $this->scopeConfig->getValue(self::CONFIG_DONATION_IS_ENABLED, $storeScope);
        return $isEnabled;
    }

    /**
     * Donation module lable
     *
     * @return mixed
     */
    public function getDonationLabel()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $donationLabel = $this->scopeConfig->getValue(self::CONFIG_DONATION_LABEL, $storeScope);
        return $donationLabel;
    }

}
