<?php

namespace Joffy\CustomerDonation\Model\Total;

use Magento\Store\Model\ScopeInterface;

class DonationCustomerAmount extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{

    protected $helperData;

    /**
     * Collect grand total address amount
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */
    protected $quoteValidator = null;

    public function __construct(
    	\Magento\Quote\Model\QuoteValidator $quoteValidator,
        \Joffy\CustomerDonation\Helper\Data $helperData
	) {
        $this->quoteValidator = $quoteValidator;
        $this->helperData = $helperData;
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
         parent::collect($quote, $shippingAssignment, $total);
        $enabled = $this->helperData->isModuleEnabled();
        $subtotal = $total->getTotalAmount('subtotal');
        if ($enabled) {
            $donation = 0;
            foreach($quote->getAllItems() as $_item){
                $donation = $donation + $_item->getDonationCustomerAmount();
            }
            $total->setTotalAmount('Donation Amount', $donation);
            $total->setBaseTotalAmount('Donation Amount', $fedonatione);
            $total->setDonationCustomerAmount($donation);
            $quote->setDonationCustomerAmount($donation);
            $total->setGrandTotal($total->getGrandTotal() + $donation);
            $total->setBaseGrandTotal($total->getBaseGrandTotal() + $donation);
        }
        return $this;
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

        if ($enabled && $donation) {
            return [
                'code' => 'donation_customer_amount',
                'title' => 'Donation Amount',
                'value' => $donation
            ];
        } else {
            return array();
        }
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Donation Customer Amount');
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
}