<?php namespace Argentum\FacturacionModerna\Document;

use Argentum\Common\Document\CreditNote as CommonCreditNote;

class CreditNote extends CommonCreditNote
{
    /**
     * Validate this credit note.
     *
     * @return void
     */
    public function validate()
    {
        $this->getTo()->getAddress()->setParametersRequired(['country']);

        parent::validate();
    }
}
