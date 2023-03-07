<?php
    namespace Joffy\CustomerDonation\Observer;
 
    use Magento\Framework\Event\ObserverInterface;
    use Magento\Framework\App\RequestInterface;
 
    class SetCustomerDonationAmount implements ObserverInterface
    {
        protected $_logger;

        public function __construct(
            \Magento\Framework\App\RequestInterface $request,
            \Psr\Log\LoggerInterface $logger
        ){
            $this->_logger = $logger;
            $this->_request = $request;
        }

        public function execute(\Magento\Framework\Event\Observer $observer)
        {
            $data = $this->_request->getPost(); 

            if($data->donation_active_value){         
                $quoteItem  = $observer->getQuoteItem();
                $quoteItem->setDonationCustomerAmount($data->donation_customer_amount);
            }
        }
     
    }
