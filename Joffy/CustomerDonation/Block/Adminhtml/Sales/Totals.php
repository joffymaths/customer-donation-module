<?php

namespace Joffy\CustomerDonation\Block\Adminhtml\Sales;

class Totals extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Joffy\CustomerDonation\Helper\Data
     */
    protected $_dataHelper;
   

    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $_currency;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Joffy\CustomerDonation\Helper\Data $dataHelper,
        \Magento\Directory\Model\Currency $currency,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_dataHelper = $dataHelper;
        $this->_currency = $currency;
    }

    /**
     * Retrieve current order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * @return string
     */
    public function getCurrencySymbol()
    {
        return $this->_currency->getCurrencySymbol();
    }

    /**
     *
     *
     * @return $this
     */
    public function initTotals()
    {
        $this->getParentBlock();
        $this->getOrder();
        $this->getSource();

        if(!$this->getSource()->getDonationCustomerAmount()) {
            return $this;
        }
        $total = new \Magento\Framework\DataObject(
            [
                'code' => 'donation',
                'value' => $this->getSource()->getDonationCustomerAmount(),
                'label' => $this->_dataHelper->getDonationLabel(),
            ]
        );
        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }
}
