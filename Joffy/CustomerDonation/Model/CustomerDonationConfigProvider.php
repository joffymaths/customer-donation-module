<?php
namespace Joffy\CustomerDonation\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Quote\Model\Quote;

class CustomerDonationConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Joffy\CustomerDonation\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    
    protected $taxHelper;

    /**
     * @param \Joffy\CustomerDonation\Helper\Data $dataHelper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Joffy\CustomerDonation\Helper\Data $dataHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->dataHelper = $dataHelper;
        $this->checkoutSession = $checkoutSession;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $donationConfig = [];
        
        $enabled = $this->dataHelper->isModuleEnabled();
        
        $donationConfig['donation_label'] = $this->dataHelper->getDonationLabel();
        
        $quote = $this->checkoutSession->getQuote();
        $subtotal = $quote->getSubtotal();

        $donation = 0;
        foreach($quote->getAllItems() as $_item){
            $donation = $donation + $_item->getDonationCustomerAmount();
        }
        
        $donationConfig['donation_amount'] = $donation;
        
        $donationConfig['show_hide_donation_block'] = ($enabled) ? true : false;
        return $donationConfig;
    }

    protected function _getAddressFromQuote(Quote $quote)
    {
        return $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
    }
}