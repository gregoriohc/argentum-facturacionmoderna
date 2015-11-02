<?php namespace Argentum\FacturacionModerna\Message;

/**
 * Facturacion Moderna Sign Request
 */
class SignRequest extends AbstractRequest
{
    public function getDocument()
    {
        return $this->getParameter('document');
    }

    public function setDocument($value)
    {
        return $this->setParameter('document', $value);
    }

    public function getData()
    {
        $this->validate('document');

        $data = array();
        $data['document'] = $this->getDocument();

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new SignResponse($this, $data);
    }
}
