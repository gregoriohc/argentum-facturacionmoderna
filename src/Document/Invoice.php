<?php namespace Argentum\FacturacionModerna\Document;

use Argentum\Common\Document\Invoice as CommonInvoice;

class Invoice extends CommonInvoice
{
    /**
     * Validate this invoice.
     *
     * @return void
     */
    public function validate()
    {
        $this->getTo()->getAddress()->setParametersRequired(['country']);

        parent::validate();
    }
}
