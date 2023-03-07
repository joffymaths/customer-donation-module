<?php

namespace Joffy\CustomerDonation\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;
use \Magento\Checkout\Model\Session;
use Joffy\CustomerDonation\Helper\Data;

class AddDonationToTotal implements ObserverInterface
{
    protected $checkout;
    protected $helper;

    public function __construct(
    	Session $checkout,
		Data $helper,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->checkout = $checkout;
        $this->helper = $helper;
        $this->_logger = $logger;
    }

    public function execute(Observer $observer)
    {
        if(!$this->helper->isModuleEnabled()){
            return $this;
        }
        $cart = $observer->getEvent()->getCart();
        $quote = $this->checkout->getQuote();
        
        $this->_logger->debug(" quote id ". $quote->getId() );
        $donation = 0;
        foreach($quote->getAllItems() as $_item){
            $donation = $donation + $_item->getDonationCustomerAmount();
        }
        
        $customAmount = $donation;
        $this->_logger->debug(" customAmount ". $customAmount );
        $label = $this->helper->getDonationLabel();
        if($customAmount) {
            $cart->addCustomItem($label, 1, $customAmount, $label);
        }
    }
}