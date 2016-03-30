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
    const ERROR_INVALID_USER = 'FM501';
    const ERROR_MISMATCHED_RFC = 'FM507';
    const ERROR_UNLISTED_RFC = 'FM402';
    const ERROR_INVALID_CFDI = '301';
    const ERROR_INVALID_STAMP = '302';
    const ERROR_INVALID_CERTIFICATE_NUMBER = '303';
    const ERROR_REVOKED_CERTIFICATE = '304';
    const ERROR_EXPIRED_CERTIFICATE = '305';
    const ERROR_WRONG_SIGNED_CFDI = '306';
    const ERROR_SIGN_INCLUDED_CFDI = '307';
    const ERROR_ALREADY_SIGNED_CFDI = '307';
    const ERROR_WRONG_DATED_CFDI = '401';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Facturacion Moderna';
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultParameters()
    {
        return array(
            'testMode'                  => false,
            'userId'                    => '',
            'userPassword'              => '',
            'issuerRfc'                 => '',
            'issuerCertificateId'       => '',
            'issuerPublicCertificate'   => '',
            'issuerPrivateCertificate'  => '',
            'issuerCancelPassword'      => '',
        );
    }

    /**
     * Get user id
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->getParameter('userId');
    }

    /**
     * Set user id
     *
     * @param string $value
     * @return $this
     */
    public function setUserId($value)
    {
        return $this->setParameter('userId', $value);
    }

    /**
     * Get user password
     *
     * @return string
     */
    public function getUserPassword()
    {
        return $this->getParameter('userPassword');
    }

    /**
     * Set user password
     *
     * @param string $value
     * @return $this
     */
    public function setUserPassword($value)
    {
        return $this->setParameter('userPassword', $value);
    }

    /**
     * Get issuer RFC
     *
     * @return string
     */
    public function getIssuerRfc()
    {
        return $this->getParameter('issuerRfc');
    }

    /**
     * Set issuer RFC
     *
     * @param string $value
     * @return $this
     */
    public function setIssuerRfc($value)
    {
        return $this->setParameter('issuerRfc', $value);
    }

    /**
     * Get issuer certificate id
     *
     * @return string
     */
    public function getIssuerCertificateId()
    {
        return $this->getParameter('issuerCertificateId');
    }

    /**
     * Set issuer certificate id
     *
     * @param string $value
     * @return $this
     */
    public function setIssuerCertificateId($value)
    {
        return $this->setParameter('issuerCertificateId', $value);
    }

    /**
     * Get issuer public certificate path
     *
     * @return string
     */
    public function getIssuerPublicCertificate()
    {
        return $this->getParameter('issuerPublicCertificate');
    }

    /**
     * Set issuer public certificate path
     *
     * @param string $value
     * @return $this
     */
    public function setIssuerPublicCertificate($value)
    {
        return $this->setParameter('issuerPublicCertificate', $value);
    }

    /**
     * Get issuer private certificate path
     *
     * @return string
     */
    public function getIssuerPrivateCertificate()
    {
        return $this->getParameter('issuerPrivateCertificate');
    }

    /**
     * Set issuer private certificate path
     *
     * @param string $value
     * @return $this
     */
    public function setIssuerPrivateCertificate($value)
    {
        return $this->setParameter('issuerPrivateCertificate', $value);
    }

    /**
     * Get issuer cancel password
     *
     * @return string
     */
    public function getIssuerCancelPassword()
    {
        return $this->getParameter('issuerCancelPassword');
    }

    /**
     * Set issuer cancel password
     *
     * @param string $value
     * @return $this
     */
    public function setIssuerCancelPassword($value)
    {
        return $this->setParameter('issuerCancelPassword', $value);
    }

    /**
     * Sign document
     *
     * @param array $parameters
     * @return Message\Response
     */
    public function sign(array $parameters = array())
    {
        return $this->createRequest('\Argentum\FacturacionModerna\Message\SignRequest', $parameters);
    }

    /**
     * Sign document
     *
     * @param array $parameters
     * @return Message\Response
     */
    public function cancel(array $parameters = array())
    {
        return $this->createRequest('\Argentum\FacturacionModerna\Message\CancelRequest', $parameters);
    }
}
