<?php namespace Argentum\FacturacionModerna\Message;
use GuzzleHttp\ClientInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Facturacion Moderna Sign Request
 */
class SignRequest extends AbstractRequest
{
    const CFDI_VERSION_3_2 = '3.2';
    const CFDI_VERSION_3_3 = '3.3';

    /**
     * Create a new Request
     *
     * @param ClientInterface $httpClient  A Guzzle client to make API calls with
     * @param RequestStack    $httpRequestStack A Symfony HTTP request stack
     */
    public function __construct(ClientInterface $httpClient, RequestStack $httpRequestStack)
    {
        parent::__construct($httpClient, $httpRequestStack);

        $this->setCfdiVersion(self::CFDI_VERSION_3_2);
    }

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
     * Get CFDI version
     *
     * @return string
     */
    public function getCfdiVersion()
    {
        return $this->getParameter('cfdi_version');
    }

    /**
     * Set CFDI version
     *
     * @param string $value
     * @return \Argentum\Common\Message\AbstractRequest
     */
    public function setCfdiVersion($value)
    {
        return $this->setParameter('cfdi_version', $value);
    }

    /**
     * Stamp document xml
     *
     * @param string $xml
     * @return string
     */
    protected function stampXml($xml)
    {
        if (self::CFDI_VERSION_3_3 == $this->getCfdiVersion()) {
            $privateCertificate = openssl_pkey_get_private(file_get_contents($this->getIssuerPrivateCertificate()));
            $publicCertificate = str_replace(array('\n', '\r'), '', base64_encode(file_get_contents($this->getIssuerPublicCertificate())));

            $xmlDocument = new \DomDocument();
            $xmlDocument->loadXML($xml) or die("Invalid XML");

            /** @var \DOMElement $header */
            $header = $xmlDocument->getElementsByTagNameNS('http://www.sat.gob.mx/cfd/3', 'Comprobante')->item(0);
            $header->setAttribute('Certificado', $publicCertificate);
            $header->setAttribute('NoCertificado', $this->getIssuerCertificateId());

            $xsltDocument = new \DOMDocument();
            $xsltDocument->load(__DIR__.'/../../resources/xslt/3.3/cadenaoriginal_3_3.xslt');

            $xsltProcessor = new \XSLTProcessor;
            $xsltProcessor->importStyleSheet($xsltDocument);
            $xmlProcessed = $xsltProcessor->transformToXML($xmlDocument);
            openssl_sign($xmlProcessed, $signature, $privateCertificate, OPENSSL_ALGO_SHA256);
            $stamp = base64_encode($signature);

            $header->setAttribute('Sello', $stamp);

            return $xmlDocument->saveXML();
        }

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
        $xmlTemplateCode = 'xml';
        if (self::CFDI_VERSION_3_2 != $this->getCfdiVersion()) {
            $xmlTemplateCode = 'xml' . str_replace('.', '', $this->getCfdiVersion());
        }
        $xml = $document->render($xmlTemplateCode);
        $xml = $this->stampXml($xml);
        //if ($document->getType() == 'creditNote') { echo $xml; die(); }
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
