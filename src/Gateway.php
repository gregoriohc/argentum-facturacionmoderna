<?php namespace Argentum\FacturacionModerna;

use Argentum\Common\AbstractGateway;

/**
 * Facturacion Moderna Gateway
 *
 * The gateway uses the Facturacion Moderna service for signing electronic documents in Mexico
 * 
 * @see \Omnipay\Common\AbstractGateway
 * @link https://developers.facturacionmoderna.com/
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Facturacion Moderna';
    }

    public function getDefaultParameters()
    {
        return array(
            'userId' => '',
            'userPass' => '',
            'issuerRfc' => '',
            'testMode' => false,
        );
    }

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

    public function sign(array $parameters = array())
    {
        return $this->createRequest('\Argentum\FacturacionModerna\Message\SignRequest', $parameters);
    }
}
