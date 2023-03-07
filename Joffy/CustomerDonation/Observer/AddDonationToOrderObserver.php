<?php
namespace Joffy\CustomerDonation\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class AddDonationToOrderObserver implements ObserverInterface
{
	/**
     * @var \Magento\Framework\DataObject\Copy
     */
    protected $objectCopyService;
	
	/**
     * AddDonationToOrderObserver constructor.
     */
     /**
     * @param \Magento\Framework\DataObject\Copy $objectCopyService
     */
    public function __construct(
        \Magento\Framework\DataObject\Copy $objectCopyService,
        \Psr\Log\LoggerInterface $logger
    ) {
		$this->objectCopyService = $objectCopyService;
        $this->_logger = $logger;

    }
	
    /**
     * Set Donation amount to order
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getData('order');
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getData('quote');
        $donation = 0;
        foreach($quote->getAllItems() as $_item){
            $donation = $donation + $_item->getDonationCustomerAmount();
        }
		
    
        $this->_logger->debug(" Donation ". $donation );
		
        if (!$donation) {
            return $this;
        }
        $order->setDonationCustomerAmount($donation);

        $this->_logger->debug(" Donation to order". $donation );

		$this->objectCopyService->copyFieldsetToTarget('sales_convert_quote', 'to_order', $quote, $order);
        return $this;
    }
}