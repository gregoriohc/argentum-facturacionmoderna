<?php namespace Argentum\FacturacionModerna\Message;
use Argentum\Common\Message\RequestInterface;

/**
 * Facturacion Moderna Sign Response
 */
class SignResponse extends Response
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        // Decode base64 encoded properties
        foreach(['xml', 'pdf', 'png', 'txt'] as $property){
            if(isset($this->data[$property])){
                $this->data[$property] = base64_decode($this->data[$property]);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccessful()
    {
        return false !== $this->getReference();
    }

    /**
     * {@inheritDoc}
     */
    public function getReference()
    {
        $reference = false;

        if (isset($this->data['xml'])) {
            try {
                $xml = simplexml_load_string($this->data['xml']);
                $xml->registerXPathNamespace("tfd", "http://www.sat.gob.mx/TimbreFiscalDigital");
                $tfd = $xml->xpath('//tfd:TimbreFiscalDigital');
                $reference = (string)$tfd[0]['UUID'];
            } catch (\Exception $e) {
            }
        }

        return $reference;
    }

    /**
     * Get response XML
     *
     * @return string
     */
    public function getXml()
    {
        return $this->data['xml'];
    }

    /**
     * Get response XML
     *
     * @return string
     */
    public function getUnsignedXml()
    {
        return base64_decode($this->request->getParameters()['text2CFDI']);
    }
}
