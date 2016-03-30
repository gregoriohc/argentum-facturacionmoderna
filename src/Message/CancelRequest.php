<?php namespace Argentum\FacturacionModerna\Message;

/**
 * Facturacion Moderna Cancel Request
 */
class CancelRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    protected function getFunction()
    {
        return 'requestCancelarCFDI';
    }

    /**
     * Get document to sign
     *
     * @return \Argentum\Common\Document\AbstractDocument|\Argentum\Common\Document\Ticket
     */
    public function getDocument()
    {
        return $this->getParameter('document');
    }

    /**
     * Set document to sign
     *
     * @param \Argentum\Common\Document\AbstractDocument $value
     * @return \Argentum\Common\Message\AbstractRequest
     */
    public function setDocument($value)
    {
        return $this->setParameter('document', $value);
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        /** @var \Argentum\Common\Document\Invoice $document */
        $document = $this->getDocument();
        $document->validate();

        $data = [
            'uuid' => $document->getId(),
        ];

        return array_merge(parent::getData(), $data);
    }

    /**
     * {@inheritDoc}
     */
    protected function createResponse($data)
    {
        return $this->response = new CancelResponse($this, $data);
    }
}
