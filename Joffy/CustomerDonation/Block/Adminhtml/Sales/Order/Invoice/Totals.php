<?php

namespace Joffy\CustomerDonation\Block\Adminhtml\Sales\Order\Invoice;

class Totals extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Joffy\CustomerDonation\Helper\Data
     */
    protected $_dataHelper;

    /**
     * Order invoice
     *
     * @var \Magento\Sales\Model\Order\Invoice|null
     */
    protected $_invoice = null;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Joffy\CustomerDonation\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->_dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getInvoice()
    {
        return $this->getParentBlock()->getInvoice();
    }
    /**
     * Initialize payment donation totals
     *
     * @return $this
     */
    public function initTotals()
    {
        $this->getParentBlock();
        $this->getInvoice();
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
