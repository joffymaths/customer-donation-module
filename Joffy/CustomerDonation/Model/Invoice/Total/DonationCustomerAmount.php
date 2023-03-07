<?php

namespace Joffy\CustomerDonation\Model\Invoice\Total;

use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

class DonationCustomerAmount extends AbstractTotal
{
    /**
     * @param \Magento\Sales\Model\Order\Invoice $invoice
     * @return $this
     */
    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
    {
        $invoice->setDonationCustomerAmount(0);

        $donation = $invoice->getOrder()->getDonationCustomerAmount();
        $invoice->setTotalDonationAmount($donation);

        $invoice->setGrandTotal($invoice->getGrandTotal() + $invoice->getDonationCustomerAmount());
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $invoice->getDonationCustomerAmount());

        return $this;
    }
}
