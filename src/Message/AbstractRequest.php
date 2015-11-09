<?php namespace Argentum\FacturacionModerna\Message;

use Argentum\Common\Message\AbstractRequest as CommonAbstractRequest;

/**
 * Facturacion Moderna Abstract Request
 */
abstract class AbstractRequest extends CommonAbstractRequest
{
    protected $testEndpoint = 'https://t1demo.facturacionmoderna.com/timbrado/wsdl';
    protected $liveEndpoint = 'https://t1demo.facturacionmoderna.com/timbrado/wsdl';

    /**
     * Get user ID
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->getParameter('userId');
    }

    /**
     * Set user ID
     *
     * @param $value
     * @return CommonAbstractRequest
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
     * @param $value
     * @return CommonAbstractRequest
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
     * @param $value
     * @return CommonAbstractRequest
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
     * Get Facturacion Moderna SOAP endpoint
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        $data = [
            'UserID'        => $this->getUserId(),
            'UserPass'      => $this->getUserPassword(),
            'emisorRFC'     => $this->getIssuerRfc(),
        ];

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function sendData($data)
    {
        try {
            $soapClient = new \SoapClient($this->getEndpoint(), array('trace' => 1));
            $function = $this->getFunction();
            $response = (array) $soapClient->$function((object) $data);
        } catch (\SoapFault $e) {
            $response = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }catch (\Exception $e) {
            $response = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
        return $this->response = $this->createResponse($response);
    }

    /**
     * @param array $data
     * @return Response
     */
    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }

    abstract protected function getFunction();
}
