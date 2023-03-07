<?php

namespace Joffy\CustomerDonation\Model\Quote\Total;

use Magento\Store\Model\ScopeInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Address\Total;

class DonationCustomerAmount extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
	protected $quoteValidator = null;
	protected $_priceCurrency;
    protected $helperData;
    /**
     * Collect grand total address amount
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @return $this
     */
    public function __construct(
    	\Magento\Quote\Model\QuoteValidator $quoteValidator,
    	\Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Joffy\CustomerDonation\Helper\Data $helperData
	) {
        $this->quoteValidator = $quoteValidator;
		$this->_priceCurrency = $priceCurrency;
        $this->helperData     = $helperData;
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
         parent::collect($quote, $shippingAssignment, $total);
        $enabled = $this->helperData->isModuleEnabled();
        $subtotal = $total->getTotalAmount('subtotal');
        if ($enabled ) {
            $donation = 0;
            foreach($quote->getAllItems() as $_item){
                $donation = $donation + $_item->getDonationCustomerAmount();
            }
            $total->setTotalAmount('donation', $donation);
            $total->setBaseTotalAmount('donation', $donation);
            $total->setDonationCustomerAmount($donation);
			$quote->setDonationCustomerAmount($donation);			
        }
        return $this;
    }

	public function getMagentoVersion()
	{
	    return $this->productMetadata->getVersion();
	}
	
    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array
     */
    public function fetch(
    	\Magento\Quote\Model\Quote $quote,
    	\Magento\Quote\Model\Quote\Address\Total $total
	) {

        $enabled = $this->helperData->isModuleEnabled();
        $subtotal = $quote->getSubtotal();
        
        $donation = 0;
        foreach($quote->getAllItems() as $_item){
            $donation = $donation + $_item->getDonationCustomerAmount();
        }
       
		$address = $this->_getAddressFromQuote($quote);

        $result = [];
        if ($enabled && $donation) {
            $result = [
                'code' => 'donation',
                'title' => $this->helperData->getDonationLabel(),
                'value' => $donation
            ];
        }
        return $result;
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Donation Amount');
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     */
    protected function clearValues(\Magento\Quote\Model\Quote\Address\Total $total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);

    }
	protected function _getAddressFromQuote(Quote $quote)
    {
        return $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
    }

}