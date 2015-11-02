<?php namespace Argentum\FacturacionModerna\Message;

use Argentum\Common\Message\AbstractRequest as CommonAbstractRequest;

/**
 * Facturacion Moderna Abstract Request
 */
class AbstractRequest extends CommonAbstractRequest
{
    protected $testEndpoint = 'https://t1demo.facturacionmoderna.com/timbrado/wsdl';
    protected $liveEndpoint = 'https://t1demo.facturacionmoderna.com/timbrado/wsdl';

    public function getUserId()
    {
        return $this->getParameter('userId');
    }

    public function setUserId($value)
    {
        return $this->setParameter('userId', $value);
    }

    public function getUserPass()
    {
        return $this->getParameter('userPass');
    }

    public function setUserPass($value)
    {
        return $this->setParameter('userPass', $value);
    }

    public function getIssuerRfc()
    {
        return $this->getParameter('issuerRfc');
    }

    public function setIssuerRfc($value)
    {
        return $this->setParameter('issuerRfc', $value);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
