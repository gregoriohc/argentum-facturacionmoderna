<?php namespace Argentum\FacturacionModerna\Message;

/**
 * Facturacion Moderna Sign Request
 */
class SignRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    protected function getFunction()
    {
        return 'requestTimbrarCFDI';
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
     * Get unsigned xml of the document to sign
     *
     * @return string
     */
    public function getUnsignedXml()
    {
        return $this->getParameter('unsignedXml');
    }

    /**
     * Set unsigned xml of the document to sign
     *
     * @param string $value
     * @return \Argentum\Common\Message\AbstractRequest
     */
    public function setUnsignedXml($value)
    {
        return $this->setParameter('unsignedXml', $value);
    }

    /**
     * Stamp document xml
     *
     * @param string $xml
     * @return string
     */
    protected function stampXml($xml)
    {
        $privateCertificate = openssl_pkey_get_private(file_get_contents($this->getIssuerPrivateCertificate()));
        $publicCertificate = str_replace(array('\n', '\r'), '', base64_encode(file_get_contents($this->getIssuerPublicCertificate())));

        $xmlDocument = new \DomDocument();
        $xmlDocument->loadXML($xml) or die("Invalid XML");
        $xsltDocument = new \DOMDocument();
        $xsltDocument->load(__DIR__.'/../../resources/xslt/3.2/cadenaoriginal_3_2.xslt');

        $xsltProcessor = new \XSLTProcessor;
        $xsltProcessor->importStyleSheet($xsltDocument);
        $xmlProcessed = $xsltProcessor->transformToXML($xmlDocument);
        openssl_sign($xmlProcessed, $signature, $privateCertificate);
        $stamp = base64_encode($signature);
        /** @var \DOMElement $header */
        $header = $xmlDocument->getElementsByTagNameNS('http://www.sat.gob.mx/cfd/3', 'Comprobante')->item(0);
        $header->setAttribute('sello', $stamp);
        $header->setAttribute('certificado', $publicCertificate);
        $header->setAttribute('noCertificado', $this->getIssuerCertificateId());
        return $xmlDocument->saveXML();
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        $this->validate('document');
        /** @var \Argentum\Common\Document\AbstractDocument $document */
        $document = $this->getDocument();
        $xml = $document->render('xml');
        $xml = $this->stampXml($xml);
        $this->setUnsignedXml($xml);

        $data = [
            'text2CFDI'     => base64_encode($xml),
            'generarTXT'    => false,
            'generarPDF'    => false,
            'generarCBB'    => false,
        ];

        return array_merge(parent::getData(), $data);
    }

    /**
     * {@inheritDoc}
     */
    protected function createResponse($data)
    {
        return $this->response = new SignResponse($this, $data);
    }
}
